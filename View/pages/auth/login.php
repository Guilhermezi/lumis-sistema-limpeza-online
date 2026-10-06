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

    <!-- Design System global -->
    <link rel="stylesheet" href="../../css/style.css">

    <!-- Link para o arquivo CSS -->
    <link rel="stylesheet" href="../../css/entrar.css">

    <!-- Link para o RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <title>Lumis - Login</title>

</head>

<body data-page="cadastro">
    <?php
$base = '../';
$active = '';
include __DIR__ . '/../../partials/header.php';
?>

    <main class="auth-page">

        <!-- ========== HERO ========== -->
        <section class="auth-hero">
            <div class="auth-hero-bg"></div>
            <div class="hero-content">
                <span class="auth-tag"><i class="ri-login-box-line"></i> <span data-i18n="cadastro-acesse-conta">Acesse sua conta</span></span>
                <h1><span data-i18n="cadastro-bem-vindo">Bem-vindo à</span> <span class="highlight">Lumis</span></h1>
                <p data-i18n="cadastro-subtitulo">Entre ou crie sua conta para contratar serviços, acompanhar sua agenda e gerenciar tudo em um só lugar.</p>
            </div>
        </section>

        <!-- ========== AUTH ========== -->
        <section class="auth-section">
            <div class="section-container">

                <div class="auth-card" id="container">

                    <!-- Cadastro -->
                    <div class="form-container sign-up">
                        <form class="auth-form" data-auth-form="cadastro" data-auth-tipo="cliente"
                              data-auth-redirecionar="<?= $base ?>perfil.php"
                              action="../../../Controller/api/cadastro.php" method="post" novalidate>
                            <h1 data-i18n="cadastro-criar-conta">Criar conta</h1>
                            <div class="social-login">
                                <div class="social-buttons">
                                    <a class="social-btn google" aria-label="Cadastrar com Google"><i class="ri-google-line"></i></a>
                                    <a class="social-btn facebook" aria-label="Cadastrar com Facebook"><i class="ri-facebook-circle-fill"></i></a>
                                    <a class="social-btn apple" aria-label="Cadastrar com Apple"><i class="ri-apple-fill"></i></a>
                                    <a class="social-btn linkedin" aria-label="Cadastrar com LinkedIn"><i class="ri-linkedin-fill"></i></a>
                                </div>
                            </div>
                            <span class="auth-divider" data-i18n="cadastro-ou-email">ou use o email para se cadastrar</span>

                            <div class="auth-field">
                                <label for="signup-name" data-i18n="cadastro-nome-completo">Nome Completo</label>
                                <input type="text" id="signup-name" name="nome" placeholder="Nome Completo" data-i18n-placeholder="cadastro-nome-completo-placeholder" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-email" data-i18n="cadastro-email">Email</label>
                                <input type="email" id="signup-email" name="email" placeholder="seuemail@exemplo.com" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-telefone" data-i18n="cadastro-telefone">Telefone</label>
                                <input type="tel" id="signup-telefone" name="telefone" inputmode="numeric"
                                       placeholder="(11) 98765-4321" data-i18n-placeholder="cadastro-telefone-placeholder"
                                       data-mask-phone autocomplete="tel" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-nascimento" data-i18n="cadastro-nascimento">Data de nascimento</label>
                                <input type="date" id="signup-nascimento" name="data_nascimento" autocomplete="bday">
                            </div>
                            <div class="auth-field">
                                <label for="signup-password" data-i18n="cadastro-senha">Senha</label>
                                <input type="password" id="signup-password" name="senha" placeholder="••••••••" autocomplete="new-password" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-confirmar" data-i18n="cadastro-confirmar-senha">Confirmar senha</label>
                                <input type="password" id="signup-confirmar" name="senha_confirma" placeholder="••••••••" autocomplete="new-password" required>
                            </div>

                            <div class="auth-message" data-auth-mensagem role="status" aria-live="polite" hidden></div>

                            <button type="submit" class="btn-primary auth-submit" data-i18n="cadastro-btn-criar-conta">Criar conta</button>
                        </form>
                    </div>

                    <!-- Login -->
                    <div class="form-container sign-in">
                        <form class="auth-form" data-auth-form="login" data-auth-tipo="cliente"
                              data-auth-redirecionar="<?= $base ?>perfil.php"
                              action="../../../Controller/api/login.php" method="post" novalidate>
                            <h1 data-i18n="cadastro-entrar">Entrar</h1>
                            <div class="social-login">
                                <div class="social-buttons">
                                    <a class="social-btn google" aria-label="Entrar com Google"><i class="ri-google-line"></i></a>
                                    <a class="social-btn facebook" aria-label="Entrar com Facebook"><i class="ri-facebook-circle-fill"></i></a>
                                    <a class="social-btn apple" aria-label="Entrar com Apple"><i class="ri-apple-fill"></i></a>
                                    <a class="social-btn linkedin" aria-label="Entrar com LinkedIn"><i class="ri-linkedin-fill"></i></a>
                                </div>
                            </div>
                            <span class="auth-divider" data-i18n="cadastro-ou-senha">ou use sua senha de e-mail</span>

                            <div class="auth-field">
                                <label for="login-email" data-i18n="cadastro-email">Email</label>
                                <input type="email" id="login-email" name="email" placeholder="seuemail@exemplo.com" required>
                            </div>
                            <div class="auth-field">
                                <label for="login-password" data-i18n="cadastro-senha">Senha</label>
                                <input type="password" id="login-password" name="senha" placeholder="••••••••" autocomplete="current-password" required>
                            </div>

                            <a href="#" class="auth-forgot" data-i18n="cadastro-esqueceu-senha">Esqueceu sua senha?</a>

                            <div class="auth-message" data-auth-mensagem role="status" aria-live="polite" hidden></div>

                            <button type="submit" class="btn-primary auth-submit" data-i18n="cadastro-btn-entrar">Entrar</button>
                        </form>
                    </div>

                    <!-- Painel animado -->
                    <div class="toggle-container">
                        <div class="toggle">
                            <div class="toggle-panel toggle-left">
                                <h2 data-i18n="cadastro-bem-vindo-volta">Bem-vindo de volta!</h2>
                                <p data-i18n="cadastro-toggle-left-desc">Insira seus dados pessoais para usar todos os recursos do site</p>
                                <button class="toggle-btn" id="login" data-i18n="cadastro-toggle-entrar">Entrar</button>
                                <a href="cadastro-trabalhador.php" data-i18n="cadastro-entrar-contribuidor">Entrar como contribuidor</a>
                            </div>
                            <div class="toggle-panel toggle-right">
                                <h2 data-i18n="cadastro-ola-bem-vindo">Olá, seja bem-vindo!</h2>
                                <p data-i18n="cadastro-toggle-right-desc">Cadastre-se com seus dados pessoais para acessar todos os recursos do site</p>
                                <button class="toggle-btn" id="register" data-i18n="cadastro-toggle-criar-conta">Criar conta</button>
                                <a href="cadastro-trabalhador.php" data-i18n="cadastro-criar-conta-contribuidor">Criar conta de contribuidor</a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Toggle mobile (apenas telas menores) -->
                <div class="auth-mobile-toggle">
                    <button class="auth-tab active" id="mobile-login-tab" data-i18n="cadastro-mobile-entrar">Entrar</button>
                    <button class="auth-tab" id="mobile-register-tab" data-i18n="cadastro-mobile-criar-conta">Criar conta</button>
                </div>

                <p class="auth-terms" data-i18n="cadastro-termos">Ao continuar, você concorda com nossos <a href="../empresa/termos-de-uso.php">Termos de Uso</a> e <a href="../empresa/politica-de-privacidade.php">Política de Privacidade</a>.</p>

            </div>
        </section>

    </main>

    <script src="../../../Controller/Entrar.js"></script>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
