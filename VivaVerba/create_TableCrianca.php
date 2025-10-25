<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vivaverba";

try {
    // Tenta conectar ao BD. Adicionei a porta 3306 para evitar problemas de conexão
    $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL CORRIGIDO:
    // 1. 'data-nascimento' foi alterado para 'data_nascimento' (snake_case).
    // 2. O tipo de dado foi alterado para DATE, que é o padrão para datas.
    $sql = "CREATE TABLE crianca (
                id INT PRIMARY KEY AUTO_INCREMENT,
                nome VARCHAR(50) NOT NULL,
                data_nascimento DATE NOT NULL
                score VARCHAR(5) NOT NULL
            );"; 
            
    $conn->exec($sql);

    echo "Tabela 'crianca' criada com sucesso!";
    
} catch(PDOException $e) {
    // Se a tabela já existir, a mensagem de erro será exibida.
    echo "Erro ao criar a tabela: " . $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>