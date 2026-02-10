<?php
// backend/update_score.php

// 1. INICIAR SESSÃO
// Essencial para saber QUEM está logado.
session_start();

// Define a resposta como JSON
header('Content-Type: application/json');

// 2. VERIFICAR LOGIN
// Se não houver 'id_crianca' na sessão, o usuário é um visitante.
// Não podemos salvar o score, mas o jogo (frontend) continua funcionando.
if (!isset($_SESSION['id_crianca'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não logado. Pontuação não salva.'
    ]);
    exit; // Para a execução
}

// 3. CONEXÃO COM BANCO DE DADOS
// (Assume que você tem seu arquivo de conexão, como fizemos antes)
require_once 'db_connect.php'; // (ex: $pdo = new PDO(...))

// 4. LÓGICA DE PONTUAÇÃO
$id_crianca_logada = $_SESSION['id_crianca'];
$id_jogo_desembaralhar = 2; // Vamos definir '2' como o ID deste jogo (o '1' foi o Caça-Vogais)
$pontos_por_palavra = 10;   // Você decide quantos pontos vale cada palavra correta

try {
    // 5. O SQL "UPSERT" (INSERT... ON DUPLICATE KEY UPDATE)
    // Esta é a lógica de backend mais importante:
    // 1. Tenta INSERIR um novo registro para esta criança e este jogo.
    // 2. Se essa combinação (id_crianca, id_jogo) já existir,
    //    ele executa o UPDATE e SOMA os novos pontos.

    $sql = "INSERT INTO Progresso (id_crianca, id_jogo, Pontuacao) 
            VALUES (:id_crianca, :id_jogo, :pontos)
            ON DUPLICATE KEY UPDATE 
            Pontuacao = Pontuacao + :pontos_update";

    // Prepara e executa a query com segurança
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':id_crianca' => $id_crianca_logada,
        ':id_jogo' => $id_jogo_desembaralhar,
        ':pontos' => $pontos_por_palavra,
        ':pontos_update' => $pontos_por_palavra // O valor é usado nos dois lugares
    ]);

    // 6. RETORNAR SUCESSO
    echo json_encode([
        'success' => true,
        'message' => 'Pontuação atualizada com sucesso!',
        'pontos_ganhos' => $pontos_por_palavra
    ]);

} catch (PDOException $e) {
    // 7. RETORNAR ERRO (Se falhar o banco)
    echo json_encode([
        'success' => false,
        'message' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
}
?>