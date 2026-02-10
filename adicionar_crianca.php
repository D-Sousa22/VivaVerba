<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: parental.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$idade = isset($_POST['idade']) ? (int)$_POST['idade'] : null;
$avatar = trim($_POST['avatar'] ?? '');

// Validação
if ($nome === '' || $idade === null || $idade < 4 || $idade > 12 || $avatar === '') {
    header('Location: parental.php?error=invalid_input');
    exit;
}

// Configuração do banco
$servername = "localhost";
$port = 3306;
$username = "u358404112_verbovivo";
$password = "VivaVerba2025";
$dbname = "u358404112_vivaverba";

try {
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Insere a nova criança
    $stmt = $conn->prepare("
        INSERT INTO criancas (id_responsavel, nome, idade, avatar, progresso, pontos_totais) 
        VALUES (:resp, :nome, :idade, :avatar, 0, 0)
    ");
    
    $stmt->execute([
        ':resp' => $_SESSION['user_id'],
        ':nome' => $nome,
        ':idade' => $idade,
        ':avatar' => $avatar
    ]);

    header('Location: parental.php?success=added');
    exit;

} catch (PDOException $e) {
    error_log("Erro ao adicionar criança: " . $e->getMessage());
    header('Location: parental.php?error=db_error');
    exit;
}
?>