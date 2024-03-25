<?php
// Configurações do banco de dados
$servername = "localhost";
$port = 7306;
$username = "root";
$password = "";
$dbname = "banco_de_dados";

try {
    // Criação da conexão PDO
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    // Configuração para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Em caso de erro na conexão, exibe uma mensagem de erro
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    // Encerra o script
    die();
}
?>
