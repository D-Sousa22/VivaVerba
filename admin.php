<?php
session_start();

// Lista de emails de superadmins
$superadmins = [
    'admin@vivaverba.com',
    'pedrohenriquehtmtanjiro@gmail.com'
];

// Verifica se usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Buscar email do usuário logado no banco de dados
$servername = "localhost";
$port = 3306;
$username = "u358404112_verbovivo";
$password = "VivaVerba2025";
$dbname = "u358404112_vivaverba";

try {
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Buscar email do usuário logado
    $stmt = $conn->prepare("SELECT email FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: login.php');
        exit;
    }
    
    $userEmail = $user['email'];
    
    // Verifica se é superadmin
    if (!in_array($userEmail, $superadmins)) {
        die('<html><head><meta charset="UTF-8"><style>
            body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f5f7fa; }
            .error-box { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; }
            .error-box h2 { color: #e53e3e; margin-bottom: 20px; }
            .error-box a { display: inline-block; margin-top: 20px; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; }
            .error-box a:hover { background: #5568d3; }
        </style>
        <link rel="stylesheet" href="Css/acessibilidade-extra.css">
        </head>
        <body>
            <div class="error-box">
                <h2>🚫 Acesso Negado</h2>
                <p>Apenas administradores podem acessar esta página.</p>
                <p><strong>Seu email:</strong> ' . htmlspecialchars($userEmail) . '</p>
                <a href="index.php">← Voltar para o Início</a>
            </div> 
            <script src="Js/acessibilidade.js"></script>
        </body></html>');
    }

    // Estatísticas gerais
    $stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios");
    $totalUsuarios = $stmt->fetch()['total'];

    $stmt = $conn->query("SELECT COUNT(*) as total FROM criancas");
    $totalCriancas = $stmt->fetch()['total'];

    $stmt = $conn->query("SELECT COUNT(*) as total FROM jogos_completados");
    $totalJogosCompletos = $stmt->fetch()['total'];

    $stmt = $conn->query("SELECT SUM(pontos_totais) as total FROM criancas");
    $totalPontos = $stmt->fetch()['total'] ?? 0;

    // Usuários recentes
    $stmt = $conn->query("SELECT * FROM usuarios ORDER BY data_cadastro DESC LIMIT 10");
    $usuariosRecentes = $stmt->fetchAll();

    // Crianças mais ativas
    $stmt = $conn->query("
        SELECT c.*, u.nome_responsavel 
        FROM criancas c 
        LEFT JOIN usuarios u ON c.id_responsavel = u.id 
        ORDER BY c.pontos_totais DESC 
        LIMIT 10
    ");
    $criancasAtivas = $stmt->fetchAll();

    // Atividade recente
    $stmt = $conn->query("
        SELECT jc.*, c.nome, u.nome_responsavel 
        FROM jogos_completados jc
        LEFT JOIN criancas c ON jc.id_crianca = c.id
        LEFT JOIN usuarios u ON c.id_responsavel = u.id
        ORDER BY jc.data_conclusao DESC 
        LIMIT 15
    ");
    $atividadeRecente = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - VivaVerba</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: #2d3748;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .admin-header h1 {
            font-size: 2em;
            margin-bottom: 5px;
        }

        .admin-header p {
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card.purple .icon {
            background: #e9d8fd;
            color: #805ad5;
        }

        .stat-card.blue .icon {
            background: #bee3f8;
            color: #3182ce;
        }

        .stat-card.green .icon {
            background: #c6f6d5;
            color: #38a169;
        }

        .stat-card.orange .icon {
            background: #fed7d7;
            color: #e53e3e;
        }

        .stat-card .value {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card .label {
            color: #718096;
            font-size: 0.9em;
        }

        .data-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .data-section h2 {
            font-size: 1.4em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-section h2 i {
            color: #667eea;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f7fafc;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.85em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 12px;
            border-top: 1px solid #e2e8f0;
        }

        table tbody tr:hover {
            background: #f7fafc;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .badge.success {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge.info {
            background: #bee3f8;
            color: #2c5282;
        }

        .badge.warning {
            background: #feebc8;
            color: #7c2d12;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-danger {
            background: #fc8181;
            color: white;
        }

        .btn-danger:hover {
            background: #f56565;
        }

        .btn-logout {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-logout:hover {
            background: #667eea;
            color: white;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1em;
            transition: border-color 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.85em;
            }

            .btn-logout {
                position: static;
                margin-bottom: 20px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="btn btn-logout">
        <i class="fas fa-sign-out-alt"></i> Sair
    </a>

    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-shield-alt"></i> Painel Administrativo</h1>
            <p>Bem-vindo, <?php echo htmlspecialchars($userEmail); ?></p>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card purple">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="value"><?php echo number_format($totalUsuarios); ?></div>
                <div class="label">Total de Usuários</div>
            </div>

            <div class="stat-card blue">
                <div class="icon"><i class="fas fa-child"></i></div>
                <div class="value"><?php echo number_format($totalCriancas); ?></div>
                <div class="label">Crianças Cadastradas</div>
            </div>

            <div class="stat-card green">
                <div class="icon"><i class="fas fa-gamepad"></i></div>
                <div class="value"><?php echo number_format($totalJogosCompletos); ?></div>
                <div class="label">Jogos Completados</div>
            </div>

            <div class="stat-card orange">
                <div class="icon"><i class="fas fa-star"></i></div>
                <div class="value"><?php echo number_format($totalPontos); ?></div>
                <div class="label">Pontos Totais</div>
            </div>
        </div>

        <!-- Usuários Recentes -->
        <div class="data-section">
            <h2><i class="fas fa-user-plus"></i> Usuários Recentes</h2>
            <div class="search-box">
                <input type="text" id="searchUsuarios" placeholder="🔍 Buscar usuários...">
            </div>
            <table id="tableUsuarios">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuariosRecentes as $usuario): ?>
                    <tr>
                        <td>#<?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome_responsavel']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($usuario['data_cadastro'])); ?></td>
                        <td class="actions">
                            <button class="btn btn-primary" onclick="viewUser(<?php echo $usuario['id']; ?>)">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Crianças Mais Ativas -->
        <div class="data-section">
            <h2><i class="fas fa-trophy"></i> Ranking de Crianças</h2>
            <table>
                <thead>
                    <tr>
                        <th>Posição</th>
                        <th>Nome</th>
                        <th>Responsável</th>
                        <th>Pontos</th>
                        <th>Progresso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($criancasAtivas as $index => $crianca): ?>
                    <tr>
                        <td>
                            <?php if ($index === 0): ?>
                                <span class="badge" style="background: #ffd700; color: #7c2d12;">🥇 1º</span>
                            <?php elseif ($index === 1): ?>
                                <span class="badge" style="background: #c0c0c0; color: #2d3748;">🥈 2º</span>
                            <?php elseif ($index === 2): ?>
                                <span class="badge" style="background: #cd7f32; color: white;">🥉 3º</span>
                            <?php else: ?>
                                <?php echo $index + 1; ?>º
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($crianca['nome']); ?></td>
                        <td><?php echo htmlspecialchars($crianca['nome_responsavel']); ?></td>
                        <td><span class="badge success"><?php echo number_format($crianca['pontos_totais']); ?> pts</span></td>
                        <td><span class="badge info"><?php echo $crianca['progresso']; ?>%</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Atividade Recente -->
        <div class="data-section">
            <h2><i class="fas fa-history"></i> Atividade Recente</h2>
            <table>
                <thead>
                    <tr>
                        <th>Criança</th>
                        <th>Responsável</th>
                        <th>Jogo</th>
                        <th>Pontos</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atividadeRecente as $atividade): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($atividade['nome']); ?></td>
                        <td><?php echo htmlspecialchars($atividade['nome_responsavel']); ?></td>
                        <td><span class="badge info"><?php echo strtoupper($atividade['jogo_id']); ?></span></td>
                        <td><span class="badge success"><?php echo $atividade['pontos_obtidos']; ?> pts</span></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($atividade['data_conclusao'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Busca em tempo real
        document.getElementById('searchUsuarios').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tableUsuarios tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function viewUser(userId) {
            alert('Funcionalidade de visualização de usuário #' + userId);
            // Aqui você pode adicionar um modal ou redirecionar para página de detalhes
        }
    </script>
</body>
</html>