<?php
require_once dirname(__DIR__, 3) . '/Controller/api/_bootstrap.php';
require_once dirname(__DIR__, 3) . '/Model/email_verificacao.php';

$mensagem = '';
$mensagemI18n = '';
$status = 'info';
$debugUrl = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $csrfRecebido = $_POST['_csrf'] ?? '';
    if (!is_string($csrfRecebido) || !hash_equals(tokenCsrf(), $csrfRecebido)) {
        $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
        $mensagemI18n = 'email-verificacao-sessao';
        $status = 'erro';
    } else {
        $tipo = (string) ($_POST['tipo'] ?? '');
        $email = normalizarEmail($_POST['email'] ?? '');
        $cfg = configTipo($tipo);
        if (!$cfg || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
            $mensagem = 'Informe um e-mail válido para receber as instruções.';
            $mensagemI18n = 'email-verificacao-email-invalido';
            $status = 'erro';
        } else {
            $stmt = $pdo->prepare(
                'SELECT ' . $cfg['pk'] . ' AS id, nome, email, email_verificado
                   FROM ' . $cfg['tabela'] . ' WHERE email = :email LIMIT 1'
            );
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && !(int) $usuario['email_verificado']) {
                $envio = criarVerificacaoEmail($pdo, $tipo, $usuario);
                $debugUrl = $envio['debug_url'];
            }

            $_SESSION['verificacao_email_flash'] = 'Se houver uma conta pendente para esse endereço, enviaremos um novo link de confirmação.';
            if ($debugUrl) {
                $_SESSION['email_verificacao_debug_url'] = $debugUrl;
            }
            header('Location: verificar-email.php?reenviado=1', true, 303);
            exit;
        }
    }
} elseif (isset($_GET['token'], $_GET['tipo'])) {
    $tipo = (string) $_GET['tipo'];
    $token = (string) $_GET['token'];
    $cfg = configTipo($tipo);

    if ($cfg && preg_match('/^[a-f0-9]{64}$/', $token)) {
        try {
            $pdo->beginTransaction();
            $busca = $pdo->prepare(
                'SELECT id_verificacao, usuario_id FROM VerificacaoEmail
                 WHERE tipo = :tipo AND token_hash = :token_hash AND usado_em IS NULL
                   AND expira_em > NOW() LIMIT 1 FOR UPDATE'
            );
            $busca->execute(['tipo' => $tipo, 'token_hash' => hash('sha256', $token)]);
            $verificacao = $busca->fetch();

            if ($verificacao) {
                $pdo->prepare(
                    'UPDATE ' . $cfg['tabela'] . ' SET email_verificado = 1 WHERE ' . $cfg['pk'] . ' = :id'
                )->execute(['id' => $verificacao['usuario_id']]);
                $pdo->prepare(
                    'UPDATE VerificacaoEmail SET usado_em = NOW()
                     WHERE tipo = :tipo AND usuario_id = :usuario_id AND usado_em IS NULL'
                )->execute(['tipo' => $tipo, 'usuario_id' => $verificacao['usuario_id']]);
                $pdo->commit();
                $mensagem = 'E-mail confirmado! Agora você já pode entrar na sua conta.';
                $mensagemI18n = 'email-verificacao-sucesso';
                $status = 'sucesso';
            } else {
                $pdo->rollBack();
                $mensagem = 'Este link expirou ou já foi utilizado. Você pode solicitar outro abaixo.';
                $mensagemI18n = 'email-verificacao-expirado';
                $status = 'erro';
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log('email-verificacao: ' . $e->getMessage());
            $mensagem = 'Não foi possível confirmar o e-mail agora. Tente novamente.';
            $mensagemI18n = 'email-verificacao-falha';
            $status = 'erro';
        }
    } else {
        $mensagem = 'O link de confirmação é inválido. Você pode solicitar outro abaixo.';
        $mensagemI18n = 'email-verificacao-link-invalido';
        $status = 'erro';
    }
} elseif (isset($_GET['reenviado'])) {
    $mensagem = $_SESSION['verificacao_email_flash'] ?? 'Se houver uma conta pendente, enviaremos um novo link.';
    $mensagemI18n = 'email-verificacao-reenviado';
    unset($_SESSION['verificacao_email_flash']);
    $status = 'info';
}

if ($mensagem === '' && isset($_SESSION['verificacao_email_flash'])) {
    $mensagem = (string) $_SESSION['verificacao_email_flash'];
    $mensagemI18n = 'email-verificacao-reenviado';
    unset($_SESSION['verificacao_email_flash']);
    $status = 'info';
}
if (getenv('APP_ENV') === 'development') {
    $debugUrl = $_SESSION['email_verificacao_debug_url'] ?? null;
    unset($_SESSION['email_verificacao_debug_url']);
}
$csrf = tokenCsrf();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/verificar-email.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title data-i18n="email-verificacao-titulo">Confirme seu e-mail - Lumis</title>
</head>
<body data-page="verificar-email">
    <?php
    $base = '../';
    $tituloI18n = '';
    $titulo = '';
    include __DIR__ . '/../../partials/header-minimo.php';
    ?>
    <main class="email-verification-page">
        <section class="email-verification-card" aria-labelledby="verificationTitle">
            <span class="email-verification-icon <?= htmlspecialchars($status) ?>"><i class="ri-mail-check-line"></i></span>
            <p class="email-verification-eyebrow" data-i18n="email-verificacao-seguranca">Segurança da conta</p>
            <h1 id="verificationTitle" data-i18n="<?= $status === 'sucesso' ? 'email-verificacao-confirmado-titulo' : 'email-verificacao-titulo' ?>"><?= $status === 'sucesso' ? 'E-mail confirmado' : 'Confirme seu e-mail' ?></h1>
            <p class="email-verification-copy"<?= $mensagemI18n ? ' data-i18n="' . htmlspecialchars($mensagemI18n, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>
                <?= $mensagem !== ''
                    ? htmlspecialchars($mensagem)
                    : 'Sua conta só poderá ser acessada depois da confirmação. Confira sua caixa de entrada e a pasta de spam.' ?>
            </p>

            <?php if ($debugUrl): ?>
                <a class="email-verification-debug" href="<?= htmlspecialchars($debugUrl, ENT_QUOTES, 'UTF-8') ?>">
                    <span data-i18n="email-verificacao-debug">Abrir link de verificação (ambiente local)</span>
                </a>
            <?php endif; ?>

            <?php if ($status !== 'sucesso'): ?>
                <form class="email-resend-form" method="post" action="verificar-email.php">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
                    <label for="verificationType" data-i18n="email-verificacao-tipo">Tipo de conta</label>
                    <select id="verificationType" name="tipo" required>
                        <option value="cliente" data-i18n="email-verificacao-cliente">Cliente</option>
                        <option value="profissional" data-i18n="email-verificacao-profissional">Profissional</option>
                    </select>
                    <label for="verificationEmail" data-i18n="email-verificacao-email">Seu e-mail</label>
                    <input id="verificationEmail" type="email" name="email" maxlength="100" autocomplete="email" required>
                    <button type="submit" class="btn-primary" data-i18n="email-verificacao-reenviar">Reenviar confirmação</button>
                </form>
            <?php endif; ?>

            <a class="email-verification-login" href="login.php" data-i18n="email-verificacao-voltar">Voltar para o login</a>
        </section>
    </main>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
