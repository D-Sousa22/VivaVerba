<?php
session_start();
include('Api/vlibras.html');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Senha</title>
  <link rel="stylesheet" href="Css/recuperar.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    /* ===== MODAL ===== */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 20000;
    }

    .modal {
      background: #fff;
      padding: 30px 40px;
      width: 350px;
      border-radius: 15px;
      text-align: center;
      font-family: 'Poppins', sans-serif;
      animation: scaleShow 0.3s ease;
    }

    @keyframes scaleShow {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .modal h3 {
      font-size: 22px;
      margin-bottom: 10px;
      color: #333;
    }

    .modal p {
      margin-bottom: 20px;
      font-size: 16px;
      color: #444;
    }

    .modal button {
      background: #791dcf;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
    }
  </style>

</head>
<body>

<!-- MODAL DE CONFIRMAÇÃO -->
<div class="modal-overlay" id="modalEmail">
  <div class="modal">
    <h3>Verifique seu e-mail</h3>
    <p>Enviamos um link para redefinir sua senha.</p>
    <button id="btnOk">OK</button>
  </div>
</div>

<!-- Botão Voltar -->
<a href="index.php" class="botao-voltar" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i></a>

<header class="cabecalho">
  <div class="logo">
      <a href="index.php"><img src="Img/logo.png" alt="Logo VivaVerba"></a>
  </div>
</header>

<div class="botao-container">
  <a href="login.php" class="botao-entrar">Entrar</a>
  <a href="cadastro.php" class="botao-criar-conta">Criar conta</a>
</div>

<div class="container">

  <div class="recuperar-area">
    <h2>Recuperar Senha</h2>
    <p class="descricao">Digite seu e-mail cadastrado para receber o link de redefinição de senha.</p>

    <!-- FORMULÁRIO SEM REDIRECIONAMENTO -->
    <form id="formRecuperar" method="POST">
      <input type="email" name="email" id="email" placeholder="Digite seu e-mail" required>
      <button type="submit">Enviar Link</button>
    </form>

    <div class="texto-abaixo">
      <a href="login.php">Voltar para o login</a>
    </div>
  </div>

</div>

<footer class="rodape">
  <div class="logo-rodape">
    <img src="imagens/logo2.png" alt="Logo VivaVerba">
  </div>
  <div class="links-rodape">
    <a href="politicas-privacidade.php">Política de Privacidade</a>
    <a href="termos.php">Termos de Uso</a>
    <a href="politicas-cookies.php">Política de Cookies</a>
    <a href="contato.php">Contato</a>
  </div>
  <div class="redes-rodape">
    <a>Nos siga nas redes</a>
    <a href="#"><img src="imagens/youtube.png" alt="YouTube"> Youtube</a>
    <a href="#"><img src="imagens/instagram.png" alt="Instagram"> Instagram </a>
    <a href="#"><img src="imagens/facebook.png" alt="Facebook" style="width: 11px; margin-left: 3px;"> Facebook</a>
  </div>
</footer>

<script>
  const form = document.getElementById("formRecuperar");
  const modal = document.getElementById("modalEmail");
  const btnOk = document.getElementById("btnOk");

  form.addEventListener("submit", function(e) {
    e.preventDefault(); // evita sair da página

    // Envia via AJAX para o enviar_redefinicao.php
    const email = document.getElementById("email").value;

    fetch("enviar_redefinicao.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "email=" + encodeURIComponent(email)
    })
    .then(response => response.text())
    .then(() => {
      // exibe modal após enviar o email
      modal.style.display = "flex";
    })
    .catch(err => {
      console.log("Erro:", err);
    });
  });

  btnOk.addEventListener("click", () => {
    window.location.href = "login.php";
  });
</script>

</body>
</html>
