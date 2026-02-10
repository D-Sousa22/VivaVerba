<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logged = isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
?>
<header class="cabecalho">
  <div class="logo">
    <a href="index.php"><img src="Img/logo.png" alt="Logo VivaVerba"></a>
  </div>

  <div class="menu">

    <?php if (!empty($_SESSION['user_id'])): ?>
        <!-- BOTÃO ABRE O MODAL AGORA -->
        <a href="#" class="botao-entrar btn-open-logout-modal">
            <img src="Img/icone-entrar.png" alt="Ícone">
            <span>Sair</span>
        </a>
    <?php else: ?>
        <a href="login.php" class="botao-entrar">
            <img src="Img/icone-entrar.png" alt="Ícone">
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

<!-- ======================= -->
<!--  MODAL DE SAIR          -->
<!-- ======================= -->

<div class="modal-sair-overlay" id="modalSair">
    <div class="modal-sair">
        <h3>Deseja realmente sair?</h3>
        <p>Você será desconectado da sua conta.</p>

        <div class="botoes-sair">
            <button class="btn-sair" id="confirmarLogout">Sim</button>
            <button class="btn-cancelar" id="cancelarLogout">Não</button>
        </div>
    </div>
</div>

<!-- ======================= -->
<!--   SCRIPT DO MODAL       -->
<!-- ======================= -->

<script>
// abrir modal
const btnOpenLogout = document.querySelector('.btn-open-logout-modal');
const modalSair = document.getElementById('modalSair');
const cancelarLogout = document.getElementById('cancelarLogout');
const confirmarLogout = document.getElementById('confirmarLogout');

if (btnOpenLogout) {
    btnOpenLogout.addEventListener('click', function(e){
        e.preventDefault();
        modalSair.style.display = 'flex';
    });
}

// fechar modal
cancelarLogout.addEventListener('click', function(){
    modalSair.style.display = 'none';
});

// confirmar
confirmarLogout.addEventListener('click', function(){
    window.location.href = "logout.php";
});
</script>
