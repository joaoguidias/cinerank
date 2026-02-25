<?php
// Importa as configurações do banco e da API
include 'db.php'; // Isso puxa a conexão automaticamente
require_once '../config.php';

// try/catch: tenta executar o código, se der erro cai no catch
try {
    // Conecta no banco de dados usando PDO
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );

    // Define que erros do banco devem lançar exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lê os dados enviados pelo JavaScript no corpo da requisição
    $dados = json_decode(file_get_contents('php://input'), true);

    // Pega o id do filme que vai ser removido
    $id = $dados['id'];

    // Busca a posição do filme antes de deletar
    // Vai precisar dela pra reorganizar o ranking depois
    $stmt = $pdo->prepare('SELECT posicao FROM ranking WHERE id = ?');
    $stmt->execute([$id]);
    $posicao = $stmt->fetchColumn();

    // Deleta o filme do banco
    $stmt = $pdo->prepare('DELETE FROM ranking WHERE id = ?');
    $stmt->execute([$id]);

    // Reorganiza as posições dos filmes que estavam abaixo do removido
    // Ex: se removeu o filme na posição 3, os filmes 4,5,6... viram 3,4,5...
    $stmt = $pdo->prepare('UPDATE ranking SET posicao = posicao - 1 WHERE posicao > ?');
    $stmt->execute([$posicao]);

    // Devolve sucesso pro JavaScript
    echo json_encode(['sucesso' => true]);

} catch(PDOException $e) {
    // Se qualquer coisa der errado, devolve a mensagem de erro
    echo json_encode(['erro' => $e->getMessage()]);
}