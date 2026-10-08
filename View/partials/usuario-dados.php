<?php
// Dados do usuário logado, vindos da sessão que o cadastro/login gravaram.
// Não usa JavaScript: o perfil precisa mostrar o nome real mesmo antes do
// Auth.js rodar, e funciona igual se o JS falhar ou for bloqueado.

$raizPhp = dirname(__DIR__, 2);
require_once $raizPhp . '/Model/conexao.php';
require_once $raizPhp . '/Model/usuario.php';
require_once $raizPhp . '/Model/perfil.php';

$dirPagina = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$login = $dirPagina . '/auth/login.php';

function redirecionarLoginCliente(string $login): void{
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) session_destroy();
    header('Location: ' . $login . '?motivo=login');
    exit;
}

if (empty($_SESSION['usuario'])){
    // O Location é resolvido pelo navegador a partir do DOCUMENTO (/View/pages/perfil.php),
    // não a partir deste arquivo. Por isso não dá para escrever '../auth/login.php':
    // esse caminho apontaria para /View/auth/login.php, que não existe.
    // Aqui montamos o caminho a partir da pasta real da página que está rodando.
    redirecionarLoginCliente($login);
}

if (($_SESSION['usuario']['tipo'] ?? '') !== 'cliente'){
    header('Location: ' . $dirPagina . '/perfil-profissional.php');
    exit;
}

$agora = time();
$iniciada = (int) ($_SESSION['auth_iniciada_em'] ?? $agora);
$ultima = (int) ($_SESSION['auth_ultima_atividade'] ?? $agora);
if (($agora - $ultima) > (int) (getenv('SESSION_IDLE_SECONDS') ?: 1800)
    || ($agora - $iniciada) > (int) (getenv('SESSION_MAX_SECONDS') ?: 28800)){
    redirecionarLoginCliente($login);
}
$_SESSION['auth_ultima_atividade'] = $agora;

$usuario = $_SESSION['usuario'];

$nome      = $usuario['nome'];
$email     = $usuario['email'];
$id        = (int) $usuario['id'];

// Primeiro nome para saudações curtas
$primeiroNome = trim(explode(' ', $nome)[0]);

// Traz a linha completa do banco para pegar telefone, foto e o resto
$linha = buscarUsuario($pdo, $id, $usuario['tipo']) ?? [];
if (!$linha || (int) ($linha['auth_versao'] ?? 1) !== (int) ($usuario['auth_versao'] ?? 1)){
    redirecionarLoginCliente($login);
}
$nome = $linha['nome'];
$email = $linha['email'];

// Monta (11) 98765-4321 a partir de 11987654321
// 11 dígitos = 2 (DDD) + 5 (prefixo) + 4 (final). O último bloco sempre
// tem 4, então o substr precisa de comprimento explícito; sem ele, substr($d,5)
// traz o resto todo e o número sai com dígito a mais.
function formatarTelefone(string $digitos): string{
    $d = preg_replace('/\D/', '', $digitos);

    if (strlen($d) === 11){
        return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 5), substr($d, 7, 4));
    }
    if (strlen($d) === 10){
        return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 4), substr($d, 6, 4));
    }
    return $digitos;
}

$telefoneBruto    = $linha['telefone'] ?? '';
$telefoneFormatado = $telefoneBruto !== '' ? formatarTelefone($telefoneBruto) : '—';

// Sem foto no banco, a view exibe as iniciais em vez de uma pessoa fictícia.
$foto   = $linha['foto'] ?? null;
$avatar = $foto ?: null;
$iniciaisAvatar = iniciaisPerfil($nome);

// Data de nascimento vinda do banco; mostra só o ano na linha de meta
$nascimento = $linha['data_nascimento'] ?? null;

// Conteúdo do dashboard. Quando uma tabela ainda não possui registros, as
// consultas retornam listas vazias e a página mostra um estado vazio honesto.
$resumoPerfil = buscarResumoPerfilCliente($pdo, $id);
$visitasPerfil = buscarVisitasPerfil($pdo, 'cliente', $id);
$datasAgendaPerfil = buscarDatasAgendaPerfil($pdo, 'cliente', $id);
$atividadesPerfil = buscarAtividadesPerfil($pdo, 'cliente', $id);
$profissionalPreferido = buscarProfissionalPreferidoPerfil($pdo, $id);
$avaliacoesPerfil = buscarAvaliacoesPerfil($pdo, 'cliente', $id);
