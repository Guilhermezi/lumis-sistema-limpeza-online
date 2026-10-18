<?php
/** Copie como config.local.php e substitua pelos valores do painel da hospedagem. */
return [
    'APP_ENV' => 'production',
    'APP_URL' => 'https://lumis.great-site.net',
    'APP_DEMO_MODE' => '1',
    'DB_HOST' => 'copie-o-host-do-banco-no-painel',
    'DB_NAME' => 'copie-o-nome-completo-do-banco',
    'DB_USER' => 'copie-o-usuario-completo-do-banco',
    'DB_PASSWORD' => 'coloque-a-senha-do-banco-aqui',
    'MAIL_FROM' => 'seu-remetente-verificado@example.com',
    'MAIL_FROM_NAME' => 'Lumis',
    'SMTP_HOST' => 'servidor-smtp-do-provedor',
    'SMTP_PORT' => '587',
    'SMTP_ENCRYPTION' => 'tls',
    'SMTP_USERNAME' => 'usuario-smtp',
    'SMTP_PASSWORD' => 'senha-smtp',
    'SESSION_IDLE_SECONDS' => '1800',
    'SESSION_MAX_SECONDS' => '28800',
];
