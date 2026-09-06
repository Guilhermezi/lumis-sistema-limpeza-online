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
    <title>Lumis - Cidade consciente</title>
    <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/cidade-consciente.css">
    <script src="../../../Controller/cidade_consciente2.js"></script>
</head>
<body data-page="cidade">
 <?php
$base = '../';
$active = 'cidade';
include __DIR__ . '/../../partials/header.php';
?>

    <!-- Hero Section -->
    <section class="hero-cidade">
        <div class="hero-content">
            <div class="hero-text">
                <span class="cidade-tag"><i class="ri-earth-line"></i> <span data-i18n="cidade-tag">Sustentabilidade e tecnologia</span></span>
                <h1 data-i18n="cidade-hero-titulo">Projeto cidade <span class="highlight">consciente</span></h1>
                <p data-i18n="cidade-hero-sub">Sustentabilidade e limpeza em um só lugar</p>
                <div class="hero-buttons">
                    <a href="#" class="btn-primary" data-i18n="cidade-hero-junte">Junte-se a nós</a>
                    <a href="#" class="btn-secondary" data-i18n="cidade-hero-contribua">Contribua</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Projeto Cidade Consciente -->
    <section class="projeto-section">
        <div class="projeto-content">
            <h2 class="section-title" data-i18n="cidade-projeto-titulo">Projeto Cidade Consciente</h2>
            <p class="projeto-description" data-i18n="cidade-projeto-descricao">
                Nossa tecnologia revoluciona a gestão de resíduos urbanos, utilizando 
                sensores inteligentes que fornecem dados das lixeiras públicas em tempo real para otimizar a coleta seletiva das cidades.
            </p>

            <!-- LIXEIRA ORGANIZADA -->
            <div class="monitor-container">
                <div class="battery">
                    <div class="battery-body">
                        <div class="charge"></div>
                    </div>
                    <div class="status-atual" id="statusAtual" data-i18n="cidade-status-monitorando">
                        Monitorando...
                    </div>
                </div>
                
                <div class="legenda">
                    <div class="status-badge disponivel">
                        <div class="color-indicator"></div>
                        <span data-i18n="cidade-status-disponivel">0-25% - Disponível</span>
                    </div>
                    
                    <div class="status-badge atencao">
                        <div class="color-indicator"></div>
                        <span data-i18n="cidade-status-atencao">26-50% - Atenção</span>
                    </div>
                    
                    <div class="status-badge prioridade">
                        <div class="color-indicator"></div>
                        <span data-i18n="cidade-status-prioritaria">51-75% - Coleta Prioritária</span>
                    </div>
                    
                    <div class="status-badge coletar">
                        <div class="color-indicator"></div>
                        <span data-i18n="cidade-status-imediata">76-95% - Coleta imediata</span>
                    </div>
                </div>
            </div>

            <p class="sistema-info" data-i18n="cidade-sistema-info">O sistema atualiza automaticamente o status e prioriza coletas necessárias ao redor das cidades</p>
            <button class="saiba-mais-btn" data-i18n="cidade-saiba-mais">Saiba mais</button>
        </div>
    </section>

    <!-- Conheça Mais Section -->
    <section class="conheca-section">
        <div class="conheca-content">
            <div class="projeto-card">
                <div class="projeto-info">
                    <h3 data-i18n="cidade-conheca-titulo">Projeto Cidade Consciente</h3>
                    <p data-i18n="cidade-conheca-descricao">O projeto Cidade Consciente é uma iniciativa da Lumis que une tecnologia com sustentabilidade, e tem como objetivo promover cidades mais sustentáveis.</p>
                    
                    <ul class="projeto-features">
                        <li data-i18n="cidade-feature-monitoramento">Monitoramento em tempo real</li>
                        <li data-i18n="cidade-feature-reducao-custos">Redução de custos operacionais</li>
                        <li data-i18n="cidade-feature-coleta-rotas">Coleta seletiva otimizada por rotas</li>
                        <li data-i18n="cidade-feature-educacao">Educação ambiental integrada</li>
                    </ul>

                    <button class="btn-conhecer" data-i18n="cidade-conheca-btn">Conheça nossos serviços oferecidos</button>
                </div>

                <img src="../../img/projetocidade_consciente.jpg" alt="Infográfico do projeto" class="projeto-imagem">
            </div>
        </div>
    </section>

    <!-- Parceiros Section -->
    <section class="parceiros-section">
  <div class="parceiros-content">
    <h2 class="section-title" data-i18n="cidade-parceiros-titulo">Parceiros e Patrocinadores</h2>

    <div class="carrossel-wrapper">
      <div class="parceiros-grid">
        <div class="parceiro-card">
          <div class="parceiro-icon">
            <i class="ri-building-line"></i>
          </div>
          <h4>Empresa XYZ</h4>
          <p data-i18n="cidade-parceiro1-desc">Líder em tecnologia sustentável para cidades inteligentes</p>
          <button class="btn-parceiro" data-i18n="cidade-parceiro1-btn">Conhecer</button>
        </div>

        <div class="parceiro-card">
          <div class="parceiro-icon">
            <i class="ri-recycle-line"></i>
          </div>
          <h4>EcoTech Solutions</h4>
          <p data-i18n="cidade-parceiro2-desc">Especializada em reciclagem e gestão de resíduos urbanos</p>
          <button class="btn-parceiro" data-i18n="cidade-parceiro2-btn">Conhecer</button>
        </div>

        <div class="parceiro-card">
          <div class="parceiro-icon">
            <i class="ri-team-line"></i>
          </div>
          <h4 data-i18n="cidade-parceiro3-nome">Comunidade</h4>
          <p data-i18n="cidade-parceiro3-desc">Engajamento cidadão para uma cidade mais sustentável</p>
          <button class="btn-parceiro" data-i18n="cidade-parceiro3-btn">Conhecer</button>
        </div>

        <div class="parceiro-card">
          <div class="parceiro-icon">
            <i class="ri-government-line"></i>
          </div>
          <h4 data-i18n="cidade-parceiro4-nome">Órgãos de inovação</h4>
          <p data-i18n="cidade-parceiro4-desc">Apoio governamental para projetos de sustentabilidade</p>
          <button class="btn-parceiro" data-i18n="cidade-parceiro4-btn">Conhecer</button>
        </div>
      </div>
    </div>

    <div class="dots-navigation">
      <div class="dot active"></div>
      <div class="dot"></div>
    </div>
  </div>
</section>

    <?php include __DIR__ . '/../../partials/footer.php'; ?>
        <script src="../../../Controller/tema-idioma.js"></script>
</body>
    <script src="../../../Controller/cidade_consciente.js"></script>
    </html>