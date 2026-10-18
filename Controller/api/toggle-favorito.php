<?php
require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

if (!manterSessaoAtiva()) responderErro('sessao-expirada', [], 403);
$sessao = $_SESSION['usuario'] ?? [];
if (($sessao['tipo'] ?? '') !== 'cliente') responderErro('apenas-clientes', [], 403);

$dados = lerDados();
$contratacaoId = filter_var($dados['contratacao_id'] ?? null, FILTER_VALIDATE_INT);
if (!$contratacaoId) responderErro('contratacao-invalida', [], 422);

try {
    $pdo->beginTransaction();
    $busca = $pdo->prepare(
        'SELECT id_profissional FROM Contratacao
         WHERE id_contratacao = :contratacao AND id_cliente = :cliente
         LIMIT 1 FOR UPDATE'
    );
    $busca->execute(['contratacao' => $contratacaoId, 'cliente' => $sessao['id']]);
    $contratacao = $busca->fetch();
    if (!$contratacao) {
        $pdo->rollBack();
        responderErro('contratacao-invalida', [], 404);
    }

    $parametros = ['cliente' => $sessao['id'], 'profissional' => $contratacao['id_profissional']];
    $existe = $pdo->prepare('SELECT 1 FROM Favorito WHERE id_cliente = :cliente AND id_profissional = :profissional');
    $existe->execute($parametros);
    if ($existe->fetchColumn()) {
        $pdo->prepare('DELETE FROM Favorito WHERE id_cliente = :cliente AND id_profissional = :profissional')->execute($parametros);
        $favorito = false;
    } else {
        $servico = $pdo->prepare('SELECT id_servico FROM ItemContratacao WHERE id_contratacao = :contratacao ORDER BY id_item LIMIT 1');
        $servico->execute(['contratacao' => $contratacaoId]);
        $idServico = $servico->fetchColumn();
        $pdo->prepare(
            'INSERT INTO Favorito (id_cliente, id_profissional, id_servico, data)
             VALUES (:cliente, :profissional, :servico, CURDATE())'
        )->execute($parametros + ['servico' => $idServico ?: null]);
        $favorito = true;
    }

    $pdo->commit();
    responder(['ok' => true, 'favorito' => $favorito]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    responderServidor($e);
}
