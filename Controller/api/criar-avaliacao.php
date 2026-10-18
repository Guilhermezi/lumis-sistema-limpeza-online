<?php
require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

if (!manterSessaoAtiva()) responderErro('sessao-expirada', [], 403);
$sessao = $_SESSION['usuario'] ?? [];
if (($sessao['tipo'] ?? '') !== 'cliente') responderErro('apenas-clientes', [], 403);

$dados = lerDados();
$contratacaoId = filter_var($dados['contratacao_id'] ?? null, FILTER_VALIDATE_INT);
$nota = filter_var($dados['nota'] ?? null, FILTER_VALIDATE_INT);
$comentario = trim((string) ($dados['comentario'] ?? ''));
if (!$contratacaoId || !$nota || $nota < 1 || $nota > 5 || mb_strlen($comentario) > 1000) {
    responderErro('dados-avaliacao-invalidos', [], 422);
}

try {
    $pdo->beginTransaction();
    $busca = $pdo->prepare(
        'SELECT id_profissional, status_contratacao FROM Contratacao
         WHERE id_contratacao = :contratacao AND id_cliente = :cliente
         LIMIT 1 FOR UPDATE'
    );
    $busca->execute(['contratacao' => $contratacaoId, 'cliente' => $sessao['id']]);
    $contratacao = $busca->fetch();
    if (!$contratacao) {
        $pdo->rollBack();
        responderErro('contratacao-invalida', [], 404);
    }
    if (!str_starts_with(mb_strtolower((string) $contratacao['status_contratacao']), 'conclu')) {
        $pdo->rollBack();
        responderErro('servico-nao-concluido', [], 409);
    }

    $existe = $pdo->prepare('SELECT 1 FROM Avaliacao WHERE id_contratacao = :contratacao LIMIT 1');
    $existe->execute(['contratacao' => $contratacaoId]);
    if ($existe->fetchColumn()) {
        $pdo->rollBack();
        responderErro('avaliacao-ja-enviada', [], 409);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO Avaliacao (nota_avaliacao, comentario_avaliacao, id_cliente, id_profissional, id_contratacao)
         VALUES (:nota, :comentario, :cliente, :profissional, :contratacao)'
    );
    $stmt->execute([
        'nota' => $nota,
        'comentario' => $comentario === '' ? null : $comentario,
        'cliente' => $sessao['id'],
        'profissional' => $contratacao['id_profissional'],
        'contratacao' => $contratacaoId,
    ]);
    $pdo->commit();
    responder(['ok' => true]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    responderServidor($e);
}
