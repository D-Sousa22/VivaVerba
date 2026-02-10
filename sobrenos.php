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
  <link rel="stylesheet" href="Css/sobrenos.css">
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
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="#" class="botao-entrar" id="btnSair">
        <img src="Img/icone-entrar.png" alt="Ícone" />
        <span>Sair</span>
      </a>
    <?php else: ?>
      <a href="login.php" class="botao-entrar">
        <img src="Img/icone-entrar.png" alt="Ícone" />
        <span>Entrar</span>
      </a>
    <?php endif; ?>

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
    <a href="https://youtube.com" target="_blank" class="youtube"><i class="fab fa-youtube"></i></a>
    <a href="https://instagram.com" target="_blank" class="instagram"><i class="fab fa-instagram"></i></a>
    <a href="https://facebook.com" target="_blank" class="facebook"><i class="fab fa-facebook"></i></a>
  </div>
</div>

<!-- Conteúdo da página -->
<section class="intro">
  <h1>Quem somos nós?</h1>
  <p>Nascemos do sonho de transformar o tempo de tela em uma ferramenta poderosa para o desenvolvimento infantil. Acreditamos que a educação pode e deve ser uma aventura emocionante.</p>
  <img src="Img/trem.png" alt="Trem colorido" class="trem">
</section>

<section class="missao-visao-valores">
  <div class="card">
    <img src="Img/alvo.png" alt="Ícone de missão">
    <h3>Missão</h3>
    <p>Oferecer recursos educativos que tornem o aprendizado divertido e acessível para todas as crianças.</p>
  </div>
  <div class="card">
    <img src="Img/lampada.png" alt="Ícone de visão">
    <h3>Visão</h3>
    <p>Ser referência em educação digital infantil, unindo tecnologia e pedagogia de forma inovadora.</p>
  </div>
  <div class="card">
    <img src="Img/estrela-icon.png" alt="Ícone de valores">
    <h3>Valores</h3>
    <p>Educação, Criatividade e Diversão em cada atividade, tornando o aprendizado leve e inspirador.</p>
  </div>
</section>

<section class="historia">
  <img src="Img/menina-foguete.png" alt="Menina com foguete" class="menina">
  <div class="texto">
    <h2>Como tudo começou</h2>
    <img src="Img/icone-ideia.png" alt="Ícone de Ideia Brilhante" class="icone-ideia">
    <p>O VivaVerba nasceu em fevereiro de 2025, durante a elaboração de um TCC escolar. A ideia surgiu quando uma das integrantes da equipe notou que seu irmão tinha dificuldade na fala e quis criar algo que pudesse ajudar crianças em situações semelhantes. Assim, surgiu o VivaVerba: um site pensado para apoiar crianças com desafios na aprendizagem, oferecendo ferramentas e atividades que tornam o aprendizado mais acessível, divertido e motivador.</p>
  </div>
</section>

<section class="equipe">
  <h2>Conheça a Nossa Equipe</h2>
  <div class="membros">
    <div class="membro">
      <img style="border-color: #f2407a; background-color: #f2407a" src="Img/ana.png" alt="Ana Beatriz da Silva">
      <h4>Ana Beatriz da Silva</h4>
      <p>Front-end</p>
    </div>
    <div class="membro">
      <img style="border-color: #7819ce;  background-color: #7819ce;" src="Img/brenda.png" alt="Brenda Lira">
      <h4>Brenda Lira</h4>
      <p>Design e Front-end</p>
    </div>
    <div class="membro">
      <img style="border-color: #fdc727; background-color: #fdc727"  src="Img/lucas.png" alt="Lucas Souza">
      <h4>Lucas de Sousa</h4>
      <p>Back-end</p>
    </div>
    <div class="membro">
      <img style="border-color: #008ee8; background-color: #008ee8" src="Img/pedro.png" alt="Pedro Brandão">
      <h4>Pedro Brandão</h4>
      <p>Back-end</p>
    </div>
    <div class="membro">
      <img style="border-color: #02bea7; background-color: #02bea7" src="Img/victoria.png" alt="Victória Régia">
      <h4>Victória Régia</h4>
      <p>Documentação</p>
    </div>
  </div>
</section>

<!-- Rodapé -->
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

<!-- Modal de saída -->
<div class="modal-sair-overlay" id="modalSairOverlay">
  <div class="modal-sair">
    <h3>Confirmação</h3>
    <p>Você deseja realmente sair?</p>
    <div class="botoes-sair">
      <button class="btn-sair" id="confirmarSair">Sim</button>
      <button class="btn-cancelar" id="cancelarSair">Não</button>
    </div>
  </div>
</div>

<script>
  // Menu flutuante
  const hamburguer = document.querySelector('.hamburguer');
  const menuFlutuante = document.getElementById('menuFlutuante');
  const fecharMenu = document.getElementById('fecharMenu');
  const overlay = document.getElementById('overlay');

  hamburguer.addEventListener('click', () => {
    menuFlutuante.style.display = 'flex';
    overlay.style.display = 'block';
  });
  fecharMenu.addEventListener('click', () => {
    menuFlutuante.style.display = 'none';
    overlay.style.display = 'none';
  });
  overlay.addEventListener('click', () => {
    menuFlutuante.style.display = 'none';
    overlay.style.display = 'none';
  });

  // Modal de saída
  const btnSair = document.getElementById('btnSair');
  const modalSairOverlay = document.getElementById('modalSairOverlay');
  const confirmarSair = document.getElementById('confirmarSair');
  const cancelarSair = document.getElementById('cancelarSair');

  btnSair?.addEventListener('click', (e) => {
    e.preventDefault();
    modalSairOverlay.style.display = 'flex';
  });

  confirmarSair.addEventListener('click', () => {
    window.location.href = 'logout.php';
  });

  cancelarSair.addEventListener('click', () => {
    modalSairOverlay.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target == modalSairOverlay) {
      modalSairOverlay.style.display = 'none';
    }
  });
</script>

</body>
</html>
