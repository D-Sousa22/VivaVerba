<?php
session_start();
header('Content-Type: application/json');

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
    if (!isset($_SESSION['id_crianca'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Nenhuma criança selecionada'
        ]);
        exit;
    }

    // Recebe dados do POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode([
            'success' => false,
            'error' => 'Dados inválidos'
        ]);
        exit;
    }

    $idCrianca = $_SESSION['id_crianca'];
    $jogoId = $data['jogo_id'] ?? null;
    $pontos = (int)($data['pontos'] ?? 0);
    $tempo = (int)($data['tempo'] ?? 0);
    $tipoAtividade = $data['tipo_atividade'] ?? '';
    $completo = $data['completo'] ?? false;

    if (!$jogoId) {
        echo json_encode([
            'success' => false,
            'error' => 'ID do jogo não informado'
        ]);
        exit;
    }

    $conn->beginTransaction();

    try {
        // Verifica se o jogo já foi completado antes
        $stmt = $conn->prepare("
            SELECT id FROM jogos_completados 
            WHERE id_crianca = :id_crianca AND jogo_id = :jogo_id
        ");
        $stmt->execute([
            ':id_crianca' => $idCrianca,
            ':jogo_id' => $jogoId
        ]);
        $jaCompletado = $stmt->fetch();

        if (!$jaCompletado && $completo) {
            // Registra conclusão do jogo
            $stmt = $conn->prepare("
                INSERT INTO jogos_completados (id_crianca, jogo_id, pontos_obtidos, tempo_conclusao) 
                VALUES (:id_crianca, :jogo_id, :pontos, :tempo)
            ");
            $stmt->execute([
                ':id_crianca' => $idCrianca,
                ':jogo_id' => $jogoId,
                ':pontos' => $pontos,
                ':tempo' => $tempo
            ]);

            // Registra no histórico de pontos
            $stmt = $conn->prepare("
                INSERT INTO historico_pontos (id_crianca, jogo_id, pontos, tipo_atividade) 
                VALUES (:id_crianca, :jogo_id, :pontos, :tipo_atividade)
            ");
            $stmt->execute([
                ':id_crianca' => $idCrianca,
                ':jogo_id' => $jogoId,
                ':pontos' => $pontos,
                ':tipo_atividade' => $tipoAtividade
            ]);

            // Atualiza pontos totais da criança
            $stmt = $conn->prepare("
                UPDATE criancas 
                SET pontos_totais = pontos_totais + :pontos 
                WHERE id = :id_crianca
            ");
            $stmt->execute([
                ':pontos' => $pontos,
                ':id_crianca' => $idCrianca
            ]);

            // Calcula e atualiza progresso
            // Busca total de jogos disponíveis para a idade da criança
            $stmt = $conn->prepare("SELECT idade FROM criancas WHERE id = :id_crianca");
            $stmt->execute([':id_crianca' => $idCrianca]);
            $crianca = $stmt->fetch();
            $idade = (int)$crianca['idade'];

            // Define quantos jogos existem por faixa etária
            $totalJogosPorIdade = 3; // Cada faixa tem 3 jogos

            // Conta quantos jogos a criança completou
            $stmt = $conn->prepare("
                SELECT COUNT(*) as total 
                FROM jogos_completados 
                WHERE id_crianca = :id_crianca
            ");
            $stmt->execute([':id_crianca' => $idCrianca]);
            $resultado = $stmt->fetch();
            $jogosCompletos = (int)$resultado['total'];

            // Calcula percentual de progresso
            $progresso = round(($jogosCompletos / $totalJogosPorIdade) * 100);
            $progresso = min(100, $progresso); // Limita a 100%

            // Atualiza progresso
            $stmt = $conn->prepare("
                UPDATE criancas 
                SET progresso = :progresso 
                WHERE id = :id_crianca
            ");
            $stmt->execute([
                ':progresso' => $progresso,
                ':id_crianca' => $idCrianca
            ]);
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Pontuação registrada com sucesso',
            'pontos' => $pontos,
            'novo_registro' => !$jaCompletado
        ]);

    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao registrar pontuação: ' . $e->getMessage()
    ]);
}
?>