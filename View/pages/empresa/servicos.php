<?php
require_once dirname(__DIR__, 3) . '/Model/conexao.php';
require_once dirname(__DIR__, 3) . '/Model/servicos.php';

$servicos = buscarCatalogoServicos($pdo);
$estatisticas = buscarEstatisticasCatalogo($pdo);
$depoimentos = buscarDepoimentosCatalogo($pdo);
$categorias = agruparCategoriasCatalogo($servicos);
$precos = array_map('floatval', array_column($servicos, 'preco'));
$precoMinimo = $precos ? (int) floor(min($precos) / 10) * 10 : 0;
$precoMaximo = $precos ? (int) ceil(max($precos) / 50) * 50 : 500;
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
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/servicos-lista.css">
    <link rel="stylesheet" href="../../css/checkout-servico.css">
    <script src="../../../Controller/Servicos.js" defer></script>
    <script src="../../../Controller/checkout-servico.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>Lumis - Serviços</title>
</head>
<body data-page="servicos">
    <?php
    $base = '../';
    $active = 'servicos';
    include __DIR__ . '/../../partials/header.php';
    ?>

    <main class="services-page">
        <section class="services-hero">
            <div class="hero-bg"></div>
            <div class="hero-content">
                <span class="hero-tag"><i class="ri-search-line"></i> <span data-i18n="servicos-hero-tag">Limpeza sustentável e profissional</span></span>
                <h1 data-i18n="servicos-hero-titulo">Encontre o serviço de limpeza <span class="highlight">perfeito</span> para você</h1>
                <p data-i18n="servicos-hero-sub">Profissionais verificados, avaliação garantida e resultados impecáveis. Agende em menos de 2 minutos.</p>
                <div class="hero-search">
                    <div class="search-box">
                        <i class="ri-search-line"></i>
                        <input type="search" placeholder="Buscar por nome, descrição ou categoria..." data-i18n-placeholder="servicos-busca-placeholder" id="searchInput" autocomplete="off">
                        <button class="search-btn" type="button" data-i18n="servicos-buscar">Buscar</button>
                    </div>
                    <?php if ($categorias): ?>
                    <div class="search-tags" aria-label="Atalhos de categorias">
                        <?php foreach ($categorias as $tipo => $total): ?>
                        <button class="search-tag" type="button" data-filter="<?= htmlspecialchars($tipo) ?>"><?= htmlspecialchars(rotuloCategoriaCatalogo($tipo)) ?></button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="trust-bar" aria-label="Números atuais da plataforma" data-i18n-aria-label="servicos-trust-aria">
            <div class="trust-item">
                <span class="trust-number" data-target="<?= $estatisticas['profissionais'] ?>">0</span>
                <span class="trust-label" data-i18n="servicos-trust-profissionais">Profissionais</span>
            </div>
            <div class="trust-divider"></div>
            <div class="trust-item">
                <span class="trust-number" data-target="<?= $estatisticas['concluidos'] ?>">0</span>
                <span class="trust-label" data-i18n="servicos-trust-servicos">Serviços realizados</span>
            </div>
            <div class="trust-divider"></div>
            <div class="trust-item">
                <span class="trust-number" data-target="<?= $estatisticas['avaliacoes_cinco'] ?>">0</span>
                <span class="trust-label" data-i18n="servicos-trust-avaliacoes">Avaliações 5 estrelas</span>
            </div>
            <div class="trust-divider"></div>
            <div class="trust-item">
                <span class="trust-number" data-target="<?= $estatisticas['satisfacao'] ?>">0</span>
                <span class="trust-label" data-i18n="servicos-trust-satisfacao">% Satisfação</span>
            </div>
        </section>

        <?php if ($categorias): ?>
        <section class="categories-section">
            <div class="section-container">
                <div class="section-header">
                    <h2 data-i18n="servicos-categorias">Categorias</h2>
                    <p data-i18n="servicos-categorias-sub">Escolha o tipo de serviço que melhor atende sua necessidade.</p>
                </div>
                <div class="categories-grid">
                    <?php foreach ($categorias as $tipo => $total): ?>
                    <a href="#servicos" class="category-card" data-category="<?= htmlspecialchars($tipo) ?>">
                        <div class="category-icon"><i class="<?= htmlspecialchars(iconeCategoriaCatalogo($tipo)) ?>"></i></div>
                        <h3><?= htmlspecialchars(rotuloCategoriaCatalogo($tipo)) ?></h3>
                        <p><?= htmlspecialchars(descricaoCategoriaCatalogo($tipo)) ?></p>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section class="services-section" id="servicos">
            <div class="section-container">
                <div class="services-layout">
                    <aside class="filters-sidebar">
                        <div class="filters-header">
                            <h3><i class="ri-filter-3-line"></i> <span data-i18n="servicos-filtros">Filtros</span></h3>
                            <button class="filters-clear" id="clearFilters" type="button" data-i18n="servicos-limpar-tudo">Limpar tudo</button>
                        </div>

                        <?php if ($categorias): ?>
                        <div class="filter-group">
                            <h4 data-i18n="servicos-categoria">Categoria</h4>
                            <div class="filter-options">
                                <?php foreach ($categorias as $tipo => $total): ?>
                                <label class="filter-check">
                                    <input type="checkbox" name="category" value="<?= htmlspecialchars($tipo) ?>">
                                    <span class="checkmark"></span>
                                    <span><?= htmlspecialchars(rotuloCategoriaCatalogo($tipo)) ?> (<?= $total ?>)</span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="filter-group">
                            <h4 data-i18n="servicos-preco-maximo">Preço máximo</h4>
                            <div class="price-range">
                                <input type="range" min="<?= $precoMinimo ?>" max="<?= $precoMaximo ?>" value="<?= $precoMaximo ?>" step="10" class="range-slider" id="priceRange">
                                <div class="price-labels">
                                    <span>R$ <?= number_format($precoMinimo, 0, ',', '.') ?></span>
                                    <span id="priceValue">R$ <?= number_format($precoMaximo, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="filter-group">
                            <h4 data-i18n="servicos-avaliacao-minima">Avaliação mínima</h4>
                            <div class="rating-filter">
                                <button class="rating-btn active" type="button" data-rating="0" data-i18n="servicos-avaliacao-todas">Todas</button>
                                <button class="rating-btn" type="button" data-rating="4"><i class="ri-star-fill"></i> 4+</button>
                                <button class="rating-btn" type="button" data-rating="4.5"><i class="ri-star-fill"></i> 4,5+</button>
                            </div>
                        </div>
                    </aside>

                    <div class="services-main">
                        <div class="services-topbar">
                            <div class="results-info">
                                <span class="results-count" data-count="<?= count($servicos) ?>"><?= count($servicos) ?> serviço<?= count($servicos) === 1 ? '' : 's' ?> encontrado<?= count($servicos) === 1 ? '' : 's' ?></span>
                            </div>
                            <div class="sort-control">
                                <label for="sortSelect" data-i18n="servicos-ordenar-por">Ordenar por:</label>
                                <select id="sortSelect">
                                    <option value="relevancia" data-i18n="servicos-ord-relevancia">Ordem do catálogo</option>
                                    <option value="avaliacao" data-i18n="servicos-ord-melhor-avaliados">Melhor avaliados</option>
                                    <option value="preco-menor" data-i18n="servicos-ord-menor-preco">Menor preço</option>
                                    <option value="preco-maior" data-i18n="servicos-ord-maior-preco">Maior preço</option>
                                </select>
                            </div>
                        </div>

                        <div class="services-grid" id="servicesGrid">
                            <?php if (!$servicos): ?>
                            <div class="home-empty-state home-empty-state-wide">
                                <i class="ri-service-line"></i>
                                <h3 data-i18n="servicos-vazio-titulo">Catálogo em atualização</h3>
                                <p data-i18n="servicos-vazio-texto">Nenhum serviço está cadastrado no momento.</p>
                            </div>
                            <?php else: ?>
                                <?php foreach ($servicos as $servico): ?>
                                <?php
                                $avaliacao = (float) $servico['avaliacao_real'];
                                $totalAvaliacoes = (int) $servico['total_avaliacoes'];
                                $totalContratacoes = (int) $servico['total_contratacoes'];
                                ?>
                                <article class="service-card"
                                         data-order="<?= (int) $servico['id_servico'] ?>"
                                         data-category="<?= htmlspecialchars($servico['tipo']) ?>"
                                         data-price="<?= htmlspecialchars((string) $servico['preco']) ?>"
                                         data-rating="<?= $avaliacao ?>">
                                    <div class="card-image">
                                        <img src="../../img/<?= rawurlencode(imagemCatalogo($servico['imagem'])) ?>" alt="<?= htmlspecialchars($servico['nome_servico']) ?>">
                                        <?php if ($totalContratacoes > 0): ?>
                                        <span class="card-badge"><?= $totalContratacoes ?> contrataç<?= $totalContratacoes === 1 ? 'ão' : 'ões' ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-category"><?= htmlspecialchars(rotuloCategoriaCatalogo($servico['tipo'])) ?></div>
                                        <h3><?= htmlspecialchars($servico['nome_servico']) ?></h3>
                                        <p><?= htmlspecialchars($servico['descricao_servico'] ?: 'Descrição em atualização.') ?></p>
                                        <div class="card-meta">
                                            <div class="card-rating">
                                                <i class="ri-star-fill"></i>
                                                <?php if ($totalAvaliacoes > 0): ?>
                                                    <span><?= number_format($avaliacao, 1, ',', '.') ?></span>
                                                    <span class="rating-count">(<?= $totalAvaliacoes ?>)</span>
                                                <?php else: ?>
                                                    <span data-i18n="servicos-sem-avaliacoes">Sem avaliações</span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($servico['periodo']): ?>
                                            <span class="card-location"><i class="ri-time-line"></i> <?= htmlspecialchars($servico['periodo']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-footer">
                                            <div class="card-price">
                                                <span class="price-from" data-i18n="servicos-a-partir-de">A partir de</span>
                                                <span class="price-value">R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?></span>
                                            </div>
                                            <button type="button" class="btn-primary btn-sm service-book-button"
                                                    data-service-id="<?= (int) $servico['id_servico'] ?>"
                                                    data-service-name="<?= htmlspecialchars($servico['nome_servico']) ?>"
                                                    data-service-price="<?= htmlspecialchars((string) $servico['preco']) ?>"
                                                    data-service-period="<?= htmlspecialchars((string) ($servico['periodo'] ?: '4 horas')) ?>">
                                                <span data-i18n="servicos-agendar">Agendar</span>
                                            </button>
                                        </div>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="home-empty-state catalog-filter-empty" id="filterEmpty" hidden>
                            <i class="ri-search-eye-line"></i>
                            <h3 data-i18n="servicos-filtro-vazio-titulo">Nenhum serviço encontrado</h3>
                            <p data-i18n="servicos-filtro-vazio-texto">Tente remover algum filtro ou pesquisar outro termo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonials-section">
            <div class="section-container">
                <div class="section-header">
                    <h2 data-i18n="servicos-depoimentos">O que os clientes avaliaram</h2>
                    <p data-i18n="servicos-depoimentos-sub">Comentários vinculados a contratações registradas.</p>
                </div>
                <?php if (!$depoimentos): ?>
                    <div class="home-empty-state"><i class="ri-chat-smile-2-line"></i><h3 data-i18n="servicos-sem-depoimentos">Ainda não há avaliações</h3><p data-i18n="servicos-sem-depoimentos-sub">Os primeiros comentários aparecerão aqui.</p></div>
                <?php else: ?>
                <div class="testimonials-grid">
                    <?php foreach ($depoimentos as $depoimento): ?>
                    <?php $nota = max(1, min(5, (int) $depoimento['nota_avaliacao'])); ?>
                    <article class="testimonial-card">
                        <div class="testimonial-stars" aria-label="<?= $nota ?> de 5 estrelas">
                            <?php for ($estrela = 1; $estrela <= 5; $estrela++): ?><i class="<?= $estrela <= $nota ? 'ri-star-fill' : 'ri-star-line' ?>"></i><?php endfor; ?>
                        </div>
                        <p>“<?= htmlspecialchars($depoimento['comentario_avaliacao']) ?>”</p>
                        <div class="testimonial-author">
                            <div class="author-avatar" aria-hidden="true"><?= htmlspecialchars(inicialCatalogo($depoimento['cliente'])) ?></div>
                            <div>
                                <strong><?= htmlspecialchars(nomePublicoCatalogo($depoimento['cliente'])) ?></strong>
                                <span><?= htmlspecialchars($depoimento['servico']) ?></span>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="cta-section">
            <div class="section-container">
                <div class="cta-content">
                    <h2 data-i18n="servicos-cta-titulo">Quer solicitar um serviço?</h2>
                    <p data-i18n="servicos-cta-sub">Entre na sua conta ou fale com a Lumis informando o serviço escolhido.</p>
                    <div class="cta-buttons">
                        <a href="../auth/login.php" class="btn-primary" data-i18n="servicos-cta-entrar">Entrar na conta</a>
                        <a href="contato.php" class="btn-secondary" data-i18n="servicos-cta-contato">Entrar em contato</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="checkout-modal" id="serviceCheckout" role="dialog" aria-modal="true" aria-labelledby="checkoutTitle" hidden>
        <button class="checkout-backdrop" type="button" data-checkout-close aria-label="Fechar agendamento"></button>
        <div class="checkout-dialog">
            <button class="checkout-close" type="button" data-checkout-close aria-label="Fechar"><i class="ri-close-line"></i></button>

            <div class="checkout-step" data-checkout-step="1">
                <div class="checkout-header">
                    <span class="checkout-eyebrow"><i class="ri-calendar-check-line"></i> Agendamento</span>
                    <h2 id="checkoutTitle">Configure seu serviço</h2>
                    <p id="checkoutServiceName">Serviço selecionado</p>
                </div>

                <form id="checkoutDetailsForm" class="checkout-layout">
                    <div class="checkout-fields">
                        <section class="checkout-block">
                            <h3><i class="ri-calendar-line"></i> Data e horário</h3>
                            <div class="checkout-grid checkout-grid-2">
                                <label>Data<input type="date" id="checkoutDate" required></label>
                                <label>Horário
                                    <select id="checkoutTime" required>
                                        <option value="">Selecione</option>
                                        <option value="07:00">07:00</option><option value="08:00">08:00</option>
                                        <option value="09:00">09:00</option><option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option><option value="12:00">12:00</option>
                                        <option value="13:00">13:00</option><option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option><option value="16:00">16:00</option>
                                    </select>
                                </label>
                            </div>
                            <small id="checkoutDuration">Duração informada no catálogo</small>
                        </section>

                        <section class="checkout-block">
                            <h3><i class="ri-home-4-line"></i> Detalhes do ambiente</h3>
                            <div class="checkout-grid checkout-grid-2">
                                <label>Quartos<input type="number" id="checkoutRooms" min="1" max="30" value="1" required></label>
                                <label>Banheiros<input type="number" id="checkoutBathrooms" min="1" max="20" value="1" required></label>
                            </div>
                            <small>As quantidades ajudam o profissional a se preparar. O valor inicial permanece o cadastrado no catálogo.</small>
                        </section>

                        <section class="checkout-block">
                            <h3><i class="ri-map-pin-line"></i> Endereço do serviço</h3>
                            <div class="checkout-grid checkout-grid-2">
                                <label>CEP
                                    <span class="checkout-cep-field"><input type="text" id="checkoutCep" inputmode="numeric" maxlength="9" placeholder="00000-000" required><i class="ri-search-line" id="checkoutCepIcon" aria-hidden="true"></i></span>
                                    <small class="checkout-cep-status" id="checkoutCepStatus" aria-live="polite">Digite os 8 números para buscar o endereço.</small>
                                </label>
                                <label>Cidade<input type="text" id="checkoutCity" maxlength="60" required></label>
                            </div>
                            <label>Rua<input type="text" id="checkoutStreet" maxlength="100" required></label>
                            <div class="checkout-grid checkout-grid-2">
                                <label>Número<input type="text" id="checkoutNumber" maxlength="10" required></label>
                                <label>Bairro<input type="text" id="checkoutDistrict" maxlength="50" required></label>
                            </div>
                            <div class="checkout-grid checkout-grid-2">
                                <label>Estado<input type="text" id="checkoutState" maxlength="2" placeholder="SP" required></label>
                                <label><span class="checkout-label-title">Complemento <small>(opcional)</small></span><input type="text" id="checkoutComplement" maxlength="50"></label>
                            </div>
                        </section>
                    </div>

                    <aside class="checkout-summary">
                        <h3>Resumo</h3>
                        <div class="checkout-summary-line"><span>Serviço</span><strong id="checkoutSummaryService">—</strong></div>
                        <div class="checkout-summary-line"><span>Duração</span><strong id="checkoutSummaryPeriod">—</strong></div>
                        <div class="checkout-total"><span>Total inicial</span><strong id="checkoutSummaryPrice">R$ 0,00</strong></div>
                        <p>O profissional disponível será atribuído ao confirmar o pedido.</p>
                        <button class="btn-primary checkout-next" type="submit">Ir para pagamento <i class="ri-arrow-right-line"></i></button>
                    </aside>
                </form>
            </div>

            <div class="checkout-step" data-checkout-step="2" hidden>
                <div class="checkout-header">
                    <span class="checkout-eyebrow"><i class="ri-bank-card-line"></i> Pagamento</span>
                    <h2>Como deseja pagar?</h2>
                    <p>Escolha a forma de pagamento para registrar o agendamento.</p>
                </div>
                <div class="checkout-payment-summary">
                    <span id="checkoutPaymentService">Serviço</span>
                    <strong id="checkoutPaymentPrice">R$ 0,00</strong>
                </div>
                <div class="checkout-payment-options" role="radiogroup" aria-label="Forma de pagamento">
                    <button type="button" class="checkout-payment" data-payment="pix"><i class="ri-qr-code-line"></i><span><strong>PIX</strong><small>Pagamento pendente</small></span><i class="ri-check-line checkout-check"></i></button>
                    <button type="button" class="checkout-payment" data-payment="cartao"><i class="ri-bank-card-line"></i><span><strong>Cartão</strong><small>Pagamento pendente</small></span><i class="ri-check-line checkout-check"></i></button>
                    <button type="button" class="checkout-payment" data-payment="boleto"><i class="ri-barcode-line"></i><span><strong>Boleto</strong><small>Pagamento pendente</small></span><i class="ri-check-line checkout-check"></i></button>
                    <button type="button" class="checkout-payment" data-payment="na-hora"><i class="ri-hand-coin-line"></i><span><strong>No atendimento</strong><small>Pague ao profissional</small></span><i class="ri-check-line checkout-check"></i></button>
                </div>
                <p class="checkout-payment-note"><i class="ri-information-line"></i> Este projeto registra a opção escolhida, mas não realiza cobrança bancária real.</p>
                <p class="checkout-error" id="checkoutError" role="alert" hidden></p>
                <div class="checkout-actions">
                    <button type="button" class="btn-secondary" id="checkoutBack"><i class="ri-arrow-left-line"></i> Voltar</button>
                    <button type="button" class="btn-primary" id="checkoutConfirm">Confirmar agendamento</button>
                </div>
            </div>

            <div class="checkout-step checkout-success" data-checkout-step="3" hidden>
                <span class="checkout-success-icon"><i class="ri-check-line"></i></span>
                <h2>Agendamento registrado!</h2>
                <p>O pedido e o pagamento pendente foram salvos no banco.</p>
                <div class="checkout-success-details" id="checkoutSuccessDetails"></div>
                <div class="checkout-actions checkout-actions-center">
                    <a href="../perfil.php" class="btn-secondary">Ver meu perfil</a>
                    <button type="button" class="btn-primary" data-checkout-close>Concluir</button>
                </div>
            </div>

            <div class="checkout-step checkout-auth" data-checkout-step="auth" hidden>
                <span class="checkout-auth-icon"><i class="ri-user-line"></i></span>
                <h2 id="checkoutAuthTitle">Entre para agendar</h2>
                <p id="checkoutAuthText">O agendamento está disponível para contas de cliente.</p>
                <a href="../auth/login.php" class="btn-primary">Ir para o login</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
