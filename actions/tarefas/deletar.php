<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../config.php';

$tarefa_id = $_POST['tarefa_id'] ?? null;

if (!$tarefa_id) {
    echo json_encode([
        'success' => false,
        'message' => 'ID não enviado'
    ]);
    exit;
}

try {

    $pdo = getConnection();

    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->execute([$tarefa_id]);

    echo json_encode([
        'success' => true
    ]);

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}