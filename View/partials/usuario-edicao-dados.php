<?php
// Guarda genérica da edição: aceita cliente ou profissional, mas sempre carrega
// os dados diretamente do id autenticado na sessão.
$raizPhp = dirname(__DIR__, 2);
require_once $raizPhp . '/Model/conexao.php';
require_once $raizPhp . '/Model/usuario.php';
require_once $raizPhp . '/Model/perfil.php';

$dirPagina = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$login = $dirPagina . '/auth/decisao.php';

if (empty($_SESSION['usuario'])){
    header('Location: ' . $login . '?motivo=login');
    exit;
}

$agora = time();
$iniciada = (int) ($_SESSION['auth_iniciada_em'] ?? $agora);
$ultima = (int) ($_SESSION['auth_ultima_atividade'] ?? $agora);
if (($agora - $ultima) > (int) (getenv('SESSION_IDLE_SECONDS') ?: 1800)
    || ($agora - $iniciada) > (int) (getenv('SESSION_MAX_SECONDS') ?: 28800)){
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $login . '?motivo=login');
    exit;
}

$usuario = $_SESSION['usuario'];
$tipo = (string) ($usuario['tipo'] ?? '');
$cfg = configTipo($tipo);
$linha = $cfg ? buscarUsuario($pdo, (int) $usuario['id'], $tipo) : null;
if (!$linha || (int) ($linha['auth_versao'] ?? 1) !== (int) ($usuario['auth_versao'] ?? 1)){
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $login . '?motivo=login');
    exit;
}

$_SESSION['auth_ultima_atividade'] = $agora;
if (empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$perfilDestino = $tipo === 'profissional' ? 'perfil-profissional.php' : 'perfil.php';
$foto = $linha['foto'] ?? null;
$iniciaisAvatar = iniciaisPerfil($linha['nome']);

function telefoneEdicao(string $telefone): string
{
    $digitos = preg_replace('/\D/', '', $telefone);
    if (strlen($digitos) === 11) return sprintf('(%s) %s-%s', substr($digitos, 0, 2), substr($digitos, 2, 5), substr($digitos, 7));
    if (strlen($digitos) === 10) return sprintf('(%s) %s-%s', substr($digitos, 0, 2), substr($digitos, 2, 4), substr($digitos, 6));
    return $telefone;
}
