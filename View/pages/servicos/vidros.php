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
    <title>Lumis - Vidros</title>
     <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/servicos.css">
</head>
<body data-page="pages-footer">
    <?php
$base = '../';
$active = '';
include __DIR__ . '/../../partials/header-simples.php';
?>

    <!-- Hero Section -->
    <section class="hero-vidros">
        <div class="hero-content">
            <div class="hero-text">
                <h1 data-i18n="pf-vidros-hero-titulo">Limpeza de <span class="highlight">Vidros</span></h1>
                <p data-i18n="pf-vidros-hero-subtitulo">Especialistas em renovar e higienizar vidros com técnicas profissionais</p>
                <div class="hero-buttons">
                    <a href="#" class="btn-primary" data-i18n="pf-vidros-btn-orcamento">Solicitar orçamento</a>
                    <a href="#" class="btn-secondary" data-i18n="pf-vidros-btn-como-funciona">Como funciona</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre o Serviço -->
    <section class="sobre-servico">
        <div class="sobre-content">
            <div class="texto-servico">
                <h2 data-i18n="pf-vidros-sobre-titulo">Por que escolher nossa limpeza de vidros?</h2>
                <p data-i18n="pf-vidros-sobre-texto">
                    Nossos vidros acumulam sujeira, manchas e resíduos ao longo do tempo. Nossa equipe especializada
                    utiliza equipamentos profissionais e produtos específicos para devolver a transparência e brilho aos seus vidros.
                </p>
                <ul class="beneficios-lista">
                   <li data-i18n="pf-vidros-beneficio-1"> Limpeza profunda e eficaz</li>
                    <li data-i18n="pf-vidros-beneficio-2"> Produtos seguros e sustentáveis</li>
                    <li data-i18n="pf-vidros-beneficio-3"> Técnicas que evitam riscos e manchas</li>
                    <li data-i18n="pf-vidros-beneficio-4"> Atendimento rápido e flexível</li>
                    <li data-i18n="pf-vidros-beneficio-5"> Preços competitivos e transparência</li>
                </ul>
            </div>
            <div class="imagem-servico">
                <div class="vidro-visual"></div>
            </div>
        </div>
    </section>

    <!-- Processo de Limpeza -->
    <section class="processo-section" id="processo">
        <div class="processo-content">
            <h2 class="section-title" data-i18n="pf-vidros-processo-titulo">Nosso processo profissional</h2>
            <div class="etapas-grid">
                <div class="etapa-card">
                    <div class="etapa-numero">1</div>
                    <h3 data-i18n="pf-vidros-etapa-1-titulo">Avaliação</h3>
                    <p data-i18n="pf-vidros-etapa-1-texto">Identificamos o tipo de vidro, manchas e sujeiras para escolher a melhor técnica de limpeza.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">2</div>
                    <h3 data-i18n="pf-vidros-etapa-2-titulo">Aspiração</h3>
                    <p data-i18n="pf-vidros-etapa-2-texto">Removemos poeira, pelos e detritos da superfície usando aspiradores profissionais potentes.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">3</div>
                    <h3 data-i18n="pf-vidros-etapa-3-titulo">Pré-tratamento</h3>
                    <p data-i18n="pf-vidros-etapa-3-texto">Aplicamos produtos específicos nas manchas mais difíceis para facilitar a remoção.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">4</div>
                    <h3 data-i18n="pf-vidros-etapa-4-titulo">Limpeza profunda</h3>
                    <p data-i18n="pf-vidros-etapa-4-texto">Utilizamos extratoras profissionais com água quente para higienização completa do vidro.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">5</div>
                    <h3 data-i18n="pf-vidros-etapa-5-titulo">Secagem</h3>
                    <p data-i18n="pf-vidros-etapa-5-texto">Aceleramos o processo de secagem com equipamentos especializados para evitar umidade.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">6</div>
                    <h3 data-i18n="pf-vidros-etapa-6-titulo">Proteção</h3>
                    <p data-i18n="pf-vidros-etapa-6-texto">Aplicamos impermeabilizante para proteção contra futuras manchas e facilitar manutenção.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tipos de vidros -->
    <section class="tipos-section">
        <div class="tipos-content">
            <h2 class="section-title" data-i18n="pf-vidros-tipos-titulo">Atendemos todos os tipos de vidros</h2>
            <div class="tipos-grid">
                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-armchair-line"></i>
                    </div>
                    <h3 data-i18n="pf-vidros-tipo-1-titulo">Vidros de Janelas</h3>
                    <p data-i18n="pf-vidros-tipo-1-texto">Limpeza completa de vidros de janelas, incluindo persianas e caixilhos, com cuidado especial para cada tipo de vidro.</p>
                </div>

                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-wheelchair-line"></i>
                    </div>
                    <h3 data-i18n="pf-vidros-tipo-2-titulo">Vidros de Escritório</h3>
                    <p data-i18n="pf-vidros-tipo-2-texto">Higienização profissional de vidros de escritório, incluindo janelas, divisórias e mesas, com cuidado especial para cada tipo de vidro.</p>
                </div>

                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-home-8-line"></i>
                    </div>
                    <h3 data-i18n="pf-vidros-tipo-3-titulo">Vidros de Residência</h3>
                    <p data-i18n="pf-vidros-tipo-3-texto">Higienização profunda de vidros de residência, incluindo janelas, portas e vitrôs, com cuidado especial para cada tipo de vidro.</p>
                </div>

                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-building-2-line"></i>
                    </div>
                    <h3 data-i18n="pf-vidros-tipo-4-titulo">Vidros Comerciais</h3>
                    <p data-i18n="pf-vidros-tipo-4-texto">Limpeza especializada de vidros comerciais, incluindo vitrines, fachadas e portas automáticas, com cuidado especial para cada tipo de vidro.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 data-i18n="pf-vidros-cta-titulo">Transforme seus vidros hoje mesmo!</h2>
            <p data-i18n="pf-vidros-cta-texto">Entre em contato conosco e receba um orçamento personalizado sem compromisso. Nossos especialistas estão prontos para renovar seus vidros.</p>
            <div class="cta-buttons">
                <a href="../empresa/servicos.php" class="btn-cta-primary" data-i18n="pf-vidros-cta-btn-orcamento">Solicitar orçamento gratuito</a>
                <a href="#" class="btn-cta-secondary" data-i18n="pf-vidros-cta-btn-whatsapp">Falar no WhatsApp</a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>

        <script src="../../../Controller/tema-idioma.js"></script>
</body>
    <script src="../../../Controller/pages_footer.js"></script>
</body>
</html>
