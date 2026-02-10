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

$crianca_id = isset($_POST['crianca_id']) ? (int)$_POST['crianca_id'] : 0;

if ($crianca_id <= 0) {
    header('Location: parental.php?error=invalid_id');
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

    // Verifica se a criança pertence ao usuário logado
    $stmt = $conn->prepare("SELECT id FROM criancas WHERE id = :id AND id_responsavel = :resp LIMIT 1");
    $stmt->execute([':id' => $crianca_id, ':resp' => $_SESSION['user_id']]);
    $crianca = $stmt->fetch();

    if (!$crianca) {
        header('Location: parental.php?error=not_found');
        exit;
    }

    // Se a criança selecionada for a que está na sessão, remove da sessão
    if (isset($_SESSION['id_crianca']) && $_SESSION['id_crianca'] == $crianca_id) {
        unset($_SESSION['id_crianca']);
        unset($_SESSION['nome_crianca']);
        unset($_SESSION['idade_crianca']);
    }

    // Deleta a criança (CASCADE vai deletar registros relacionados)
    $deleteStmt = $conn->prepare("DELETE FROM criancas WHERE id = :id AND id_responsavel = :resp");
    $deleteStmt->execute([':id' => $crianca_id, ':resp' => $_SESSION['user_id']]);

    header('Location: parental.php?success=deleted');
    exit;

} catch (PDOException $e) {
    error_log("Erro ao deletar criança: " . $e->getMessage());
    header('Location: parental.php?error=db_error');
    exit;
}
?>