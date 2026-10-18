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
    <title data-i18n="politica-titulo">Aviso de privacidade da demonstração - Lumis</title>
    <link rel="shortcut icon" href="../../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/politica-de-privacidade.css">
</head>
<body data-page="politica">
<?php
$base = '../';
$active = '';
$tituloI18n = 'politica-titulo';
$titulo = 'Aviso de privacidade da demonstração - Lumis';
include __DIR__ . '/../../partials/header-minimo.php';
?>
    <main class="container">
        <section>
            <h2 data-i18n="politica-demo-aviso-titulo">Aviso: protótipo acadêmico</h2>
            <p data-i18n="politica-demo-aviso">Esta versão pública é uma demonstração escolar, não um serviço comercial. Não informe dados pessoais reais. Os dados enviados aos formulários podem ser armazenados no banco de demonstração e apagados sem aviso.</p>
        </section>
        <section>
            <h2 data-i18n="politica-demo-dados-titulo">Dados usados na demonstração</h2>
            <p data-i18n="politica-demo-dados">Para demonstrar cadastro, perfil e agendamento, o sistema pode registrar nomes, e-mails, telefones, endereços, fotos de perfil e informações de teste. Prefira sempre valores fictícios.</p>
        </section>
        <section>
            <h2 data-i18n="politica-demo-armazenamento-titulo">Armazenamento e segurança</h2>
            <p data-i18n="politica-demo-armazenamento">O banco e os arquivos enviados pertencem ao ambiente de demonstração do projeto. Este protótipo não passou por auditoria de segurança para uso comercial; não o utilize para guardar informações reais ou sensíveis.</p>
        </section>
        <section>
            <h2 data-i18n="politica-demo-finalidade-titulo">Finalidade</h2>
            <p data-i18n="politica-demo-finalidade">As informações fictícias servem apenas para apresentar e testar o trabalho escolar. Não há cobrança nem prestação real de serviços nesta demonstração.</p>
        </section>
    </main>
    <?php include __DIR__ . '/../../partials/footer.php'; ?>
    <script src="../../../Controller/tema-idioma.js"></script>
</body>
</html>
