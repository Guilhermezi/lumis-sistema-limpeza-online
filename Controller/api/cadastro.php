<?php
// POST /Controller/api/cadastro.php
// Cria a conta (cliente ou profissional) e já deixa o usuário logado.

require __DIR__ . '/_bootstrap.php';

try {
    $dados = lerDados();
    $cfg   = tipoValido($dados['tipo'] ?? null);

    // valida antes de gravar; $erros fica vazio quando está tudo certo
    $erros = validarCadastro($dados, $cfg);
    if ($erros){
        responderErro('obrigatorios', $erros);
    }

    $id = cadastrar($pdo, $dados, $cfg);

    // cadastrar devolve null quando o email já existe (erro 1062 do banco)
    if ($id === null){
        responderErro('email-em-uso', ['email' => 'email-em-uso']);
    }

    session_regenerate_id(true);   // id novo evita sequestro de sessão
    $_SESSION['usuario'] = [
        'id'    => $id,
        'nome'  => $dados['nome'],
        'email' => $dados['email'],
        'tipo'  => $dados['tipo'],   // 'cliente' ou 'profissional', que é o que a guarda compara
    ];

    responder([
        'ok'       => true,
        'redirect' => '../' . $cfg['perfil'],
        'usuario'  => [
            'nome'  => $dados['nome'],
            'email' => $dados['email'],
            'tipo'  => $dados['tipo'],
        ],
    ]);

} catch (Throwable $e){
    responderServidor($e);
}