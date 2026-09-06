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
                <div class="service-card">
                    <div class="service-icon">
                        <i class="ri-home-4-line"></i>
                    </div>
                    <h3 data-i18n="home-services1-title">Limpeza Residencial</h3>
                    <p data-i18n="home-services1-desc">Serviços completos de limpeza para sua casa, incluindo todos os cômodos com produtos de alta qualidade.</p>
                    <a href="empresa/servicos.php" class="learn-more"><span data-i18n="home-services1-link">Saiba mais</span> <i class="ri-arrow-right-line"></i></a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="ri-building-line"></i>
                    </div>
                    <h3 data-i18n="home-services2-title">Limpeza Comercial</h3>
                    <p data-i18n="home-services2-desc">Soluções profissionais de limpeza para escritórios, lojas e estabelecimentos comerciais.</p>
                    <a href="empresa/servicos.php" class="learn-more"><span data-i18n="home-services2-link">Saiba mais</span> <i class="ri-arrow-right-line"></i></a>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="ri-tools-line"></i>
                    </div>
                    <h3 data-i18n="home-services3-title">Limpeza Pós-Obra</h3>
                    <p data-i18n="home-services3-desc">Remoção de resíduos de construção e limpeza especializada após reformas e obras.</p>
                    <a href="empresa/servicos.php" class="learn-more"><span data-i18n="home-services3-link">Saiba mais</span> <i class="ri-arrow-right-line"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="sobre">
        <div class="about-content">
            <div class="about-text">
                <h2 data-i18n="home-about-title">Seu ambiente limpo e higienizado em um só lugar!</h2>
                <p data-i18n="home-about-desc">Na Lumis, oferecemos serviços completos de limpeza e higienização com foco na sustentabilidade. Nossa equipe especializada utiliza produtos eco-friendly e técnicas modernas para garantir o melhor resultado.</p>
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
                    <h4 data-i18n="home-why1-title">Profissionais Qualificados</h4>
                    <p data-i18n="home-why1-desc">Equipe treinada e experiente</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-leaf-line"></i>
                    </div>
                    <h4 data-i18n="home-why2-title">Produtos Eco-friendly</h4>
                    <p data-i18n="home-why2-desc">Sustentabilidade em primeiro lugar</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-time-line"></i>
                    </div>
                    <h4 data-i18n="home-why3-title">Pontualidade</h4>
                    <p data-i18n="home-why3-desc">Compromisso com horários</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <h4 data-i18n="home-why4-title">Garantia de Qualidade</h4>
                    <p data-i18n="home-why4-desc">Satisfação garantida</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="ri-24-hours-line"></i>
                    </div>
                    <h4 data-i18n="home-why5-title">Flexibilidade de Horários</h4>
                    <p data-i18n="home-why5-desc">Atendimento quando você precisar</p>
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
                    <h4 data-i18n="home-work1-title">Oportunidades de crescimento</h4>
                    <p data-i18n="home-work1-desc">Oferecemos um ambiente de trabalho que valoriza o desenvolvimento profissional e pessoal de nossos colaboradores.</p>
                </div>

                <div class="work-card">
                    <h4 data-i18n="home-work2-title">Ambiente colaborativo</h4>
                    <p data-i18n="home-work2-desc">Trabalhe em uma equipe unida, onde cada membro é valorizado e suas ideias são ouvidas e implementadas.</p>
                </div>

                <div class="work-card">
                    <h4 data-i18n="home-work3-title">Benefícios atrativos</h4>
                    <p data-i18n="home-work3-desc">Pacote completo de benefícios incluindo plano de saúde, vale-alimentação e programas de capacitação.</p>
                </div>

                <div class="work-card">
                    <h4 data-i18n="home-work4-title">Propósito sustentável</h4>
                    <p data-i18n="home-work4-desc">Faça parte de uma empresa comprometida com a sustentabilidade e o cuidado com o meio ambiente.</p>
                </div>
            </div>
            <a href="auth/cadastro-trabalhador.php" class="btn-primary" style="margin-top: 2rem;" data-i18n="home-work-btn">Candidate-se agora</a>
        </div>
    </section>

    
<section class="testimonials">
        <h2 data-i18n="home-test-title">Comentários</h2>
        <!-- Carousel comentarios -->
        <div class="carousel-container">
            <button class="prev">&#10094;</button>

            <div class="carousel">
                    <!-- Slide 1 -->
                    <div class="carousel-item">
                        <div class="card">
                            <img src="../img/João Neves.png" alt="João Neves" class="avatar">
                            <h3>João Neves</h3>
                            <div class="stars">★★★★★</div>
                            <p data-i18n="home-test1">“Assinei o plano semanal e foi a melhor escolha! A equipe é pontual e deixa minha casa impecável toda semana.”</p>
                        </div>
                        <div class="card">
                            <img src="../img/foto_usuario.png" alt="Maria de Souza" class="avatar"> 
                            <h3>Maria de Souza</h3>
                            <div class="stars">★★★★★</div>
                            <p data-i18n="home-test2">“Fechamos o plano empresarial e o escritório nunca esteve tão bem cuidado. Atendimento excelente e serviço de primeira.”</p>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="card">
                            <img src="../img/Ana Ribeiro.png" alt="Ana Ribeiro" class="avatar">
                            <h3>Ana Ribeiro</h3>
                            <div class="stars">★★★★★</div>
                            <p data-i18n="home-test3">“Fiquei impressionada com a qualidade do serviço! Além de super cuidadosos com meus móveis, a equipe da Lumis é muito educada. Recomendo demais!”</p>
                        </div>
                        <div class="card">
                            <img src="../img/Lucas Martins.png" alt="Lucas Martins" class="avatar">
                            <h3>Lucas Martins</h3>
                            <div class="stars">★★★★★</div>
                            <p data-i18n="home-test4">“Assinei o plano mensal e já na primeira limpeza senti a diferença. Minha casa nunca esteve tão limpa e cheirosa!”</p>
                        </div>
                    </div>
                </div>

            <button class="next">&#10095;</button>
        </div>

        <!-- As bolinhas de navegação -->
        <div class="indicators">
            <span class="dot active"></span>
            <span class="dot"></span>
        </div>
    </section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
            

    <script src="../../Controller/index.js"></script>

    <script src="../../Controller/tema-idioma.js"></script>
</body>
</html>