<?php
require_once __DIR__ . '/../../Model/config.php';
if ((string) configuracaoLumis('APP_DEMO_MODE', '1') === '1'):
?>
<aside class="demo-notice" role="note">
    <strong data-i18n="demo-aviso-titulo">Projeto demonstrativo</strong>
    <span data-i18n="demo-aviso-texto">Use apenas dados fictícios. Agendamentos e pagamentos não são reais e os dados podem ser apagados.</span>
</aside>
<?php endif; ?>
