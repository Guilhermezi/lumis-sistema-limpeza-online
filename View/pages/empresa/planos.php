<?php
require_once dirname(__DIR__, 3) . '/Model/conexao.php';
$planosStmt = $pdo->query("SELECT id_plano, nome, preco, descricao, duracao, beneficios, tipo FROM Planos ORDER BY (tipo = 'personalizado'), preco, id_plano");
$planos = $planosStmt->fetchAll();
$rotulosPlanos = ['Basico' => 'Plano Básico', 'Regular' => 'Plano Regular', 'Premium' => 'Plano Premium', 'Personalizado' => 'Plano Personalizado'];
?>
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
    <link rel="stylesheet" href="../../css/planos-checkout.css">

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
                    <?php foreach ($planos as $indice => $plano): ?>
                        <?php
                        $nomePlano = $rotulosPlanos[$plano['nome']] ?? $plano['nome'];
                        $personalizado = $plano['tipo'] === 'personalizado' || (float) $plano['preco'] <= 0;
                        $classeCard = $indice === 1 ? ' pricing-featured' : ($indice === 2 ? ' pricing-accent' : '');
                        $iconePlano = match ($plano['nome']) {
                            'Basico' => 'ri-home-smile-2-line',
                            'Regular' => 'ri-sparkling-2-line',
                            'Premium' => 'ri-vip-crown-line',
                            default => 'ri-settings-3-line',
                        };
                        $beneficios = array_filter(array_map('trim', explode(';', (string) $plano['beneficios'])));
                        ?>
                        <article class="pricing-card<?= $classeCard ?>">
                            <?php if ($indice === 1): ?><span class="pricing-badge">Mais Popular</span><?php endif; ?>
                            <?php if ($indice === 2): ?><span class="pricing-badge">Recomendado</span><?php endif; ?>
                            <div class="pricing-icon"><i class="<?= htmlspecialchars($iconePlano) ?>"></i></div>
                            <h3 class="pricing-name"><?= htmlspecialchars($nomePlano) ?></h3>
                            <p class="pricing-desc"><?= htmlspecialchars((string) $plano['descricao']) ?></p>
                            <div class="pricing-price">
                                <?php if ($personalizado): ?>
                                    <span class="pricing-amount pricing-custom">Sob consulta</span>
                                <?php else: ?>
                                    <span class="pricing-currency">R$</span>
                                    <span class="pricing-amount"><?= number_format((float) $plano['preco'], 2, ',', '.') ?></span>
                                    <span class="pricing-period">/<?= htmlspecialchars($plano['duracao']) ?></span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="<?= $personalizado ? 'btn-secondary' : 'btn-primary' ?> pricing-btn plan-select-button"
                                    data-plan-id="<?= (int) $plano['id_plano'] ?>"
                                    data-plan-name="<?= htmlspecialchars($nomePlano, ENT_QUOTES, 'UTF-8') ?>"
                                    data-plan-price="<?= htmlspecialchars((string) $plano['preco']) ?>"
                                    data-plan-duration="<?= htmlspecialchars((string) $plano['duracao'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-plan-benefits="<?= htmlspecialchars((string) $plano['beneficios'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-plan-custom="<?= $personalizado ? 'true' : 'false' ?>">
                                Quero este plano
                            </button>
                            <hr class="pricing-divider">
                            <ul class="pricing-features">
                                <?php foreach ($beneficios as $beneficio): ?>
                                    <li><i class="ri-checkbox-circle-fill"></i> <span><?= htmlspecialchars($beneficio) ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endforeach; ?>
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

    <div class="plan-checkout-modal" id="planCheckout" role="dialog" aria-modal="true" aria-labelledby="planCheckoutTitle" hidden>
        <button class="plan-checkout-backdrop" type="button" data-plan-close aria-label="Fechar"></button>
        <div class="plan-checkout-dialog">
            <button class="plan-checkout-close" type="button" data-plan-close aria-label="Fechar"><i class="ri-close-line"></i></button>

            <section class="plan-checkout-step" data-plan-step="summary">
                <span class="plan-checkout-eyebrow"><i class="ri-price-tag-3-line"></i> Plano selecionado</span>
                <h2 id="planCheckoutTitle">Confira seu plano</h2>
                <p class="plan-checkout-subtitle" id="planCheckoutDuration"></p>
                <div class="plan-checkout-summary">
                    <div><span>Plano</span><strong id="planCheckoutName">—</strong></div>
                    <div><span>Valor</span><strong id="planCheckoutPrice">—</strong></div>
                </div>
                <h3>O que está incluído</h3>
                <ul class="plan-checkout-benefits" id="planCheckoutBenefits"></ul>
                <div class="plan-checkout-actions">
                    <button class="btn-secondary" type="button" data-plan-close>Voltar aos planos</button>
                    <button class="btn-primary" type="button" id="planContinue">Continuar</button>
                    <a class="btn-primary" id="planCustomContact" href="contato.php" hidden>Falar com especialista</a>
                </div>
            </section>

            <section class="plan-checkout-step" data-plan-step="payment" hidden>
                <span class="plan-checkout-eyebrow"><i class="ri-bank-card-line"></i> Pagamento simulado</span>
                <h2>Como deseja pagar?</h2>
                <p class="plan-checkout-subtitle">A forma escolhida será registrada como pendente. Nenhuma cobrança real será feita.</p>
                <div class="plan-checkout-payment-options" role="radiogroup" aria-label="Forma de pagamento">
                    <button type="button" class="plan-checkout-payment" data-plan-payment="pix"><i class="ri-qr-code-line"></i><span><strong>PIX</strong><small>Pagamento pendente</small></span><i class="ri-check-line"></i></button>
                    <button type="button" class="plan-checkout-payment" data-plan-payment="cartao"><i class="ri-bank-card-line"></i><span><strong>Cartão</strong><small>Pagamento pendente</small></span><i class="ri-check-line"></i></button>
                    <button type="button" class="plan-checkout-payment" data-plan-payment="boleto"><i class="ri-barcode-line"></i><span><strong>Boleto</strong><small>Pagamento pendente</small></span><i class="ri-check-line"></i></button>
                    <button type="button" class="plan-checkout-payment" data-plan-payment="na-hora"><i class="ri-hand-coin-line"></i><span><strong>No atendimento</strong><small>Combine com a equipe</small></span><i class="ri-check-line"></i></button>
                </div>
                <p class="plan-checkout-error" id="planCheckoutError" role="alert" hidden></p>
                <div class="plan-checkout-actions">
                    <button class="btn-secondary" type="button" id="planBack">Voltar</button>
                    <button class="btn-primary" type="button" id="planConfirm">Confirmar plano</button>
                </div>
            </section>

            <section class="plan-checkout-step plan-checkout-result" data-plan-step="success" hidden>
                <span class="plan-checkout-result-icon"><i class="ri-check-line"></i></span>
                <h2>Solicitação registrada!</h2>
                <p>O plano e a opção de pagamento ficaram pendentes no sistema.</p>
                <div class="plan-checkout-summary" id="planCheckoutReceipt"></div>
                <button class="btn-primary" type="button" data-plan-close>Concluir</button>
            </section>

            <section class="plan-checkout-step plan-checkout-result" data-plan-step="auth" hidden>
                <span class="plan-checkout-result-icon"><i class="ri-user-line"></i></span>
                <h2 id="planAuthTitle">Entre para continuar</h2>
                <p id="planAuthText">A contratação de planos está disponível para contas de cliente.</p>
                <a class="btn-primary" href="../auth/login.php">Ir para o login</a>
            </section>
        </div>
    </div>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>

    <script src="../../../Controller/Plano.js"></script>
    <script src="../../../Controller/planos-checkout.js"></script>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
