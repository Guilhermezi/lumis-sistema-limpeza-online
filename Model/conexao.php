<?php 
    // Abre a sessão antes de qualquer outra coisa.
    // $_SESSION só existe DEPOIS do session_start(). Sem esta chamada, um
    // require deste arquivo em uma página comum (que não passa pelo
    // Controller/api/_bootstrap.php) deixaria $_SESSION indefinido, e toda
    // checagem de "está logado?" cairia para "não está".
    // As regras do cookie são as mesmas do _bootstrap.php; ao rodar em página,
    // um cookie já emitido precisa continuar válido, por isso só se configura
    // quando ainda não existe sessão.
    if (session_status() === PHP_SESSION_NONE){
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => !empty($_SERVER['HTTPS']),
        ]);
        session_start();
    }

    // Conexão com o banco de dados
    $host = "localhost"; 
    // nome do banco de dados
    $dbname = "lumis";
    // nome do usuário do banco de dados
    $user = "root";
    // senha do usuário do banco de dados
    $password = "";

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
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $user, 
        $password,
        $opcoes
        );
    } catch (PDOException $e) {
        // encerra o script sem expor ao usuário o motivo da falha
        http_response_code(500);
        exit;
    }
