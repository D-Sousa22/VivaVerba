<?php
session_start();
header('Content-Type: application/json');

// Verifica se usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

try {
    // Se for convidado
    if (isset($_POST['guest']) && $_POST['guest'] === 'true') {
        $_SESSION['crianca_jogando_id'] = null;
        $_SESSION['crianca_jogando_nome'] = 'Convidado';
        $_SESSION['is_guest'] = true;
        echo json_encode(['success' => true, 'guest' => true]);
        exit;
    }

    // Se for criança cadastrada
    if (!isset($_POST['child_id']) || !is_numeric($_POST['child_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID da criança inválido']);
        exit;
    }

    $childId = (int)$_POST['child_id'];

    // Conecta ao banco e verifica se a criança pertence ao responsável
    $dsn = "mysql:host=localhost;port=3306;dbname=vivaverba;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $stmt = $pdo->prepare("SELECT id, nome, idade, avatar FROM criancas WHERE id = :id AND id_responsavel = :id_responsavel");
    $stmt->execute([
        ':id' => $childId,
        ':id_responsavel' => $_SESSION['user_id']
    ]);

    $crianca = $stmt->fetch();

    if (!$crianca) {
        echo json_encode(['success' => false, 'message' => 'Criança não encontrada']);
        exit;
    }

    // Salva na sessão
    $_SESSION['crianca_jogando_id'] = $crianca['id'];
    $_SESSION['crianca_jogando_nome'] = $crianca['nome'];
    $_SESSION['crianca_jogando_idade'] = $crianca['idade'];
    $_SESSION['crianca_jogando_avatar'] = $crianca['avatar'];
    $_SESSION['is_guest'] = false;

    echo json_encode([
        'success' => true,
        'crianca' => [
            'id' => $crianca['id'],
            'nome' => $crianca['nome']
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados']);
}
?>