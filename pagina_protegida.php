<?php
session_start();
if (empty($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
// usuário autenticado — usar $_SESSION['user_name']
// ...existing code...
?>