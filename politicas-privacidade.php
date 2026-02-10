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
  <link rel="stylesheet" href="Css/politicas-privacidade.css">
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
     <a href="login.php" class="botao-entrar">
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
    <div class="titulo">Política de Privacidade</div>
    <div class="data">19 de Outubro, 2025 </div>

    <!-- Box resumida / introdução -->
    <div class="box">
        <p>O Viva Verba valoriza a privacidade de seus usuários e se compromete a proteger os dados coletados de crianças e responsáveis, de acordo com a legislação brasileira vigente (LGPD).</p>
    </div>

    <!-- Box detalhada / completa -->
    <div class="box" id="privacidadeDetalhes">
        <h2>1. Dados coletados</h2>
        <p>Para criar uma conta no site, coletamos apenas informações básicas necessárias para o cadastro:</p>
        <ul>
            <li>Nome</li>
            <li>E-mail</li>
            <li>Senha</li>
        </ul>

        <h2>2. Finalidade da coleta</h2>
        <p>Os dados coletados são utilizados exclusivamente para:</p>
        <ul>
            <li>Permitir o acesso à conta do usuário;</li>
            <li>Personalizar a experiência no site, oferecendo atividades adequadas;</li>
            <li>Manter o funcionamento seguro e eficiente da plataforma.</li>
        </ul>

        <h2>3. Compartilhamento de dados</h2>
        <p>Os dados dos usuários <strong>não são compartilhados com terceiros</strong>. Nenhum parceiro, anunciante ou plataforma externa tem acesso às informações de cadastro.</p>

        <h2>4. Armazenamento e segurança</h2>
        <p>Todos os dados são armazenados em servidores localizados no Brasil. Embora não haja medidas de segurança específicas mencionadas, o site se compromete a proteger as informações de acessos indevidos.</p>

        <h2>5. Direitos dos usuários</h2>
        <p>Os usuários podem, a qualquer momento:</p>
        <ul>
            <li>Visualizar os dados cadastrados em sua conta;</li>
            <li>Solicitar a exclusão completa da conta e de seus dados.</li>
        </ul>

        <h2>6. Contato</h2>
        <p>Para dúvidas, solicitações de acesso ou exclusão de dados, entre em contato pelo e-mail: <a href="mailto:vivaverbatcc@gmail.com">vivaverbatcc@gmail.com</a></p>

        <h2>7. Alterações nesta política</h2>
        <p>Esta Política de Privacidade pode ser atualizada sempre que necessário. Recomendamos que os usuários revisem esta página periodicamente.</p>
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