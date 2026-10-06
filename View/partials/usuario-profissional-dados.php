<?php
// Dados do profissional logado, vindos da sessão, e guarda do servidor.
//
// Mesma ideia do usuario-dados.php, mas exigindo perfil de PROFISSIONAL:
// se o visitante estiver logado como cliente, ele sai daqui, porque essa
// página não deve abrir para o perfil errado.

$raizPhp = dirname(__DIR__, 2);
require_once $raizPhp . '/Model/conexao.php';
require_once $raizPhp . '/Model/usuario.php';

// Caminho da página atual, para o redirect não depender de "../" chutado
$dirPagina = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$login     = $dirPagina . '/auth/login.php';

if (empty($_SESSION['usuario'])){
    header('Location: ' . $login);
    exit;
}

if ($_SESSION['usuario']['tipo'] !== 'profissional'){
    header('Location: ' . $login);
    exit;
}

$usuario = $_SESSION['usuario'];

$nome  = $usuario['nome'];
$email = $usuario['email'];
$id    = (int) $usuario['id'];

$primeiroNome = trim(explode(' ', $nome)[0]);

// Linha completa do banco
$linha = buscarUsuario($pdo, $id, 'profissional') ?? [];

// 11 dígitos = 2 (DDD) + 5 (prefixo) + 4 (final). O último bloco sempre
// tem 4, então o substr precisa de comprimento explícito; sem ele, substr($d,5)
// traz o resto todo e o número sai com dígito a mais.
function formatarTelefonePro(string $digitos): string{
    $d = preg_replace('/\D/', '', $digitos);

    if (strlen($d) === 11){
        return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 5), substr($d, 7, 4));
    }
    if (strlen($d) === 10){
        return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 4), substr($d, 6, 4));
    }
    return $digitos;
}

$telefoneBruto     = $linha['telefone'] ?? '';
$telefoneFormatado = $telefoneBruto !== '' ? formatarTelefonePro($telefoneBruto) : '—';

$foto   = $linha['foto'] ?? null;
$avatar = $foto ? $foto : '../img/João Neves.png';

// Campos que só o profissional tem, direto do banco
$regiao     = $linha['regiao_atuacao'] ?? '—';
$experiencia = $linha['experiencia'] ?? '—';
$valorMinimo = isset($linha['valor_minimo']) && $linha['valor_minimo'] !== null
    ? 'R$ ' . number_format((float) $linha['valor_minimo'], 2, ',', '.')
    : '—';

// verificado é tinyint no banco (0 ou 1); o HTML precisa de texto
$verificado = !empty($linha['verificado']);

$nascimento = $linha['data_nascimento'] ?? null;