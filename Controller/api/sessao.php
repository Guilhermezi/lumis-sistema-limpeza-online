<?php
// GET /Controller/api/sessao.php
// Diz ao JavaScript se tem alguém logado. Não devolve a senha, nunca.

require __DIR__ . '/_bootstrap.php';

if (empty($_SESSION['usuario'])){
    responder(['logado' => false]);
}

$u = $_SESSION['usuario'];

// monta o que o header precisa: primeiro nome e o tipo.
// o campo 'tipo' aqui é a página de perfil que a guarda espera
responder([
    'logado'  => true,
    'usuario' => [
        'id'    => (int) $u['id'],
        'nome'  => $u['nome'],
        'email' => $u['email'],
        'tipo'  => $u['tipo'],
    ],
]);