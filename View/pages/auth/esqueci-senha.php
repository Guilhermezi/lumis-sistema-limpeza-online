<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/entrar.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>Lumis - Recuperar senha</title>
</head>
<body data-page="recuperar-senha">
<?php
$base = '../';
$active = '';
$tipo = ($_GET['tipo'] ?? '') === 'profissional' ? 'profissional' : 'cliente';
include __DIR__ . '/../../partials/header.php';
?>
<main class="auth-page">
    <section class="auth-section">
        <div class="section-container">
            <div class="auth-card auth-card-compact">
                <div class="form-container sign-in">
                    <form class="auth-form" data-auth-form="recuperar" data-auth-tipo="<?= htmlspecialchars($tipo) ?>" novalidate>
                        <h1>Recuperar senha</h1>
                        <p class="auth-help">Informe seu e-mail. Se a conta existir, você receberá um link válido por 30 minutos.</p>
                        <div class="auth-field">
                            <label for="recovery-email">E-mail</label>
                            <input type="email" id="recovery-email" name="email" autocomplete="email" required>
                        </div>
                        <div class="auth-message" data-auth-mensagem role="status" aria-live="polite" hidden></div>
                        <button type="submit" class="btn-primary auth-submit">Enviar instruções</button>
                        <a class="auth-back" href="login.php">Voltar ao login</a>
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
