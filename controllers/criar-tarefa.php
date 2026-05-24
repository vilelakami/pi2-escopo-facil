<?php
require_once __DIR__ . '/../config.php';

global $pdo;
if (!isset($pdo) && function_exists('getConnection')) {
    $pdo = getConnection();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegando os dados vindos dos names do formulário
    $projeto_id  = filter_input(INPUT_POST, 'projeto_id', FILTER_VALIDATE_INT);
    $titulo      = filter_input(INPUT_POST, 'taskTitle', FILTER_UNSAFE_RAW);
    $descricao   = filter_input(INPUT_POST, 'taskDescription', FILTER_UNSAFE_RAW);
    $prioridade  = filter_input(INPUT_POST, 'taskPriority', FILTER_VALIDATE_INT) ?? 1;
    $status      = filter_input(INPUT_POST, 'taskStatus', FILTER_VALIDATE_INT) ?? 1;
    $prazo       = $_POST['taskDate'] ?: null;
    
    $criado_por = 1; // ID do Natan para testes

    if ($projeto_id && $titulo) {
        try {
            $sql = "INSERT INTO tarefas (projeto_id, titulo, descricao, prioridade, status, prazo, criado_por) 
                    VALUES (:projeto_id, :titulo, :descricao, :prioridade, :status, :prazo, :criado_por)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':projeto_id'  => $projeto_id,
                ':titulo'      => $titulo,
                ':descricao'   => $descricao,
                ':prioridade'  => $prioridade,
                ':status'      => $status,
                ':prazo'       => $prazo,
                ':criado_por'  => $criado_por
            ]);
            
            // Sucesso! Retorna para o Kanban do projeto correspondente
            header("Location: ../pages/tarefas/index.php?projeto_id=" . $projeto_id);
            exit;

        } catch (PDOException $e) {
            die("Erro no banco: " . $e->getMessage());
        }
    } else {
        die("Preencha todos os campos obrigatórios.");
    }
}   