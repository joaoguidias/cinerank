<?php
include 'db.php'; // Isso puxa a conexão automaticamente
require_once '../config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query('SELECT * FROM ranking ORDER BY posicao ASC');

    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    echo json_encode($filmes);

} catch(PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}