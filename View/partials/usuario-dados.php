<?php
// Dados do usuário logado, vindos da sessão que o cadastro/login gravaram.
// Não usa JavaScript: o perfil precisa mostrar o nome real mesmo antes do
// Auth.js rodar, e funciona igual se o JS falhar ou for bloqueado.

$raizPhp = dirname(__DIR__, 2);
require_once $raizPhp . '/Model/conexao.php';
require_once $raizPhp . '/Model/usuario.php';

if (empty($_SESSION['usuario'])){
    // O Location é resolvido pelo navegador a partir do DOCUMENTO (/View/pages/perfil.php),
    // não a partir deste arquivo. Por isso não dá para escrever '../auth/login.php':
    // esse caminho apontaria para /View/auth/login.php, que não existe.
    // Aqui montamos o caminho a partir da pasta real da página que está rodando.
    $dirPagina = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    header('Location: ' . $dirPagina . '/auth/login.php');
    exit;
}

$usuario = $_SESSION['usuario'];

$nome      = $usuario['nome'];
$email     = $usuario['email'];
$id        = (int) $usuario['id'];
$ehCliente = $usuario['tipo'] === 'cliente';

// Primeiro nome para saudações curtas
$primeiroNome = trim(explode(' ', $nome)[0]);

// Traz a linha completa do banco para pegar telefone, foto e o resto
$linha = buscarUsuario($pdo, $id, $usuario['tipo']) ?? [];

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

// Avatar: usa a foto do banco se existir, senão cai na imagem padrão
$foto   = $linha['foto'] ?? null;
$avatar = $foto ? $foto : '../img/foto_usuario.png';

// Data de nascimento vinda do banco; mostra só o ano na linha de meta
$nascimento = $linha['data_nascimento'] ?? null;