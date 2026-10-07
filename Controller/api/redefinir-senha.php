<?php
// POST /Controller/api/redefinir-senha.php
// Consome um token válido uma única vez, troca a senha e revoga sessões antigas.
require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

try {
    $dados = lerDados();
    $cfg = tipoValido($dados['tipo'] ?? null);
    $token = (string) ($dados['token'] ?? '');
    $senha = (string) ($dados['senha'] ?? '');
    $confirmar = (string) ($dados['senha_confirma'] ?? '');

    $campos = [];
    if (mb_strlen($senha) < 10 || mb_strlen($senha) > 1024) $campos['senha'] = 'senha-curta';
    if ($confirmar === '' || $senha !== $confirmar) $campos['senha_confirma'] = 'senhas-diferentes';
    if ($campos) responderErro('dados-invalidos', $campos, 422);
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) responderErro('token-invalido', [], 422);

    // A transação e o FOR UPDATE impedem dois usos simultâneos do mesmo token.
    $pdo->beginTransaction();
    $stmt = $pdo->prepare(
        'SELECT id_recuperacao, usuario_id FROM RecuperacaoSenha
         WHERE token_hash = :token_hash AND tipo = :tipo AND usado_em IS NULL AND expira_em >= NOW()
         LIMIT 1 FOR UPDATE'
    );
    $stmt->execute(['token_hash' => hash('sha256', $token), 'tipo' => $dados['tipo']]);
    $recuperacao = $stmt->fetch();
    if (!$recuperacao){
        $pdo->rollBack();
        responderErro('token-invalido', [], 422);
    }

    // auth_versao invalida todas as sessões criadas antes da troca de senha.
    $sql = 'UPDATE ' . $cfg['tabela'] . ' SET senha = :senha, auth_versao = auth_versao + 1 WHERE ' . $cfg['pk'] . ' = :id';
    $pdo->prepare($sql)->execute([
        'senha' => password_hash($senha, PASSWORD_DEFAULT),
        'id' => $recuperacao['usuario_id'],
    ]);
    $pdo->prepare(
        'UPDATE RecuperacaoSenha SET usado_em = NOW() WHERE tipo = :tipo AND usuario_id = :usuario_id AND usado_em IS NULL'
    )->execute(['tipo' => $dados['tipo'], 'usuario_id' => $recuperacao['usuario_id']]);
    $pdo->commit();

    if (!empty($_SESSION['usuario'])
        && $_SESSION['usuario']['tipo'] === $dados['tipo']
        && (int) $_SESSION['usuario']['id'] === (int) $recuperacao['usuario_id']){
        encerrarSessao();
    }

    responder([
        'ok' => true,
        'redirect' => $dados['tipo'] === 'profissional'
            ? 'cadastro-trabalhador.php?senha=redefinida'
            : 'login.php?senha=redefinida',
    ]);
} catch (Throwable $e){
    if ($pdo->inTransaction()) $pdo->rollBack();
    responderServidor($e);
}
