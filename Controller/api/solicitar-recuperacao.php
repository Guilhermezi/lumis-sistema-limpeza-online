<?php
// POST /Controller/api/solicitar-recuperacao.php
// Gera um token descartável de recuperação sem revelar se o e-mail existe.
require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

try {
    $dados = lerDados();
    $cfg = tipoValido($dados['tipo'] ?? null);
    $email = normalizarEmail($dados['email'] ?? '');

    if (mb_strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        responderErro('email-invalido', ['email' => 'email-invalido'], 422);
    }

    $sql = 'SELECT ' . $cfg['pk'] . ' AS id, nome, email FROM ' . $cfg['tabela'] . ' WHERE email = :email LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();

    // A resposta é sempre igual para impedir enumeração de contas.
    $resposta = [
        'ok' => true,
        'mensagem' => 'Se existir uma conta com esse e-mail, enviaremos as instruções de recuperação.',
    ];

    if ($usuario){
        $limite = $pdo->prepare(
            'SELECT COUNT(*) FROM RecuperacaoSenha
             WHERE tipo = :tipo AND usuario_id = :usuario_id
               AND criado_em >= DATE_SUB(NOW(), INTERVAL 1 HOUR)'
        );
        $limite->execute(['tipo' => $dados['tipo'], 'usuario_id' => $usuario['id']]);

        // Evita que o endpoint seja usado para bombardear uma conta com e-mails.
        if ((int) $limite->fetchColumn() < 3){
            $token = bin2hex(random_bytes(32));
            $hash = hash('sha256', $token);
            $expira = (new DateTimeImmutable('+30 minutes'))->format('Y-m-d H:i:s');

            // Invalida solicitações anteriores: apenas o link mais recente funciona.
            $pdo->prepare(
                'UPDATE RecuperacaoSenha SET usado_em = NOW()
                 WHERE tipo = :tipo AND usuario_id = :usuario_id AND usado_em IS NULL'
            )->execute(['tipo' => $dados['tipo'], 'usuario_id' => $usuario['id']]);

            $pdo->prepare(
                'INSERT INTO RecuperacaoSenha (tipo, usuario_id, token_hash, expira_em)
                 VALUES (:tipo, :usuario_id, :token_hash, :expira_em)'
            )->execute([
                'tipo' => $dados['tipo'],
                'usuario_id' => $usuario['id'],
                'token_hash' => $hash,
                'expira_em' => $expira,
            ]);

            // APP_URL é preferível em produção; a montagem pela requisição é só fallback local.
            $base = rtrim((string) getenv('APP_URL'), '/');
            if ($base === ''){
                $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
                $host = preg_replace('/[^a-zA-Z0-9.:-]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
                $pasta = preg_replace('#/Controller/api$#', '', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
                $base = ($https ? 'https' : 'http') . '://' . $host . $pasta;
            }

            $url = $base . '/View/pages/auth/redefinir-senha.php?tipo=' . rawurlencode($dados['tipo'])
                . '&token=' . rawurlencode($token);
            $remetente = getenv('MAIL_FROM') ?: 'no-reply@lumis.local';
            $assunto = 'Redefinição de senha - Lumis';
            $mensagem = "Olá, {$usuario['nome']}.\n\nUse o link abaixo para redefinir sua senha. "
                . "Ele expira em 30 minutos e só pode ser usado uma vez.\n\n{$url}\n\n"
                . "Se você não fez esta solicitação, ignore esta mensagem.";
            $enviado = @mail($usuario['email'], $assunto, $mensagem, "From: Lumis <{$remetente}>\r\nContent-Type: text/plain; charset=UTF-8");
            if (!$enviado) error_log('api: não foi possível enviar e-mail de recuperação para o usuário ' . $usuario['id']);

            // Facilita o teste local sem expor o token quando APP_ENV é production.
            if ((getenv('APP_ENV') ?: 'production') === 'development'){
                $resposta['debug_url'] = $url;
            }
        }
    }

    responder($resposta);
} catch (Throwable $e){
    responderServidor($e);
}
