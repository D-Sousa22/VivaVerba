<?php
session_start();
include('Api/vlibras.html');

$servername = "localhost";
$port = 3306;
$username = "u358404112_verbovivo";
$password = "VivaVerba2025";
$dbname = "u358404112_vivaverba";

$erro_senha = false;
$sucesso = false;

if (!empty($_POST)) {
  $nome_responsavel = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $senha = $_POST['senha'] ?? '';
  $confirmar = $_POST['confirmar_senha'] ?? '';

  if ($senha !== $confirmar) {
      $erro_senha = "As senhas não conferem!";
  } 
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $erro_senha = "Por favor, insira um e-mail válido!";
  }
  else {
    try {
      $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      
      if ($stmt->rowCount() > 0) {
          $erro_senha = "Este e-mail já está cadastrado!";
      } else {
          if (strlen($senha) < 6) {
              $erro_senha = "A senha deve ter no mínimo 6 caracteres!";
          } else {
              $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

              $stmt = $conn->prepare("
                  INSERT INTO usuarios (nome_responsavel, email, senha)
                  VALUES (:nome_responsavel, :email, :senha)
              ");
              $stmt->bindParam(':nome_responsavel', $nome_responsavel);
              $stmt->bindParam(':email', $email);
              $stmt->bindParam(':senha', $senha_hash);
              $stmt->execute();

              $sucesso = "Cadastro realizado com sucesso!";
          }
      }
    } catch (Exception $e) {
      $erro_senha = "Erro ao cadastrar. Tente novamente.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <script src="https://cdn.userway.org/widget.js" data-account="5Oy3ihG84d"></script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro - Viva Verba</title>
  <link rel="stylesheet" href="Css/cadastro.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://unpkg.com/validator@latest/validator.min.js"></script>
  
  <style>
    /* Estilos adicionais para validação */
    .form-group {
      position: relative;
      margin-bottom: 8px;
      width: 100%;
    }

    .form-group input {
      width: 100%;
      margin-bottom: 0 !important;
    }

    .input-erro {
      display: block;
      color: #dc3545;
      font-size: 0.75rem;
      margin: 3px 0 5px 0;
      min-height: 16px;
      font-weight: 500;
    }

    .input-erro.sucesso {
      color: #28a745;
    }

    .input-icon {
      position: absolute;
      right: 15px;
      top: 18px;
      font-size: 1.1rem;
      pointer-events: none;
      z-index: 10;
    }

    .input-icon i {
      display: inline-block;
    }

    .input-icon.validando i {
      color: #ffa500;
      animation: spin 1s linear infinite;
    }

    .input-icon.valido i {
      color: #28a745;
    }

    .input-icon.invalido i {
      color: #dc3545;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .senha-forca {
      width: 100%;
      margin-top: 5px;
      margin-bottom: 3px;
    }

    .forca-barra {
      width: 100%;
      height: 5px;
      background: #e0e0e0;
      border-radius: 3px;
      overflow: hidden;
      margin-bottom: 5px;
    }

    .forca-progresso {
      height: 100%;
      width: 0%;
      transition: width 0.3s ease, background 0.3s ease;
      border-radius: 3px;
    }

    .forca-progresso.fraca {
      width: 33%;
      background: #dc3545;
    }

    .forca-progresso.media {
      width: 66%;
      background: #ffa500;
    }

    .forca-progresso.forte {
      width: 100%;
      background: #28a745;
    }

    .forca-texto {
      font-size: 0.75rem;
      font-weight: 600;
    }

    .forca-texto.fraca {
      color: #dc3545;
    }

    .forca-texto.media {
      color: #ffa500;
    }

    .forca-texto.forte {
      color: #28a745;
    }

    #btnCadastrar:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      background: #999 !important;
    }

    .senha-container {
      position: relative;
      width: 100%;
    }

    .login-area form button {
      margin-top: 15px !important;
    }
  </style>
  
  <link rel="stylesheet" href="Css/acessibilidade-extra.css">
</head>

<body>
  <a href="login.php" class="botao-voltar"><i class="fa-solid fa-arrow-left"></i></a>

  <div class="botao-container">
    <a href="login.php" class="botao-entrar">
      <img src="Img/icone-entrar.png" alt="Ícone" />
      <span>Entrar</span>
    </a>
  </div>

  <div class="container">
    <div class="imagem-area">
      <img src="Img/luiz felipe e julia.png" alt="Imagem ilustrativa">
    </div>

    <div class="login-area">
      <form action="#" method="POST" id="formCadastro">
        <h2>Seja bem-vindo(a) ao <br>Viva Verba!</h2>

        <div class="form-group">
          <input type="text" name="nome" id="nomeInput" placeholder="Nome do Responsável" required>
          <small class="input-erro" id="nomeErro"></small>
        </div>
        
        <div class="form-group">
          <input type="email" id="emailInput" name="email" placeholder="Seu e-mail" required>
          <span class="input-icon" id="emailIcon"></span>
          <small class="input-erro" id="emailErro"></small>
        </div>

        <div class="form-group">
          <div class="senha-container">
            <input type="password" id="senha1" name="senha" placeholder="Sua senha" required minlength="6">
            <i class="fa-solid fa-eye-slash toggle-senha" id="toggleSenha1"></i>
          </div>
          <small class="input-erro" id="senhaErro"></small>
          <div class="senha-forca">
            <div class="forca-barra">
              <div class="forca-progresso" id="forcaProgresso"></div>
            </div>
            <span class="forca-texto" id="forcaTexto"></span>
          </div>
        </div>

        <div class="form-group">
          <div class="senha-container">
            <input type="password" id="senha2" name="confirmar_senha" placeholder="Confirmar senha" required minlength="6">
            <i class="fa-solid fa-eye-slash toggle-senha" id="toggleSenha2"></i>
          </div>
          <small class="input-erro" id="confirmarErro"></small>
        </div>

        <button type="submit" id="btnCadastrar">Cadastrar</button>

        <p class="texto-abaixo">
          Ao entrar no Viva Verba você concorda com os nossos
          <a href="termos.php">Termos</a> e <a href="politicas-privacidade.php">Política de privacidade</a>.
        </p>
      </form>
    </div>
  </div>

  <footer class="rodape">
    <div class="logo-rodape">
      <img src="Img/logo2.png" alt="Logo Viva Verba">
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
      <a href="#"><img src="Img/instagram.png" alt="Instagram"> Instagram</a>
      <a href="#"><img src="Img/facebook.png" alt="Facebook" style="width: 11px; margin-left: 3px;"> Facebook</a>
    </div>
  </footer>
  
  <script src="Js/acessibilidade.js"></script>

  <div id="modal-erro" class="modal-erro" style="<?php echo $erro_senha ? 'display:flex' : 'display:none'; ?>">
    <div class="modal-erro-content">
      <h3><?php echo $erro_senha; ?></h3>
      <a href="cadastro.php" class="btn-fechar-erro">Fechar</a>
    </div>
  </div>

  <div id="modal-sucesso" class="modal-sucesso" style="<?php echo $sucesso ? 'display:flex' : 'display:none'; ?>">
    <div class="modal-sucesso-content">
      <h3><?php echo $sucesso; ?></h3>
      <a href="login.php" class="btn-sucesso">Ir para o Login</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Script carregado');
      
      // Verifica se Validator.js carregou
      if (typeof validator === 'undefined') {
        console.error('Validator.js não foi carregado!');
        alert('Erro ao carregar biblioteca de validação. Recarregue a página.');
        return;
      }

      console.log('Validator.js carregado com sucesso');

      // Configurar toggle de senha
      function setupToggle(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (!input || !icon) {
          console.error('Elementos não encontrados:', inputId, iconId);
          return;
        }
        
        icon.addEventListener('click', function() {
          if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          }
        });
      }

      setupToggle('senha1', 'toggleSenha1');
      setupToggle('senha2', 'toggleSenha2');

      // Elementos do formulário
      const nomeInput = document.getElementById('nomeInput');
      const nomeErro = document.getElementById('nomeErro');
      const emailInput = document.getElementById('emailInput');
      const emailIcon = document.getElementById('emailIcon');
      const emailErro = document.getElementById('emailErro');
      const senha1 = document.getElementById('senha1');
      const senha2 = document.getElementById('senha2');
      const senhaErro = document.getElementById('senhaErro');
      const confirmarErro = document.getElementById('confirmarErro');
      const forcaProgresso = document.getElementById('forcaProgresso');
      const forcaTexto = document.getElementById('forcaTexto');
      const btnCadastrar = document.getElementById('btnCadastrar');
      const formCadastro = document.getElementById('formCadastro');

      // Estado de validação
      let validacoes = {
        nome: false,
        email: false,
        emailDisponivel: false,
        senha: false,
        confirma: false
      };

      // Função para verificar se pode habilitar o botão
      function verificarBotao() {
        const tudoValido = validacoes.nome && 
                          validacoes.email && 
                          validacoes.emailDisponivel && 
                          validacoes.senha && 
                          validacoes.confirma;
        
        btnCadastrar.disabled = !tudoValido;
        console.log('Estado das validações:', validacoes, 'Botão habilitado:', tudoValido);
      }

      // Validação do Nome
      nomeInput.addEventListener('input', function() {
        const nome = this.value.trim();
        
        if (nome.length === 0) {
          nomeErro.textContent = '';
          validacoes.nome = false;
        } else if (!validator.isLength(nome, { min: 3 })) {
          nomeErro.textContent = 'O nome deve ter no mínimo 3 caracteres';
          nomeErro.classList.remove('sucesso');
          validacoes.nome = false;
        } else {
          nomeErro.textContent = '';
          validacoes.nome = true;
        }
        
        verificarBotao();
      });

      // Validação do Email
      let emailTimeout;
      emailInput.addEventListener('input', function() {
        clearTimeout(emailTimeout);
        const email = this.value.trim();
        
        if (email.length === 0) {
          emailIcon.innerHTML = '';
          emailIcon.className = 'input-icon';
          emailErro.textContent = '';
          emailErro.classList.remove('sucesso');
          validacoes.email = false;
          validacoes.emailDisponivel = false;
          verificarBotao();
          return;
        }

        // Mostra spinner
        emailIcon.innerHTML = '<i class="fas fa-spinner"></i>';
        emailIcon.className = 'input-icon validando';
        emailErro.textContent = 'Verificando...';
        emailErro.classList.remove('sucesso');
        emailErro.style.color = '#ffa500';

        emailTimeout = setTimeout(() => {
          validarEmail(email);
        }, 800);
      });

      function validarEmail(email) {
        console.log('Validando email:', email);
        
        // Valida formato
        if (!validator.isEmail(email)) {
          emailIcon.innerHTML = '<i class="fas fa-times"></i>';
          emailIcon.className = 'input-icon invalido';
          emailErro.textContent = 'Formato de e-mail inválido';
          emailErro.classList.remove('sucesso');
          emailErro.style.color = '#dc3545';
          validacoes.email = false;
          validacoes.emailDisponivel = false;
          verificarBotao();
          return;
        }

        // Valida domínio
        const dominio = email.split('@')[1];
        if (!validator.isFQDN(dominio)) {
          emailIcon.innerHTML = '<i class="fas fa-times"></i>';
          emailIcon.className = 'input-icon invalido';
          emailErro.textContent = 'Domínio de e-mail inválido';
          emailErro.classList.remove('sucesso');
          emailErro.style.color = '#dc3545';
          validacoes.email = false;
          validacoes.emailDisponivel = false;
          verificarBotao();
          return;
        }

        validacoes.email = true;

        // Verifica disponibilidade no banco
        fetch('verificar_email.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ email: email })
        })
        .then(response => {
          console.log('Resposta recebida:', response);
          return response.json();
        })
        .then(data => {
          console.log('Dados recebidos:', data);
          
          if (data.existe === true) {
            emailIcon.innerHTML = '<i class="fas fa-times"></i>';
            emailIcon.className = 'input-icon invalido';
            emailErro.textContent = 'Este e-mail já está cadastrado';
            emailErro.classList.remove('sucesso');
            emailErro.style.color = '#dc3545';
            validacoes.emailDisponivel = false;
          } else {
            emailIcon.innerHTML = '<i class="fas fa-check"></i>';
            emailIcon.className = 'input-icon valido';
            emailErro.textContent = '✓ E-mail disponível';
            emailErro.classList.add('sucesso');
            emailErro.style.color = '#28a745';
            validacoes.emailDisponivel = true;
          }
          
          verificarBotao();
        })
        .catch(error => {
          console.error('Erro ao verificar email:', error);
          // Em caso de erro, permite continuar
          emailIcon.innerHTML = '<i class="fas fa-check"></i>';
          emailIcon.className = 'input-icon valido';
          emailErro.textContent = '';
          emailErro.classList.remove('sucesso');
          validacoes.emailDisponivel = true;
          verificarBotao();
        });
      }

      // Validação da Senha
      senha1.addEventListener('input', function() {
        const senha = this.value;
        
        if (senha.length === 0) {
          senhaErro.textContent = '';
          forcaProgresso.className = 'forca-progresso';
          forcaProgresso.style.width = '0%';
          forcaTexto.textContent = '';
          forcaTexto.className = 'forca-texto';
          validacoes.senha = false;
          verificarBotao();
          return;
        }

        // Verifica tamanho mínimo
        if (!validator.isLength(senha, { min: 6 })) {
          senhaErro.textContent = 'A senha deve ter no mínimo 6 caracteres';
          senhaErro.classList.remove('sucesso');
          validacoes.senha = false;
          verificarBotao();
          return;
        }

        senhaErro.textContent = '';
        validacoes.senha = true;

        // Calcula força da senha
        let pontosForca = 0;
        
        if (validator.isLength(senha, { min: 8 })) pontosForca++;
        if (/[a-z]/.test(senha)) pontosForca++;
        if (/[A-Z]/.test(senha)) pontosForca++;
        if (/[0-9]/.test(senha)) pontosForca++;
        if (/[^a-zA-Z0-9]/.test(senha)) pontosForca++;

        if (pontosForca <= 2) {
          forcaProgresso.className = 'forca-progresso fraca';
          forcaTexto.className = 'forca-texto fraca';
          forcaTexto.textContent = 'Senha Fraca';
        } else if (pontosForca <= 4) {
          forcaProgresso.className = 'forca-progresso media';
          forcaTexto.className = 'forca-texto media';
          forcaTexto.textContent = 'Senha Média';
        } else {
          forcaProgresso.className = 'forca-progresso forte';
          forcaTexto.className = 'forca-texto forte';
          forcaTexto.textContent = 'Senha Forte';
        }

        // Verifica confirmação se já foi digitada
        if (senha2.value.length > 0) {
          verificarConfirmacaoSenha();
        }

        verificarBotao();
      });

      // Validação da Confirmação de Senha
      senha2.addEventListener('input', verificarConfirmacaoSenha);

      function verificarConfirmacaoSenha() {
        const senha = senha1.value;
        const confirmacao = senha2.value;

        if (confirmacao.length === 0) {
          confirmarErro.textContent = '';
          confirmarErro.classList.remove('sucesso');
          validacoes.confirma = false;
          verificarBotao();
          return;
        }

        if (!validator.equals(senha, confirmacao)) {
          confirmarErro.textContent = 'As senhas não conferem';
          confirmarErro.classList.remove('sucesso');
          confirmarErro.style.color = '#dc3545';
          validacoes.confirma = false;
        } else {
          confirmarErro.textContent = '✓ Senhas conferem';
          confirmarErro.classList.add('sucesso');
          confirmarErro.style.color = '#28a745';
          validacoes.confirma = true;
        }

        verificarBotao();
      }

      // Validação final ao enviar
      formCadastro.addEventListener('submit', function(e) {
        const nome = nomeInput.value.trim();
        const email = emailInput.value.trim();
        const senha = senha1.value;
        const confirmacao = senha2.value;

        if (!validator.isLength(nome, { min: 3 })) {
          e.preventDefault();
          alert('O nome deve ter no mínimo 3 caracteres');
          return false;
        }

        if (!validator.isEmail(email)) {
          e.preventDefault();
          alert('Por favor, insira um e-mail válido');
          return false;
        }

        if (!validator.isLength(senha, { min: 6 })) {
          e.preventDefault();
          alert('A senha deve ter no mínimo 6 caracteres');
          return false;
        }

        if (!validator.equals(senha, confirmacao)) {
          e.preventDefault();
          alert('As senhas não conferem');
          return false;
        }

        console.log('Formulário enviado com sucesso');
        return true;
      });

      // Inicia com botão desabilitado
      btnCadastrar.disabled = true;
      console.log('Inicialização completa');
    });
  </script>
</body>
</html>
<?php
// Configurações de resposta JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configurações do banco de dados
$servername = "localhost";
$port = 3306;
$username = "u358404112_verbovivo";
$password = "VivaVerba2025";
$dbname = "u358404112_vivaverba";

// Log para debug
error_log("verificar_email.php chamado");

try {
    // Recebe os dados
    $input = file_get_contents('php://input');
    error_log("Input recebido: " . $input);
    
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['email'])) {
        error_log("Dados inválidos ou email não fornecido");
        echo json_encode([
            'existe' => false, 
            'erro' => 'Dados inválidos'
        ]);
        exit;
    }
    
    $email = trim($data['email']);
    error_log("Email a verificar: " . $email);

    // Validação básica
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Email com formato inválido");
        echo json_encode([
            'existe' => false, 
            'erro' => 'Email inválido'
        ]);
        exit;
    }

    // Conexão com o banco
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    error_log("Conexão estabelecida com sucesso");

    // Consulta no banco
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $existe = ($stmt->rowCount() > 0);
    error_log("Email existe: " . ($existe ? 'sim' : 'não'));

    // Retorna resultado
    echo json_encode([
        'existe' => $existe,
        'email' => $email
    ]);

} catch (PDOException $e) {
    error_log("Erro PDO: " . $e->getMessage());
    echo json_encode([
        'existe' => false, 
        'erro' => 'Erro no banco de dados',
        'detalhe' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Erro geral: " . $e->getMessage());
    echo json_encode([
        'existe' => false, 
        'erro' => 'Erro no servidor',
        'detalhe' => $e->getMessage()
    ]);
}
?>