<?php
session_start();
header('Content-Type: application/json');

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

    if (!isset($_SESSION['id_crianca'])) {
        echo json_encode(['success' => false, 'error' => 'Criança não selecionada']);
        exit;
    }

    $idCrianca = (int)$_SESSION['id_crianca'];
    $metodo = $_SERVER['REQUEST_METHOD'];

    // POST - Registrar tempo de jogo
    if ($metodo === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $minutos = isset($data['minutos']) ? (int)$data['minutos'] : 0;

        if ($minutos <= 0) {
            echo json_encode(['success' => false, 'error' => 'Tempo inválido']);
            exit;
        }

        $hoje = date('Y-m-d');

        // Verifica se já existe registro para hoje
        $stmt = $conn->prepare("
            SELECT id, tempo_minutos 
            FROM tempo_jogo 
            WHERE id_crianca = :id_crianca AND data = :data
        ");
        $stmt->execute([
            ':id_crianca' => $idCrianca,
            ':data' => $hoje
        ]);
        $registro = $stmt->fetch();

        if ($registro) {
            // Atualiza tempo existente
            $stmt = $conn->prepare("
                UPDATE tempo_jogo 
                SET tempo_minutos = tempo_minutos + :minutos 
                WHERE id = :id
            ");
            $stmt->execute([
                ':minutos' => $minutos,
                ':id' => $registro['id']
            ]);
        } else {
            // Cria novo registro
            $stmt = $conn->prepare("
                INSERT INTO tempo_jogo (id_crianca, data, tempo_minutos) 
                VALUES (:id_crianca, :data, :minutos)
            ");
            $stmt->execute([
                ':id_crianca' => $idCrianca,
                ':data' => $hoje,
                ':minutos' => $minutos
            ]);
        }

        echo json_encode([
            'success' => true,
            'minutos_adicionados' => $minutos
        ]);
    }

    // GET - Buscar dados de tempo
    if ($metodo === 'GET') {
        $tipo = $_GET['tipo'] ?? 'semanal';
        $idCriancaGet = isset($_GET['id_crianca']) ? (int)$_GET['id_crianca'] : $idCrianca;

        if ($tipo === 'semanal') {
            // Últimos 7 dias
            $stmt = $conn->prepare("
                SELECT 
                    DAYOFWEEK(data) as dia_semana,
                    SUM(tempo_minutos) as minutos
                FROM tempo_jogo
                WHERE id_crianca = :id_crianca
                AND data >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DAYOFWEEK(data)
                ORDER BY data
            ");
            $stmt->execute([':id_crianca' => $idCriancaGet]);
            $dados = $stmt->fetchAll();

            // Mapeia para dias da semana
            $diasSemana = [
                1 => 'Dom', 2 => 'Seg', 3 => 'Ter', 4 => 'Qua',
                5 => 'Qui', 6 => 'Sex', 7 => 'Sáb'
            ];

            $resultado = [];
            for ($i = 1; $i <= 7; $i++) {
                $minutos = 0;
                foreach ($dados as $d) {
                    if ((int)$d['dia_semana'] === $i) {
                        $minutos = (int)$d['minutos'];
                        break;
                    }
                }
                $resultado[] = [
                    'dia' => $diasSemana[$i],
                    'minutos' => $minutos
                ];
            }

            echo json_encode([
                'success' => true,
                'dados' => $resultado
            ]);

        } elseif ($tipo === 'total_semana') {
            // Total da última semana
            $stmt = $conn->prepare("
                SELECT SUM(tempo_minutos) as total
                FROM tempo_jogo
                WHERE id_crianca = :id_crianca
                AND data >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            ");
            $stmt->execute([':id_crianca' => $idCriancaGet]);
            $resultado = $stmt->fetch();

            $totalMinutos = (int)($resultado['total'] ?? 0);
            $horas = floor($totalMinutos / 60);
            $minutos = $totalMinutos % 60;

            echo json_encode([
                'success' => true,
                'total_minutos' => $totalMinutos,
                'horas' => $horas,
                'minutos' => $minutos,
                'formatado' => "{$horas}h {$minutos}m"
            ]);
        }
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>