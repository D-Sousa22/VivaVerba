<?php
/**
 * ARQUIVO DE DIAGNÓSTICO - VivaVerba
 * 
 * Cole este arquivo na RAIZ do projeto como "diagnostico_sessao.php"
 * Acesse: http://seu-site.com/diagnostico_sessao.php
 * 
 * Este arquivo mostra os dados da sessão e testa a API
 */

session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Sessão - VivaVerba</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #252526;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }
        h1 {
            color: #4fc3f7;
            border-bottom: 2px solid #4fc3f7;
            padding-bottom: 10px;
        }
        h2 {
            color: #81c784;
            margin-top: 30px;
        }
        .info-block {
            background: #1e1e1e;
            border-left: 4px solid #4fc3f7;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .success {
            border-left-color: #81c784;
            background: #1b3e1f;
        }
        .error {
            border-left-color: #e57373;
            background: #3e1f1f;
        }
        .warning {
            border-left-color: #ffb74d;
            background: #3e341f;
        }
        .key {
            color: #9cdcfe;
            font-weight: bold;
        }
        .value {
            color: #ce9178;
        }
        .null {
            color: #569cd6;
            font-style: italic;
        }
        .btn {
            display: inline-block;
            background: #4fc3f7;
            color: #1e1e1e;
            padding: 10px 20px;
            margin: 10px 5px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #29b6f6;
        }
        .btn-danger {
            background: #e57373;
        }
        .btn-danger:hover {
            background: #ef5350;
        }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border: 1px solid #3e3e42;
        }
        .api-test {
            margin: 20px 0;
        }
        #api-result {
            min-height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico de Sessão - VivaVerba</h1>
        
        <h2>📊 Status da Sessão</h2>
        
        <?php
        $sessionActive = isset($_SESSION['user_id']);
        $criancaSelecionada = isset($_SESSION['id_crianca']);
        ?>
        
        <div class="info-block <?php echo $sessionActive ? 'success' : 'error'; ?>">
            <strong>Status do Login:</strong> 
            <?php echo $sessionActive ? '✓ Usuário logado' : '✗ Usuário NÃO logado'; ?>
        </div>
        
        <div class="info-block <?php echo $criancaSelecionada ? 'success' : 'error'; ?>">
            <strong>Status da Criança:</strong> 
            <?php echo $criancaSelecionada ? '✓ Criança selecionada' : '✗ Criança NÃO selecionada'; ?>
        </div>

        <h2>📝 Dados da Sessão</h2>
        
        <div class="info-block">
            <pre><?php
            echo "<span class='key'>Session ID:</span> <span class='value'>" . session_id() . "</span>\n\n";
            
            $sessionVars = [
                'user_id' => 'ID do Responsável',
                'id_crianca' => 'ID da Criança',
                'nome_crianca' => 'Nome da Criança',
                'idade_crianca' => 'Idade da Criança',
                'avatar_crianca' => 'Avatar da Criança'
            ];
            
            foreach ($sessionVars as $var => $label) {
                $value = isset($_SESSION[$var]) ? $_SESSION[$var] : null;
                echo "<span class='key'>{$label} (\${$var}):</span> ";
                if ($value !== null) {
                    echo "<span class='value'>{$value}</span>";
                } else {
                    echo "<span class='null'>NULL (não definido)</span>";
                }
                echo "\n";
            }
            
            echo "\n<span class='key'>Todas as variáveis de sessão:</span>\n";
            print_r($_SESSION);
            ?></pre>
        </div>

        <h2>🎮 Teste da API de Jogos</h2>
        
        <div class="api-test">
            <button class="btn" onclick="testarAPI()">🔄 Testar API de Jogos</button>
            <button class="btn" onclick="testarAPICompleta()">📋 Testar API Completa</button>
            
            <div id="api-result" class="info-block" style="margin-top: 15px; display: none;">
                <h3>Resultado:</h3>
                <pre id="api-response"></pre>
            </div>
        </div>

        <h2>🔧 Ações</h2>
        
        <div>
            <?php if (!$criancaSelecionada): ?>
                <a href="parental.php" class="btn">👶 Selecionar Criança</a>
            <?php endif; ?>
            
            <?php if ($sessionActive): ?>
                <a href="logout.php" class="btn btn-danger">🚪 Fazer Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">🔐 Fazer Login</a>
            <?php endif; ?>
            
            <a href="atv1.php" class="btn">🎯 Ir para Atividades 5-7</a>
            <a href="atv2.php" class="btn">🚀 Ir para Atividades 8-10</a>
        </div>

        <h2>💡 Diagnóstico</h2>
        
        <?php if (!$sessionActive): ?>
            <div class="info-block error">
                <strong>⚠️ PROBLEMA:</strong> Você não está logado.<br>
                <strong>SOLUÇÃO:</strong> Faça login em <a href="login.php" style="color: #4fc3f7;">login.php</a>
            </div>
        <?php elseif (!$criancaSelecionada): ?>
            <div class="info-block warning">
                <strong>⚠️ PROBLEMA:</strong> Nenhuma criança selecionada.<br>
                <strong>SOLUÇÃO:</strong> Vá para <a href="parental.php" style="color: #4fc3f7;">parental.php</a> e selecione uma criança clicando no botão "Jogar" 🎮
            </div>
        <?php else: ?>
            <div class="info-block success">
                <strong>✓ TUDO OK!</strong> A sessão está configurada corretamente.<br>
                Os jogos devem carregar normalmente em atv1.php ou atv2.php
            </div>
        <?php endif; ?>

        <h2>🗄️ Verificação do Banco de Dados</h2>
        
        <?php
        try {
            $servername = "localhost";
            $port = 3306;
            $username = "u358404112_verbovivo";
            $password = "VivaVerba2025";
            $dbname = "u358404112_vivaverba";

            $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
            $conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            echo '<div class="info-block success">';
            echo '<strong>✓ Conexão com o banco:</strong> OK<br>';
            
            if ($criancaSelecionada) {
                $stmt = $conn->prepare("SELECT * FROM criancas WHERE id = :id");
                $stmt->execute([':id' => $_SESSION['id_crianca']]);
                $crianca = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($crianca) {
                    echo '<br><strong>Dados da criança no banco:</strong><br>';
                    echo '<pre>';
                    print_r($crianca);
                    echo '</pre>';
                    
                    // Verifica jogos completados
                    $stmt = $conn->prepare("SELECT * FROM jogos_completados WHERE id_crianca = :id");
                    $stmt->execute([':id' => $_SESSION['id_crianca']]);
                    $jogosCompletos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo '<br><strong>Jogos completados:</strong> ' . count($jogosCompletos) . '<br>';
                    if (count($jogosCompletos) > 0) {
                        echo '<pre>';
                        print_r($jogosCompletos);
                        echo '</pre>';
                    }
                }
            }
            
            echo '</div>';

        } catch (PDOException $e) {
            echo '<div class="info-block error">';
            echo '<strong>✗ Erro de conexão:</strong> ' . htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>

        <h2>📂 Verificação de Arquivos</h2>
        
        <?php
        $arquivos = [
            'api_jogos_desbloqueados.php' => 'API de Jogos',
            'api_pontos.php' => 'API de Pontos',
            'Jogos/Jogo1.php' => 'Jogo 1',
            'Jogos/jogo2.php' => 'Jogo 2',
            'Jogos/jogo3.php' => 'Jogo 3',
            'Jogos/jogo4.php' => 'Jogo 4',
            'Jogos/jogo5.php' => 'Jogo 5',
            'Jogos/jogo6.php' => 'Jogo 6'
        ];
        
        foreach ($arquivos as $arquivo => $nome) {
            $existe = file_exists($arquivo);
            echo '<div class="info-block ' . ($existe ? 'success' : 'error') . '">';
            echo $existe ? '✓' : '✗';
            echo " <strong>{$nome}:</strong> {$arquivo} - ";
            echo $existe ? 'Encontrado' : 'NÃO encontrado';
            echo '</div>';
        }
        ?>
    </div>

    <script>
        async function testarAPI() {
            const resultDiv = document.getElementById('api-result');
            const responseDiv = document.getElementById('api-response');
            
            resultDiv.style.display = 'block';
            responseDiv.textContent = 'Carregando...';
            
            try {
                const response = await fetch('api_jogos_desbloqueados.php');
                const data = await response.json();
                
                responseDiv.textContent = JSON.stringify(data, null, 2);
                
                if (data.success) {
                    resultDiv.className = 'info-block success';
                } else {
                    resultDiv.className = 'info-block error';
                }
            } catch (error) {
                resultDiv.className = 'info-block error';
                responseDiv.textContent = 'ERRO: ' + error.message;
            }
        }
        
        async function testarAPICompleta() {
            const resultDiv = document.getElementById('api-result');
            const responseDiv = document.getElementById('api-response');
            
            resultDiv.style.display = 'block';
            responseDiv.textContent = 'Testando...\n\n';
            
            try {
                // Testa API de jogos
                responseDiv.textContent += '=== TESTE 1: API de Jogos ===\n';
                const response1 = await fetch('api_jogos_desbloqueados.php');
                const data1 = await response1.json();
                responseDiv.textContent += 'Status: ' + response1.status + '\n';
                responseDiv.textContent += JSON.stringify(data1, null, 2) + '\n\n';
                
                // Verifica console de erros
                responseDiv.textContent += '=== Verifique o Console do Navegador (F12) para mais detalhes ===\n';
                console.log('Resposta da API:', data1);
                
            } catch (error) {
                resultDiv.className = 'info-block error';
                responseDiv.textContent += '\nERRO: ' + error.message;
            }
        }
    </script>
</body>
</html>