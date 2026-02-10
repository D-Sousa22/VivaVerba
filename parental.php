<?php
session_start();
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Conexão com o banco
$servername = "localhost";
$port = 3306;
$username = "u358404112_verbovivo";
$password = "VivaVerba2025";
$dbname = "u358404112_vivaverba";

try {
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);

    // Buscar crianças do usuário logado
    $stmt = $conn->prepare("SELECT * FROM criancas WHERE id_responsavel = :id_responsavel ORDER BY id DESC");
    $stmt->bindParam(':id_responsavel', $_SESSION['user_id']);
    $stmt->execute();
    $criancas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verifica se há criança selecionada
    $criancaSelecionada = isset($_SESSION['id_crianca']) ? (int)$_SESSION['id_crianca'] : null;
    
    // Se há criança selecionada, busca dados detalhados dela
    $dadosCriancaSelecionada = null;
    if ($criancaSelecionada) {
        foreach ($criancas as $crianca) {
            if ((int)$crianca['id'] === $criancaSelecionada) {
                $dadosCriancaSelecionada = $crianca;
                
                // Busca tempo de jogo da última semana
                $stmt = $conn->prepare("
                    SELECT SUM(tempo_minutos) as total_minutos
                    FROM tempo_jogo
                    WHERE id_crianca = :id_crianca
                    AND data >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                ");
                $stmt->execute([':id_crianca' => $criancaSelecionada]);
                $tempo = $stmt->fetch();
                $dadosCriancaSelecionada['tempo_semana_minutos'] = $tempo['total_minutos'] ?? 0;
                
                // Busca conquistas
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as total
                    FROM conquistas_criancas
                    WHERE id_crianca = :id_crianca
                ");
                $stmt->execute([':id_crianca' => $criancaSelecionada]);
                $conquistas = $stmt->fetch();
                $dadosCriancaSelecionada['total_conquistas'] = $conquistas['total'] ?? 0;
                
                break;
            }
        }
    }

    // Calcular estatísticas gerais (todas as crianças)
    $totalPontos = 0;
    $totalConquistas = 0;
    $progressoGeral = 0;
    
    if (count($criancas) > 0) {
        foreach ($criancas as $crianca) {
            $totalPontos += $crianca['pontos_totais'] ?? 0;
            $progressoGeral += $crianca['progresso'] ?? 0;
        }
        $progressoGeral = round($progressoGeral / count($criancas));
        
        // Contar conquistas totais
        $stmtConquistas = $conn->prepare("
            SELECT COUNT(DISTINCT jogo_id) as total 
            FROM jogos_completados jc
            INNER JOIN criancas c ON jc.id_crianca = c.id
            WHERE c.id_responsavel = :id_responsavel
        ");
        $stmtConquistas->execute([':id_responsavel' => $_SESSION['user_id']]);
        $conquistas = $stmtConquistas->fetch();
        $totalConquistas = $conquistas['total'] ?? 0;
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
    $criancas = [];
}

include('Api/vlibras.html');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <script src="https://cdn.userway.org/widget.js" data-account="5Oy3ihG84d"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VivaVerba - Área Parental</title>
  <link rel="stylesheet" href="Css/parental.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
/* Destaque da criança selecionada */
.caixa-filho {
    position: relative;
    transition: all 0.3s ease;
}

.caixa-filho.selecionada {
    transform: scale(1.1);
    z-index: 10;
    box-shadow: none;
    background: none;
}

.caixa-filho.selecionada::before {
    content: '✓ SELECIONADA';
    position: absolute;
    top: -15px;
    white-space: nowrap;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    font-family: 'Fredoka One', cursive;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.5);
    z-index: 100;
    animation: pulse-badge 2s infinite;
}

.caixa-filho.selecionada .progresso-barra {
    stroke: #667eea !important;
    filter: drop-shadow(0 0 10px rgba(102, 126, 234, 0.6));
}

@keyframes pulse-badge {
    0%, 100% {
        transform: translateX(-50%) scale(1);
    }
    50% {
        transform: translateX(-50%) scale(1.05);
    }
}

/* Seção de informações da criança selecionada */
.info-crianca-selecionada {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 30px;
    margin: 30px auto;
    max-width: 900px;
    color: white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    display: none;
    white-space: nowrap;
}

.info-crianca-selecionada.ativa {
    display: block;
    animation: slideDown 0.5s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.info-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.info-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.info-dados h2 {
    font-family: 'Fredoka One', cursive;
    font-size: 2em;
    margin: 0 0 5px 0;
}

.info-dados p {
    opacity: 0.9;
    margin: 0;
}

.info-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.stat-item {
    background: rgba(255, 255, 255, 0.15);
    padding: 15px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.stat-item h4 {
    font-size: 0.9em;
    opacity: 0.9;
    margin: 0 0 5px 0;
    font-weight: 600;
}

.stat-item .valor {
    font-size: 1.8em;
    font-family: 'Fredoka One', cursive;
    margin: 0;
}
</style>

<link rel="stylesheet" href="Css/acessibilidade-extra.css">
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

    <div class="filhos">
    <h3>Seus filhos</h3>
    <div class="area-filhos">
        <?php foreach ($criancas as $crianca): ?>
            <div class="caixa-filho"
                 data-id="<?php echo (int)$crianca['id']; ?>"
                 data-nome="<?php echo htmlspecialchars($crianca['nome']); ?>"
                 data-idade="<?php echo htmlspecialchars($crianca['idade']); ?>"
                 data-avatar="<?php echo htmlspecialchars($crianca['avatar'] ?? 'default-avatar.png'); ?>"
                 data-progress="<?php echo isset($crianca['progresso']) ? (int)$crianca['progresso'] : 0; ?>">
                <div class="progresso-wrapper">
                    <svg class="progresso-svg" viewBox="0 0 120 120">
                        <circle class="progresso-fundo" cx="60" cy="60" r="54" fill="none" stroke="#eee" stroke-width="10"/>
                        <circle class="progresso-barra" cx="60" cy="60" r="54" fill="none" stroke="#7819ce" stroke-width="10" stroke-linecap="round" style="stroke-dasharray:0 9999; stroke-dashoffset:0; transition: stroke-dashoffset 0.6s ease;"></circle>
                    </svg>
                    <div class="progresso-interno">
                        <img src="Img/<?php echo htmlspecialchars($crianca['avatar'] ?? 'default-avatar.png'); ?>" 
                             alt="Avatar de <?php echo htmlspecialchars($crianca['nome']); ?>">
                    </div>
                    
                    <!-- Botão Editar -->
                     <div class="botao-editar" onclick="openEditModal(<?php echo (int)$crianca['id']; ?>)" title="Editar">
                    ✎

                    </div>
                    
               
                </div>
                <div class="nome-filho"><?php echo htmlspecialchars($crianca['nome']); ?></div>
                <div class="idade-filho"><?php echo htmlspecialchars($crianca['idade']); ?> anos</div>
                <div class="pontos-filho"><?php echo number_format($crianca['pontos_totais'] ?? 0); ?> pontos</div>
                     <!-- NOVO: Botão Deletar -->
                     <div class="container-botoes-acao">
                    <div class="botao-deletar" onclick="confirmarDelete(<?php echo (int)$crianca['id']; ?>, '<?php echo htmlspecialchars($crianca['nome']); ?>')" title="Deletar">
                        <span class="material-icons" style="font-size: 16px;">delete</span>
                    </div>
                    
                    <!-- NOVO: Botão Jogar -->
                    <div class="botao-jogar" onclick="selecionarCrianca(<?php echo (int)$crianca['id']; ?>)" title="Jogar">
                        <span class="material-icons" style="font-size: 16px;">sports_esports</span>
                    </div>
            </div>
             </div>
        <?php endforeach; ?>

        <div class="caixa-filho" onclick="openAddModal()">
            <button class="caixa-adicionar">
                 <svg class="linha-tracejada" viewBox="0 0 120 120">
              <circle cx="60" cy="60" r="55" fill="none" stroke="#999" stroke-width="4" stroke-dasharray="10 15" stroke-linecap="round"/>
            </svg>
                <svg class="icone-mais" viewBox="0 0 100 100">
              <line x1="50" y1="20" x2="50" y2="80" stroke="#333" stroke-width="13" stroke-linecap="round"/>
              <line x1="20" y1="50" x2="80" y2="50" stroke="#333" stroke-width="13" stroke-linecap="round"/>
            </svg>
            </button>
            <div class="nome-adicionar">Adicionar criança</div>
        </div>
    </div>
</div>

<!-- NOVO: Modal de Confirmação de Exclusão -->
<div class="modal" id="deleteModal" style="display: none;">
    <div class="modal-content">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza que deseja excluir <strong id="delete-nome-crianca"></strong>?</p>
        <p style="color: #d32f2f; font-size: 14px;">Esta ação não pode ser desfeita e todos os dados serão perdidos.</p>
        
        <form id="deleteForm" method="POST" action="deletar_crianca.php">
            <input type="hidden" name="crianca_id" id="delete-crianca-id">
            <div class="buttons-container">
                 <button type="submit" class="salvar" style="border-color: #b71c1c ; background-color: #d32f2f;">Sim, Excluir</button>
                <button type="button" class="cancelar" onclick="closeDeleteModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

      <!-- Modal Adicionar Criança -->
<div class="modal" id="addChildModal">
  <div class="modal-content">
    <h2>Adicionar Nova Criança</h2>

    <!-- Avatar grande -->
    <div class="avatar-preview">
      <img id="newSelectedAvatar" src="Img/dinossauro.png" alt="Avatar Selecionado">

    </div>

    <!-- Seleção de Avatares -->
    <div class="avatar-selector">

      <div class="avatar-option" onclick="selectNewAvatar(this)" data-avatar="cachorro.png">
        <img src="Img/cachorro.png" alt="Avatar Cachorro" class="avatar">
      </div>

      <div class="avatar-option" onclick="selectNewAvatar(this)" data-avatar="dinossauro.png">
        <img src="Img/dinossauro.png" alt="Avatar Dinossauro" class="avatar">
      </div>

      <div class="avatar-option" onclick="selectNewAvatar(this)" data-avatar="unicornio.png">
        <img src="Img/unicornio.png" alt="Avatar Unicornio" class="avatar">
      </div>

      <div class="avatar-option" onclick="selectNewAvatar(this)" data-avatar="hamster.png">
        <img src="Img/hamster.png" alt="Avatar Hamster" class="avatar">
      </div>

      <div class="avatar-option" onclick="selectNewAvatar(this)" data-avatar="gato.png">
        <img src="Img/gato.png" alt="Avatar Gato" class="avatar">
      </div>

    </div>

    <!-- Formulário funcional -->
    <form id="addChildForm" method="POST" action="adicionar_crianca.php">

      <label for="nome">Nome da Criança:</label>
      <input type="text" id="nome" name="nome" required>

      <label for="idade">Idade:</label>
      <input type="number" id="idade" name="idade" min="4" max="12" required>

      <!-- Avatar escolhido -->
      <input type="hidden" name="avatar" id="selectedAvatarNew" required value="dinossauro.png">

      <div class="buttons-container">
        <button type="submit" class="salvar">Adicionar</button>
        <button type="button" class="cancelar" onclick="closeAddModal()">Cancelar</button>
      </div>
    </form>

  </div>
</div>


<!-- Modal de Edição -->
<div class="modal" id="editProfileModal">
  <div class="modal-content">

    <h2>Editar Criança</h2>

    <form id="editChildForm" method="POST" action="editar_crianca.php">
      <input type="hidden" id="crianca_id" name="crianca_id">

      <!-- Avatar grande com botão editar -->
<div class="avatar-preview">
    <img id="editSelectedAvatar" src="Img/dinossauro.png" alt="Avatar Selecionado">
    <img src="Img/icon-editar.png" alt="Editar avatar" class="edit-overlay">
</div>


      <!-- Avatares pequenos -->
      <div class="avatar-selector">

        <div class="avatar-option" onclick="selectAvatar(this,'edit')" data-avatar="cachorro.png">
          <img src="Img/cachorro.png" alt="Avatar Cachorro" class="avatar">
        </div>

        <div class="avatar-option" onclick="selectAvatar(this,'edit')" data-avatar="dinossauro.png">
          <img src="Img/dinossauro.png" alt="Avatar Dinossauro" class="avatar">
        </div>

        <div class="avatar-option" onclick="selectAvatar(this,'edit')" data-avatar="unicornio.png">
          <img src="Img/unicornio.png" alt="Avatar Unicornio" class="avatar">
        </div>

        <div class="avatar-option" onclick="selectAvatar(this,'edit')" data-avatar="hamster.png">
          <img src="Img/hamster.png" alt="Avatar Hamster" class="avatar">
        </div>

        <div class="avatar-option" onclick="selectAvatar(this,'edit')" data-avatar="gato.png">
          <img src="Img/gato.png" alt="Avatar Gato" class="avatar">
        </div>

      </div>

      <input type="hidden" id="avatar_selecionado_edit" name="avatar">

      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" required>

      <label for="idade">Idade:</label>
      <input type="number" id="idade" name="idade" min="4" max="12" required>

      <div class="buttons-container">
        <button type="submit" class="salvar">Salvar</button>
        <button type="button" class="cancelar" onclick="closeEditModal()">Cancelar</button>
      </div>

    </form>
  </div>
</div>


     <div class="estatisticas-container">
  <div class="estatistica-card purple">
    <div class="icone">
      <i class="fas fa-clock"></i>
    </div>
    <div class="dados">
      <span class="valor">0h </span>
      <br>
      <span class="descricao">Esta semana</span>
    </div>
  </div>

  <div class="estatistica-card blue">
    <div class="icone">
      <i class="fas fa-trophy"></i>
    </div>
    <div class="dados">
      <span class="valor"><?php echo $totalConquistas; ?></span>
      <br>
      <span class="descricao">Conquistas</span>
    </div>
  </div>

  <div class="estatistica-card green">
    <div class="icone">
      <i class="fas fa-star"></i>
    </div>
    <div class="dados">
      <span class="valor"><?php echo number_format($totalPontos); ?></span>
      <br>
      <span class="descricao">Pontos</span>
    </div>
  </div>

  <div class="estatistica-card orange">
    <div class="icone">
      <i class="fas fa-chart-line"></i>
    </div>
    <div class="dados">
      <span class="valor"><?php echo $progressoGeral; ?>%</span>
      <br>
      <span class="descricao">Progresso geral</span>
    </div>
  </div>
</div>

<div class="container2">
    <div class="conquistas-container">
      <h3>Resumo das Conquistas</h3>
        <div class="conquistas-icons">
            <div class="conquista-item">
      <img src="Img/icon_bussola.png" alt="Aventureiro Semanal" class="conquista-icon">
      <div>Aventureiro Semanal</div>
    </div>
    <div class="conquista-item">
      <img src="Img/icon_abc.png" alt="Mestre das Palavras" class="conquista-icon">
      <div>Mestre das Palavras</div>
    </div>
    <div class="conquista-item">
      <img src="Img/icon_book.png" alt="Leitor Iniciante" class="conquista-icon">
      <div>Leitor Iniciante</div>
    </div>
    <div class="conquista-item">
      <img src="Img/icon_trophy.png" alt="Primeiros Passos" class="conquista-icon">
      <div>Primeiros Passos</div>
    </div>
  </div>
  
</div>
   <div class="missoes-container">
    <h3>Missões semanais</h3>
    <div class="missao-item">
        <input type="checkbox" id="missao1" class="checkbox-conquista">
        <label for="missao1">Faça login 5 vezes na semana</label>
    </div>
    <div class="missao-item">
        <input type="checkbox" id="missao2" checked class="checkbox-conquista">
        <label for="missao2"><span>Conquistar 3 estrelas em um jogo educativo</span></label>
    </div>
    <div class="missao-item">
        <input type="checkbox" id="missao3" class="checkbox-conquista">
        <label for="missao3">Concluir uma sequência de 3 atividades sem errar</label>
    </div>
    <div class="missao-item">
        <input type="checkbox" id="missao4" class="checkbox-conquista">
        <label for="missao4">Ler 20 páginas de um livro esta semana</label>
    </div>
</div>

</div>
</div>

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
      <a href="#"><img src="Img/youtube.png" alt="YouTube"> Youtube</a>
      <a href="#"><img src="Img/instagram.png" alt="Instagram"> Instagram </a>
      <a href="#"><img src="Img/facebook.png" alt="Facebook" style="width: 11px; margin-left: 3px; "> Facebook</a>
    </div>
  </footer>
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

<script src="Js/acessibilidade.js"></script>

<script>
  // Gráfico de Atividade Semanal
const ctxAtividade = document.getElementById('graficoAtividadeSemanal').getContext('2d');
const dadosAtividade = [40, 55, 50, 45, 35, 50, 60];
const maxAtividade = Math.max(...dadosAtividade);
const cores = dadosAtividade.map(valor => valor === maxAtividade ? '#fddc5f' : '#9b5ef7');
const coresHover = dadosAtividade.map((valor, indice) => {
    if (indice === 6) return '#fdc82b';
    return '#7819ce';
});

const graficoAtividadeSemanal = new Chart(ctxAtividade, {
    type: 'bar',
    data: {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
        datasets: [{
            label: 'Atividade',
            data: dadosAtividade,
            backgroundColor: cores,
            borderColor: '#7819ce',
            hoverBackgroundColor: coresHover,
            hoverBorderColor: '#9b5ef7',
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { font: { size: 16 } },
                grid: { display: false }
            },
            x: {
                ticks: { font: { size: 16 } },
                grid: { display: false }
            }
        },
        plugins: {
            legend: { display: false },
            title: { display: false }
        }
    }
});
</script>

<script>
// ============================================
// 1. MENU HAMBÚRGUER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const hamburguer = document.querySelector('.hamburguer');
    const menuFlutuante = document.getElementById('menuFlutuante');
    const fecharMenu = document.getElementById('fecharMenu');
    const overlay = document.getElementById('overlay');

    function abrirMenu() {
        if (menuFlutuante) menuFlutuante.style.display = 'flex';
        if (overlay) overlay.style.display = 'block';
    }

    function fecharMenuFunc() {
        if (menuFlutuante) menuFlutuante.style.display = 'none';
        if (overlay) overlay.style.display = 'none';
    }

    if (hamburguer) {
        hamburguer.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            abrirMenu();
        });
    }

    if (fecharMenu) {
        fecharMenu.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fecharMenuFunc();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fecharMenuFunc();
        });
    }

    // ============================================
    // 2. MODAL DE SAIR (CORRIGIDO)
    // ============================================
    const modalSair = document.getElementById("modal-sair");
    const btnCancelarSair = document.getElementById("cancelar-sair");
    const btnConfirmarSair = document.getElementById("confirmar-sair");

    // Intercepta TODOS os cliques em links/botões de logout
    document.body.addEventListener('click', function(e) {
        // Verifica se clicou em elemento com href="logout.php" OU texto "Sair"
        const target = e.target.closest('a[href="logout.php"], .botao-entrar[href*="logout"], a:has(span:contains("Sair"))');
        
        if (target && target.getAttribute('href') === 'logout.php') {
            e.preventDefault();
            e.stopPropagation();
            if (modalSair) {
                modalSair.classList.add('ativo');
                modalSair.style.display = 'flex';
            }
        }
    });

    // Botão cancelar
    if (btnCancelarSair) {
        btnCancelarSair.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (modalSair) {
                modalSair.classList.remove('ativo');
                modalSair.style.display = 'none';
            }
        });
    }

    // Botão confirmar
    if (btnConfirmarSair) {
        btnConfirmarSair.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = 'logout.php';
        });
    }

    // ============================================
    // 3. GRÁFICO DE ATIVIDADE SEMANAL (CORRIGIDO)
    // ============================================
    
    // Pega ID da criança selecionada (do PHP)
    const idCriancaSelecionada = <?php echo isset($_SESSION['id_crianca']) ? $_SESSION['id_crianca'] : 'null'; ?>;
    
    if (idCriancaSelecionada) {
        carregarGraficoAtividade(idCriancaSelecionada);
    }

    async function carregarGraficoAtividade(idCrianca) {
        try {
            console.log('Carregando gráfico para criança:', idCrianca);
            
            const response = await fetch(`api_tempo_jogo.php?tipo=semanal&id_crianca=${idCrianca}`);
            const data = await response.json();
            
            if (!data.success) {
                console.error('Erro ao buscar dados:', data);
                return;
            }

            const dados = data.dados || [];
            console.log('Dados recebidos:', dados);

            // Cria gráfico
            const ctx = document.getElementById('graficoAtividadeSemanal');
            if (!ctx) {
                console.error('Canvas não encontrado');
                return;
            }

            const valores = dados.map(d => d.minutos || 0);
            const maxMinutos = Math.max(...valores, 1);

            const cores = valores.map((v, i) => {
                if (v === maxMinutos && v > 0) return '#fddc5f';
                return '#9b5ef7';
            });

            // Destrói gráfico anterior se existir
            if (window.graficoAtividade) {
                window.graficoAtividade.destroy();
            }

            // Cria novo gráfico
            window.graficoAtividade = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dados.map(d => d.dia),
                    datasets: [{
                        label: 'Minutos jogados',
                        data: valores,
                        backgroundColor: cores,
                        borderColor: '#7819ce',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                font: { size: 14 },
                                callback: function(value) {
                                    return value + ' min';
                                }
                            },
                            grid: { display: false }
                        },
                        x: {
                            ticks: { font: { size: 14 } },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const mins = context.parsed.y;
                                    const h = Math.floor(mins / 60);
                                    const m = mins % 60;
                                    return h > 0 ? `${h}h ${m}min` : `${m}min`;
                                }
                            }
                        }
                    }
                }
            });

            console.log('Gráfico criado com sucesso');

        } catch (error) {
            console.error('Erro ao carregar gráfico:', error);
        }
    }

    // ============================================
    // 4. DESTACAR CRIANÇA SELECIONADA
    // ============================================
    
    if (idCriancaSelecionada) {
        const caixas = document.querySelectorAll('.caixa-filho');
        caixas.forEach(caixa => {
            const id = parseInt(caixa.getAttribute('data-id'));
            if (id === parseInt(idCriancaSelecionada)) {
                caixa.classList.add('selecionada');
            }
        });
    }

    // ============================================
    // 5. FUNÇÕES DE MODAL E SELEÇÃO
    // ============================================
    
    window.selecionarCrianca = function(criancaId) {
        if (!criancaId) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'selecionar_crianca.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'crianca_id';
        input.value = criancaId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    };

    window.openEditModal = function(childId) {
        if (!childId) return;
        
        const box = document.querySelector(`.caixa-filho[data-id="${childId}"]`);
        if (!box) return;

        const nome = box.getAttribute('data-nome') || '';
        const idade = box.getAttribute('data-idade') || '';
        const avatar = box.getAttribute('data-avatar') || 'dinossauro.png';

        document.getElementById('crianca_id').value = childId;
        
        const inputNome = document.querySelector('#editProfileModal input[name="nome"]');
        const inputIdade = document.querySelector('#editProfileModal input[name="idade"]');
        
        if (inputNome) inputNome.value = nome;
        if (inputIdade) inputIdade.value = idade;

        const preview = document.getElementById('editSelectedAvatar');
        if (preview) preview.src = 'Img/' + avatar;
        
        const hiddenAvatar = document.getElementById('avatar_selecionado_edit');
        if (hiddenAvatar) hiddenAvatar.value = avatar;

        document.getElementById('editProfileModal').style.display = 'flex';
        document.body.classList.add('modal-open');
    };

    window.closeEditModal = function() {
        document.getElementById('editProfileModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    };

    window.openAddModal = function() {
        document.getElementById('addChildModal').style.display = 'flex';
        document.body.classList.add('modal-open');
    };

    window.closeAddModal = function() {
        document.getElementById('addChildModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    };

    window.confirmarDelete = function(criancaId, nomeCrianca) {
        document.getElementById('delete-crianca-id').value = criancaId;
        document.getElementById('delete-nome-crianca').textContent = nomeCrianca;
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.classList.add('modal-open');
    };

    window.closeDeleteModal = function() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    };

    window.selectAvatar = function(element, mode) {
        document.querySelectorAll('.avatar-option').forEach(opt => opt.classList.remove('selected'));
        element.classList.add('selected');

        const avatar = element.getAttribute('data-avatar');
        
        if (mode === 'edit') {
            const preview = document.getElementById('editSelectedAvatar');
            if (preview) preview.src = 'Img/' + avatar;
            
            const hidden = document.getElementById('avatar_selecionado_edit');
            if (hidden) hidden.value = avatar;
        }
    };

    window.selectNewAvatar = function(element) {
        document.querySelectorAll('#addChildModal .avatar-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        element.classList.add('selected');
        
        const avatarName = element.getAttribute('data-avatar');
        document.getElementById('selectedAvatarNew').value = avatarName;

        const previewImage = document.getElementById('newSelectedAvatar');
        if (previewImage) {
            previewImage.src = 'Img/' + avatarName;
        }
    };

    // ============================================
    // 6. ATUALIZAR BARRAS DE PROGRESSO
    // ============================================
    
    function updateProgressBar(svgElement, progressValue) {
        if (!svgElement) return;
        const progressBar = svgElement.querySelector('.progresso-barra');
        if (!progressBar) return;

        const radius = parseFloat(progressBar.getAttribute('r'));
        const circumference = 2 * Math.PI * radius;
        const value = Math.max(0, Math.min(100, Number(progressValue) || 0));
        const offset = circumference * (100 - value) / 100;

        progressBar.style.strokeDasharray = `${circumference} ${circumference}`;
        progressBar.style.strokeDashoffset = offset;
    }

    document.querySelectorAll('.caixa-filho').forEach(box => {
        const svg = box.querySelector('.progresso-svg');
        const progress = box.getAttribute('data-progress') || 0;
        if (svg) updateProgressBar(svg, progress);
    });

    // ============================================
    // 7. FECHAR MODAIS AO CLICAR FORA
    // ============================================
    
    window.onclick = function(event) {
        if (event.target == document.getElementById('addChildModal')) {
            closeAddModal();
        }
        if (event.target == document.getElementById('editProfileModal')) {
            closeEditModal();
        }
        if (event.target == document.getElementById('deleteModal')) {
            closeDeleteModal();
        }
    };

    console.log('Área parental inicializada com sucesso');
});
</script>

<script>
// NOVA FUNÇÃO: Selecionar criança para jogar
function selecionarCrianca(criancaId) {
    if (!criancaId) return;
    
    // Cria formulário e envia
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'selecionar_crianca.php';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'crianca_id';
    input.value = criancaId;
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

// NOVA FUNÇÃO: Confirmar exclusão
function confirmarDelete(criancaId, nomeCrianca) {
    document.getElementById('delete-crianca-id').value = criancaId;
    document.getElementById('delete-nome-crianca').textContent = nomeCrianca;
    document.getElementById('deleteModal').style.display = 'flex'; // Usar flex para centralizar
    document.body.classList.add('modal-open');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

// Funções existentes de edição
function openEditModal(childId) {
  if (!childId) return;
  
  const selector = '.caixa-filho[data-id="' + childId + '"]';
  const box = document.querySelector(selector);
  if (!box) return;

  const nome = box.getAttribute('data-nome') || '';
  const idade = box.getAttribute('data-idade') || '';
  const avatar = box.getAttribute('data-avatar') || 'default-avatar.png';

  // Preenche o ID da criança
  document.getElementById('crianca_id').value = childId;
  
  // CORREÇÃO: Agora preenche os campos de nome e idade corretamente
  const inputNome = document.querySelector('#editProfileModal input[name="nome"]');
  const inputIdade = document.querySelector('#editProfileModal input[name="idade"]');
  
  if (inputNome) inputNome.value = nome;
  if (inputIdade) inputIdade.value = idade;

  // Atualiza o avatar preview
  const avatarSrc = 'Img/' + avatar;
  const preview = document.getElementById('editSelectedAvatar');
  if (preview) {
    preview.src = avatarSrc;
    preview.alt = 'Avatar de ' + nome;
  }
  
  const hiddenAvatar = document.getElementById('avatar_selecionado_edit');
  if (hiddenAvatar) hiddenAvatar.value = avatar;

  // Marca o avatar selecionado
  document.querySelectorAll('.avatar-option').forEach(opt => opt.classList.remove('selected'));
  const matching = Array.from(document.querySelectorAll('.avatar-option')).find(o => o.getAttribute('data-avatar') === avatar);
  if (matching) matching.classList.add('selected');

  document.getElementById('editProfileModal').style.display = 'flex';
  document.body.classList.add('modal-open');
}

function closeEditModal() {
  document.getElementById("editProfileModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

function selectAvatar(element, mode) {
  document.querySelectorAll(".avatar-option").forEach(opt => opt.classList.remove("selected"));
  element.classList.add("selected");

  const img = element.querySelector("img");
  const avatar = element.getAttribute('data-avatar') || (img && img.getAttribute('src').split('/').pop());
  
  if (mode === 'edit') {
    const preview = document.getElementById('editSelectedAvatar');
    if (preview && img) {
      preview.src = img.src;
      preview.alt = img.alt;
    }
    const hidden = document.getElementById('avatar_selecionado_edit');
    if (hidden) hidden.value = avatar;
  } else {
    const hiddenNew = document.getElementById('selectedAvatarNew');
    if (hiddenNew) hiddenNew.value = avatar;
  }
}

function selectNewAvatar(element) {
  const options = document.querySelectorAll('#addChildModal .avatar-option');
  options.forEach(opt => {
    opt.classList.remove('selected');
  });

  element.classList.add('selected');
  const avatarName = element.getAttribute('data-avatar');
  document.getElementById('selectedAvatarNew').value = avatarName;

  const previewImage = document.getElementById('newSelectedAvatar');
  if (previewImage) {
    previewImage.src = 'Img/' + avatarName;
  }
}

function selecionarCrianca(criancaId) {
  if (!criancaId) return;
  
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = 'selecionar_crianca.php';
  
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'crianca_id';
  input.value = criancaId;
  
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
}

function confirmarDelete(criancaId, nomeCrianca) {
  document.getElementById('delete-crianca-id').value = criancaId;
  document.getElementById('delete-nome-crianca').textContent = nomeCrianca;
  document.getElementById('deleteModal').style.display = 'flex';
  document.body.classList.add('modal-open');
}

function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
  document.body.classList.remove('modal-open');
}

window.onclick = function(event) {
  if (event.target == document.getElementById('addChildModal')) {
    closeAddModal();
  }
  if (event.target == document.getElementById('editProfileModal')) {
    closeEditModal();
  }
  if (event.target == document.getElementById('deleteModal')) {
    closeDeleteModal();
  }
}

function openAddModal() {
  document.getElementById('addChildModal').style.display = 'flex'; 
  document.body.classList.add('modal-open');
}

function closeAddModal() {
  document.getElementById('addChildModal').style.display = 'none';
  document.body.classList.remove('modal-open');
}

function updateProgressBar(svgElement, progressValue) {
  if (!svgElement) return;
  const progressBar = svgElement.querySelector('.progresso-barra');
  if (!progressBar) return;

  const radius = parseFloat(progressBar.getAttribute('r'));
  const circumference = 2 * Math.PI * radius;
  const value = Math.max(0, Math.min(100, Number(progressValue) || 0));
  const offset = circumference * (100 - value) / 100;

  progressBar.style.strokeDasharray = `${circumference} ${circumference}`;
  progressBar.style.strokeDashoffset = offset;
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.caixa-filho').forEach(box => {
    const svg = box.querySelector('.progresso-svg');
    const progress = box.getAttribute('data-progress') || 0;
    if (svg) updateProgressBar(svg, progress);
  });
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

if (confirmarSair) {
  confirmarSair.addEventListener('click', () => {
    window.location.href = 'logout.php';
  });
}

if (cancelarSair) {
  cancelarSair.addEventListener('click', () => {
    modalSairOverlay.style.display = 'none';
  });
}

window.addEventListener('click', (e) => {
  if (e.target == modalSairOverlay) {
    modalSairOverlay.style.display = 'none';
  }
});
</script>

<style>
/* Estilos dos novos botões */
/* NOVO CSS PARA OS BOTÕES DE AÇÃO */
/* Contêiner para envolver os botões de ação */
.container-botoes-acao {
    display: flex; /* ESSA LINHA COLOCA OS FILHOS LADO A LADO */
    justify-content: center; /* Centraliza o grupo de botões na caixa do filho */
    gap: 3px;Aumentei o espaçamento entre o botão jogar e deletar */
    margin-top: 10px; 
    position: relative; 
    z-index: 10; 
}

/* 2. Estilo para os ícones (apenas cinza, sem fundo) */
.botao-deletar, .botao-jogar {
    width: 30px; 
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    
    background: none !important; /* Força a remoção do background */
    border: none !important;     /* Força a remoção da borda */
    box-shadow: none !important; /* Força a remoção da sombra */
    padding: 0; 
    
    color: #616161 !important; /* Força a cor cinza no ícone (btn-deletar / btn-jogar) */
    
    position: static; /* Deve ser 'static' para respeitar o flexbox */
}

/* 3. Efeitos de hover */
.botao-deletar:hover, .botao-jogar:hover {
    color: #333333 !important; 
    transform: scale(1.1);
}

/* 4. Ajustes nos outros elementos */
.pontos-filho {
    font-size: 13px;
    color: #791dcf;
    font-weight: 600;
    margin-top: 3px;
    text-align: center; 
}
.idade-filho {
    font-size: 14px;
    color: #666;
    text-align: center;
}

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
    
    .nome-filho {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 10px 0 5px;
}

/* Adicionei este estilo caso o CSS externo não tenha */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
    /* Estas 2 linhas garantem que o conteúdo fique no centro se o display for flex */
    justify-content: center;
    align-items: center;
}

</style>

<script>
// JavaScript para destacar criança selecionada
document.addEventListener('DOMContentLoaded', function() {
    // Pega ID da criança selecionada da sessão PHP
    const idCriancaSelecionada = <?php echo isset($_SESSION['id_crianca']) ? $_SESSION['id_crianca'] : 'null'; ?>;
    
    if (idCriancaSelecionada) {
        destacarCriancaSelecionada(idCriancaSelecionada);
        criarInfoCriancaSelecionada(idCriancaSelecionada);
    }
});

function destacarCriancaSelecionada(idCrianca) {
    const caixas = document.querySelectorAll('.caixa-filho');
    
    caixas.forEach(caixa => {
        const id = parseInt(caixa.getAttribute('data-id'));
        
        if (id === parseInt(idCrianca)) {
            caixa.classList.add('selecionada');
            
            // Scroll suave até a criança selecionada
            setTimeout(() => {
                caixa.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center',
                    inline: 'center'
                });
            }, 300);
        } else {
            caixa.classList.remove('selecionada');
        }
    });
}

function criarInfoCriancaSelecionada(idCrianca) {
    const caixa = document.querySelector(`.caixa-filho[data-id="${idCrianca}"]`);
    if (!caixa) return;

    const nome = caixa.getAttribute('data-nome');
    const idade = caixa.getAttribute('data-idade');
    const avatar = caixa.getAttribute('data-avatar');
    const progresso = caixa.getAttribute('data-progress');
    const pontos = caixa.querySelector('.pontos-filho')?.textContent || '0 pontos';

    // Cria seção de informações
    const infoSection = document.createElement('div');
    infoSection.className = 'info-crianca-selecionada';
    infoSection.innerHTML = `
        <div class="info-header">
            <img src="Img/${avatar}" alt="${nome}" class="info-avatar">
            <div class="info-dados">
                <h2>${nome}</h2>
                <p>${idade} anos • Criança Ativa</p>
            </div>
        </div>
        <div class="info-stats">
            <div class="stat-item">
                <h4>Progresso Geral</h4>
                <p class="valor">${progresso}%</p>
            </div>
            <div class="stat-item">
                <h4>Pontos Totais</h4>
                <p class="valor">${pontos}</p>
            </div>
            <div class="stat-item">
                <h4>Status</h4>
                <p class="valor"><i class="fa-solid fa-gamepad"></i>
 Jogando</p>
            </div>
        </div>
    `;

    // Insere após a área de filhos
    const areaFilhos = document.querySelector('.filhos');
    if (areaFilhos) {
        areaFilhos.insertAdjacentElement('afterend', infoSection);
        setTimeout(() => infoSection.classList.add('ativa'), 100);
    }
}

// Atualiza quando selecionar nova criança
function selecionarCrianca(criancaId) {
    if (!criancaId) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'selecionar_crianca.php';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'crianca_id';
    input.value = criancaId;
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>