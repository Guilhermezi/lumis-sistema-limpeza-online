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

    <!-- Estilos compartilhados da area de login/cadastro -->
    <link rel="stylesheet" href="../../css/entrar.css">

    <!-- Link para o RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <title>Lumis - Contribuidor</title>

</head>

<body data-page="cadastro-trabalhador">
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
                <span class="auth-tag"><i class="ri-user-2-line"></i> <span data-i18n="cadastro-trabalhador-contribuidores">Contribuidores Lumis</span></span>
                <h1><span data-i18n="cadastro-trabalhador-comece-trabalhar">Comece a trabalhar com a</span> <span class="highlight">Lumis</span></h1>
                <p data-i18n="cadastro-trabalhador-subtitulo">Crie sua conta de contribuidor para oferecer seus serviços, receber avaliações e encontrar novos clientes.</p>
            </div>
        </section>

        <!-- ========== AUTH ========== -->
        <section class="auth-section">
            <div class="section-container">

                <div class="auth-card" id="container">

                    <!-- Cadastro -->
                    <div class="form-container sign-up">
                        <form class="auth-form" data-auth-form="cadastro" data-auth-tipo="profissional"
                              data-auth-redirecionar="<?= $base ?>perfil-profissional.php"
                              action="../../../Controller/api/cadastro.php" method="post" novalidate>
                            <h1 data-i18n="cadastro-trabalhador-criar-conta">Criar conta</h1>
                            <div class="social-login">
                                <div class="social-buttons">
                                    <a class="social-btn google" aria-label="Cadastrar com Google"><i class="ri-google-line"></i></a>
                                    <a class="social-btn facebook" aria-label="Cadastrar com Facebook"><i class="ri-facebook-circle-fill"></i></a>
                                    <a class="social-btn apple" aria-label="Cadastrar com Apple"><i class="ri-apple-fill"></i></a>
                                    <a class="social-btn linkedin" aria-label="Cadastrar com LinkedIn"><i class="ri-linkedin-fill"></i></a>
                                </div>
                            </div>
                            <span class="auth-divider" data-i18n="cadastro-trabalhador-ou-email">ou use o email para se cadastrar</span>

                            <div class="auth-field">
                                <label for="signup-name" data-i18n="cadastro-trabalhador-nome-completo">Nome Completo</label>
                                <input type="text" id="signup-name" name="nome" placeholder="Nome Completo" data-i18n-placeholder="cadastro-trabalhador-nome-completo-placeholder" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-email" data-i18n="cadastro-trabalhador-email">Email</label>
                                <input type="email" id="signup-email" name="email" placeholder="seuemail@exemplo.com" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-telefone" data-i18n="cadastro-trabalhador-telefone">Telefone</label>
                                <input type="tel" id="signup-telefone" name="telefone" inputmode="numeric"
                                       placeholder="(11) 98765-4321" data-i18n-placeholder="cadastro-trabalhador-telefone-placeholder"
                                       data-mask-phone autocomplete="tel" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-nascimento" data-i18n="cadastro-trabalhador-nascimento">Data de nascimento</label>
                                <input type="date" id="signup-nascimento" name="data_nascimento" autocomplete="bday">
                            </div>
                            <div class="auth-field">
                                <label for="signup-regiao" data-i18n="cadastro-trabalhador-regiao">Região de atuação</label>
                                <input type="text" id="signup-regiao" name="regiao_atuacao"
                                       placeholder="Ex.: São Paulo - SP" data-i18n-placeholder="cadastro-trabalhador-regiao-placeholder" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-experiencia" data-i18n="cadastro-trabalhador-experiencia">Experiência</label>
                                <input type="text" id="signup-experiencia" name="experiencia"
                                       placeholder="Ex.: 5 anos em limpeza residencial" data-i18n-placeholder="cadastro-trabalhador-experiencia-placeholder">
                            </div>
                            <div class="auth-field">
                                <label for="signup-password" data-i18n="cadastro-trabalhador-senha">Senha</label>
                                <input type="password" id="signup-password" name="senha" placeholder="••••••••" autocomplete="new-password" required>
                            </div>
                            <div class="auth-field">
                                <label for="signup-confirmar" data-i18n="cadastro-trabalhador-confirmar-senha">Confirmar senha</label>
                                <input type="password" id="signup-confirmar" name="senha_confirma" placeholder="••••••••" autocomplete="new-password" required>
                            </div>

                            <div class="auth-message" data-auth-mensagem role="status" aria-live="polite" hidden></div>

                            <button type="submit" class="btn-primary auth-submit" data-i18n="cadastro-trabalhador-btn-criar-conta">Criar conta</button>
                        </form>
                    </div>

                    <!-- Login -->
                    <div class="form-container sign-in">
                        <form class="auth-form" data-auth-form="login" data-auth-tipo="profissional"
                              data-auth-redirecionar="<?= $base ?>perfil-profissional.php"
                              action="../../../Controller/api/login.php" method="post" novalidate>
                            <h1 data-i18n="cadastro-trabalhador-entrar">Entrar</h1>
                            <div class="social-login">
                                <div class="social-buttons">
                                    <a class="social-btn google" aria-label="Entrar com Google"><i class="ri-google-line"></i></a>
                                    <a class="social-btn facebook" aria-label="Entrar com Facebook"><i class="ri-facebook-circle-fill"></i></a>
                                    <a class="social-btn apple" aria-label="Entrar com Apple"><i class="ri-apple-fill"></i></a>
                                    <a class="social-btn linkedin" aria-label="Entrar com LinkedIn"><i class="ri-linkedin-fill"></i></a>
                                </div>
                            </div>
                            <span class="auth-divider" data-i18n="cadastro-trabalhador-ou-senha">ou use sua senha de e-mail</span>

                            <div class="auth-field">
                                <label for="login-email" data-i18n="cadastro-trabalhador-email">Email</label>
                                <input type="email" id="login-email" name="email" placeholder="seuemail@exemplo.com" required>
                            </div>
                            <div class="auth-field">
                                <label for="login-password" data-i18n="cadastro-trabalhador-senha">Senha</label>
                                <input type="password" id="login-password" name="senha" placeholder="••••••••" autocomplete="current-password" required>
                            </div>

                            <a href="#" class="auth-forgot" data-i18n="cadastro-trabalhador-esqueceu-senha">Esqueceu sua senha?</a>

                            <div class="auth-message" data-auth-mensagem role="status" aria-live="polite" hidden></div>
                            <button type="submit" class="btn-primary auth-submit" data-i18n="cadastro-trabalhador-btn-entrar">Entrar</button>
                        </form>
                    </div>

                    <!-- Painel animado -->
                    <div class="toggle-container">
                        <div class="toggle">
                            <div class="toggle-panel toggle-left">
                                <h2 data-i18n="cadastro-trabalhador-bem-vindo-volta">Bem-vindo de volta!</h2>
                                <p data-i18n="cadastro-trabalhador-toggle-left-desc">Insira seus dados pessoais para acessar sua conta de contribuidor e começar a trabalhar</p>
                                <button class="toggle-btn" id="login" data-i18n="cadastro-trabalhador-toggle-entrar">Entrar</button>
                                <a href="login.php" data-i18n="cadastro-trabalhador-entrar-usuario">Entrar como usuário</a>
                            </div>
                            <div class="toggle-panel toggle-right">
                                <h2 data-i18n="cadastro-trabalhador-ola-bem-vindo">Olá, seja bem-vindo!</h2>
                                <p data-i18n="cadastro-trabalhador-toggle-right-desc">Cadastre-se como contribuidor para oferecer seus serviços e encontrar novos clientes</p>
                                <button class="toggle-btn" id="register" data-i18n="cadastro-trabalhador-toggle-criar-conta">Criar conta de contribuidor</button>
                                <a href="login.php" data-i18n="cadastro-trabalhador-criar-conta-usuario">Criar conta de usuário</a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Toggle mobile (apenas telas menores) -->
                <div class="auth-mobile-toggle">
                    <button class="auth-tab active" id="mobile-login-tab" data-i18n="cadastro-trabalhador-mobile-entrar">Entrar</button>
                    <button class="auth-tab" id="mobile-register-tab" data-i18n="cadastro-trabalhador-mobile-criar-conta">Criar conta</button>
                </div>

                <p class="auth-terms" data-i18n="cadastro-trabalhador-termos">Ao continuar, você concorda com nossos <a href="../empresa/termos-de-uso.php">Termos de Uso</a> e <a href="../empresa/politica-de-privacidade.php">Política de Privacidade</a>.</p>

            </div>
        </section>

    </main>

    <script src="../../../Controller/Entrar.js"></script>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
