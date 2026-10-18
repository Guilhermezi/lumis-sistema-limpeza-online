<?php
declare(strict_types=1);

/**
 * Configuração central. A hospedagem gratuita não oferece variáveis de ambiente;
 * nesse caso, use Model/config.local.php, ignorado pelo Git e bloqueado no Apache.
 */
$configuracoesLocais = __DIR__ . '/config.local.php';
$configuracoesLumis = is_file($configuracoesLocais) ? require $configuracoesLocais : [];
if (!is_array($configuracoesLumis)) {
    $configuracoesLumis = [];
}

function configuracaoLumis(string $chave, mixed $padrao = null): mixed
{
    global $configuracoesLumis;
    $ambiente = getenv($chave);
    if ($ambiente !== false && $ambiente !== '') {
        return $ambiente;
    }

    return $configuracoesLumis[$chave] ?? $padrao;
}
