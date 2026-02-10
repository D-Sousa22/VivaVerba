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
    $stmt = $conn->prepare("SELECT * FROM criancas WHERE id = :id AND id_responsavel = :resp LIMIT 1");
    $stmt->execute([':id' => $crianca_id, ':resp' => $_SESSION['user_id']]);
    $crianca = $stmt->fetch();

    if (!$crianca) {
        header('Location: parental.php?error=not_found');
        exit;
    }

    // Salva a criança selecionada na sessão
    $_SESSION['id_crianca'] = $crianca['id'];
    $_SESSION['nome_crianca'] = $crianca['nome'];
    $_SESSION['idade_crianca'] = $crianca['idade'];

    // Atualiza a sessão de jogo
    $stmtSessao = $conn->prepare("
        INSERT INTO sessao_jogo (id_responsavel, id_crianca) 
        VALUES (:resp, :crianca)
        ON DUPLICATE KEY UPDATE id_crianca = :crianca, ultima_atualizacao = CURRENT_TIMESTAMP
    ");
    $stmtSessao->execute([
        ':resp' => $_SESSION['user_id'],
        ':crianca' => $crianca_id
    ]);

    // Redireciona para a página apropriada baseada na idade
    $idade = (int)$crianca['idade'];
    if ($idade >= 5 && $idade <= 7) {
        header('Location: atv1.php');
    } elseif ($idade >= 8 && $idade <= 10) {
        header('Location: atv2.php');
    } else {
        header('Location: index.php');
    }
    exit;

} catch (PDOException $e) {
    error_log("Erro ao selecionar criança: " . $e->getMessage());
    header('Location: parental.php?error=db_error');
    exit;
}
?>