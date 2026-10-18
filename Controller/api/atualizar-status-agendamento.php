<?php
require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

if (!manterSessaoAtiva()) responderErro('sessao-expirada', [], 403);
$sessao = $_SESSION['usuario'] ?? [];
$tipoUsuario = (string) ($sessao['tipo'] ?? '');
if (!in_array($tipoUsuario, ['cliente', 'profissional'], true)) responderErro('usuario-invalido', [], 403);

$dados = lerDados();
$contratacaoId = filter_var($dados['contratacao_id'] ?? null, FILTER_VALIDATE_INT);
$acao = (string) ($dados['acao'] ?? '');
if (!$contratacaoId || !in_array($acao, ['confirmar', 'concluir', 'recusar', 'cancelar'], true)) {
    responderErro('dados-status-invalidos', [], 422);
}
if (($tipoUsuario === 'cliente' && $acao !== 'cancelar') || ($tipoUsuario === 'profissional' && !in_array($acao, ['confirmar', 'concluir', 'recusar', 'cancelar'], true))) {
    responderErro('acao-nao-permitida', [], 403);
}

try {
    $pdo->beginTransaction();
    // O cliente é autorizado pela contratação; o profissional, pelo vínculo
    // da contratação e do agendamento com a própria conta. Não aplique ambos
    // os filtros a todos os perfis, pois isso torna a consulta impossível.
    if ($tipoUsuario === 'cliente') {
        $filtroResponsavel = 'AND c.id_cliente = :cliente';
        $parametrosResponsavel = ['cliente' => $sessao['id']];
    } else {
        $filtroResponsavel = 'AND c.id_profissional = :profissional_contratacao
                              AND a.id_profissional = :profissional_agenda';
        $parametrosResponsavel = [
            'profissional_contratacao' => $sessao['id'],
            'profissional_agenda' => $sessao['id'],
        ];
    }

    $busca = $pdo->prepare(
        'SELECT a.id_agendamento, a.status_agenda,
                (TIMESTAMP(a.data_agenda, a.hora_inicio_agenda) > NOW()) AS horario_futuro,
                (TIMESTAMP(a.data_agenda, a.hora_fim_agenda) <= NOW()) AS horario_encerrado
         FROM Agendamento a
         INNER JOIN Contratacao c ON c.id_contratacao = a.id_contratacao
         WHERE c.id_contratacao = :contratacao
           ' . $filtroResponsavel . '
         LIMIT 1 FOR UPDATE'
    );
    $busca->execute(['contratacao' => $contratacaoId] + $parametrosResponsavel);
    $agendamento = $busca->fetch();
    if (!$agendamento) {
        $pdo->rollBack();
        responderErro('agendamento-invalido', [], 404);
    }

    $status = mb_strtolower((string) $agendamento['status_agenda']);
    if ($tipoUsuario === 'cliente') {
        if (!in_array($status, ['aguardando confirmação', 'confirmado'], true) || !(int) $agendamento['horario_futuro']) {
            $pdo->rollBack();
            responderErro('agendamento-nao-pode-ser-cancelado', [], 409);
        }
        $novoStatus = 'Cancelado pelo cliente';
    } elseif ($acao === 'confirmar') {
        if ($status !== 'aguardando confirmação') {
            $pdo->rollBack();
            responderErro('agendamento-ja-atualizado', [], 409);
        }
        $novoStatus = 'Confirmado';
    } elseif ($acao === 'recusar') {
        if ($status !== 'aguardando confirmação' || !(int) $agendamento['horario_futuro']) {
            $pdo->rollBack();
            responderErro('agendamento-nao-pode-ser-recusado', [], 409);
        }
        $novoStatus = 'Recusado pelo profissional';
    } elseif ($acao === 'cancelar') {
        if ($status !== 'confirmado' || !(int) $agendamento['horario_futuro']) {
            $pdo->rollBack();
            responderErro('agendamento-nao-pode-ser-cancelado', [], 409);
        }
        $novoStatus = 'Cancelado pelo profissional';
    } else {
        if ($status !== 'confirmado' || !(int) $agendamento['horario_encerrado']) {
            $pdo->rollBack();
            responderErro('servico-ainda-nao-pode-ser-concluido', [], 409);
        }
        $novoStatus = 'Concluído';
    }

    $pdo->prepare('UPDATE Agendamento SET status_agenda = :status WHERE id_agendamento = :id')
        ->execute(['status' => $novoStatus, 'id' => $agendamento['id_agendamento']]);
    if (in_array($novoStatus, ['Concluído', 'Recusado pelo profissional', 'Cancelado pelo cliente', 'Cancelado pelo profissional'], true)) {
        $pdo->prepare('UPDATE Contratacao SET status_contratacao = :status WHERE id_contratacao = :id')
            ->execute(['status' => $novoStatus, 'id' => $contratacaoId]);
    }

    $pdo->commit();
    responder(['ok' => true, 'status' => $novoStatus]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    responderServidor($e);
}
