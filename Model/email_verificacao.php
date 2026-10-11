<?php

/**
 * Cria uma confirmação de e-mail de uso único e tenta enviá-la.
 * Em ambiente development, devolve o link de teste para o usuário.
 */
function criarVerificacaoEmail(PDO $pdo, string $tipo, array $usuario, bool $respeitarLimite = true): array
{
    $cfg = configTipo($tipo);
    if (!$cfg || empty($usuario['id']) || empty($usuario['email'])) {
        throw new InvalidArgumentException('dados-verificacao-invalidos');
    }

    if ($respeitarLimite) {
        $limite = $pdo->prepare(
            'SELECT COUNT(*) FROM VerificacaoEmail
             WHERE tipo = :tipo AND usuario_id = :usuario_id
               AND criado_em >= DATE_SUB(NOW(), INTERVAL 1 HOUR)'
        );
        $limite->execute(['tipo' => $tipo, 'usuario_id' => $usuario['id']]);
        if ((int) $limite->fetchColumn() >= 3) {
            return ['enviado' => false, 'limitado' => true, 'debug_url' => null];
        }
    }

    $token = bin2hex(random_bytes(32));
    $url = urlBaseAplicacao() . '/View/pages/auth/verificar-email.php?tipo=' . rawurlencode($tipo)
        . '&token=' . rawurlencode($token);

    $pdo->prepare(
        'UPDATE VerificacaoEmail SET usado_em = NOW()
         WHERE tipo = :tipo AND usuario_id = :usuario_id AND usado_em IS NULL'
    )->execute(['tipo' => $tipo, 'usuario_id' => $usuario['id']]);

    $pdo->prepare(
        'INSERT INTO VerificacaoEmail (tipo, usuario_id, token_hash, expira_em)
         VALUES (:tipo, :usuario_id, :token_hash, DATE_ADD(NOW(), INTERVAL 24 HOUR))'
    )->execute([
        'tipo' => $tipo,
        'usuario_id' => $usuario['id'],
        'token_hash' => hash('sha256', $token),
    ]);

    $remetente = getenv('MAIL_FROM') ?: 'no-reply@lumis.local';
    if (!filter_var($remetente, FILTER_VALIDATE_EMAIL)) {
        $remetente = 'no-reply@lumis.local';
    }
    $nome = trim(str_replace(["\r", "\n"], '', (string) ($usuario['nome'] ?? '')));
    $assunto = 'Confirme seu e-mail - Lumis';
    $mensagem = "Olá, {$nome}.\n\nConfirme seu endereço de e-mail acessando o link abaixo. "
        . "O link expira em 24 horas e só pode ser usado uma vez.\n\n{$url}\n\n"
        . "Se você não criou uma conta na Lumis, ignore esta mensagem.";
    $enviado = @mail(
        (string) $usuario['email'],
        $assunto,
        $mensagem,
        "From: Lumis <{$remetente}>\r\nContent-Type: text/plain; charset=UTF-8"
    );
    if (!$enviado) {
        error_log('email-verificacao: falha no envio para usuario ' . (int) $usuario['id']);
    }

    $development = (getenv('APP_ENV') ?: 'production') === 'development';
    return [
        'enviado' => $enviado,
        'limitado' => false,
        'debug_url' => $development ? $url : null,
    ];
}

function urlBaseAplicacao(): string
{
    $base = rtrim((string) getenv('APP_URL'), '/');
    if ($base !== '') {
        return $base;
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $host = preg_replace('/[^a-zA-Z0-9.:-]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $root = preg_replace('#/(?:Controller/api|View/pages/auth)$#', '', $scriptDirectory);
    return ($https ? 'https' : 'http') . '://' . $host . rtrim($root, '/');
}
