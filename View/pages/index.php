<?php
require_once dirname(__DIR__, 2) . '/Model/conexao.php';
require_once dirname(__DIR__, 2) . '/Model/home.php';

$servicosHome = buscarServicosHome($pdo);
$avaliacoesHome = buscarAvaliacoesHome($pdo, 12);
$iconesServico = [
    'residencial' => 'ri-home-4-line',
    'comercial' => 'ri-building-line',
    'estofados' => 'ri-sofa-line',
    'vidros' => 'ri-window-line',
    'carros' => 'ri-car-washing-line',
];
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
    <title>Lumis - home</title>

    <!---Links do css e outros-->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="website icon" type="png" href="../img/Logo_Sem_Nome.png">
    
    <!-- Link para o RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>
<body data-page="index">
<?php
$base = '';
$active = 'inicio';
include __DIR__ . '/../partials/header.php';
?>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-content">
            <div class="hero-text">
                <span class="home-tag"><i class="ri-home-4-line"></i> <span data-i18n="home-tag">Limpeza profissional e sustentável</span></span>
                <h1 data-i18n="home-hero-title">Transforme seu <span class="highlight">espaço</span></h1>
                <p data-i18n="home-hero-subtitle">Sustentabilidade e limpeza em um só lugar.</p>
                <div class="hero-buttons">
                    <a href="empresa/servicos.php" class="btn-primary" data-i18n="home-hero-btn-servicos">Conheça nossos serviços</a>
                    <a href="empresa/contato.php" class="btn-secondary" data-i18n="home-hero-btn-orcamento">Solicite orçamento</a>
                </div>
            </div>
           
    </section>

    <!-- Services Section -->
    <section class="services" id="servicos">
        <div class="services-content">
            <h2 class="section-title" data-i18n="home-services-title">Nossos Serviços</h2>
            <div class="services-grid">
                <?php if (!$servicosHome): ?>
                    <div class="home-empty-state home-empty-state-wide"><i class="ri-service-line"></i><h3>Catálogo em atualização</h3><p>Nenhum serviço está disponível no momento.</p></div>
                <?php else: ?>
                    <?php foreach ($servicosHome as $servico): ?>
                    <article class="service-card">
                        <div class="service-icon"><i class="<?= htmlspecialchars($iconesServico[$servico['tipo']] ?? 'ri-sparkling-2-line') ?>"></i></div>
                        <h3><?= htmlspecialchars($servico['nome_servico']) ?></h3>
                        <p><?= htmlspecialchars($servico['descricao_servico'] ?: 'Descrição em atualização.') ?></p>
                        <div class="service-card-meta">
                            <span>A partir de <strong>R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?></strong></span>
                            <?php if ($servico['periodo']): ?><span><i class="ri-time-line"></i> <?= htmlspecialchars($servico['periodo']) ?></span><?php endif; ?>
                        </div>
                        <a href="empresa/servicos.php" class="learn-more">Ver serviço <i class="ri-arrow-right-line"></i></a>
                    </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="sobre">
        <div class="about-content">
            <div class="about-text">
                <h2 data-i18n="home-about-title">Seu ambiente limpo e higienizado em um só lugar!</h2>
                <p>A Lumis reúne clientes e profissionais de limpeza em uma plataforma para consultar serviços, organizar perfis e acompanhar agendamentos.</p>
                <a href="empresa/sobre.php" class="btn-primary" data-i18n="home-about-btn">Conheça nossa história</a>
            </div>
            <div class="about-image">
                <img src="../img/Home_cidade.png" alt="Ambiente limpo">
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section class="why-choose">
        <div class="why-choose-content">
            <h2 class="section-title" data-i18n="home-why-title">Por que escolher a Lumis?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-user-star-line"></i>
                    </div>
                    <h4>Perfis profissionais</h4>
                    <p>Informações de atuação, experiência e avaliações reunidas no perfil</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-leaf-line"></i>
                    </div>
                    <h4>Catálogo transparente</h4>
                    <p>Descrição, duração e preço inicial cadastrados para cada serviço</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-time-line"></i>
                    </div>
                    <h4>Agenda organizada</h4>
                    <p>Datas e horários aparecem diretamente no perfil do usuário</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <h4>Avaliações reais</h4>
                    <p>Comentários vinculados a contratações registradas na plataforma</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-24-hours-line"></i>
                    </div>
                    <h4>Dados atualizáveis</h4>
                    <p>Clientes e profissionais podem manter seus perfis atualizados</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Work With Us Section -->
    <section class="work-with-us">
        <div class="work-content">
            <h2 class="section-title" data-i18n="home-work-title">Faça parte da nossa equipe</h2>
            <div class="work-grid">
                <div class="work-card">
                    <h4>Crie seu perfil profissional</h4>
                    <p>Cadastre seus dados de contato, experiência e região de atuação.</p>
                </div>

                <div class="work-card">
                    <h4>Apresente seus serviços</h4>
                    <p>Suas especialidades ficarão reunidas no perfil profissional.</p>
                </div>

                <div class="work-card">
                    <h4>Organize sua agenda</h4>
                    <p>Acompanhe visitas e atividades relacionadas às contratações.</p>
                </div>

                <div class="work-card">
                    <h4>Construa sua reputação</h4>
                    <p>Receba avaliações de clientes após serviços registrados.</p>
                </div>
            </div>
            <a href="auth/cadastro-trabalhador.php" class="btn-primary" style="margin-top: 2rem;" data-i18n="home-work-btn">Candidate-se agora</a>
        </div>
    </section>

    
    <section class="testimonials">
        <h2 data-i18n="home-test-title">Comentários</h2>
        <?php if (!$avaliacoesHome): ?>
            <div class="home-empty-state testimonials-empty"><i class="ri-chat-smile-2-line"></i><h3>Ainda não há avaliações publicadas</h3><p>Os comentários aparecerão aqui depois das primeiras contratações avaliadas.</p></div>
        <?php else: ?>
            <div class="testimonial-carousel" aria-label="Avaliações de clientes">
                <div class="testimonial-toolbar">
                    <div class="testimonial-controls">
                        <button class="prev" type="button" aria-label="Ver avaliações anteriores"><i class="ri-arrow-left-s-line" aria-hidden="true"></i></button>
                        <button class="testimonial-pause" type="button" aria-label="Pausar rotação automática" aria-pressed="false"><i class="ri-pause-mini-fill" aria-hidden="true"></i></button>
                        <button class="next" type="button" aria-label="Ver próximas avaliações"><i class="ri-arrow-right-s-line" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="testimonial-viewport" tabindex="0" aria-label="Use as setas do teclado ou deslize para navegar">
                    <div class="carousel">
                        <?php foreach ($avaliacoesHome as $indice => $avaliacao): ?>
                        <?php $nota = max(0, min(5, (int) $avaliacao['nota_avaliacao'])); ?>
                        <article class="card testimonial-slide" aria-label="Avaliação <?= $indice + 1 ?> de <?= count($avaliacoesHome) ?>">
                            <div class="testimonial-avatar-initials" aria-hidden="true"><?= htmlspecialchars(iniciaisHome($avaliacao['cliente'])) ?></div>
                            <h3><?= htmlspecialchars(nomePublicoHome($avaliacao['cliente'])) ?></h3>
                            <div class="stars" aria-label="<?= $nota ?> de 5 estrelas"><?= str_repeat('★', $nota) ?><?= str_repeat('☆', 5 - $nota) ?></div>
                            <p>“<?= htmlspecialchars($avaliacao['comentario_avaliacao']) ?>”</p>
                            <span class="testimonial-service"><?= htmlspecialchars($avaliacao['servico']) ?> · profissional <?= htmlspecialchars(nomePublicoHome($avaliacao['profissional'])) ?></span>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="testimonial-progress" aria-hidden="true"><span class="testimonial-progress-value"></span></div>
            </div>
        <?php endif; ?>
    </section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
            

    <script src="../../Controller/index.js"></script>

    <script src="../../Controller/tema-idioma.js"></script>
</body>
</html>
