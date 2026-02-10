<?php
// 1. INICIAR SESSÃO
// Isso é essencial para sabermos qual criança (id_crianca) está logada.
session_start();

// Configuração da resposta (sempre será JSON)
header('Content-Type: application/json');

// --- Validação de Login ---
// Se não houver um 'id_crianca' na sessão, significa que ninguém está logado.
if (!isset($_SESSION['id_crianca'])) {
    // Retorna um erro
    echo json_encode([
        'success' => false,
        'message' => 'Usuário (criança) não autenticado.'
    ]);
    exit; // Para a execução
}

// Se o login existe, pegamos o ID
$id_crianca_logada = $_SESSION['id_crianca'];


// 2. CONEXÃO COM BANCO DE DADOS (MySQL)
// (Mantenha seus dados de conexão em um arquivo separado, ex: 'db_connect.php')
require_once 'db_connect.php'; // (Você precisa criar este arquivo)

/* // Exemplo do que 'db_connect.php' conteria:
$servidor = "localhost";
$usuario = "root";
$senha = ""; // Senha do seu PHPMyAdmin
$banco = "vivaverba_db"; // O nome do seu banco de dados
$pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
*/


// 3. RECEBER DADOS (JSON) DO JAVASCRIPT
$dados_json = file_get_contents('php://input');
$dados = json_decode($dados_json, true); // 'true' para transformar em array associativo

// Validação simples dos dados recebidos
if (empty($dados) || !isset($dados['id_jogo']) || !isset($dados['pontuacao'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados incompletos recebidos.'
    ]);
    exit;
}

$id_jogo_recebido = $dados['id_jogo'];
$pontuacao_recebida = $dados['pontuacao'];
$tempo_de_jogo = 60; // Exemplo. (Você pode calcular o tempo no JS e enviar para cá)

try {
    // 4. INSERIR OS DADOS NO BANCO (SQL)
    // Usando o DER que vocês fizeram (Slide 30), vamos inserir na tabela 'Progresso'.
    // O DER sugere que 'Progresso' e 'Aprendizagem' estão ligados.
    // Vamos supor que precisamos primeiro garantir um registro em 'Aprendizagem'
    // (Vou simplificar e inserir direto em 'Progresso', assumindo que 'Aprendizagem' já existe ou não é obrigatório para o progresso)

    // O DER parece indicar que 'Progresso' é filho de 'Aprendizagem', que é filho de 'Crianca'.
    // E 'Jogo' também se liga a 'Progresso'.
    // Vamos usar a tabela 'Progresso'
    // (Ajuste os nomes das colunas se necessário, baseado no seu DER)

    // A consulta SQL
    $sql = "INSERT INTO Progresso (id_crianca, id_jogo, pontuacao, Tempo_de_Jogo, Niveis_Concluidos) 
            VALUES (:id_crianca, :id_jogo, :pontuacao, :tempo_jogo, 1)
            ON DUPLICATE KEY UPDATE 
            pontuacao = pontuacao + VALUES(pontuacao), 
            Tempo_de_Jogo = Tempo_de_Jogo + VALUES(Tempo_de_Jogo), 
            Niveis_Concluidos = Niveis_Concluidos + 1";
    
    // (Usei ON DUPLICATE KEY UPDATE para que, se a criança jogar de novo, os pontos sejam somados,
    // mas isso depende da sua chave primária/única. Um INSERT simples pode ser melhor.)

    // Prepara e executa a query com segurança (Prepared Statements)
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':id_crianca', $id_crianca_logada, PDO::PARAM_INT);
    $stmt->bindParam(':id_jogo', $id_jogo_recebido, PDO::PARAM_INT);
    $stmt->bindParam(':pontuacao', $pontuacao_recebida, PDO::PARAM_INT);
    $stmt->bindParam(':tempo_jogo', $tempo_de_jogo, PDO::PARAM_INT); // Exemplo de tempo
    
    $stmt->execute();

    // 5. RETORNAR SUCESSO
    echo json_encode([
        'success' => true,
        'message' => 'Progresso salvo com sucesso!'
    ]);

} catch (PDOException $e) {
    // 6. RETORNAR ERRO (Se falhar o banco)
    echo json_encode([
        'success' => false,
        'message' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
}
?>