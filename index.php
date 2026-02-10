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
  <link rel="stylesheet" href="Css/style.css">
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

</div>
  <!-- Parte inicial -->
  <section class="parte-inicial">
    <img class="imagem-canto" src="Img/canto-amarelo.png" alt="Imagem canto">
    <div class="texto-inicial">
      <h1>Transforme o<br> tempo em tela em aprendizado!</h1>
      <p>Jogos educativos e atividades<br> para auxiliar no aprendizado de crianças<br> do ensino fundamental</p>
      <button class="botao-amarelo" style="font-family: 'Poppins';">Começar agora</button>
    </div>
    <div class="imagem-inicial">
      <img src="Img/criancas.png" alt="Crianças estudando">
    </div>
</section>


  <!-- Vantagens -->
  <section class="vantagens">
    <div class="caixa roxa">
      <img src="Img/icon-jogos.png" alt="Ícone jogos">
      <p>Jogos Interativos Educativos</p>
    </div>
    <div class="caixa rosa">
      <img src="Img/icon-comunicacao.png" alt="Ícone comunicação">
      <p>Estímulo à Comunicação</p>
    </div>
    <div class="caixa amarela">
      <img src="Img/icon-aprendizado.png" alt="Ícone aprendizado">
      <p>Aprendizagem Personalizada</p>
    </div>
  </section>

  <!-- Área dos pais e app -->
<section class="sessao-pais-app">
  <!-- Caixa dos Pais -->
  <div class="caixa-pais">
    <img class="icone-pais" src="Img/icone-pais.png" alt="Ícone pais">
    <div class="conteudo">
      <h3>Área dos Pais</h3>
      <p>Acompanhe o progresso do<br> seu filho e veja dicas para<br> ajudá-lo no desenvolvimento</p>
      <a href="parental.php" style="text-decoration: none; font-family: 'Inter';" class="botao-amarelo-dois">
        <img src="Img/icone-cadeado2.png" alt="Ícone cadeado" class="icone-cadeado">
        ﾠAcessar como responsável
      </a>
    </div>
  </div>

  <!-- Caixa do App -->
  <div class="caixa-app">
    <div class="conteudo">
      <h3>Acesse também no<br> App</h3>
      <p>Use mesmo sem internet<br>Versão offline disponível</p>
      <a href="Jogos/vivaverba.apk" download>
        <button class="botao-roxo">
            <img src="Img/icone-download.png" alt="Ícone download">
            Baixar agora
        </button>
      </a>
    </div>
    <img class="icone-app" src="Img/icone-app.png" alt="Ícone celular">
  </div>
</section>

  <!-- Atividades -->
  <section class="atividades" id="atividades">
    <h2>Embarque nessa jornada cheia de diversão!</h2>
    <div class="lista-atividades">
       <a style="text-decoration: none;" href="atv1.php">
      <div class="atividade">
        <img src="Img/crianca-pintando.jpeg" alt="Criança pintando">
        <p>Atividades recomendadas para<br> crianças de 5 à 7 anos</p>
      </div>
      </a>
         <a style="text-decoration: none;" href="atv2.php">
      <div class="atividade">
        <img src="Img/crianca-escrevendo.png" alt="Criança escrevendo">
        <p>Atividades recomendadas para<br>  crianças de 8 à 10 anos</p>
      </div>
      </a>
    </div>
  </section>
</a>

  <!-- Depoimentos -->
<section class="depoimentos">
  <h2>O que os pais estão dizendo</h2>
  <div class="lista-depoimentos">
    
    <div class="depoimento depoimento1">
      <p>“Minha filha aprendeu a ler com o VivaVerba! Foi incrível ver como ela ganhou confiança e começou a se divertir com a leitura. Hoje, explora histórias sozinha e está cada vez mais apaixonada por livros. Recomendo demais!”</p>
     <div class="estrelas">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
</div>

      <img class="avatar" src="Img/avatar1.png" alt="Avatar mãe">
    </div>
    
    <div class="depoimento depoimento2">
      <p>“Melhor plataforma para o aprendizado infantil! Com métodos divertidos e educativos, meus filhos aprendem brincando e se envolvem com cada atividade. É realmente eficaz e faz o aprendizado ser uma experiência prazerosa todos os dias.”</p>
 <div class="estrelas">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
</div>
      <img class="avatar" src="Img/avatar2.png" alt="Avatar pai">
    </div>
    
    <div class="depoimento depoimento3">
      <p>“Meu filho ama o app! É super interativo, educativo e divertido, e ele não se cansa de explorar todas as atividades. É incrível ver como cada dia ele aprende algo novo enquanto brinca, desenvolvendo habilidades importantes de forma divertida.”</p>
    <div class="estrelas">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
    <path fill="#FFD700" d="M12 2.5c.3 0 .6.15.75.4l2.4 4.87 5.38.45c.35.03.63.28.68.63.05.35-.12.7-.42.92l-4.1 3.54 1.24 5.4c.08.34-.04.7-.3.93-.26.23-.63.32-.98.22L12 17.77l-4.95 2.34c-.35.17-.76.07-1.02-.22-.26-.29-.38-.68-.3-1.03l1.24-5.4-4.1-3.54c-.3-.22-.47-.57-.42-.92.05-.35.33-.6.68-.63l5.38-.45 2.4-4.87c.15-.25.45-.4.75-.4z"/>
  </svg>
</div>
      <img class="avatar" src="Img/avatar3.png" alt="Avatar responsável">
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
document.addEventListener("DOMContentLoaded", function() {
    const banner = document.getElementById('cookie-banner');
    const acceptBtn = document.getElementById('accept-cookies');

    // 1. Verifica se já existe o registro no navegador
    if (!localStorage.getItem('cookiesAceitos')) {
        // Se NÃO tem o registro, mostra o banner
        // Usamos 'flex' para facilitar alinhamento, ou 'block'
        banner.style.display = 'flex'; 
    } else {
        // Se JÁ tem, garante que fique oculto
        banner.style.display = 'none';
    }

    // 2. Ação ao clicar em aceitar
    acceptBtn.addEventListener('click', function() {
        // Esconde o banner visualmente
        banner.style.display = 'none';

        // Salva a "lembrança" no navegador do usuário
        localStorage.setItem('cookiesAceitos', 'true');
    });
});
</script>




<div id="cookie-banner" class="cookie-banner" style="display: none;">
    <p>Usamos cookies para melhorar sua experiência. Ao continuar, você aceita nossa <a href="politicas-privacidade.php">política de privacidade</a>.</p>
    <button id="accept-cookies" class="btn-accept">Aceitar</button>
</div>


<!-- MODAL DE CONFIRMAÇÃO DE SAÍDA -->
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


<div id="modal-aviso-login" class="modal-sair-overlay" style="display: none;">
  <div class="modal-sair">
    <h3>Ops! Quase lá...</h3>
    
    <p>Para poder começar, primeiro faça sua conta ou entre caso já tenha um cadastro.</p>
    
    <div class="botoes-sair">
      <button id="btn-ir-cadastro" class="btn-sair">Criar Conta</button>
      
      <button id="btn-fechar-aviso" class="btn-cancelar">Voltar</button>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // --- Variáveis Globais ---
    // Pega o status de login do PHP
    const isLogged = <?php echo json_encode(!empty($_SESSION['user_id'])); ?>;

    // --- PARTE 1: LÓGICA DO BOTÃO COMEÇAR E LINKS ---
    const botaoComecar = document.querySelector('.botao-amarelo');
    const secaoAtividades = document.querySelector('.atividades');
    const linksAtividades = document.querySelectorAll('.atividades a');
    
    // Elementos do Novo Modal de Aviso
    const modalAviso = document.getElementById('modal-aviso-login');
    const btnIrCadastro = document.getElementById('btn-ir-cadastro');
    const btnFecharAviso = document.getElementById('btn-fechar-aviso');

    // Função para abrir o modal de aviso
    function abrirModalAviso() {
        if(modalAviso) modalAviso.style.display = 'flex';
    }

    // Ação do botão "Começar Agora"
    if (botaoComecar) {
        botaoComecar.addEventListener('click', (e) => {
            if (!isLogged) {
                // SE NÃO ESTIVER LOGADO: Abre o modal e não desce a tela
                e.preventDefault(); 
                abrirModalAviso();
            } else {
                // SE ESTIVER LOGADO: Faz o scroll suave até as atividades
                if (secaoAtividades) {
                    const start = window.scrollY;
                    const end = secaoAtividades.offsetTop;
                    const distance = end - start;
                    const duration = 300;
                    let startTime = null;

                    function scroll(timestamp) {
                        if (!startTime) startTime = timestamp;
                        const elapsed = timestamp - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        window.scrollTo(0, start + distance * progress);
                        if (progress < 1) requestAnimationFrame(scroll);
                    }
                    requestAnimationFrame(scroll);
                }
            }
        });
    }

    // Bloqueia clique nos links das atividades (img e texto) se não estiver logado
    linksAtividades.forEach(link => {
        link.addEventListener('click', (e) => {
            if (!isLogged) {
                e.preventDefault();
                abrirModalAviso();
            }
        });
    });

    // Botões de dentro do Modal de Aviso
    if (btnIrCadastro) {
        btnIrCadastro.addEventListener('click', function() {
            // Mude aqui para 'cadastro.php' ou 'login.php' conforme sua necessidade
            window.location.href = 'cadastro.php'; 
        });
    }

    if (btnFecharAviso) {
        btnFecharAviso.addEventListener('click', function() {
            if(modalAviso) modalAviso.style.display = 'none';
        });
    }


    // --- PARTE 2: LÓGICA DO BOTÃO SAIR (LOGOUT) ---
    const botoesSair = document.querySelectorAll("a[href='logout.php']");
    const modalSair = document.getElementById("modal-sair");
    const btnCancelarSair = document.getElementById("cancelar-sair");
    const btnConfirmarSair = document.getElementById("confirmar-sair");

    if (botoesSair.length > 0 && modalSair) {
        botoesSair.forEach(botao => {
            botao.addEventListener("click", function(e) {
                e.preventDefault(); // Impede sair direto
                modalSair.style.display = "flex"; // Abre modal de sair
            });
        });
    }

    // Botão Cancelar (Sair)
    if (btnCancelarSair) {
        btnCancelarSair.addEventListener("click", function() {
            if(modalSair) modalSair.style.display = "none";
        });
    }

    // Botão Confirmar (Sair)
    if (btnConfirmarSair) {
        btnConfirmarSair.addEventListener("click", function() {
            window.location.href = "logout.php";
        });
    }
});
</script>
</body>
</html> 