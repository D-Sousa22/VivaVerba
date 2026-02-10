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
  <link rel="stylesheet" href="Css/contato.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

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

  <main class="contato-container">
    <h2>Contato</h2>
    <form id="formContato" method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="text" name="assunto" placeholder="Assunto" required>
        <input type="hidden" name="acao" value="enviar">
        <textarea name="mensagem" placeholder="Mensagem" required></textarea>
        
        <button type="submit" id="btnEnviar">Enviar</button>
    </form>
  </main>
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
  
  <div class="modal-sucesso" id="modalSucesso">
  <div class="modal-sucesso-content">
    <h3>Mensagem enviada com sucesso!</h3>
    <p style="margin-bottom: 15px;">Agradecemos o seu contato. Responderemos em breve.</p>
    <a href="#" class="btn-sucesso" onclick="fecharModal('modalSucesso')">Fechar</a>
  </div>
</div>

<div class="modal-erro" id="modalErro">
  <div class="modal-erro-content">
    <h3>Ops! Algo deu errado.</h3>
    <p id="msgErroTexto" style="margin-bottom: 15px;">Houve uma falha no envio.</p>
    <a href="#" class="btn-fechar-erro" onclick="fecharModal('modalErro')">Tentar Novamente</a>
  </div>
</div>

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

<script>
  // Funções para abrir e fechar modais
  function abrirModal(id) {
    document.getElementById(id).style.display = 'flex';
  }

  function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
  }

  // Escutar o envio do formulário
  const formContato = document.getElementById('formContato');
  const btnEnviar = document.getElementById('btnEnviar');

  formContato.addEventListener('submit', function(e) {
    e.preventDefault(); // Impede o recarregamento da página (Isso é crucial!)

    // Muda texto do botão para feedback visual
    const textoOriginal = btnEnviar.innerText;
    btnEnviar.innerText = 'Enviando...';
    btnEnviar.disabled = true;

    // Coleta os dados do formulário
    const formData = new FormData(this);

    // Envia via FETCH (AJAX) para o PHP
    fetch('enviar_email.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json()) // Espera uma resposta JSON do PHP
    .then(data => {
      if (data.status === 'success') {
        // Se o PHP disse que deu certo:
        abrirModal('modalSucesso');
        formContato.reset(); // Limpa os campos
      } else {
        // Se o PHP disse que deu erro:
        document.getElementById('msgErroTexto').innerText = "Erro: " + data.message; // Mostra o erro real se quiser
        abrirModal('modalErro');
      }
    })
    .catch(error => {
      // Erro de conexão ou servidor caiu
      console.error('Erro:', error);
      abrirModal('modalErro');
    })
    .finally(() => {
      // Restaura o botão
      btnEnviar.innerText = textoOriginal;
      btnEnviar.disabled = false;
    });
  });
</script>
</body>
</html>
