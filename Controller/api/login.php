<?php
// POST /Controller/api/login.php
// Confere email e senha, e abre a sessão.

require __DIR__ . '/_bootstrap.php';

try {
    $dados = lerDados();
    $cfg   = tipoValido($dados['tipo'] ?? null);

    $email = trim($dados['email'] ?? '');
    $senha = $dados['senha'] ?? '';

    if ($email === '' || $senha === ''){
        responderErro('credenciais');
    }

    // nome da tabela e da chave primária vêm do mapa, nunca do usuário
    $sql = 'SELECT ' . $cfg['pk'] . ' AS id, nome, email, senha
              FROM ' . $cfg['tabela'] . '
             WHERE email = :email
             LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();   // false se o email não existir

    // mensagem genérica de propósito: dizer "o email existe mas a senha está errada"
    // entrega ao atacante a lista de quem tem conta no site
    if (!$usuario || !password_verify($senha, $usuario['senha'])){
        responderErro('credenciais');
    }

    session_regenerate_id(true);   // id novo evita sequestro de sessão

    $_SESSION['usuario'] = [
        'id'    => (int) $usuario['id'],
        'nome'  => $usuario['nome'],
        'email' => $usuario['email'],
        'tipo'  => $dados['tipo'],
    ];

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