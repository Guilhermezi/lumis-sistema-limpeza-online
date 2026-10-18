<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script>
        try {
            var temaSalvo = localStorage.getItem('tema');
            document.documentElement.setAttribute('data-theme',
                temaSalvo || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        } catch (e) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">

    <!-- Link para o Design System global -->
    <link rel="stylesheet" href="../../css/style.css">
    <!-- Link para o arquivo CSS -->
    <link rel="stylesheet" href="../../css/decisao.css">

    <!-- Link para o RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>Lumis - Login</title>
</head>
<body data-page="decisao">
    <?php
$base = '../';
$active = '';
include __DIR__ . '/../../partials/header.php';
?>

    <main class="decision-page">

        <!-- ========== HERO ========== -->
        <section class="decision-hero">
            <div class="decision-hero-bg"></div>
            <div class="hero-content">
                <span class="decision-tag"><i class="ri-user-heart-line"></i> <span data-i18n="decisao-acesso">Acesso Lumis</span></span>
                <h1><span data-i18n="decisao-bem-vindo">Bem-vindo à</span> <span class="highlight">Lumis</span></h1>
                <p data-i18n="decisao-subtitulo">Escolha o perfil que melhor se encaixa em você e comece agora.</p>
            </div>
        </section>

        <!-- ========== ESCOLHA ========== -->
        <section class="decision-section">
            <div class="section-container">
                <div class="decision-card">
                    <h2 id="decisionTitle" data-i18n="decisao-cadastre-se-como">Cadastre-se como</h2>

                    <div class="decision-options">
                        <a href="login.php" class="decision-option">
                            <span class="decision-icon"><i class="ri-user-line"></i></span>
                            <strong data-i18n="decisao-usuario">Usuário</strong>
                            <span class="decision-option-desc" data-i18n="decisao-usuario-desc">Contrate serviços de limpeza e gerencie sua agenda.</span>
                        </a>

                        <a href="cadastro-trabalhador.php" class="decision-option">
                            <span class="decision-icon"><i class="ri-user-2-line"></i></span>
                            <strong data-i18n="decisao-trabalhador">Trabalhador</strong>
                            <span class="decision-option-desc" data-i18n="decisao-trabalhador-desc">Ofereça seus serviços e encontre novas oportunidades.</span>
                        </a>
                    </div>

                    <div class="decision-toggle">
                        <p>
                            <span id="decisionTogglePrefix" data-i18n="decisao-ja-tem-conta">Já tem uma conta?</span>
                            <button id="decisionToggleLink" class="decision-toggle-link" data-i18n="decisao-faca-login">Faça login</button>
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script src="../../../Controller/Decisao.js"></script>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
