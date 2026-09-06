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
    <title>Lumis - Carros</title>
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
    <section class="hero-carros">
        <div class="hero-content">
            <div class="hero-text">
                <h1 data-i18n="pf-carros-hero-titulo">Limpeza de <span class="highlight">Carros</span></h1>
                <p data-i18n="pf-carros-hero-subtitulo">Especialistas em renovar e higienizar interiores de veículos com técnicas profissionais</p>
                <div class="hero-buttons">
                    <a href="#" class="btn-primary" data-i18n="pf-carros-btn-orcamento">Solicitar orçamento</a>
                    <a href="#" class="btn-secondary" data-i18n="pf-carros-btn-como-funciona">Como funciona</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre o Serviço -->
    <section class="sobre-servico">
        <div class="sobre-content">
            <div class="texto-servico">
                <h2 data-i18n="pf-carros-sobre-titulo">Por que escolher nossa limpeza de carros?</h2>
                <p data-i18n="pf-carros-sobre-texto">
                    Nossos carros acumulam sujeira, ácaros e odores ao longo do tempo. Nossa equipe especializada
                    utiliza equipamentos profissionais e produtos específicos para devolver a vida aos seus veículos.
                </p>
                <ul class="beneficios-lista">
                    <li data-i18n="pf-carros-beneficio-1">Remoção profunda de manchas e sujeiras</li>
                    <li data-i18n="pf-carros-beneficio-2">Eliminação de ácaros e bactérias</li>
                    <li data-i18n="pf-carros-beneficio-3">Produtos eco-friendly e seguros</li>
                    <li data-i18n="pf-carros-beneficio-4">Secagem rápida e eficiente</li>
                    <li data-i18n="pf-carros-beneficio-5">Proteção antimanchas opcional</li>
                    <li data-i18n="pf-carros-beneficio-6">Equipe treinada e certificada</li>
                </ul>
            </div>
            <div class="imagem-servico">
                <div class="semaforo-visual">
            <div class="luz luz-vermelha"></div>
            <div class="luz luz-amarela"></div>
            <div class="luz luz-verde"></div>
            </div>
        </div>
    </section>

    <!-- Processo de Limpeza -->
    <section class="processo-section" id="processo">
        <div class="processo-content">
            <h2 class="section-title" data-i18n="pf-carros-processo-titulo">Nosso processo profissional</h2>
            <div class="etapas-grid">
                <div class="etapa-card">
    <div class="etapa-numero">1</div>
    <h3 data-i18n="pf-carros-etapa-1-titulo">Avaliação</h3>
    <p data-i18n="pf-carros-etapa-1-texto">Identificamos o tipo de veículo, estofamento e nível de sujeira para escolher os produtos e técnicas adequados.</p>
</div>

<div class="etapa-card">
    <div class="etapa-numero">2</div>
    <h3 data-i18n="pf-carros-etapa-2-titulo">Aspiração</h3>
    <p data-i18n="pf-carros-etapa-2-texto">Removemos sujeiras soltas dos bancos, tapetes e porta-malas com aspiradores de alta potência, alcançando frestas e cantos.</p>
</div>

<div class="etapa-card">
    <div class="etapa-numero">3</div>
    <h3 data-i18n="pf-carros-etapa-3-titulo">Pré-tratamento</h3>
    <p data-i18n="pf-carros-etapa-3-texto">Aplicamos produtos específicos em manchas difíceis, odores e áreas muito sujas como pedais e soleiras.</p>
</div>

<div class="etapa-card">
    <div class="etapa-numero">4</div>
    <h3 data-i18n="pf-carros-etapa-4-titulo">Limpeza profunda</h3>
    <p data-i18n="pf-carros-etapa-4-texto">Higienizamos bancos, painéis, tapetes e forração com produtos automotivos adequados para cada tipo de material.</p>
</div>

<div class="etapa-card">
    <div class="etapa-numero">5</div>
    <h3 data-i18n="pf-carros-etapa-5-titulo">Secagem</h3>
    <p data-i18n="pf-carros-etapa-5-texto">Utilizamos equipamentos para acelerar a secagem e evitar umidade que pode causar mofo e odores desagradáveis.</p>
</div>

<div class="etapa-card">
    <div class="etapa-numero">6</div>
    <h3 data-i18n="pf-carros-etapa-6-titulo">Proteção</h3>
    <p data-i18n="pf-carros-etapa-6-texto">Aplicamos protetores nos estofados e painéis para facilitar limpezas futuras e manter o interior conservado.</p>
</div>
        </div>
    </section>

    <!-- Tipos de Veículos -->
<section class="tipos-section">
    <div class="tipos-content">
        <h2 class="section-title" data-i18n="pf-carros-tipos-titulo">Atendemos todos os tipos de veículos</h2>
        <div class="tipos-grid">
            <div class="tipo-card">
                <div class="tipo-icon">
                    <i class="ri-car-line"></i>
                </div>
                <h3 data-i18n="pf-carros-tipo-1-titulo">Carros de Passeio</h3>
                <p data-i18n="pf-carros-tipo-1-texto">Limpeza completa de carros populares, sedan, hatch e SUVs com cuidado especial para cada tipo de estofamento.</p>
            </div>

            <div class="tipo-card">
                <div class="tipo-icon">
                    <i class="ri-truck-line"></i>
                </div>
                <h3 data-i18n="pf-carros-tipo-2-titulo">Caminhões e Utilitários</h3>
                <p data-i18n="pf-carros-tipo-2-texto">Higienização profissional de cabines de caminhões, vans e veículos comerciais com foco na resistência.</p>
            </div>

            <div class="tipo-card">
                <div class="tipo-icon">
                    <i class="ri-bus-line"></i>
                </div>
                <h3 data-i18n="pf-carros-tipo-3-titulo">Ônibus e Micro-ônibus</h3>
                <p data-i18n="pf-carros-tipo-3-texto">Limpeza e desinfecção de transporte coletivo, eliminando germes, odores e manchas dos bancos.</p>
            </div>

            <div class="tipo-card">
                <div class="tipo-icon">
                    <i class="ri-motorbike-line"></i>
                </div>
                <h3 data-i18n="pf-carros-tipo-4-titulo">Motos e Scooters</h3>
                <p data-i18n="pf-carros-tipo-4-texto">Higienização cuidadosa de bancos de motos, scooters e triciclos com produtos adequados.</p>
            </div>

            <div class="tipo-card">
                <div class="tipo-icon">
                    <i class="ri-taxi-line"></i>
                </div>
                <h3 data-i18n="pf-carros-tipo-5-titulo">Frota Comercial</h3>
                <p data-i18n="pf-carros-tipo-5-texto">Serviço para frotas de táxi, Uber, locadoras e empresas com planos especiais de manutenção.</p>
            </div>
        </div>
    </div>
</section>
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 data-i18n="pf-carros-cta-titulo">Transforme seu automóvel  hoje mesmo!</h2>
            <p data-i18n="pf-carros-cta-texto">Entre em contato conosco e receba um orçamento personalizado sem compromisso. Nossos especialistas estão prontos para renovar seu automóvel.</p>
            <div class="cta-buttons">
                <a href="../empresa/servicos.php" class="btn-cta-primary" data-i18n="pf-carros-cta-btn-orcamento">Solicitar orçamento gratuito</a>
                <a href="#" class="btn-cta-secondary" data-i18n="pf-carros-cta-btn-whatsapp">Falar no WhatsApp</a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>

        <script src="../../../Controller/tema-idioma.js"></script>
</body>
    <script src="../../../Controller/pages_footer.js"></script>
</body>
</html>
