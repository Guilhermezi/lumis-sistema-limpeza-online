<?php
require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

if (!manterSessaoAtiva()) {
    responderErro('sessao-expirada', [], 403);
}

$sessao = $_SESSION['usuario'] ?? [];
if (($sessao['tipo'] ?? '') !== 'cliente') {
    responderErro('apenas-clientes', [], 403);
}

try {
    $cliente = buscarUsuario($pdo, (int) ($sessao['id'] ?? 0), 'cliente');
    if (!$cliente || !(int) ($cliente['email_verificado'] ?? 0)) {
        responderErro('email-nao-verificado', [], 403);
    }

    $dados = lerDados();
    $planoId = filter_var($dados['plano_id'] ?? null, FILTER_VALIDATE_INT);
    $metodo = (string) ($dados['metodo_pagamento'] ?? '');
    $metodos = [
        'pix' => 'PIX',
        'cartao' => 'Cartão',
        'boleto' => 'Boleto',
        'na-hora' => 'Pagamento no atendimento',
    ];
    if (!$planoId) responderErro('plano-invalido', [], 422);
    if (!isset($metodos[$metodo])) responderErro('pagamento-invalido', [], 422);

    $planoStmt = $pdo->prepare('SELECT id_plano, nome, preco, tipo FROM Planos WHERE id_plano = :id LIMIT 1');
    $planoStmt->execute(['id' => $planoId]);
    $plano = $planoStmt->fetch();
    if (!$plano || (float) $plano['preco'] <= 0 || $plano['tipo'] === 'personalizado') {
        responderErro('plano-invalido', [], 422);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO AssinaturaPlano
            (id_cliente, id_plano, valor_assinatura, forma_pagamento)
         VALUES (:cliente, :plano, :valor, :forma)"
    );
    $stmt->execute([
        'cliente' => $cliente['id_cliente'],
        'plano' => $plano['id_plano'],
        'valor' => $plano['preco'],
        'forma' => $metodos[$metodo],
    ]);

    responder([
        'ok' => true,
        'assinatura' => [
            'codigo' => 'LUM-P' . str_pad((string) $pdo->lastInsertId(), 6, '0', STR_PAD_LEFT),
            'plano' => $plano['nome'],
            'valor' => (float) $plano['preco'],
            'pagamento' => $metodos[$metodo],
        ],
    ]);
} catch (Throwable $e) {
    responderServidor($e);
}
