<?php
session_start();

// Verifica se há criança selecionada
$criancaSelecionada = isset($_SESSION['id_crianca']) && isset($_SESSION['idade_crianca']);
$idadeCrianca = $criancaSelecionada ? (int)$_SESSION['idade_crianca'] : 0;
$nomeCrianca = $criancaSelecionada ? $_SESSION['nome_crianca'] : '';

// Verifica se a criança tem idade para esta seção (5-7 anos)
$acessoPermitido = $criancaSelecionada && ($idadeCrianca >= 5 && $idadeCrianca <= 7);

include('Api/vlibras.html');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <script src="https://cdn.userway.org/widget.js" data-account="5Oy3ihG84d"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VivaVerba - Atividades 5-7 anos</title>
  <link rel="stylesheet" href="Css/atv1.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <style>
  /* Garantir que modais estejam ocultos por padrão */
    .modal-sair-overlay {
      display: none !important;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }
    .modal-sair-overlay.ativo {
      display: flex !important;
    }
  </style>
</head>
<body>

  <!-- Modal Sair -->
  <div id="modal-sair" class="modal-sair-overlay">
    <div class="modal-sair">
        <h3>Deseja sair?</h3>
        <p>Você será desconectado da sua conta.</p>
        <div class="botoes-sair">
            <button id="confirmar-sair" class="btn-sair">Sim</button>
            <button id="cancelar-sair" class="btn-cancelar">Não</button>
        </div>
    </div>
  </div>

  <!-- Modal de Bloqueio por Idade -->
  <?php if ($criancaSelecionada && !$acessoPermitido): ?>
  <div id="modal-bloqueio-idade" class="modal-sair-overlay ativo">
    <div class="modal-sair">
        <h3>Ops! Área Restrita</h3>
        <p><strong><?php echo htmlspecialchars($nomeCrianca); ?></strong> tem <?php echo $idadeCrianca; ?> anos.</p>
        <p>Esta área é recomendada para crianças de 8 a 10 anos.</p>
        <p>Que tal explorar a área para sua idade?</p>
        <div class="botoes-sair">
            <?php if ($idadeCrianca >= 5 && $idadeCrianca <= 7): ?>
                <a href="atv1.php" class="btn-sair" style="text-decoration: none; display: inline-block;">Ir para minha área</a>
            <?php endif; ?>
            <a href="parental.php" class="btn-cancelar" style="text-decoration: none; display: inline-block;">Escolher outra criança</a>
        </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Modal de Seleção Obrigatória -->
  <?php if (!$criancaSelecionada): ?>
  <div id="modal-selecao-obrigatoria" class="modal-sair-overlay ativo">
    <div class="modal-sair">
        <h3>Selecione uma Criança</h3>
        <p>Para jogar, você precisa selecionar uma criança na área parental.</p>
        <div class="botoes-sair">
            <a href="parental.php" class="btn-sair" style="text-decoration: none; display: inline-block;">Ir para Área Parental</a>
            <a href="index.php" class="btn-cancelar" style="text-decoration: none; display: inline-block;">Voltar ao Início</a>
        </div>
    </div>
  </div>
  <?php endif; ?>

  <header class="cabecalho">
    <div class="logo">
      <a href="index.php"><img src="Img/logo.png" alt="Logo VivaVerba"></a>
    </div>
    <div class="menu">
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="logout.php" class="botao-entrar">
          <img src="Img/icone-entrar.png" alt="Ícone" />
          <span>Sair</span>
        </a>
      <?php else: ?>
        <a href="login.php" class="botao-entrar">
          <img src="Img/icone-entrar.png" alt="Ícone" />
          <span>Entrar</span>
        </a>
      <?php endif; ?>
      <div class="hamburguer">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </header>

  <div class="overlay" id="overlay"></div>

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

  <div class="container">
    <div class="left">
      <img src="Img/abelha.png" alt="Abelha" class="bee">
      <h2>Atividades<br> recomendadas para<br> crianças de 5 a 7 anos</h2>
      <img src="Img/nuvem.png" alt="Nuvem" class="cloud">
      <p>Jogos e atividades focados no desenvolvimento<br> da alfabetização, coordenação motora e criatividade.</p>
    </div>
    <div class="right">
      <img src="Img/ana-lendo.png" alt="Criança lendo" class="child">
    </div>
  </div>

  <section class="secao-jogos">
    <img src="Img/natureza.jpg" alt="Fundo jogos" class="fundo-jogos">
    <h2>Comece a Jogar</h2>

    <div class="container-geral-jogos">
        <div class="container-boxes" id="jogos-container">
            <div class="box-jogar" style="justify-content: center; align-items: center;">
                <p style="color: #fff; font-size: 18px;">Carregando jogos...</p>
            </div>
        </div>
    </div>

    <div class="barra-progresso">
      <div class="preenchimento" id="barra-preenchimento"></div>
      <img src="Img/pegadas.png" class="pegada" id="pegada-progresso" alt="Pegada">
    </div>
  </section>

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

    if (hamburguer) hamburguer.addEventListener('click', abrirMenu);
    if (fecharMenu) fecharMenu.addEventListener('click', fecharMenuFunc);
    if (overlay) overlay.addEventListener('click', fecharMenuFunc);

    // Modal Sair
    const botoesSair = document.querySelectorAll("a[href='logout.php']");
    const modalSair = document.getElementById("modal-sair");
    const btnCancelarSair = document.getElementById("cancelar-sair");
    const btnConfirmarSair = document.getElementById("confirmar-sair");

    if (botoesSair.length > 0 && modalSair) {
        botoesSair.forEach(botao => {
            botao.addEventListener("click", function(e) {
                e.preventDefault();
                modalSair.classList.add('ativo');
            });
        });
    }

    if (btnCancelarSair) {
        btnCancelarSair.addEventListener("click", function() {
            modalSair.classList.remove('ativo');
        });
    }

    if (btnConfirmarSair) {
        btnConfirmarSair.addEventListener("click", function() {
            window.location.href = "logout.php";
        });
    }

    // Carrega jogos dinamicamente
        async function carregarJogos() {
        try {
            console.log('Iniciando carregamento de jogos...');
            const response = await fetch('api_jogos_desbloqueados.php');
            
            if (!response.ok) {
                throw new Error('Erro na resposta da API: ' + response.status);
            }
            
            const data = await response.json();
            console.log('Dados recebidos:', data);
            
            const container = document.getElementById('jogos-container');
            if (!container) {
                return;
            }
            
            container.innerHTML = '';

            if (!data.success) {
                container.innerHTML = '<div class="box-jogar"><p style="color: #fff;">Erro ao carregar jogos: ' + (data.error || 'Erro desconhecido') + '</p></div>';
                return;
            }

            const jogos = data.jogos || [];
            
            if (jogos.length === 0) {
                container.innerHTML = '<div class="box-jogar"><p style="color: #fff;">Nenhum jogo disponível para esta idade.</p></div>';
                return;
            }

            jogos.forEach((jogo, index) => {
                const box = document.createElement('div');
                box.classList.add('box-jogar');
                
                if (jogo.desbloqueado) {
                    box.classList.add('active');
                    box.innerHTML = `
                        <a href="${jogo.arquivo}" style="text-decoration: none; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; height: 100%;">
                            <img src="Img/${getImagemJogo(jogo.id)}" alt="${jogo.nome}" class="jogo-imagem-principal">
                            <span class="iniciar-texto">
                                <i class="fas fa-play"></i> Iniciar
                            </span>
                        </a>
                    `;
                } else {
                    box.classList.add('box-bloqueado');
                    box.innerHTML = `
                        <img src="Img/${getImagemJogo(jogo.id)}" class="imagem-fundo-bloqueado" alt="${jogo.nome}">
                        <img src="Img/cadeado.png" class="icone-cadeado" alt="Cadeado">
                    `;
                }
                
                container.appendChild(box);
            });

            // Atualiza barra de progresso
            const jogosCompletos = jogos.filter(j => j.completo).length;
            const progresso = jogos.length > 0 ? (jogosCompletos / jogos.length) * 100 : 0;
            atualizarBarraProgresso(progresso);

        } catch (error) {
            console.error('Erro ao carregar jogos:', error);
            const container = document.getElementById('jogos-container');
            if (container) {
                container.innerHTML = '<div class="box-jogar"><p style="color: #fff;">Erro ao carregar jogos. Tente recarregar a página.</p></div>';
            }
        }
    }

    function getImagemJogo(jogoId) {
        const imagens = {
            'jogo1': 'maca.png',
            'jogo2': 'jogo2.png',
            'jogo3': 'jogo3.png'
        };
        return imagens[jogoId] || 'default.png';
    }

    function atualizarBarraProgresso(percentual) {
        const preenchimento = document.getElementById('barra-preenchimento');
        const pegada = document.getElementById('pegada-progresso');
        
        if (!preenchimento || !pegada) return;

        const barraWidth = preenchimento.parentElement.offsetWidth;
        const preenchimentoWidth = (percentual / 100) * barraWidth;
        let leftPos = preenchimentoWidth + barraWidth * 0.03 - (pegada.offsetWidth / 2);

        if (leftPos + pegada.offsetWidth > barraWidth) {
            leftPos = barraWidth - pegada.offsetWidth;
        }

        pegada.style.left = `${leftPos}px`;
        preenchimento.style.width = `${percentual}%`;
    }

    // Carrega jogos ao iniciar - SOMENTE se tiver acesso permitido
    <?php if ($criancaSelecionada && $acessoPermitido): ?>
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', carregarJogos);
    } else {
        carregarJogos();
    }
    <?php endif; ?>
  </script>
</body>
</html>