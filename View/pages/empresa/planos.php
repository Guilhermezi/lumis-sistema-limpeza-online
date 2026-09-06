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
    <!-- Estilos especificos da pagina Plano -->
    <link rel="stylesheet" href="../../css/planos.css">

    <!-- Link para o RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>Lumis - Planos</title>
</head>
<body data-page="plano">
    <?php
$base = '../';
$active = '';
include __DIR__ . '/../../partials/header.php';
?>

    <main class="plans-page">

        <!-- ========== HERO ========== -->
        <section class="plans-hero">
            <div class="plans-hero-bg"></div>
            <div class="hero-content">
                <span class="plans-tag"><i class="ri-price-tag-3-line"></i> <span data-i18n="plano-tag">Planos flexíveis para cada necessidade</span></span>
                <h1 data-i18n="plano-hero-titulo">Escolha o seu <span class="highlight">plano</span> ideal</h1>
                <p data-i18n="plano-hero-subtitulo">Planos sob medida para residências e empresas, com limpeza sustentável, atendimento ágil e profissionais verificados.</p>
            </div>
        </section>

        <!-- ========== ESTATISTICAS ========== -->
        <section class="plans-stats">
            <div class="plans-stat">
                <span class="plans-stat-number"><span class="plus" data-i18n="plano-stat-mais">+ de </span><span class="number" data-target="5000">0</span></span>
                <span class="plans-stat-label" data-i18n="plano-stat-label-familias">famílias e empresas atendidas</span>
            </div>
            <div class="plans-stat-divider"></div>
            <div class="plans-stat">
                <span class="plans-stat-number"><span class="plus" data-i18n="plano-stat-mais">+ de </span><span class="number" data-target="20">0</span></span>
                <span class="plans-stat-label" data-i18n="plano-stat-label-lixo">toneladas de lixo tratadas</span>
            </div>
            <div class="plans-stat-divider"></div>
            <div class="plans-stat">
                <span class="plans-stat-number"><span class="plus" data-i18n="plano-stat-mais">+ de </span><span class="number" data-target="8">0</span></span>
                <span class="plans-stat-label" data-i18n="plano-stat-label-experiencia">anos de experiência</span>
            </div>
        </section>

        <!-- ========== PLANOS ========== -->
        <section class="pricing-section">
            <div class="section-container">
                <h2 class="section-title" data-i18n="plano-section-titulo">Nossos planos</h2>
                <p class="plans-subtitle" data-i18n="plano-section-subtitulo">Escolha o plano que melhor se encaixa na sua rotina e no seu bolso. Sem fidelidade e sem taxas escondidas.</p>

                <div class="pricing-grid">

                    <div class="pricing-card">
                        <div class="pricing-icon">
                            <i class="ri-home-smile-2-line"></i>
                        </div>
                        <h3 class="pricing-name" data-i18n="plano-basico-nome">Plano Básico</h3>
                        <p class="pricing-desc" data-i18n="plano-basico-desc">Perfeito para quem busca praticidade com economia.</p>
                        <div class="pricing-price">
                            <span class="pricing-currency">R$</span>
                            <span class="pricing-amount">99,90</span>
                            <span class="pricing-period">/mês</span>
                        </div>
                        <a href="../auth/login.php" class="btn-primary pricing-btn" data-i18n="plano-botao-quero">Quero este plano</a>
                        <hr class="pricing-divider">
                        <ul class="pricing-features">
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-basico-item1">2 limpezas/mês (até 4 cômodos)</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-basico-item2">1 serviço especializado por trimestre (ex: sofá ou vidros)</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-basico-item3">Atendimento em até 3 dias úteis</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-basico-item4">Dicas mensais de organização e sustentabilidade</span></li>
                        </ul>
                    </div>

                    <div class="pricing-card pricing-featured">
                        <span class="pricing-badge" data-i18n="plano-badge-popular">Mais Popular</span>
                        <div class="pricing-icon">
                            <i class="ri-sparkling-2-line"></i>
                        </div>
                        <h3 class="pricing-name" data-i18n="plano-regular-nome">Plano Regular</h3>
                        <p class="pricing-desc" data-i18n="plano-regular-desc">Nosso plano mais escolhido, ideal para famílias e pequenos negócios.</p>
                        <div class="pricing-price">
                            <span class="pricing-currency">R$</span>
                            <span class="pricing-amount">179,90</span>
                            <span class="pricing-period">/mês</span>
                        </div>
                        <a href="../auth/login.php" class="btn-primary pricing-btn" data-i18n="plano-botao-quero">Quero este plano</a>
                        <hr class="pricing-divider">
                        <ul class="pricing-features">
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-regular-item1">3 limpezas/mês (até 4 cômodos)</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-regular-item2">2 serviços especializados por trimestre (ex: sofá ou vidros)</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-regular-item3">Atendimento em até 2 dias úteis</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-regular-item4">Dicas mensais de organização e sustentabilidade</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-regular-item5">Relatórios simples de impacto ambiental</span></li>
                        </ul>
                    </div>

                    <div class="pricing-card pricing-accent">
                        <span class="pricing-badge" data-i18n="plano-badge-recomendado">Recomendado</span>
                        <div class="pricing-icon">
                            <i class="ri-vip-crown-line"></i>
                        </div>
                        <h3 class="pricing-name" data-i18n="plano-premium-nome">Plano Premium</h3>
                        <p class="pricing-desc" data-i18n="plano-premium-desc">Máximo de comodidade e resultados profissionais.</p>
                        <div class="pricing-price">
                            <span class="pricing-currency">R$</span>
                            <span class="pricing-amount">399,90</span>
                            <span class="pricing-period">/mês</span>
                        </div>
                        <a href="../auth/login.php" class="btn-primary pricing-btn" data-i18n="plano-botao-quero">Quero este plano</a>
                        <hr class="pricing-divider">
                        <ul class="pricing-features">
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-premium-item1">Limpeza semanal (residencial ou pequena empresa)</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-premium-item2">Todos os serviços especializados liberados (até 4/mês)</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-premium-item3">Atendimento em até 24h</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-premium-item4">Equipe fixa ou exclusiva para empresas</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-premium-item5">Tudo que os planos inferiores oferecem, e mais</span></li>
                        </ul>
                    </div>

                    <div class="pricing-card">
                        <div class="pricing-icon">
                            <i class="ri-settings-3-line"></i>
                        </div>
                        <h3 class="pricing-name" data-i18n="plano-personalizado-nome">Plano Personalizado</h3>
                        <p class="pricing-desc" data-i18n="plano-personalizado-desc">Flexível, do seu jeito. Monte o plano perfeito.</p>
                        <div class="pricing-price">
                            <span class="pricing-amount pricing-custom" data-i18n="plano-personalizado-consulta">Sob consulta</span>
                        </div>
                        <a href="contato.php" class="btn-secondary pricing-btn" data-i18n="plano-botao-quero">Quero este plano</a>
                        <hr class="pricing-divider">
                        <ul class="pricing-features">
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-personalizado-item1">Você escolhe quanto quer pagar, a frequência e o tamanho da limpeza.</span></li>
                            <li><i class="ri-checkbox-circle-fill"></i> <span data-i18n="plano-personalizado-item2">Um profissional analisa sua proposta e, se for viável, o serviço é aprovado.</span></li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        <!-- ========== DEPOIMENTO ========== -->
        <section class="plans-testimonial">
            <div class="section-container">
                <div class="testimonial-layout">
                    <div class="testimonial-media">
                        <img src="../../img/Mulher cor fundo_f9f9fb.png" alt="Mulher sorrindo e segurando pastas">
                    </div>
                    <div class="testimonial-content">
                        <div class="testimonial-stars">
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                        </div>
                        <blockquote class="testimonial-quote" data-i18n="plano-depoimento-texto">
                            "Com a <strong>Lumis</strong>, consegui manter minha <strong>empresa sempre limpa e organizada</strong> sem me preocupar com o descarte do lixo. Hoje sei que além de ter um espaço saudável para os colaboradores, também estou <strong>contribuindo para a sustentabilidade da cidade</strong>."
                        </blockquote>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">C</div>
                            <div>
                                <strong>Carla Mendes</strong>
                                <span data-i18n="plano-depoimento-autor-funcao">Gerente Administrativa | São Paulo - SP</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== CTA ========== -->
        <section class="plans-cta">
            <div class="section-container">
                <div class="cta-content">
                    <h2 data-i18n="plano-cta-titulo">Pronto para encontrar o plano ideal?</h2>
                    <p data-i18n="plano-cta-subtitulo">Crie sua conta gratuita e comece a transformar seu espaço hoje mesmo.</p>
                    <div class="cta-buttons">
                        <a href="../auth/login.php" class="btn-primary" data-i18n="plano-cta-botao-conta">Criar conta gratuita</a>
                        <a href="contato.php" class="btn-secondary" data-i18n="plano-cta-botao-especialista">Falar com especialista</a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>

    <script src="../../../Controller/Plano.js"></script>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
