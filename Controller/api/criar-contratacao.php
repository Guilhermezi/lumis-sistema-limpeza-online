<?php

// Cria contratação, item, pagamento pendente e agendamento em uma transação.
require __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__, 2) . '/Model/contratacao.php';

exigirMetodo('POST');
exigirCsrf();

if (!manterSessaoAtiva()) {
    responderErro('sessao-expirada', [], 403);
}

$usuario = $_SESSION['usuario'];
if (($usuario['tipo'] ?? '') !== 'cliente') {
    responderErro('apenas-clientes', [], 403);
}

try {
    $pedido = criarContratacao($pdo, (int) $usuario['id'], lerDados());
    responder(['ok' => true, 'pedido' => $pedido], 201);
} catch (InvalidArgumentException $e) {
    responderErro($e->getMessage(), [], 422);
} catch (RuntimeException $e) {
    if ($e->getMessage() === 'sem-profissional') {
        responderErro('sem-profissional', [], 409);
    }
    responderServidor($e);
} catch (Throwable $e) {
    responderServidor($e);
}
