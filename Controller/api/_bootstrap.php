<?php
// ============================================================================
// BOOTSTRAP DA API
// ----------------------------------------------------------------------------
// Este arquivo não é um endpoint: ele nunca devolve nada para o navegador.
//
// Todo endpoint da API começa com a MESMA linha:
//
//     require __DIR__ . '/_bootstrap.php';
//
// E é só isso. Um `require` executa o arquivo inteiro na hora, como se as
// linhas dele tivessem sido digitadas dentro do endpoint. Isso se chama
// "include em tempo de execução" e funciona assim no PHP: ele não copia o
// arquivo para dentro do outro, ele simplesmente executa as linhas dele
// dentro do mesmo escopo, como se fossem do endpoint.
//
// Por que existe? Porque sessão, conexão e formatação de resposta precisam ser
// IGUAIS em todos os endpoints. Se cada um configurasse por conta própria, um
// dia alguém esqueceria o `httponly` no login, ou o `Cache-Control` na sessão,
// e essa falha só apareceria num endpoint específico.
//
// Concentrando num lugar só, corrigir a sessão uma vez corrige em todos.
// ============================================================================


// ----------------------------------------------------------------------------
// BLOCO 1 — SESSÃO
// ----------------------------------------------------------------------------
// A sessão é o lugar onde o PHP guarda "quem está logado" entre uma requisição
// e outra. É um arquivo no servidor, e o navegador só carrega um código dele
// (o cookie de sessão). Como o PHP é "sem memória" — cada requisição roda um
// script novo do zero — é por isso que o "estar logado" precisa ser salvo
// em disco, e não numa variável normal.
// ----------------------------------------------------------------------------

// Se a sessão ainda não foi iniciada, inicia. O if evita erro do tipo
// "headers already sent" ou "session already active" caso o arquivo seja
// carregado duas vezes na mesma requisição.
if (session_status() === PHP_SESSION_NONE){
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    $httpsAtivo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    // Define as REGRAS do cookie de sessão antes de criá-lo.
    // Precisa ser antes de session_start(): depois de iniciado, já é tarde.
    session_set_cookie_params([

        // lifetime 0 = o cookie MORRE quando o navegador fecha.
        // A pessoa loga de novo ao fechar a aba. Mais seguro que lembrar
        // por semanas. Se um dia quiser "ficar logado", muda para 30*24*60*60.
        'lifetime' => 0,

        // path '/' = o cookie vale para o site INTEIRO, em qualquer pasta.
        // Se ficasse '/Controller/api', o cookie só iria para a API e não
        // chegaria nas páginas HTML — e o usuário pareceria deslogado nelas.
        'path' => '/',

        // httponly true = JavaScript NÃO consegue ler esse cookie.
        // Se um dia existir XSS na página, o ladrão de sessão não consegue
        // ler o valor por script. É a defesa mais importante desta lista.
        'httponly' => true,

        // samesite Lax = o cookie não é enviado se a requisição vier de
        // outro site. Sem isso, um site malicioso poderia incluir um formulário
        // apontando para o seu e o navegador mandaria o cookie junto,
        // executando ações como logged-in sem a pessoa ter clicado em nada.
        'samesite' => 'Lax',

        // secure = o cookie só viaja por HTTPS. Também considera o protocolo
        // informado pelo proxy reverso quando a aplicação está atrás de um.
        'secure' => $httpsAtivo,
    ]);

    // Abre a sessão de verdade: cria/recupera o arquivo e liga o cookie.
    // DEPOIS deste ponto é que $_SESSION existe e pode ser lido e escrito.
    session_start();
}


// ----------------------------------------------------------------------------
// BLOCO 2 — CAMINHOS E DEPENDÊNCIAS
// ----------------------------------------------------------------------------
// Carregar os arquivos na ordem errada dá erro. A ordem importa:
//
//   1. Model/conexao.php   → cria $pdo (a conexão com o banco)
//   2. Model/usuario.php  → cria configTipo() e cadastrar(), que dependem do $pdo
//
// Por isso que a conexão vem primeiro.
// ----------------------------------------------------------------------------

// __DIR__ é o caminho da pasta onde ESTE arquivo está, ou seja
// ".../lumis/Controller/api".
//
// dirname($caminho, 1) sobe um nível  → ".../lumis/Controller"
// dirname($caminho, 2) sobe dois níveis → ".../lumis"  (a raiz do projeto)
//
// Usar caminho relativo como require '../Model/...' funciona, mas depende de
// onde o PHP executou. __DIR__ é o caminho absoluto do arquivo e funciona de
// qualquer lugar. Por isso não usamos caminho adivinhado.
$raiz = dirname(__DIR__, 2);

// require_once e não require: se a conexão for pedida duas vezes, o Once
// ignora a segunda e não redeclara as funções, evitando erro fatal.
require_once $raiz . '/Model/conexao.php';   // define $pdo
require_once $raiz . '/Model/usuario.php';  // define configTipo() e cadastrar()


// ----------------------------------------------------------------------------
// BLOCO 3 — CABEÇALHOS HTTP
// ----------------------------------------------------------------------------
// O cabeçalho é o "envelope" da resposta. Sayão o que vem antes do conteúdo.
// É o mesmo cabeçalho que o navegador envia a cada página que você visita.
// ----------------------------------------------------------------------------

// Sem isso, o navegador (e o proxy entre você e ele) pode guardar a resposta
// em cache. Então, você loga, aperta F5, e a página devolve o nome do usuário
// ANTERIOR. É o motivo de esta API responder "não-store": resposta com dado
// de sessão não pode ser guardada em lugar nenhum.
header('Cache-Control: no-store, no-cache, must-revalidate');

// O Pragma é o antepassado antigo do Cache-Control, usado por navegadores
// dos anos 90. Hoje em dia quase ninguém usa, mas não tem mal: em troca de
// duas linhas, cobre qualquer leitor em desuso.
header('Pragma: no-cache');


// ----------------------------------------------------------------------------
// BLOCO 4 — HELPERS (funções que os endpoints chamam)
// ----------------------------------------------------------------------------
// Um helper é só uma função que resolve um trabalho repetido. Separar isso
// aqui evita que cada endpoint precise reescrever a mesma formatação de JSON,
// headers e códigos de status.
// ----------------------------------------------------------------------------


// ----------------------------------------------------------------------------
// helper: responder()
// ----------------------------------------------------------------------------
// O QUE FAZ: envia uma resposta JSON e ENCERRA O SCRIPT.
//
// POR QUE EXISTE: todo endpoint precisa responder algo no final. Se um endpoint
// esquecer de responder, o servidor devolve uma página em branco com status
// 200, e o JavaScript receberia HTML vazio em vez de JSON — quebrando sem
// mensagem de erro. Este helper centraliza o envio e garante que acabou.
//
// $status é o código HTTP que o navegador e o JavaScript vão ler. Ele separa
// "a requisição funcionou mas o conteúdo é um erro" de "a requisição quebrou":
//
//   200 = ok
//   400 = erro NOS DADOS que o usuário enviou (email inválido, senha curta)
//   500 = erro NO SERVIDOR (banco caiu, bug no código)
//
// O JavaScript olha esse código para decidir se foi erro de formulário ou
// erro do sistema, e mostra a mensagem certa.
// ----------------------------------------------------------------------------
function responder(array $json, int $status = 200){
    http_response_code($status);  // define o status HTTP
    header('Content-Type: application/json; charset=utf-8');  // diz que é JSON
    echo json_encode($json, JSON_UNESCAPED_UNICODE);  // serializa para texto
    // JSON_UNESCAPED_UNICODE faz os acentos saírem como "é" em vez de "\u00e9".
    // Continua sendo JSON válido, só que legível.
    exit;  // ENCERRA O SCRIPT AQUI. Nada abaixo deste ponto roda.
    // O exit é de propósito: se continuasse, o script tentaria enviar uma
    // segunda resposta e o PHP reclamaria "headers already sent".
}


// ----------------------------------------------------------------------------
// helper: responderErro()
// ----------------------------------------------------------------------------
// O QUE FAZ: atalho para "responder com formato de erro".
//
// POR QUE EXISTE: os dois endpoints de escrita (cadastro e login) terminam
// quase igual: algo deu errado, responde com o código do erro. Em vez de
// repetir ['ok' => false, 'erro' => ...] em cada if, quem chama só diz qual
// é o código.
//
// $campos é o detalhe: 'email-em-uso' além do código geral diz ao JavaScript
// qual inputrecebe a borda vermelha, para o erro aparecer no campo certo e não
// só no topo da página.
//
// ATENÇÃO AO VALOR DE $codigo: NÃO é frase pronta. É um código como
// 'email-em-uso'. Quem escreve a frase é o JavaScript, via o dicionário em
// tema-idioma.js. É assim que a mesma API responde em português, inglês ou
// espanhol sem o PHP saber a diferença.
// ----------------------------------------------------------------------------
function responderErro(string $codigo, array $campos = [], int $status = 400){
    responder([
        'ok'     => false,       // o JavaScript olha este campo primeiro
        'erro'   => $codigo,     // ex: 'email-em-uso' — a chave que o JS traduz
        'campos' => $campos,     // ex: ['email' => 'email-em-uso'] — marca os inputs
    ], $status);
}


// ----------------------------------------------------------------------------
// helper: responderServidor()
// ----------------------------------------------------------------------------
// O QUE FAZ: transforma QUALQUER erro inesperado em uma resposta limpa,
// escondendo o detalhe do usuário.
//
// POR QUE EXISTE: se a conexão com o banco cair, a exceção vai carregar texto
// como "SQLSTATE[HY000] [2002] Connection refused" — que na prática entrega ao
// visitante os detalhes da sua infraestrutura: nome do banco, host, usuário.
//
// A strategy é: guarda o detalhe no log do servidor (error_log), que só você
// tem acesso, e devolve só a palavra 'servidor' para o navegador.
//
// $e é Throwable e não Exception: o PHP tem duas famílias de erro, Exception
// (que você já conhece) e Error (TypeError, ValueError...). Se você escrever
// só Exception, um TypeError escapa e mostra stack trace na tela.
// ----------------------------------------------------------------------------
function responderServidor(Throwable $e){
    // error_log grava a data, hora e o erro num arquivo do servidor.
    // Você só descobre o problema real olhando o log.
    error_log('api: ' . $e->getMessage());

    // O navegador só recebe "servidor", sem detalhe do que aconteceu.
    responder(['ok' => false, 'erro' => 'servidor'], 500);
}

function exigirMetodo(string $metodo): void{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($metodo)){
        header('Allow: ' . strtoupper($metodo));
        responder(['ok' => false, 'erro' => 'metodo-invalido'], 405);
    }
}

function tokenCsrf(): string{
    if (empty($_SESSION['csrf_token'])){
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function exigirCsrf(): void{
    $recebido = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['_csrf'] ?? '');
    if (!is_string($recebido) || !hash_equals(tokenCsrf(), $recebido)){
        responderErro('sessao-expirada', [], 403);
    }
}

function encerrarSessao(): void{
    $_SESSION = [];
    if (ini_get('session.use_cookies')){
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 42000,
            'path'     => $p['path'],
            'domain'   => $p['domain'],
            'secure'   => $p['secure'],
            'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}

function manterSessaoAtiva(): bool{
    if (empty($_SESSION['usuario'])) return false;

    $agora = time();
    $iniciada = (int) ($_SESSION['auth_iniciada_em'] ?? $agora);
    $ultima = (int) ($_SESSION['auth_ultima_atividade'] ?? $agora);
    $limiteInativo = (int) (getenv('SESSION_IDLE_SECONDS') ?: 1800);
    $limiteAbsoluto = (int) (getenv('SESSION_MAX_SECONDS') ?: 28800);

    if (($agora - $ultima) > $limiteInativo || ($agora - $iniciada) > $limiteAbsoluto){
        $_SESSION = [];
        session_regenerate_id(true);
        tokenCsrf();
        return false;
    }

    $_SESSION['auth_ultima_atividade'] = $agora;
    return true;
}

function abrirSessaoUsuario(array $usuario, string $tipo): void{
    session_regenerate_id(true);
    $agora = time();
    $_SESSION['usuario'] = [
        'id'    => (int) $usuario['id'],
        'nome'  => $usuario['nome'],
        'email' => $usuario['email'],
        'tipo'  => $tipo,
        'auth_versao' => (int) ($usuario['auth_versao'] ?? 1),
    ];
    $_SESSION['auth_iniciada_em'] = $agora;
    $_SESSION['auth_ultima_atividade'] = $agora;
    tokenCsrf();
}


// ----------------------------------------------------------------------------
// helper: lerDados()
// ----------------------------------------------------------------------------
// O QUE FAZ: lê o que o navegador mandou no corpo da requisição.
//
// POR QUE EXISTE: existem dois jeitos de mandar dados para o PHP:
//
//   fetch com JSON  → o corpo é um texto JSON, precisa ser interpretado
//   <form> sem JS    → o corpo é urlencoded e o PHP já preenche o $_POST
//
// A aplicação usa os dois. O primeiro é o normal (o JavaScript está ligado),
// mas se o JavaScript falhar ou for bloqueado, a página ainda tem que funcionar.
//
// Ler só o $_POST quebraria o caminho do fetch, e ler só o JSON quebraria o
// caminho sem JavaScript. Esta função cobre os dois.
//
// O ?: '' no fim é o "ou", do jeito do JavaScript: se file_get_contents()
// devolver null (requisição sem corpo), usamos string vazia em vez de erro.
// ----------------------------------------------------------------------------
function lerDados(): array{
    // Lê o corpo bruto da requisição. O php://input é um "endereço virtual"
    // que entrega o que veio, sem precisar de arquivo no disco.
    $bruto = file_get_contents('php://input') ?: '';

    // Tenta entender esse texto como JSON.
    // O true no final é o "assoc": em vez de devolver listas [0], [1],
    // devolve pares nome => valor, que é o que queremos usar.
    $json = json_decode($bruto, true);

    // Se conseguiu um array, é JSON válido: usa ele.
    if (is_array($json)){
        return $json;
    }

    // Não era JSON. Então é requisição de formulário comum.
    // O ?: [] no fim evita que um POST sem campos devolva null.
    return $_POST ?: [];
}


// ----------------------------------------------------------------------------
// helper: tipoValido()
// ----------------------------------------------------------------------------
// O QUE FAZ: transforma o texto que veio do navegador na configuração do tipo,
// e recusa se o tipo não existir.
//
// POR QUE EXISTE: o navegador manda 'tipo' => 'cliente' ou 'profissional'.
// Esse texto NÃO pode virar nome de tabela direto, ou o site cairia em injeção
// de SQL (alguém mandaria tipo="Cliente; DROP TABLE Cliente").
//
// A solução é: o 'tipo' nunca é usado como SQL. Ele é usado como CHAVE DE
// BUSCA dentro do mapa de tiposSuportados(), que só tem duas entradas, ambas
// escritas à mão no arquivo. Se o valor não for uma dessas duas chaves, a
// busca não acha e devolve null.
//
// Este helper é a barreira: se devolve null, a resposta é erro e o endpoint
// PARA. Não existe caminho onde um valor vindo do usuário chegue no SQL.
//
// POR QUE responderErro e não responderErro com 400: se tipo está errado, é
// erro do sistema — ninguém legítimo manda tipo diferente de 'cliente' ou
// 'profissional'.
// ----------------------------------------------------------------------------
function tipoValido(?string $tipo): array{
    // Busca a configuração do tipo no mapa. Null se não existir.
    $cfg = configTipo((string) $tipo);

    // Se não achou, o valor não é 'cliente' nem 'profissional'.
    // Isso não deveria acontecer num fluxo normal — se aconteceu, é alguém
    // forjando a requisição. Responde erro e NÃO DEIXA O SCRIPT SEGUIR
    // (o responderErro chama exit por dentro).
    if ($cfg === null){
        responderErro('tipo-invalido', [], 400);
    }

    // a partir daqui $cfg tem forma de array com 'tabela', 'pk', 'comuns', etc.
    return $cfg;
}
