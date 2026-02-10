<?php
session_start();
header('Content-Type: application/json');

// Configurações do banco
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

    // Define todas as conquistas disponíveis
    $conquistasDisponiveis = [
        [
            'id' => 'primeira_vitoria',
            'nome' => 'Primeiros Passos',
            'descricao' => 'Complete seu primeiro jogo',
            'icone' => 'icon_trophy.png',
            'criterio' => 'completar_1_jogo'
        ],
        [
            'id' => 'jogo2_completo',
            'nome' => 'Mestre das Letras',
            'descricao' => 'Complete o Jogo das Letras',
            'icone' => 'icon_abc.png',
            'criterio' => 'completar_jogo2'
        ],
        [
            'id' => 'jogo3_completo',
            'nome' => 'Caçador de Vogais',
            'descricao' => 'Complete o Caça-Palavras das Vogais',
            'icone' => 'icon_book.png',
            'criterio' => 'completar_jogo3'
        ],
        [
            'id' => 'jogo4_completo',
            'nome' => 'Memória de Elefante',
            'descricao' => 'Complete o Jogo da Memória',
            'icone' => 'icon_brain.png',
            'criterio' => 'completar_jogo4'
        ],
        [
            'id' => 'jogo5_completo',
            'nome' => 'Completador Expert',
            'descricao' => 'Complete o jogo Complete a Palavra',
            'icone' => 'icon_puzzle.png',
            'criterio' => 'completar_jogo5'
        ],
        [
            'id' => 'jogo6_completo',
            'nome' => 'Mestre das Frases',
            'descricao' => 'Complete o jogo Complete a Frase',
            'icone' => 'icon_bussola.png',
            'criterio' => 'completar_jogo6'
        ]
    ];

    $metodo = $_SERVER['REQUEST_METHOD'];

    // GET - Buscar conquistas de uma criança
    if ($metodo === 'GET') {
        if (!isset($_GET['id_crianca'])) {
            echo json_encode(['success' => false, 'error' => 'ID da criança não fornecido']);
            exit;
        }

        $idCrianca = (int)$_GET['id_crianca'];

        // Busca conquistas desbloqueadas
        $stmt = $conn->prepare("
            SELECT conquista_id, data_conquista 
            FROM conquistas_criancas 
            WHERE id_crianca = :id_crianca
        ");
        $stmt->execute([':id_crianca' => $idCrianca]);
        $conquistasDesbloqueadas = $stmt->fetchAll();

        $desbloqueadasIds = array_column($conquistasDesbloqueadas, 'conquista_id');

        // Monta resposta
        $resultado = [];
        foreach ($conquistasDisponiveis as $conquista) {
            $desbloqueada = in_array($conquista['id'], $desbloqueadasIds);
            $dataConquista = null;

            if ($desbloqueada) {
                foreach ($conquistasDesbloqueadas as $c) {
                    if ($c['conquista_id'] === $conquista['id']) {
                        $dataConquista = $c['data_conquista'];
                        break;
                    }
                }
            }

            $resultado[] = [
                'id' => $conquista['id'],
                'nome' => $conquista['nome'],
                'descricao' => $conquista['descricao'],
                'icone' => $conquista['icone'],
                'desbloqueada' => $desbloqueada,
                'data_conquista' => $dataConquista
            ];
        }

        echo json_encode([
            'success' => true,
            'conquistas' => $resultado
        ]);
    }

    // POST - Verificar e desbloquear conquistas
    if ($metodo === 'POST') {
        if (!isset($_SESSION['id_crianca'])) {
            echo json_encode(['success' => false, 'error' => 'Criança não selecionada']);
            exit;
        }

        $idCrianca = $_SESSION['id_crianca'];
        $novasConquistas = [];

        // Busca estatísticas da criança
        $stmt = $conn->prepare("
            SELECT COUNT(DISTINCT jogo_id) as total_jogos
            FROM jogos_completados 
            WHERE id_crianca = :id_crianca
        ");
        $stmt->execute([':id_crianca' => $idCrianca]);
        $stats = $stmt->fetch();

        // Busca conquistas já desbloqueadas
        $stmt = $conn->prepare("
            SELECT conquista_id FROM conquistas_criancas 
            WHERE id_crianca = :id_crianca
        ");
        $stmt->execute([':id_crianca' => $idCrianca]);
        $jaTem = array_column($stmt->fetchAll(), 'conquista_id');

        // Verifica cada conquista
        foreach ($conquistasDisponiveis as $conquista) {
            if (in_array($conquista['id'], $jaTem)) continue;

            $desbloquear = false;

            switch ($conquista['criterio']) {
                case 'completar_1_jogo':
                    $desbloquear = $stats['total_jogos'] >= 1;
                    break;

                case 'completar_jogo2':
                    $stmt = $conn->prepare("
                        SELECT id FROM jogos_completados 
                        WHERE id_crianca = :id_crianca AND jogo_id = 'jogo2'
                    ");
                    $stmt->execute([':id_crianca' => $idCrianca]);
                    $desbloquear = $stmt->rowCount() > 0;
                    break;

                case 'completar_jogo3':
                    $stmt = $conn->prepare("
                        SELECT id FROM jogos_completados 
                        WHERE id_crianca = :id_crianca AND jogo_id = 'jogo3'
                    ");
                    $stmt->execute([':id_crianca' => $idCrianca]);
                    $desbloquear = $stmt->rowCount() > 0;
                    break;

                case 'completar_jogo4':
                    $stmt = $conn->prepare("
                        SELECT id FROM jogos_completados 
                        WHERE id_crianca = :id_crianca AND jogo_id = 'jogo4'
                    ");
                    $stmt->execute([':id_crianca' => $idCrianca]);
                    $desbloquear = $stmt->rowCount() > 0;
                    break;

                case 'completar_jogo5':
                    $stmt = $conn->prepare("
                        SELECT id FROM jogos_completados 
                        WHERE id_crianca = :id_crianca AND jogo_id = 'jogo5'
                    ");
                    $stmt->execute([':id_crianca' => $idCrianca]);
                    $desbloquear = $stmt->rowCount() > 0;
                    break;

                case 'completar_jogo6':
                    $stmt = $conn->prepare("
                        SELECT id FROM jogos_completados 
                        WHERE id_crianca = :id_crianca AND jogo_id = 'jogo6'
                    ");
                    $stmt->execute([':id_crianca' => $idCrianca]);
                    $desbloquear = $stmt->rowCount() > 0;
                    break;
            }

            if ($desbloquear) {
                $stmt = $conn->prepare("
                    INSERT INTO conquistas_criancas (id_crianca, conquista_id) 
                    VALUES (:id_crianca, :conquista_id)
                ");
                $stmt->execute([
                    ':id_crianca' => $idCrianca,
                    ':conquista_id' => $conquista['id']
                ]);

                $novasConquistas[] = [
                    'id' => $conquista['id'],
                    'nome' => $conquista['nome'],
                    'descricao' => $conquista['descricao'],
                    'icone' => $conquista['icone']
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'novas_conquistas' => $novasConquistas
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>