<?php

require_once __DIR__ . '/../includes/db.php';

class ChecklistItem
{
    public static function listarPorTarefa(int $tarefaId): array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM checklist_items WHERE tarefa_id = :tarefa_id ORDER BY criado_em ASC");
        $stmt->execute(['tarefa_id' => $tarefaId]);
        return $stmt->fetchAll();
    }

    public static function criar(int $tarefaId, string $texto): array|false
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO checklist_items (tarefa_id, texto) VALUES (:tarefa_id, :texto)");
        $stmt->execute(['tarefa_id' => $tarefaId, 'texto' => $texto]);
        $id = (int) $pdo->lastInsertId();
        $fetch = $pdo->prepare("SELECT * FROM checklist_items WHERE id = :id");
        $fetch->execute(['id' => $id]);
        return $fetch->fetch() ?: false;
    }

    public static function toggle(int $id, int $tarefaId): array|false
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE checklist_items SET concluido = NOT concluido WHERE id = :id AND tarefa_id = :tarefa_id");
        $stmt->execute(['id' => $id, 'tarefa_id' => $tarefaId]);
        if ($stmt->rowCount() === 0) return false;
        $fetch = $pdo->prepare("SELECT * FROM checklist_items WHERE id = :id");
        $fetch->execute(['id' => $id]);
        return $fetch->fetch() ?: false;
    }

    public static function deletar(int $id, int $tarefaId): bool
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM checklist_items WHERE id = :id AND tarefa_id = :tarefa_id");
        $stmt->execute(['id' => $id, 'tarefa_id' => $tarefaId]);
        return $stmt->rowCount() > 0;
    }
}
