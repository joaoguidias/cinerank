<?php
// api/db.php

// Identifica o ambiente
$isLocal = ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1');

if (!$isLocal) {
    // === CONFIGURAÇÕES PRIORITÁRIAS: INFINITYFREE ===
    // Procura estes dados no teu painel em "MySQL Details"
    $host = 'sql211.infinityfree.com'; // O teu MySQL Hostname
    $dbname = 'if0_41246106_XXX';          // O teu MySQL Database Name
    $user = 'if0_41246106';                // O teu MySQL Username
    $pass = 'K2IJc5jibrvg';          // A tua MySQL Password (clica em Show/Hide no painel)
} else {
    // === CONFIGURAÇÕES SECUNDÁRIAS: LOCALHOST ===
    $host = 'localhost';
    $dbname = 'cinerank'; 
    $user = 'root';
    $pass = ''; 
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Se a ligação for feita com sucesso, o PHP não imprime nada (o que é ideal para o JSON)
} catch (PDOException $e) {
    // Se houver erro, envia um JSON para o app.js não "explodir" com erro de sintaxe
    header('Content-Type: application/json');
    http_response_code(500);
    die(json_encode([
        "sucesso" => false,
        "erro" => "Erro de ligação: " . $e->getMessage()
    ]));
}