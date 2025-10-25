<?php
$servername = "localhost:3306";
$username = "root";
$password = "usbw";
$dbname = "vivaverba";

try {

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE cadastro (
                id INT PRIMARY KEY AUTO_INCREMENT,
                nome VARCHAR(50) NOT NULL,
                email VARCHAR(40) NOT NULL,
                senha VARCHAR(32) NOT NULL
            );"; 
    $conn->exec($sql);

    echo "Tabela Cadastro criada com sucesso!";
    
} catch(PDOException $e) {
    echo "Erro ao criar a tabela: " . $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>