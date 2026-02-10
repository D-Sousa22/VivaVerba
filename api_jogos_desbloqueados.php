<?php
session_start();
header('Content-Type: application/json');

// Log para debug
error_log("=== API JOGOS DESBLOQUEADOS ===");
error_log("Session ID Crianca: " . (isset($_SESSION['id_crianca']) ? $_SESSION['id_crianca'] : 'NAO DEFINIDO'));
error_log("Session Idade: " . (isset($_SESSION['idade_crianca']) ? $_SESSION['idade_crianca'] : 'NAO DEFINIDO'));

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
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Verifica se há criança selecionada
    if (!isset($_SESSION['id_crianca']) || !isset($_SESSION['idade_crianca'])) {
        error_log("ERRO: Nenhuma criança selecionada");
        echo json_encode([
            'success' => false,
            'error' => 'Nenhuma criança selecionada'
        ]);
        exit;
    }

    $idCrianca = (int)$_SESSION['id_crianca'];
    $idadeCrianca = (int)$_SESSION['idade_crianca'];
    
    error_log("Processando para criança ID: $idCrianca, Idade: $idadeCrianca");

    // Define jogos por faixa etária
    $jogosPorIdade = [
        '5-7' => [
            ['id' => 'jogo1', 'nome' => 'Ajude a Desembaralhar', 'arquivo' => 'Jogos/Jogo1.php', 'ordem' => 1],
            ['id' => 'jogo2', 'nome' => 'Jogo das Letras', 'arquivo' => 'Jogos/jogo2.php', 'ordem' => 2],
            ['id' => 'jogo3', 'nome' => 'Caça-Palavras das Vogais', 'arquivo' => 'Jogos/jogo3.php', 'ordem' => 3]
        ],
        '8-10' => [
            ['id' => 'jogo4', 'nome' => 'Jogo da Memória', 'arquivo' => 'Jogos/jogo4.php', 'ordem' => 1],
            ['id' => 'jogo5', 'nome' => 'Complete a Palavra', 'arquivo' => 'Jogos/jogo5.php', 'ordem' => 2],
            ['id' => 'jogo6', 'nome' => 'Complete a Frase Mágica', 'arquivo' => 'Jogos/jogo6.php', 'ordem' => 3]
        ]
    ];

    // Determina qual array de jogos usar
    $jogosDisponiveis = [];
    if ($idadeCrianca >= 5 && $idadeCrianca <= 7) {
        $jogosDisponiveis = $jogosPorIdade['5-7'];
        error_log("Selecionado jogos para idade 5-7");
    } elseif ($idadeCrianca >= 8 && $idadeCrianca <= 10) {
        $jogosDisponiveis = $jogosPorIdade['8-10'];
        error_log("Selecionado jogos para idade 8-10");
    } else {
        error_log("ERRO: Idade fora do range: $idadeCrianca");
        echo json_encode([
            'success' => false,
            'error' => 'Idade fora da faixa suportada'
        ]);
        exit;
    }

    // Busca jogos completados pela criança
    $stmt = $conn->prepare("
        SELECT jogo_id, pontos_obtidos 
        FROM jogos_completados 
        WHERE id_crianca = :id_crianca
    ");
    $stmt->execute([':id_crianca' => $idCrianca]);
    $jogosCompletados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Jogos completados encontrados: " . count($jogosCompletados));

    // Cria mapa de jogos completados
    $mapaCompletados = [];
    foreach ($jogosCompletados as $jogo) {
        $mapaCompletados[$jogo['jogo_id']] = true;
        error_log("Jogo completado: " . $jogo['jogo_id']);
    }

    // Monta resposta com status de cada jogo
    $jogosComStatus = [];
    $primeiroNaoCompleto = null;

    foreach ($jogosDisponiveis as $jogo) {
        $completo = isset($mapaCompletados[$jogo['id']]);
        
        // Primeiro jogo sempre desbloqueado, demais apenas se o anterior foi completado
        $desbloqueado = false;
        if ($jogo['ordem'] === 1) {
            $desbloqueado = true;
            error_log("Jogo {$jogo['id']} (ordem 1) - DESBLOQUEADO automaticamente");
        } else {
            // Verifica se o jogo anterior foi completado
            $jogoAnterior = null;
            foreach ($jogosDisponiveis as $j) {
                if ($j['ordem'] === $jogo['ordem'] - 1) {
                    $jogoAnterior = $j;
                    break;
                }
            }
            if ($jogoAnterior && isset($mapaCompletados[$jogoAnterior['id']])) {
                $desbloqueado = true;
                error_log("Jogo {$jogo['id']} (ordem {$jogo['ordem']}) - DESBLOQUEADO (anterior completado)");
            } else {
                error_log("Jogo {$jogo['id']} (ordem {$jogo['ordem']}) - BLOQUEADO (anterior não completado)");
            }
        }

        if (!$completo && $primeiroNaoCompleto === null) {
            $primeiroNaoCompleto = $jogo['id'];
            error_log("Próximo jogo a ser completado: {$jogo['id']}");
        }

        $jogosComStatus[] = [
            'id' => $jogo['id'],
            'nome' => $jogo['nome'],
            'arquivo' => $jogo['arquivo'],
            'ordem' => $jogo['ordem'],
            'desbloqueado' => $desbloqueado,
            'completo' => $completo
        ];
    }

    error_log("Total de jogos retornados: " . count($jogosComStatus));

    echo json_encode([
        'success' => true,
        'jogos' => $jogosComStatus,
        'proximo_jogo' => $primeiroNaoCompleto,
        'debug' => [
            'id_crianca' => $idCrianca,
            'idade' => $idadeCrianca,
            'total_jogos' => count($jogosComStatus),
            'jogos_completos' => count($mapaCompletados)
        ]
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    error_log("ERRO PDO: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar jogos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("ERRO GERAL: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro inesperado: ' . $e->getMessage()
    ]);
}
?>