<?php
session_start();
include('Api/vlibras.html');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <script src="https://cdn.userway.org/widget.js" data-account="5Oy3ihG84d"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VivaVerba</title>
  <link rel="stylesheet" href="Css/politicas-cookies.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body>
  <!-- Cabeçalho -->
  <header class="cabecalho">
    <div class="logo">
      <a href="index.php"><img src="Img/logo.png" alt="Logo VivaVerba"></a>
    </div>
    <div class="menu">
     <a href="login.html" class="botao-entrar">
      <img src="Img/icone-entrar.png" alt="Ícone" />
      <span>Entrar</span>
    </a>
     
  <div class="menu">
    <!-- Botão Hambúrguer -->
    <div class="hamburguer">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  </header>

<!-- Overlay escuro -->
<div class="overlay" id="overlay"></div>

<!-- Menu Flutuante -->
<div class="menu-flutuante" id="menuFlutuante">
  <button class="fechar" id="fecharMenu">
  <span class="material-icons-round">close</span>
</button>
  <ul>
     <li><a href="index.php">Início</a></li>
    <li><a href="parental.php">Área Parental</a></li>
    <li><a href="sobrenos.php">Sobre nós</a></li>
    <li><a href="contato.php">Contato</a></li>
  </ul>
<div class="redes-sociais">
      <a href="https://youtube.com" target="_blank" class="youtube">
      <i class="fab fa-youtube"></i>
    </a>
    <a href="https://instagram.com" target="_blank" class="instagram">
      <i class="fab fa-instagram"></i>
    </a>
    <a href="https://facebook.com" target="_blank" class="facebook">
      <i class="fab fa-facebook"></i>
    </a>
  </div>

</div>

</div>

<section class="termos-intro">
    <div class="titulo">Política de Cookies</div>
    <div class="data">19 de Outubro, 2025</div>

    <!-- Box resumida / introdução -->
    <div class="box">
        <p>O Viva Verba utiliza cookies para melhorar a experiência do usuário, tornar o site mais seguro e personalizar o conteúdo apresentado.</p>
    </div>

    <!-- Box detalhada / completa -->
    <div class="box" id="cookiesDetalhes">
        <h2>1. O que são cookies?</h2>
        <p>Cookies são pequenos arquivos de texto armazenados no dispositivo do usuário ao acessar um site. Eles ajudam a lembrar preferências e informações durante a navegação.</p>

        <h2>2. Finalidade dos cookies</h2>
        <ul>
            <li>Manter o login do usuário ativo durante a navegação;</li>
            <li>Personalizar a experiência com base nas preferências do usuário;</li>
            <li>Auxiliar na análise de desempenho do site e melhorar funcionalidades.</li>
        </ul>

        <h2>3. Tipos de cookies utilizados</h2>
        <ul>
            <li><strong>Cookies essenciais:</strong> necessários para o funcionamento do site, como autenticação e segurança.</li>
            <li><strong>Cookies de personalização:</strong> lembram preferências do usuário, como idioma e configurações da conta.</li>
            <li><strong>Cookies de análise:</strong> coletam dados anônimos para melhorar a experiência e o desempenho do site.</li>
        </ul>

        <h2>4. Controle de cookies</h2>
        <p>O usuário pode optar por desativar os cookies nas configurações do navegador. No entanto, isso pode afetar a funcionalidade de algumas partes do site.</p>

        <h2>5. Compartilhamento de dados</h2>
        <p>Os cookies não são compartilhados com terceiros. As informações coletadas são utilizadas apenas para os fins descritos nesta política.</p>

        <h2>6. Alterações nesta política</h2>
        <p>Esta Política de Cookies pode ser atualizada sempre que necessário. Recomendamos que os usuários revisem esta página periodicamente.</p>

        <h2>7. Contato</h2>
        <p>Para dúvidas ou solicitações relacionadas a cookies, entre em contato pelo e-mail: <a href="mailto:vivaverbatcc@gmail.com">vivaverbatcc@gmail.com</a></p>
    </div>
</section>
  <footer class="rodape">
    <div class="logo-rodape">
      <img src="Img/logo2.png" alt="Logo VivaVerba">
    </div>
    <div class="links-rodape">
      <a href="politicas-privacidade.php">Política de Privacidade</a>
      <a href="termos.php">Termos de Uso</a>
      <a href="politicas-cookies.php">Política de Cookies</a>
      <a href="contato.php">Contato</a>
    </div>
    <div class="redes-rodape">
      <a>Nos siga nas redes</a>
      <a href="https://youtube.com/@vivaverba?si=hlnmHId6ckD-271o"><img src="Img/youtube.png" alt="YouTube"> Youtube</a>
      <a href="https://www.instagram.com/viva_verba.tcc?igsh=b2hpNHB3Mm56eGdl"><img src="Img/instagram.png" alt="Instagram"> Instagram </a>
    </div>
  </footer>
<script>
  const hamburguer = document.querySelector('.hamburguer');
  const menuFlutuante = document.getElementById('menuFlutuante');
  const fecharMenu = document.getElementById('fecharMenu');
  const overlay = document.getElementById('overlay');

  function abrirMenu() {
    menuFlutuante.style.display = 'flex';
    overlay.style.display = 'block';
  }

  function fecharMenuFunc() {
    menuFlutuante.style.display = 'none';
    overlay.style.display = 'none';
  }

  hamburguer.addEventListener('click', abrirMenu);
  fecharMenu.addEventListener('click', fecharMenuFunc);
  overlay.addEventListener('click', fecharMenuFunc);
</script>
</body>
</html>