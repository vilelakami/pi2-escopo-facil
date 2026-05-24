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

            header("Location: " . BASE_URL . "/pages/tarefas/index.php?projeto_id=" . $projeto_id);
            exit;
        }
    } catch (PDOException $e) {
        die("Erro ao excluir tarefa: " . $e->getMessage());
    }
}

header("Location: " . BASE_URL);
exit;