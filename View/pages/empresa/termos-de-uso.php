<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script>
        try {
            const temaSalvo = localStorage.getItem('tema');
            document.documentElement.setAttribute('data-theme', temaSalvo || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        } catch (e) { document.documentElement.setAttribute('data-theme', 'light'); }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="termo-titulo">Termos e aviso da demonstração - Lumis</title>
    <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/termos-de-uso.css">
</head>
<body data-page="termo">
<?php
$base = '../';
$active = '';
$tituloI18n = 'termo-titulo';
$titulo = 'Termos e aviso da demonstração - Lumis';
include __DIR__ . '/../../partials/header-minimo.php';
?>
    <main class="container">
        <section>
            <h2 data-i18n="termo-demo-aviso-titulo">Aviso: protótipo acadêmico</h2>
            <p data-i18n="termo-demo-aviso">A Lumis é um projeto escolar de demonstração. Não representa uma empresa de limpeza em operação nem oferece serviços reais.</p>
        </section>
        <section>
            <h2 data-i18n="termo-demo-uso-titulo">Uso da demonstração</h2>
            <p data-i18n="termo-demo-uso">Use somente dados fictícios. Agendamentos, profissionais e avaliações são apresentados para demonstrar funcionalidades e podem ser reiniciados ou removidos.</p>
        </section>
        <section>
            <h2 data-i18n="termo-demo-pagamento-titulo">Pagamentos</h2>
            <p data-i18n="termo-demo-pagamento">As opções de pagamento são simuladas. Nenhuma cobrança, assinatura, contratação ou estorno real é processado.</p>
        </section>
        <section>
            <h2 data-i18n="termo-demo-limites-titulo">Limites do projeto</h2>
            <p data-i18n="termo-demo-limites">Este protótipo não deve ser usado para contratar serviços. Esta página é um aviso sobre a demonstração acadêmica, não um contrato para operação comercial.</p>
        </section>
    </main>
    <?php include __DIR__ . '/../../partials/footer.php'; ?>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
