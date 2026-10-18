<?php
// POST /Controller/api/logout.php
// Destrói a sessão e manda o usuário para a home.

require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();
encerrarSessao();

responder(['ok' => true]);
