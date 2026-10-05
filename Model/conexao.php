<?php 
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
