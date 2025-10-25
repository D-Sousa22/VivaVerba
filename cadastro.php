<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro</title>
  <link rel="stylesheet" href="css/cadastro.css" />
  <!-- Importando ícones do Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="botao-container">
    <a href="login.html" class="botao-entrar">
      <img src="imagens/icone-entrar.png" alt="Ícone" />
      <span>Entrar</span>
    </a>
  </div>

  <div class="container">
    <!-- Imagem à esquerda -->
    <div class="imagem-area">
      <img src="imagens/luiz felipe e julia.png" alt="Imagem ilustrativa">
    </div>

    <!-- Login à direita -->
    <div class="login-area">
      <h2>Seja bem vindo(a) ao <br>Viva Verba!</h2>
        <input type="text" placeholder="Nome do Responsável">
      <input type="email" placeholder="Seu e-mail">

      <div class="senha-container">
        <input type="password" id="senha" placeholder="Sua senha">
        <i class="fa-solid fa-eye-slash toggle-senha" id="toggleSenha"></i>
      </div>
       <div class="senha-container">
        <input type="password" id="senha" placeholder="Confirmar senha">
        <i class="fa-solid fa-eye-slash toggle-senha" id="toggleSenha"></i>
      </div>

      <button>Entrar</button>
      <p class="texto-abaixo"> Ao entrar no VivaVerba você concorda com os nossos <a href="#">Termos</a> 
        e <a href="#">Política de privacidade</a></p>
    </div>
  </div>



  <script>
    const senhaInput = document.getElementById("senha");
    const toggle = document.getElementById("toggleSenha");

    toggle.addEventListener("click", () => {
      if (senhaInput.type === "password") {
        senhaInput.type = "text"; // mostra senha
        toggle.classList.remove("fa-eye-slash");
        toggle.classList.add("fa-eye"); // olho aberto
      } else {
        senhaInput.type = "password"; // esconde senha
        toggle.classList.remove("fa-eye");
        toggle.classList.add("fa-eye-slash"); // olho fechado
      }
    });
  </script>
  
 <!-- Rodapé -->
  <footer class="rodape">
    <div class="logo-rodape">
      <img src="imagens/logo2.png" alt="Logo VivaVerba">
    </div>
    <div class="links-rodape">
      <a href="#">Política de Privacidade</a>
      <a href="#">Termos de Uso</a>
      <a href="#">Política de Cookies</a>
      <a href="#">Contato</a>
    </div>
    <div class="redes-rodape">
      <a>Nos siga nas redes</a>
      <a href="#"><img src="imagens/youtube.png" alt="YouTube"> Youtube</a>
      <a href="#"><img src="imagens/instagram.png" alt="Instagram"> Instagram </a>
      <a href="#"><img src="imagens/facebook.png" alt="Facebook" style="width: 11px; margin-left: 3px; "> Facebook</a>
    </div>
  </footer>

</body>
</html>
