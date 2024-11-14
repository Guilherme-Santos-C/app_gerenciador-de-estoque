<?php

$host = 'localhost'; // reconhecer o endereço do servidor
$dbname = 'estoque'; // nome do servidor dado no workbench
$username = 'root'; // nome padrão
$password = ''; // pode ser vazia no XAMPP ou WAMP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username , $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    

}catch (PDOException $e) {
    // tratando erro de conexão
    echo 'Erro de conexão: ' . $e->getMessage();
    exit;
}
?>