
<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if (isset($_SESSION['prev_id_crianca'])) {
        $_SESSION['id_crianca'] = $_SESSION['prev_id_crianca'];
        unset($_SESSION['prev_id_crianca']);
    } else {
        // Se não havia anterior, apenas remove a seleção atual (volta ao que for padrão)
        unset($_SESSION['id_crianca']);
    }
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>