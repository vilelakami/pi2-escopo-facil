<?php
// onecta com as configurações globais e banco de dados
require_once __DIR__ . '/../../config.php';

global $pdo;

// pega o ID da tarefa que será excluída
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $stmtBusca = $pdo->prepare("SELECT projeto_id FROM tarefas WHERE id = :id");
        $stmtBusca->execute([':id' => $id]);
        $tarefa = $stmtBusca->fetch(PDO::FETCH_ASSOC);
        
        if ($tarefa) {
            $projeto_id = $tarefa['projeto_id'];

            // Agora sim, deleta a tarefa do banco de dados
            $stmtDelete = $pdo->prepare("DELETE FROM tarefas WHERE id = :id");
            $stmtDelete->execute([':id' => $id]);

            // Se foi uma requisição AJAX, retorna JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Tarefa excluída com sucesso']);
            } else {
                // Caso contrário, redireciona (compatibilidade com links normais)
                header("Location: " . BASE_URL . "/pages/tarefas/index.php?projeto_id=" . $projeto_id);
            }
            exit;
        }
    } catch (PDOException $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir tarefa: ' . $e->getMessage()]);
        } else {
            die("Erro ao excluir tarefa: " . $e->getMessage());
        }
        exit;
    }
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
} else {
    header("Location: " . BASE_URL);
}
exit;