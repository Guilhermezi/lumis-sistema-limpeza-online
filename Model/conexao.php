<?php 
    require_once __DIR__ . '/config.php';
    // Abre a sessão antes de qualquer outra coisa.
    // $_SESSION só existe DEPOIS do session_start(). Sem esta chamada, um
    // require deste arquivo em uma página comum (que não passa pelo
    // Controller/api/_bootstrap.php) deixaria $_SESSION indefinido, e toda
    // checagem de "está logado?" cairia para "não está".
    // As regras do cookie são as mesmas do _bootstrap.php; ao rodar em página,
    // um cookie já emitido precisa continuar válido, por isso só se configura
    // quando ainda não existe sessão.
    if (session_status() === PHP_SESSION_NONE){
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');

        $httpsAtivo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => $httpsAtivo,
        ]);
        session_start();
    }

    // Em produção, nunca recorrer às credenciais locais padrão: a aplicação
    // deve falhar fechada caso o ambiente do servidor esteja incompleto.
    $ambiente = configuracaoLumis('APP_ENV', 'production');
    $hostConfigurado = configuracaoLumis('DB_HOST');
    $nomeConfigurado = configuracaoLumis('DB_NAME');
    $usuarioConfigurado = configuracaoLumis('DB_USER');
    $senhaConfigurada = configuracaoLumis('DB_PASSWORD');
    $socket = configuracaoLumis('DB_SOCKET', '');

    if ($ambiente === 'production' && (
        !$hostConfigurado || !$nomeConfigurado || !$usuarioConfigurado || !$senhaConfigurada
    )) {
        error_log('lumis: configuração obrigatória do banco ausente em produção.');
        http_response_code(500);
        exit('Configuração do servidor incompleta.');
    }

    // Desenvolvimento mantém os padrões locais documentados no README.
    $host = $hostConfigurado ?: 'localhost';
    // nome do banco de dados
    $dbname = $nomeConfigurado ?: 'lumis';
    // nome do usuário do banco de dados
    $user = $usuarioConfigurado ?: 'root';
    // senha do usuário do banco de dados
    $password = $senhaConfigurada ?: '';

    // Opções de configuração do PDO
    $opcoes = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // lança exceção em vez de retornar false
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // linhas como array associativo
        PDO::ATTR_EMULATE_PREPARES   => false,                   // usa prepared statements nativas
    ];

    // Tratamento de exceções para a conexão com o banco de dados
    try {
        // Criação da conexão PDO
        // o charset utf8mb4 no DSN garante a gravação correta de textos acentuados
        $dsn = $socket !== ''
            ? "mysql:unix_socket=$socket;dbname=$dbname;charset=utf8mb4"
            : "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn,
        $user, 
        $password,
        $opcoes
        );
    } catch (PDOException $e) {
        // encerra o script sem expor ao usuário o motivo da falha
        http_response_code(500);
        exit;
    }
