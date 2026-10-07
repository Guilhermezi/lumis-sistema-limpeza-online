<?php
// POST /Controller/api/login.php
// Confere email e senha, e abre a sessão.

require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

try {
    $dados = lerDados();
    $cfg   = tipoValido($dados['tipo'] ?? null);

    $email = normalizarEmail($dados['email'] ?? '');
    $senha = $dados['senha'] ?? '';

    if ($email === '' || mb_strlen($email) > 100 || $senha === '' || mb_strlen($senha) > 1024){
        responderErro('credenciais', [], 401);
    }

    if (loginBloqueado($pdo, $email, $dados['tipo'])){
        header('Retry-After: 900');
        responderErro('muitas-tentativas', [], 429);
    }

    // nome da tabela e da chave primária vêm do mapa, nunca do usuário
    $sql = 'SELECT ' . $cfg['pk'] . ' AS id, nome, email, senha, auth_versao
              FROM ' . $cfg['tabela'] . '
             WHERE email = :email
             LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();   // false se o email não existir

    // mensagem genérica de propósito: dizer "o email existe mas a senha está errada"
    // entrega ao atacante a lista de quem tem conta no site
    // O hash fictício reduz a diferença de tempo entre e-mail inexistente e senha errada.
    $hash = $usuario['senha'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
    if (!$usuario || !password_verify($senha, $hash)){
        registrarTentativaLogin($pdo, $email, $dados['tipo'], false);
        responderErro('credenciais', [], 401);
    }

    if (password_needs_rehash($usuario['senha'], PASSWORD_DEFAULT)){
        $atualizar = 'UPDATE ' . $cfg['tabela'] . ' SET senha = :senha WHERE ' . $cfg['pk'] . ' = :id';
        $pdo->prepare($atualizar)->execute([
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'id' => $usuario['id'],
        ]);
    }

    registrarTentativaLogin($pdo, $email, $dados['tipo'], true);
    abrirSessaoUsuario($usuario, $dados['tipo']);

    responder([
        'ok'       => true,
        'redirect' => '../' . $cfg['perfil'],
        'usuario'  => [
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'tipo'  => $dados['tipo'],
        ],
    ]);

} catch (Throwable $e){
    responderServidor($e);
}
