<?php
/**
 * TESTE RÁPIDO - Verificação de Jogos
 * 
 * Cole este arquivo na RAIZ como "teste_jogos.php"
 * Acesse: http://seu-site.com/teste_jogos.php
 */

session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Rápido - Jogos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            color: #667eea;
            margin-bottom: 20px;
        }
        .status {
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            font-weight: bold;
        }
        .success { background: #d4edda; color: #155724; border: 2px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 2px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 2px solid #ffeaa7; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5558d9;
            transform: translateY(-2px);
        }
        .test-result {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 13px;
        }
        .info { color: #666; font-size: 14px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎮 Teste Rápido - Sistema de Jogos</h1>
        
        <?php
        // TESTE 1: Verificar Sessão
        echo '<div class="test-result">';
        echo '<h3>1️⃣ Verificação de Sessão</h3>';
        
        if (isset($_SESSION['id_crianca']) && isset($_SESSION['idade_crianca'])) {
            echo '<div class="status success">✓ SESSÃO OK</div>';
            echo '<div class="info">';
            echo '<strong>ID Criança:</strong> ' . $_SESSION['id_crianca'] . '<br>';
            echo '<strong>Nome:</strong> ' . ($_SESSION['nome_crianca'] ?? 'Não definido') . '<br>';
            echo '<strong>Idade:</strong> ' . $_SESSION['idade_crianca'] . ' anos<br>';
            echo '</div>';
            
            $idade = (int)$_SESSION['idade_crianca'];
            if ($idade >= 5 && $idade <= 7) {
                echo '<div class="info">👉 Deve ver jogos em: <strong>atv1.php</strong></div>';
            } elseif ($idade >= 8 && $idade <= 10) {
                echo '<div class="info">👉 Deve ver jogos em: <strong>atv2.php</strong></div>';
            }
        } else {
            echo '<div class="status error">✗ NENHUMA CRIANÇA SELECIONADA</div>';
            echo '<div class="info">Vá para parental.php e clique no botão JOGAR 🎮</div>';
        }
        echo '</div>';

        // TESTE 2: Verificar Arquivos
        echo '<div class="test-result">';
        echo '<h3>2️⃣ Verificação de Arquivos</h3>';
        
        $arquivos = [
            'api_jogos_desbloqueados.php',
            'api_pontos.php',
            'atv1.php',
            'atv2.php'
        ];
        
        $todosOk = true;
        foreach ($arquivos as $arquivo) {
            if (file_exists($arquivo)) {
                echo '<div class="status success">✓ ' . $arquivo . '</div>';
            } else {
                echo '<div class="status error">✗ ' . $arquivo . ' NÃO ENCONTRADO</div>';
                $todosOk = false;
            }
        }
        echo '</div>';

        // TESTE 3: Teste da API
        echo '<div class="test-result">';
        echo '<h3>3️⃣ Teste da API de Jogos</h3>';
        echo '<button class="btn" onclick="testarAPI()">🔄 Testar Agora</button>';
        echo '<div id="api-result" style="display: none; margin-top: 15px;"></div>';
        echo '</div>';

        // TESTE 4: Banco de Dados
        echo '<div class="test-result">';
        echo '<h3>4️⃣ Conexão com Banco de Dados</h3>';
        
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

            echo '<div class="status success">✓ CONEXÃO OK</div>';
            
            if (isset($_SESSION['id_crianca'])) {
                $stmt = $conn->prepare("
                    SELECT jc.jogo_id, jc.pontos_obtidos, jc.data_conclusao
                    FROM jogos_completados jc
                    WHERE jc.id_crianca = :id
                    ORDER BY jc.data_conclusao DESC
                ");
                $stmt->execute([':id' => $_SESSION['id_crianca']]);
                $jogosCompletos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<div class="info">';
                echo '<strong>Jogos Completados:</strong> ' . count($jogosCompletos);
                if (count($jogosCompletos) > 0) {
                    echo '<ul style="margin-left: 20px; margin-top: 10px;">';
                    foreach ($jogosCompletos as $jogo) {
                        echo '<li>' . $jogo['jogo_id'] . ' - ' . $jogo['pontos_obtidos'] . ' pontos</li>';
                    }
                    echo '</ul>';
                }
                echo '</div>';
            }

        } catch (PDOException $e) {
            echo '<div class="status error">✗ ERRO: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        echo '</div>';

        // AÇÕES
        echo '<div class="test-result">';
        echo '<h3>🔧 Ações</h3>';
        
        if (!isset($_SESSION['id_crianca'])) {
            echo '<a href="parental.php" class="btn">👶 Selecionar Criança</a>';
        }
        
        if (isset($_SESSION['idade_crianca'])) {
            $idade = (int)$_SESSION['idade_crianca'];
            if ($idade >= 5 && $idade <= 7) {
                echo '<a href="atv1.php" class="btn">🎯 Ir para atv1.php</a>';
            } elseif ($idade >= 8 && $idade <= 10) {
                echo '<a href="atv2.php" class="btn">🚀 Ir para atv2.php</a>';
            }
        }
        
        echo '<a href="diagnostico_sessao.php" class="btn">🔍 Diagnóstico Completo</a>';
        echo '</div>';
        ?>

        <div class="test-result">
            <h3>💡 Diagnóstico Rápido</h3>
            <div id="diagnostic"></div>
        </div>
    </div>

    <script>
        async function testarAPI() {
            const resultDiv = document.getElementById('api-result');
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = '<div style="padding: 15px; background: #fff3cd; border-radius: 8px;">⏳ Testando API...</div>';
            
            try {
                const response = await fetch('api_jogos_desbloqueados.php');
                const data = await response.json();
                
                if (data.success) {
                    let html = '<div style="padding: 15px; background: #d4edda; border-radius: 8px; color: #155724;">';
                    html += '<strong>✓ API FUNCIONANDO!</strong><br><br>';
                    html += '<strong>Total de jogos:</strong> ' + data.jogos.length + '<br>';
                    html += '<strong>Próximo jogo:</strong> ' + (data.proximo_jogo || 'Nenhum') + '<br><br>';
                    html += '<strong>Jogos:</strong><ul style="margin-left: 20px; margin-top: 10px;">';
                    
                    data.jogos.forEach(jogo => {
                        const status = jogo.desbloqueado ? '🔓 Desbloqueado' : '🔒 Bloqueado';
                        const completo = jogo.completo ? ' ✓ Completo' : '';
                        html += '<li>' + jogo.nome + ' - ' + status + completo + '</li>';
                    });
                    
                    html += '</ul></div>';
                    html += '<pre style="margin-top: 15px;">' + JSON.stringify(data, null, 2) + '</pre>';
                    resultDiv.innerHTML = html;
                } else {
                    resultDiv.innerHTML = '<div style="padding: 15px; background: #f8d7da; border-radius: 8px; color: #721c24;"><strong>✗ ERRO:</strong> ' + data.error + '</div>';
                }
            } catch (error) {
                resultDiv.innerHTML = '<div style="padding: 15px; background: #f8d7da; border-radius: 8px; color: #721c24;"><strong>✗ ERRO:</strong> ' + error.message + '</div>';
            }
        }

        // Diagnóstico automático
        window.onload = function() {
            const diagnostic = document.getElementById('diagnostic');
            let html = '';
            
            <?php if (!isset($_SESSION['id_crianca'])): ?>
                html += '<div class="status error">⚠️ Problema: Nenhuma criança selecionada</div>';
                html += '<div class="info">Solução: Vá para <a href="parental.php">parental.php</a> e clique no botão JOGAR 🎮 de uma criança</div>';
            <?php elseif (!$todosOk): ?>
                html += '<div class="status error">⚠️ Problema: Arquivos faltando</div>';
                html += '<div class="info">Solução: Crie os arquivos api_jogos_desbloqueados.php e api_pontos.php na RAIZ do projeto</div>';
            <?php else: ?>
                html += '<div class="status success">✓ Tudo parece estar OK!</div>';
                html += '<div class="info">Teste a API clicando no botão acima. Se funcionar, os jogos devem carregar normalmente.</div>';
            <?php endif; ?>
            
            diagnostic.innerHTML = html;
        };
    </script>
</body>
</html>