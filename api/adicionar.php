<?php
// Importa as configurações do banco e da API
require_once '../config.php';

// try/catch: tenta executar o código, se der erro cai no catch
try {
    // Conecta no banco de dados usando PDO
    // PDO é a forma segura e moderna de conectar no MySQL com PHP
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );

    // Define que erros do banco devem lançar exceções
    // Sem isso os erros passariam silenciosos
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lê os dados enviados pelo JavaScript no corpo da requisição
    // php://input lê o corpo cru da requisição
    // json_decode converte o JSON em array PHP
    $dados = json_decode(file_get_contents('php://input'), true);

    // Separa cada dado em variáveis para facilitar o uso
    $tmdb_id = $dados['tmdb_id']; // ID do filme no TMDB
    $titulo  = $dados['titulo'];  // Nome do filme
    $poster  = $dados['poster'];  // Link da imagem do poster
    $ano     = $dados['ano'];     // Ano de lançamento

    // Busca a maior posição atual no ranking
    // MAX(posicao) retorna o maior número, ex: se tem 5 filmes retorna 5
    $stmt = $pdo->query('SELECT MAX(posicao) FROM ranking');

    // fetchColumn pega o valor da primeira coluna do resultado
    // +1 coloca o novo filme na próxima posição disponível
    $posicao = $stmt->fetchColumn() + 1;

    // Verifica se o ranking já está cheio (máximo 100 filmes)
    if($posicao > 100) {
        echo json_encode(['erro' => 'Ranking cheio! Máximo 100 filmes']);
        exit; // Para a execução aqui
    }

    // prepare() monta o SQL com ? no lugar dos valores
    // Isso evita SQL Injection (ataque onde alguém manipula o banco via formulário)
    $stmt = $pdo->prepare('INSERT INTO ranking (tmdb_id, titulo, poster, ano, posicao) VALUES (?, ?, ?, ?, ?)');

    // execute() substitui cada ? pelos valores na ordem do array
    $stmt->execute([$tmdb_id, $titulo, $poster, $ano, $posicao]);

    // Devolve sucesso pro JavaScript com a posição que o filme entrou
    echo json_encode(['sucesso' => true, 'posicao' => $posicao]);

} catch(PDOException $e) {
    // Se qualquer coisa der errado, devolve a mensagem de erro
    // getMessage() retorna a descrição do erro
    echo json_encode(['erro' => $e->getMessage()]);
}