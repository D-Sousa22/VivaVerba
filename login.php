<?php
session_start();
include('Api/vlibras.html');

$servername = "localhost";
$port = 3306;
$username = "u358404112_verbovivo";
$password = "VivaVerba2025";
$dbname = "u358404112_vivaverba";

$error = '';

if (!empty($_POST)) {
  try {
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Buscar usuário pelo email - AGORA PEGANDO O EMAIL TAMBÉM
    $stmt = $conn->prepare("SELECT id, nome_responsavel, email, senha FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($senha, $user['senha'])) {
      $error = 'E-mail ou senha incorretos.';
    } else {
      // Login bem-sucedido - SALVANDO O EMAIL NA SESSÃO
      session_regenerate_id(true);
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['nome_responsavel'];
      $_SESSION['user_email'] = $user['email']; // CORREÇÃO: Agora salva o email
      
      // Verifica se é admin e redireciona
      $superadmins = ['admin@vivaverba.com', 'pedrohenriquehtmtanjiro@gmail.com'];
      if (in_array($user['email'], $superadmins)) {
        header('Location: admin.php');
      } else {
        header('Location: index.php');
      }
      exit;
    }
  } catch (PDOException $e) {
    $error = 'Erro ao conectar: ' . $e->getMessage();
  } finally {
    $conn = null;
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <script src="https://cdn.userway.org/widget.js" data-account="5Oy3ihG84d"></script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - VivaVerba</title>
  <link rel="stylesheet" href="Css/login.css" />
  <!-- Importando ícones do Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Css/acessibilidade-extra.css">
</head>
<body>
  <!-- Botão Voltar -->
  <a href="index.php" class="botao-voltar" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i></a>

  <div class="botao-container">
    <a href="cadastro.php" class="botao-criar-conta">
      <img src="Img/icone-entrar.png" alt="Ícone" />
      <span>Criar Conta</span>
    </a>
  </div>

  <div class="container">
    <!-- Imagem à esquerda -->
    <div class="imagem-area">
      <img src="Img/luiz felipe e julia.png" alt="Imagem ilustrativa">
    </div>

    <!-- Login à direita -->
    <div class="login-area">
      <h2>Bem vindo(a) de<br> volta!</h2>

      <?php if ($error): ?>
        <div class="erro-login" style="color:#b00020; margin-bottom:10px; padding:10px; background:#ffebee; border-radius:5px;">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST" novalidate>
        <input type="email" name="email" placeholder="Seu e-mail" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

        <div class="senha-container">
          <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
          <i class="fa-solid fa-eye-slash toggle-senha" id="toggleSenha"></i>
        </div>

        <button type="submit">Entrar</button>
      </form>

      <a href="recuperar.php" class="esqueci-senha">Esqueceu a senha?</a>
      <p class="texto-abaixo"> Ao entrar no VivaVerba você concorda com os nossos <a href="#">Termos</a>
        e <a href="#">Política de privacidade</a></p>
    </div>
  </div>

  <script>
    const senhaInput = document.getElementById("senha");
    const toggle = document.getElementById("toggleSenha");

    toggle.addEventListener("click", () => {
      if (senhaInput.type === "password") {
        senhaInput.type = "text";
        toggle.classList.remove("fa-eye-slash");
        toggle.classList.add("fa-eye");
      } else {
        senhaInput.type = "password";
        toggle.classList.remove("fa-eye");
        toggle.classList.add("fa-eye-slash");
      }
    });
  </script>

  <!-- Rodapé -->
  <footer class="rodape">
    <div class="logo-rodape">
      <img src="Img/logo2.png" alt="Logo VivaVerba">
    </div>
    <div class="links-rodape">
      <a href="#">Política de Privacidade</a>
      <a href="#">Termos de Uso</a>
      <a href="#">Política de Cookies</a>
      <a href="contato.php">Contato</a>
    </div>
    <div class="redes-rodape">
      <a>Nos siga nas redes</a>
      <a href="#"><img src="Img/youtube.png" alt="YouTube"> Youtube</a>
      <a href="#"><img src="Img/instagram.png" alt="Instagram"> Instagram </a>
      <a href="#"><img src="Img/facebook.png" alt="Facebook" style="width: 11px; margin-left: 3px; "> Facebook</a>
    </div>
  </footer>
  
  <script src="Js/acessibilidade.js"></script>
</body>
</html>