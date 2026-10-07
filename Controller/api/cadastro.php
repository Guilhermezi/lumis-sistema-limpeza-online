<?php
// POST /Controller/api/cadastro.php
// Cria a conta (cliente ou profissional) e já deixa o usuário logado.

require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

try {
    $dados = lerDados();
    $cfg   = tipoValido($dados['tipo'] ?? null);

    // valida antes de gravar; $erros fica vazio quando está tudo certo
    $erros = validarCadastro($dados, $cfg);
    if ($erros){
        responderErro('obrigatorios', $erros, 422);
    }

    $id = cadastrar($pdo, $dados, $cfg);

    // cadastrar devolve null quando o email já existe (erro 1062 do banco)
    if ($id === null){
        responderErro('email-em-uso', ['email' => 'email-em-uso'], 409);
    }

    $usuarioSessao = [
        'id' => $id,
        'nome' => trim($dados['nome']),
        'email' => normalizarEmail($dados['email']),
        'auth_versao' => 1,
    ];
    abrirSessaoUsuario($usuarioSessao, $dados['tipo']);

    responder([
        'ok'       => true,
        'redirect' => '../' . $cfg['perfil'],
        'usuario'  => [
            'nome'  => $usuarioSessao['nome'],
            'email' => $usuarioSessao['email'],
            'tipo'  => $dados['tipo'],
        ],
    ]);

} catch (Throwable $e){
    responderServidor($e);
}
