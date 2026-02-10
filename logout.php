<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Remove todas as variáveis de sessão e destrói sessão
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
header('Location: index.php');
exit;
?>