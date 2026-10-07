<?php
// GET /Controller/api/sessao.php
// Diz ao JavaScript se tem alguém logado. Não devolve a senha, nunca.

require __DIR__ . '/_bootstrap.php';
exigirMetodo('GET');

if (!manterSessaoAtiva()){
    responder(['ok' => true, 'logado' => false, 'csrf' => tokenCsrf()]);
}

$u = $_SESSION['usuario'];
$linha = buscarUsuario($pdo, (int) $u['id'], (string) $u['tipo']);
if (!$linha || (int) ($linha['auth_versao'] ?? 1) !== (int) ($u['auth_versao'] ?? 1)){
    $_SESSION = [];
    session_regenerate_id(true);
    responder(['ok' => true, 'logado' => false, 'csrf' => tokenCsrf()]);
}

// Atualiza dados mutáveis para a sessão não conservar nome/e-mail antigos.
$_SESSION['usuario']['nome'] = $linha['nome'];
$_SESSION['usuario']['email'] = $linha['email'];
$u = $_SESSION['usuario'];

// monta o que o header precisa: primeiro nome e o tipo.
// o campo 'tipo' aqui é a página de perfil que a guarda espera
responder([
    'ok'      => true,
    'logado'  => true,
    'csrf'    => tokenCsrf(),
    'usuario' => [
        'id'    => (int) $u['id'],
        'nome'  => $u['nome'],
        'email' => $u['email'],
        'tipo'  => $u['tipo'],
    ],
]);
