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
    <!-- Estilos especificos da pagina -->
    <link rel="stylesheet" href="../../css/ajuda.css">

    <!-- Link para o RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title>Lumis</title>
</head>
<body data-page="ajuda">
    <?php
$base = '../';
$active = '';
include __DIR__ . '/../../partials/header.php';
?>
    <main>
       <!-- ========== HERO ========== -->
       <section class="ajuda-hero">
           <div class="ajuda-hero-bg"></div>
           <div class="hero-content">
               <span class="ajuda-tag"><i class="ri-question-line"></i> <span data-i18n="ajuda-tag">Central de ajuda</span></span>
               <h1 data-i18n="ajuda-hero-titulo">Como podemos <span class="highlight">ajudar</span> você?</h1>
               <p data-i18n="ajuda-hero-sub">Encontre respostas para as dúvidas mais comuns sobre nossos serviços, planos e pagamentos.</p>
           </div>
       </section>

       <!-- FAQ Section -->
    <section class="faq">
        <div class="faq-content">
            <h2 class="section-title" data-i18n="ajuda-faq-title">Perguntas Frequentes</h2>
            <p class="section-subtitle" data-i18n="ajuda-faq-subtitle">Tire suas dúvidas sobre nossos serviços e sistema</p>

            <div class="faq-category">
                <h2 class="category-title">
                    <i class="ri-information-line"></i>
                    <span data-i18n="ajuda-cat-sobre">Sobre a Lumis</span>
                </h2>
                
                <details>
                    <summary data-i18n="ajuda-faq-lumis-oq">O que é a Lumis?</summary>
                    <div class="faq-content-text">
                        <p data-i18n="ajuda-faq-lumis-oq-resp">A Lumis é uma empresa especializada em serviços de limpeza e higienização profissional. Oferecemos soluções completas para residências, escritórios e estabelecimentos comerciais, sempre com foco na sustentabilidade e qualidade.</p>
                    </div>
                </details>

                <details>
                    <summary data-i18n="ajuda-faq-diferenciais">Quais são nossos diferenciais?</summary>
                    <div class="faq-content-text">
                        <p data-i18n="ajuda-faq-diferenciais-intro">Nossos principais diferenciais são:</p>
                        <ul>
                            <li data-i18n="ajuda-dif-item1">Profissionais qualificados e treinados</li>
                            <li data-i18n="ajuda-dif-item2">Produtos eco-friendly e sustentáveis</li>
                            <li data-i18n="ajuda-dif-item3">Pontualidade e flexibilidade de horários</li>
                            <li data-i18n="ajuda-dif-item4">Garantia de qualidade em todos os serviços</li>
                            <li data-i18n="ajuda-dif-item5">Tecnologia avançada para gestão e controle</li>
                        </ul>
                    </div>
                </details>
            </div>

            <div class="faq-category">
                <h2 class="category-title">
                    <i class="ri-tools-line"></i>
                    <span data-i18n="ajuda-cat-servicos">Serviços</span>
                </h2>
                
                <details>
                    <summary data-i18n="ajuda-faq-tipos-limpeza">Quais tipos de limpeza vocês fazem?</summary>
                    <div class="faq-content-text">
                        <p data-i18n="ajuda-faq-tipos-limpeza-intro">Oferecemos diversos tipos de serviços:</p>
                        <ul>
                            <li data-i18n="ajuda-servico-residencial"><strong>Limpeza Residencial:</strong> Casas e apartamentos completos</li>
                            <li data-i18n="ajuda-servico-comercial"><strong>Limpeza Comercial:</strong> Escritórios, lojas e empresas</li>
                            <li data-i18n="ajuda-servico-higienizacao"><strong>Higienização:</strong> Sanitização completa com produtos certificados</li>
                            <li data-i18n="ajuda-servico-posobra"><strong>Limpeza Pós-Obra:</strong> Remoção de resíduos de construção</li>
                        </ul>
                    </div>
                </details>

                <details>
                    <summary data-i18n="ajuda-faq-finais-semana">Vocês trabalham em finais de semana?</summary>
                    <div class="faq-content-text">
                        <p data-i18n="ajuda-faq-finais-semana-resp">Sim! Oferecemos flexibilidade de horários, incluindo atendimento em finais de semana e feriados. Nossa equipe se adapta às suas necessidades para garantir o melhor serviço no horário mais conveniente.</p>
                    </div>
                </details>

                <details>
                    <summary data-i18n="ajuda-faq-avaliacao">Como funciona a avaliação de qualidade?</summary>
                    <div class="faq-content-text">
                        <p data-i18n="ajuda-faq-avaliacao-intro">Temos um sistema completo de controle de qualidade que inclui:</p>
                        <ul>
                            <li data-i18n="ajuda-avaliacao-item1">Checklist detalhado para cada serviço</li>
                            <li data-i18n="ajuda-avaliacao-item2">Supervisão periódica das equipes</li>
                            <li data-i18n="ajuda-avaliacao-item3">Sistema de feedback dos clientes</li>
                            <li data-i18n="ajuda-avaliacao-item4">Garantia de retrabalho se necessário</li>
                        </ul>
                    </div>
                </details>
            </div>

            <div class="faq-category">
                <h2 class="category-title">
                    <i class="ri-wallet-3-line"></i>
                    <span data-i18n="ajuda-cat-planos">Planos e Pagamentos</span>
                </h2>
                
                <details>
                    <summary data-i18n="ajuda-faq-planos-assinatura">Como funcionam os planos de assinatura?</summary>
                    <div class="faq-content-text">
                        <p data-i18n="ajuda-faq-planos-assinatura-intro">Oferecemos diferentes planos de assinatura mensal adaptados às suas necessidades:</p>
                        <ul>
                            <li data-i18n="ajuda-plano-basico"><strong>Plano Básico:</strong> Limpeza quinzenal</li>
                            <li data-i18n="ajuda-plano-premium"><strong>Plano Premium:</strong> Limpeza semanal</li>
                            <li data-i18n="ajuda-plano-empresarial"><strong>Plano Empresarial:</strong> Serviços diários ou personalizados</li>
                        </ul>
                        <p data-i18n="ajuda-plano-todos">Todos os planos incluem produtos eco-friendly e garantia de qualidade.</p>
                    </div>
                </details>

                <details>
                    <summary data-i18n="ajuda-faq-pagamento">Quais formas de pagamento aceitas?</summary>
                    <div class="faq-content-text">
                        <p data-i18n="ajuda-faq-pagamento-intro">Aceitamos diversas formas de pagamento:</p>
                        <ul>
                            <li data-i18n="ajuda-pagamento-cartao-credito">Cartão de crédito (até 12x)</li>
                            <li data-i18n="ajuda-pagamento-cartao-debito">Cartão de débito</li>
                            <li data-i18n="ajuda-pagamento-pix">PIX</li>
                            <li data-i18n="ajuda-pagamento-boleto">Boleto bancário</li>
                            <li data-i18n="ajuda-pagamento-transferencia">Transferência bancária</li>
                        </ul>
                    </div>
                </details>
                <br><br><br><br><br>
            <h2 data-i18n="ajuda-contato-chamada">Não conseguiu tirar sua dúvida? Entre em <span>contato</span> conosco!</h2>
    </main>
     <?php include __DIR__ . '/../../partials/footer.php'; ?>
      
    
    <script src="../../../Controller/Ajuda.js"></script>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>