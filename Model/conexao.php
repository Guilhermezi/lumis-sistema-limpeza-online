<?php 
    // Conexão com o banco de dados
    $host = "localhost"; 
    // nome do banco de dados
    $dbname = "lumis";
    // nome do usuário do banco de dados
    $user = "root";
    // senha do usuário do banco de dados
    $password = "";

    // Tratamento de exceções para a conexão com o banco de dados
    try {
        // Criação da conexão PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", 
        $user, 
        $password
        );
    } catch (PDOException $e) {
        // Exibe a mensagem de erro caso a conexão falhe
        echo "Erro na conexão: " . $e->getMessage();
    }
?>