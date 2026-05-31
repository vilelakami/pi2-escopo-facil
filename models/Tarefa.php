<?php

require_once __DIR__ . '/../includes/db.php';

class Tarefa
{
    public static function listarPorProjeto(int $projetoId): array
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            SELECT t.*, u.nome AS criado_por_nome
            FROM tarefas t
            LEFT JOIN usuarios u ON u.id = t.criado_por
            WHERE t.projeto_id = :projeto_id
            ORDER BY t.status ASC, t.prazo IS NULL ASC, t.prazo ASC, t.criado_em DESC
        ");
        $stmt->execute(['projeto_id' => $projetoId]);
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id): array|false
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: false;
    }

    public static function criar(
        int $projetoId,
        string $titulo,
        string $descricao,
        int $prioridade,
        int $status,
        ?string $prazo,
        int $criadoPor
    ): int {
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO tarefas (projeto_id, titulo, descricao, prioridade, status, prazo, criado_por)
            VALUES (:projeto_id, :titulo, :descricao, :prioridade, :status, :prazo, :criado_por)
        ");
        $stmt->execute([
            'projeto_id'  => $projetoId,
            'titulo'      => $titulo,
            'descricao'   => $descricao,
            'prioridade'  => $prioridade,
            'status'      => $status,
            'prazo'       => $prazo,
            'criado_por'  => $criadoPor,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function atualizar(
        int $id,
        string $titulo,
        string $descricao,
        int $prioridade,
        int $status,
        ?string $prazo
    ): void {
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            UPDATE tarefas
            SET titulo = :titulo,
                descricao = :descricao,
                prioridade = :prioridade,
                status = :status,
                prazo = :prazo
            WHERE id = :id
        ");
        $stmt->execute([
            'id'          => $id,
            'titulo'      => $titulo,
            'descricao'   => $descricao,
            'prioridade'  => $prioridade,
            'status'      => $status,
            'prazo'       => $prazo,
        ]);
    }

    public static function deletar(int $id): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
