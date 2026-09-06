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
    <link rel="stylesheet" href="../../css/contato.css">

    <!-- Link para o RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>Lumis - Contato</title>
</head>
<body data-page="contato">
     <?php
$base = '../';
$active = '';
include __DIR__ . '/../../partials/header.php';
?>

    <main class="contact-page">

        <!-- ========== HERO ========== -->
        <section class="contact-hero">
            <div class="contact-hero-bg"></div>
            <div class="hero-content">
                <span class="contact-tag"><i class="ri-chat-2-line"></i> <span data-i18n="contato-tag">Estamos aqui para ajudar</span></span>
                <h1 data-i18n="contato-hero-titulo">Entre em <span class="highlight">contato</span> conosco</h1>
                <p data-i18n="contato-hero-subtitulo">Tem alguma dúvida, sugestão ou deseja compartilhar sua experiência com um ambiente mais limpo e agradável? Fale com a gente!</p>
            </div>
        </section>

        <!-- ========== CONTATO ========== -->
        <section class="contact-section">
            <div class="section-container">
                <div class="contact-layout">

                    <!-- Formulário -->
                    <div class="contact-form-card">
                        <h2 data-i18n="contato-form-titulo">Envie sua mensagem</h2>
                        <p class="contact-form-desc" data-i18n="contato-form-desc">Preencha os campos abaixo e retornaremos o mais rápido possível.</p>

                        <form id="contatoForm" novalidate>
                            <div class="form-group">
                                <label for="nome" data-i18n="contato-label-nome">Nome</label>
                                <input type="text" id="nome" name="nome" data-i18n-placeholder="contato-placeholder-nome" placeholder="Seu nome completo" required>
                            </div>

                            <div class="form-group">
                                <label for="email" data-i18n="contato-label-email">E-mail</label>
                                <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>
                            </div>

                            <div class="form-group">
                                <label for="Telefone" data-i18n="contato-label-telefone">Telefone</label>
                                <input type="tel" id="Telefone" name="Telefone" placeholder="(11) 90000-0000" required>
                            </div>

                            <div class="form-group">
                                <label for="mensagem" data-i18n="contato-label-mensagem">Mensagem</label>
                                <textarea id="mensagem" name="mensagem" rows="5" data-i18n-placeholder="contato-placeholder-mensagem" placeholder="Escreva sua mensagem aqui..." required></textarea>
                            </div>

                            <button type="submit" class="btn-primary contact-submit"><span data-i18n="contato-botao-enviar">Enviar mensagem</span> <i class="ri-send-plane-line"></i></button>
                            <p class="form-success" id="formSuccess" hidden><i class="ri-checkbox-circle-fill"></i> <span data-i18n="contato-form-sucesso">Mensagem enviada com sucesso! Em breve retornaremos.</span></p>
                        </form>
                    </div>

                    <!-- Informações de contato -->
                    <div class="contact-info-card">
                        <img src="../../img/MensagemDaLumisSemFundo.png" alt="Logo mensagem Lumis" class="contact-logo">

                        <h2 data-i18n="contato-info-titulo">Informações de Contato</h2>

                        <ul class="contact-list">
                            <li class="contact-item">
                                <span class="contact-item-icon"><i class="ri-mail-line"></i></span>
                                <div>
                                    <strong data-i18n="contato-info-email">Email</strong>
                                    <a href="mailto:lumisstartup@gmail.com">lumisstartup@gmail.com</a>
                                </div>
                            </li>
                            <li class="contact-item">
                                <span class="contact-item-icon"><i class="ri-phone-line"></i></span>
                                <div>
                                    <strong data-i18n="contato-info-telefone">Telefone</strong>
                                    <a href="tel:+5511981214352">(11) 98121-4352</a>
                                </div>
                            </li>
                            <li class="contact-item">
                                <span class="contact-item-icon"><i class="ri-map-pin-line"></i></span>
                                <div>
                                    <strong data-i18n="contato-info-localizacao">Localização</strong>
                                    <span>Av. Amador Bueno da Veiga, 4430<br>São Paulo - SP</span>
                                </div>
                            </li>
                            <li class="contact-item">
                                <span class="contact-item-icon"><i class="ri-time-line"></i></span>
                                <div>
                                    <strong data-i18n="contato-info-horarios">Horários de atendimento</strong>
                                    <span data-i18n="contato-info-horarios-valor">Segunda a Sexta: 9:00 - 18:00<br>Sábado: 10:00 - 14:00<br>Domingo: Fechado</span>
                                </div>
                            </li>
                        </ul>

                        <div class="contact-social">
                            <span class="contact-social-label" data-i18n="contato-social-label">Siga a Lumis</span>
                            <div class="contact-social-icons">
                                <a href="https://www.facebook.com/profile.php?id=61580024704625" target="_blank" aria-label="Facebook"><i class="ri-facebook-circle-fill"></i></a>
                                <a href="https://www.instagram.com/lumisstartup/?next=%2F" target="_blank" aria-label="Instagram"><i class="ri-instagram-fill"></i></a>
                                <a href="https://x.com/LumisStartup" target="_blank" aria-label="Twitter / X"><i class="ri-twitter-x-fill"></i></a>
                                <a href="#" aria-label="YouTube"><i class="ri-youtube-line"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ========== CTA ========== -->
        <section class="contact-cta">
            <div class="section-container">
                <div class="cta-content">
                    <h2 data-i18n="contato-cta-titulo">Precisa de mais ajuda?</h2>
                    <p data-i18n="contato-cta-subtitulo">Confira nossa página de Ajuda com respostas para as perguntas mais frequentes.</p>
                    <div class="cta-buttons">
                        <a href="ajuda.php" class="btn-secondary" data-i18n="contato-cta-botao">Ir para a Ajuda</a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>

    <script src="../../../Controller/Contato.js"></script>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
