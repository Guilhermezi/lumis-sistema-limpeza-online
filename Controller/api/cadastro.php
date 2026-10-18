<?php
// POST /Controller/api/cadastro.php
// Cria a conta (cliente ou profissional) e inicia a confirmação do e-mail.

require __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__, 2) . '/Model/email_verificacao.php';
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

    $usuarioNovo = [
        'id' => $id,
        'nome' => trim($dados['nome']),
        'email' => normalizarEmail($dados['email']),
    ];
    $verificacao = criarVerificacaoEmail($pdo, $dados['tipo'], $usuarioNovo, false);
    if ($verificacao['debug_url']) {
        $_SESSION['email_verificacao_debug_url'] = $verificacao['debug_url'];
    }

    responder([
        'ok'       => true,
        'email_enviado' => (bool) $verificacao['enviado'],
        'redirect' => 'verificar-email.php',
        'mensagem' => 'Conta criada. Confirme seu e-mail para ativá-la.',
    ]);

} catch (Throwable $e){
    responderServidor($e);
}
