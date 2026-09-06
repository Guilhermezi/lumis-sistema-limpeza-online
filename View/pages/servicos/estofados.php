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
    <title>Lumis - Estofados</title>
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
    <section class="hero-estofados">
        <div class="hero-content">
            <div class="hero-text">
                <h1 data-i18n="pf-estofados-hero-titulo">Limpeza de <span class="highlight">Estofados</span></h1>
                <p data-i18n="pf-estofados-hero-subtitulo">Especialistas em renovar e higienizar sofás, poltronas e cadeiras com técnicas profissionais</p>
                <div class="hero-buttons">
                    <a href="#" class="btn-primary" data-i18n="pf-estofados-btn-orcamento">Solicitar orçamento</a>
                    <a href="#" class="btn-secondary" data-i18n="pf-estofados-btn-como-funciona">Como funciona</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre o Serviço -->
    <section class="sobre-servico">
        <div class="sobre-content">
            <div class="texto-servico">
                <h2 data-i18n="pf-estofados-sobre-titulo">Por que escolher nossa limpeza de estofados?</h2>
                <p data-i18n="pf-estofados-sobre-texto">
                    Nossos estofados acumulam sujeira, ácaros e odores ao longo do tempo. Nossa equipe especializada 
                    utiliza equipamentos profissionais e produtos específicos para devolver a vida aos seus móveis.
                </p>
                <ul class="beneficios-lista">
                    <li data-i18n="pf-estofados-beneficio-1">Remoção profunda de manchas e sujeiras</li>
                    <li data-i18n="pf-estofados-beneficio-2">Eliminação de ácaros e bactérias</li>
                    <li data-i18n="pf-estofados-beneficio-3">Produtos eco-friendly e seguros</li>
                    <li data-i18n="pf-estofados-beneficio-4">Secagem rápida e eficiente</li>
                    <li data-i18n="pf-estofados-beneficio-5">Proteção antimanchas opcional</li>
                    <li data-i18n="pf-estofados-beneficio-6">Equipe treinada e certificada</li>
                </ul>
            </div>
            <div class="imagem-servico">
                <div class="estofado-visual"></div>
            </div>
        </div>
    </section>

    <!-- Processo de Limpeza -->
    <section class="processo-section" id="processo">
        <div class="processo-content">
            <h2 class="section-title" data-i18n="pf-estofados-processo-titulo">Nosso processo profissional</h2>
            <div class="etapas-grid">
                <div class="etapa-card">
                    <div class="etapa-numero">1</div>
                    <h3 data-i18n="pf-estofados-etapa-1-titulo">Avaliação</h3>
                    <p data-i18n="pf-estofados-etapa-1-texto">Identificamos o tipo de tecido, manchas e sujeiras para escolher a melhor técnica de limpeza.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">2</div>
                    <h3 data-i18n="pf-estofados-etapa-2-titulo">Aspiração</h3>
                    <p data-i18n="pf-estofados-etapa-2-texto">Removemos poeira, pelos e detritos da superfície usando aspiradores profissionais potentes.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">3</div>
                    <h3 data-i18n="pf-estofados-etapa-3-titulo">Pré-tratamento</h3>
                    <p data-i18n="pf-estofados-etapa-3-texto">Aplicamos produtos específicos nas manchas mais difíceis para facilitar a remoção.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">4</div>
                    <h3 data-i18n="pf-estofados-etapa-4-titulo">Limpeza profunda</h3>
                    <p data-i18n="pf-estofados-etapa-4-texto">Utilizamos extratoras profissionais com água quente para higienização completa do tecido.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">5</div>
                    <h3 data-i18n="pf-estofados-etapa-5-titulo">Secagem</h3>
                    <p data-i18n="pf-estofados-etapa-5-texto">Aceleramos o processo de secagem com equipamentos especializados para evitar umidade.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">6</div>
                    <h3 data-i18n="pf-estofados-etapa-6-titulo">Proteção</h3>
                    <p data-i18n="pf-estofados-etapa-6-texto">Aplicamos impermeabilizante para proteção contra futuras manchas e facilitar manutenção.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tipos de Estofados -->
    <section class="tipos-section">
        <div class="tipos-content">
            <h2 class="section-title" data-i18n="pf-estofados-tipos-titulo">Atendemos todos os tipos de estofados</h2>
            <div class="tipos-grid">
                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-armchair-line"></i>
                    </div>
                    <h3 data-i18n="pf-estofados-tipo-1-titulo">Sofás e Poltronas</h3>
                    <p data-i18n="pf-estofados-tipo-1-texto">Limpeza completa de sofás de 2, 3 lugares e poltronas reclináveis com cuidado especial para cada tipo de tecido.</p>
                </div>

                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-wheelchair-line"></i>
                    </div>
                    <h3 data-i18n="pf-estofados-tipo-2-titulo">Cadeiras de Escritório</h3>
                    <p data-i18n="pf-estofados-tipo-2-texto">Higienização profissional de cadeiras ergonômicas, presidenciais e operacionais para ambientes corporativos.</p>
                </div>

                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-hotel-bed-line"></i>
                    </div>
                    <h3 data-i18n="pf-estofados-tipo-3-titulo">Colchões e Travesseiros</h3>
                    <p data-i18n="pf-estofados-tipo-3-texto">Limpeza anti-alérgica para colchões, travesseiros e almofadas, eliminando ácaros e odores.</p>
                </div>

                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-home-8-line"></i>
                    </div>
                    <h3 data-i18n="pf-estofados-tipo-4-titulo">Tapetes e Carpetes</h3>
                    <p data-i18n="pf-estofados-tipo-4-texto">Higienização profunda de tapetes persas, carpetes e passadeiras com secagem acelerada.</p>
                </div>

                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-restaurant-2-line"></i>
                    </div>
                    <h3 data-i18n="pf-estofados-tipo-5-titulo">Móveis de Restaurante</h3>
                    <p data-i18n="pf-estofados-tipo-5-texto">Limpeza comercial para banquetas, cadeiras e estofados de restaurantes e bares.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 data-i18n="pf-estofados-cta-titulo">Transforme seus estofados hoje mesmo!</h2>
            <p data-i18n="pf-estofados-cta-texto">Entre em contato conosco e receba um orçamento personalizado sem compromisso. Nossos especialistas estão prontos para renovar seus móveis.</p>
            <div class="cta-buttons">
                <a href="../empresa/servicos.php" class="btn-cta-primary" data-i18n="pf-estofados-cta-btn-orcamento">Solicitar orçamento gratuito</a>
                <a href="#" class="btn-cta-secondary" data-i18n="pf-estofados-cta-btn-whatsapp">Falar no WhatsApp</a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>

        <script src="../../../Controller/tema-idioma.js"></script>
</body>
    <script src="../../../Controller/pages_footer.js"></script>
</body>
</html>
