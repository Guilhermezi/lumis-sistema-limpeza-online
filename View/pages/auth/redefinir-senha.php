<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/entrar.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>Lumis - Nova senha</title>
</head>
<body data-page="redefinir-senha">
<?php
$base = '../';
$active = '';
$tipo = ($_GET['tipo'] ?? '') === 'profissional' ? 'profissional' : 'cliente';
$token = preg_match('/^[a-f0-9]{64}$/', $_GET['token'] ?? '') ? $_GET['token'] : '';
include __DIR__ . '/../../partials/header.php';
?>
<main class="auth-page">
    <section class="auth-section">
        <div class="section-container">
            <div class="auth-card auth-card-compact">
                <div class="form-container sign-in">
                    <form class="auth-form" data-auth-form="redefinir" data-auth-tipo="<?= htmlspecialchars($tipo) ?>" novalidate>
                        <h1>Crie uma nova senha</h1>
                        <p class="auth-help">Use pelo menos 10 caracteres e evite senhas utilizadas em outros sites.</p>
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <div class="auth-field">
                            <label for="reset-password">Nova senha</label>
                            <input type="password" id="reset-password" name="senha" autocomplete="new-password" minlength="10" required>
                        </div>
                        <div class="auth-field">
                            <label for="reset-password-confirm">Confirmar nova senha</label>
                            <input type="password" id="reset-password-confirm" name="senha_confirma" autocomplete="new-password" minlength="10" required>
                        </div>
                        <div class="auth-message" data-auth-mensagem role="status" aria-live="polite" hidden></div>
                        <button type="submit" class="btn-primary auth-submit">Redefinir senha</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<script src="../../../Controller/Entrar.js"></script>
<script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
