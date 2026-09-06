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
    <title>Lumis - empresarial</title>
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
    <section class="hero-empresarial">
        <div class="hero-content">
            <div class="hero-text">
                <h1 data-i18n="pf-empresarial-hero-titulo">Limpeza <span class="highlight">Empresarial</span></h1>
                <p data-i18n="pf-empresarial-hero-subtitulo">Especialistas em renovar e higienizar ambientes corporativos com técnicas profissionais</p>
                <div class="hero-buttons">
                    <a href="#" class="btn-primary" data-i18n="pf-empresarial-btn-orcamento">Solicitar orçamento</a>
                    <a href="#" class="btn-secondary" data-i18n="pf-empresarial-btn-como-funciona">Como funciona</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre o Serviço -->
    <section class="sobre-servico">
        <div class="sobre-content">
            <div class="texto-servico">
                <h2 data-i18n="pf-empresarial-sobre-titulo">Por que escolher nossa limpeza empresarial?</h2>
                <p data-i18n="pf-empresarial-sobre-texto">
                    Nossos ambientes corporativos acumulam sujeira, ácaros e odores ao longo do tempo. Nossa equipe especializada
                    utiliza equipamentos profissionais e produtos específicos para devolver a vida aos seus móveis.
                </p>
                <ul class="beneficios-lista">
                    <li data-i18n="pf-empresarial-beneficio-1">Ambientes mais saudáveis e limpos</li>
                    <li data-i18n="pf-empresarial-beneficio-2">Eliminação de ácaros e bactérias</li>
                    <li data-i18n="pf-empresarial-beneficio-3">Renovação da aparência dos móveis</li>
                    <li data-i18n="pf-empresarial-beneficio-4">Redução de odores desagradáveis</li>
                    <li data-i18n="pf-empresarial-beneficio-5">Manutenção preventiva para prolongar a vida útil</li>
                </ul>
            </div>
            <div class="imagem-servico">
                <div class="predio-visual predio-empresarial"></div>
            </div>
        </div>
    </section>

    <!-- Processo de Limpeza -->
    <section class="processo-section" id="processo">
        <div class="processo-content">
            <h2 class="section-title" data-i18n="pf-empresarial-processo-titulo">Nosso processo profissional</h2>
            <div class="etapas-grid">
                <div class="etapa-card">
                    <div class="etapa-numero">1</div>
                    <h3 data-i18n="pf-empresarial-etapa-1-titulo">Avaliação</h3>
                    <p data-i18n="pf-empresarial-etapa-1-texto">Identificamos o tipo de tecido, manchas e sujeiras para escolher a melhor técnica de limpeza.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">2</div>
                    <h3 data-i18n="pf-empresarial-etapa-2-titulo">Aspiração</h3>
                    <p data-i18n="pf-empresarial-etapa-2-texto">Removemos poeira, pelos e detritos da superfície usando aspiradores profissionais potentes.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">3</div>
                    <h3 data-i18n="pf-empresarial-etapa-3-titulo">Pré-tratamento</h3>
                    <p data-i18n="pf-empresarial-etapa-3-texto">Aplicamos produtos específicos nas manchas mais difíceis para facilitar a remoção.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">4</div>
                    <h3 data-i18n="pf-empresarial-etapa-4-titulo">Limpeza profunda</h3>
                    <p data-i18n="pf-empresarial-etapa-4-texto">Utilizamos extratoras profissionais com água quente para higienização completa do tecido.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">5</div>
                    <h3 data-i18n="pf-empresarial-etapa-5-titulo">Secagem</h3>
                    <p data-i18n="pf-empresarial-etapa-5-texto">Aceleramos o processo de secagem com equipamentos especializados para evitar umidade.</p>
                </div>

                <div class="etapa-card">
                    <div class="etapa-numero">6</div>
                    <h3 data-i18n="pf-empresarial-etapa-6-titulo">Proteção</h3>
                    <p data-i18n="pf-empresarial-etapa-6-texto">Aplicamos impermeabilizante para proteção contra futuras manchas e facilitar manutenção.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tipos de Estofados -->
    <section class="tipos-section">
        <div class="tipos-content">
            <h2 class="section-title" data-i18n="pf-empresarial-tipos-titulo">Atendemos todos os tipos de ambientes corporativos</h2>
            <div class="tipos-grid">
                <div class="tipo-card">
                    <div class="tipo-icon">
                        <i class="ri-armchair-line"></i>
                    </div>
                  <h3 data-i18n="pf-empresarial-tipo-1-titulo">Escritórios e Salas Comerciais</h3>
<p data-i18n="pf-empresarial-tipo-1-texto">Limpeza completa de escritórios, salas de reunião e espaços administrativos com produtos profissionais e equipamentos especializados.</p>
</div>

<div class="tipo-card">
    <div class="tipo-icon">
        <i class="ri-building-2-line"></i>
    </div>
    <h3 data-i18n="pf-empresarial-tipo-2-titulo">Prédios Corporativos</h3>
    <p data-i18n="pf-empresarial-tipo-2-texto">Higienização profissional de lobbies, corredores, elevadores e áreas comuns para condomínios empresariais.</p>
</div>

<div class="tipo-card">
    <div class="tipo-icon">
        <i class="ri-store-2-line"></i>
    </div>
    <h3 data-i18n="pf-empresarial-tipo-3-titulo">Lojas e Varejo</h3>
    <p data-i18n="pf-empresarial-tipo-3-texto">Limpeza especializada para estabelecimentos comerciais, boutiques, farmácias e pontos de venda.</p>
</div>

<div class="tipo-card">
    <div class="tipo-icon">
        <i class="ri-hospital-line"></i>
    </div>
    <h3 data-i18n="pf-empresarial-tipo-4-titulo">Clínicas e Consultórios</h3>
    <p data-i18n="pf-empresarial-tipo-4-texto">Higienização hospitalar com protocolos sanitários rigorosos para ambientes de saúde e bem-estar.</p>
</div>

<div class="tipo-card">
    <div class="tipo-icon">
        <i class="ri-restaurant-2-line"></i>
    </div>
    <h3 data-i18n="pf-empresarial-tipo-5-titulo">Restaurantes e Bares</h3>
    <p data-i18n="pf-empresarial-tipo-5-texto">Limpeza comercial especializada para cozinhas, salões, banheiros e áreas de preparo de alimentos.</p>
</div>

<div class="tipo-card">
    <div class="tipo-icon">
        <i class="ri-building-4-line"></i>
    </div>
    <h3 data-i18n="pf-empresarial-tipo-6-titulo">Indústrias e Galpões</h3>
    <p data-i18n="pf-empresarial-tipo-6-texto">Limpeza industrial para fábricas, armazéns e espaços logísticos com equipamentos de alta performance.</p>
</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 data-i18n="pf-empresarial-cta-titulo">Transforme seus ambientes hoje mesmo!</h2>
            <p data-i18n="pf-empresarial-cta-texto">Entre em contato conosco e receba um orçamento personalizado sem compromisso. Nossos especialistas estão prontos para renovar seus móveis.</p>
            <div class="cta-buttons">
                <a href="../empresa/servicos.php" class="btn-cta-primary" data-i18n="pf-empresarial-cta-btn-orcamento">Solicitar orçamento gratuito</a>
                <a href="#" class="btn-cta-secondary" data-i18n="pf-empresarial-cta-btn-whatsapp">Falar no WhatsApp</a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>

        <script src="../../../Controller/tema-idioma.js"></script>
</body>
    <script src="../../../Controller/pages_footer.js"></script>
</body>
</html>
