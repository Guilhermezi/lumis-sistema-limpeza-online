<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

/** Envia e-mails pelo SMTP configurado; só usa mail() no ambiente local. */
function enviarEmailLumis(string $destinatario, string $nome, string $assunto, string $mensagem): bool
{
    $ambiente = configuracaoLumis('APP_ENV', 'production');
    $remetente = trim((string) configuracaoLumis('MAIL_FROM', $ambiente === 'development' ? 'no-reply@lumis.local' : ''));

    if (!filter_var($destinatario, FILTER_VALIDATE_EMAIL) || !filter_var($remetente, FILTER_VALIDATE_EMAIL)) {
        error_log('lumis-email: destinatário ou remetente inválido.');
        return false;
    }

    $host = trim((string) configuracaoLumis('SMTP_HOST', ''));
    $usuario = (string) configuracaoLumis('SMTP_USERNAME', '');
    $senha = (string) configuracaoLumis('SMTP_PASSWORD', '');

    if ($host !== '' && $usuario !== '' && $senha !== '') {
        if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
            error_log('lumis-email: dependências ausentes; execute composer install.');
            return false;
        }

        try {
            $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mailer->isSMTP();
            $mailer->Host = $host;
            $mailer->SMTPAuth = true;
            $mailer->Username = $usuario;
            $mailer->Password = $senha;
            $mailer->Port = (int) configuracaoLumis('SMTP_PORT', 587);
            $mailer->Timeout = 12;

            $criptografia = strtolower((string) configuracaoLumis('SMTP_ENCRYPTION', 'tls'));
            if ($criptografia === 'tls') {
                $mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($criptografia === 'ssl') {
                $mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } else {
                error_log('lumis-email: SMTP_ENCRYPTION deve ser tls ou ssl.');
                return false;
            }

            $mailer->CharSet = 'UTF-8';
            $mailer->setFrom($remetente, (string) configuracaoLumis('MAIL_FROM_NAME', 'Lumis'));
            $mailer->addAddress($destinatario, trim(str_replace(["\r", "\n"], '', $nome)));
            $mailer->Subject = $assunto;
            $mailer->Body = $mensagem;
            $mailer->isHTML(false);
            return $mailer->send();
        } catch (Throwable $erro) {
            $detalheSmtp = isset($mailer->ErrorInfo) ? (string) $mailer->ErrorInfo : '';
            $detalheSmtp = substr(str_replace(["\r", "\n"], ' ', $detalheSmtp), 0, 500);
            error_log('lumis-email: falha SMTP (' . get_class($erro) . '): ' . $detalheSmtp);
            return false;
        }
    }

    if ($ambiente === 'production') {
        error_log('lumis-email: SMTP não configurado em produção.');
        return false;
    }

    $nomeRemetente = trim(str_replace(["\r", "\n"], '', (string) configuracaoLumis('MAIL_FROM_NAME', 'Lumis')));
    $cabecalhos = "From: {$nomeRemetente} <{$remetente}>\r\nContent-Type: text/plain; charset=UTF-8";
    return @mail($destinatario, $assunto, $mensagem, $cabecalhos);
}
