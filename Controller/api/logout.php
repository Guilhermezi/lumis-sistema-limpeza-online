<?php
// POST /Controller/api/logout.php
// Destrói a sessão e manda o usuário para a home.

require __DIR__ . '/_bootstrap.php';

$_SESSION = [];

// apaga o cookie de sessão no navegador
if (ini_get('session.use_cookies')){
    $p = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 42000,
        'path'     => $p['path'],
        'domain'   => $p['domain'],
        'secure'   => $p['secure'],
        'httponly' => $p['httponly'],
        'samesite' => $p['samesite'] ?? 'Lax',
    ]);
}

session_destroy();

responder(['ok' => true, 'redirect' => '../index.php']);