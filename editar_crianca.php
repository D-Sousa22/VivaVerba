
<?php
session_start();
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: parental.php');
    exit;
}

$crianca_id = isset($_POST['crianca_id']) ? (int)$_POST['crianca_id'] : 0;
$nome = trim($_POST['nome'] ?? '');
$idade = isset($_POST['idade']) ? (int)$_POST['idade'] : null;
$avatar = trim($_POST['avatar'] ?? '');

if ($crianca_id <= 0 || $nome === '' || $idade === null) {
    header('Location: parental.php?error=invalid_input');
    exit;
}

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
    $stmt = $conn->prepare("SELECT * FROM criancas WHERE id = :id AND id_responsavel = :resp LIMIT 1");
    $stmt->execute([':id' => $crianca_id, ':resp' => $_SESSION['user_id']]);
    $row = $stmt->fetch();

    if (!$row) {
        header('Location: parental.php?error=not_found');
        exit;
    }

    // Se avatar vazio mantém o atual
    if ($avatar === '') {
        $avatar = $row['avatar'] ?? null;
    }

    $update = $conn->prepare("UPDATE criancas SET nome = :nome, idade = :idade, avatar = :avatar WHERE id = :id AND id_responsavel = :resp");
    $update->execute([
        ':nome' => $nome,
        ':idade' => $idade,
        ':avatar' => $avatar,
        ':id' => $crianca_id,
        ':resp' => $_SESSION['user_id']
    ]);

    header('Location: parental.php?ok=edited');
    exit;

} catch (PDOException $e) {
    error_log("editar_crianca error: " . $e->getMessage());
    header('Location: parental.php?error=db_error');
    exit;
}
?>