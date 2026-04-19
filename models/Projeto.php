<?php

require_once __DIR__ . '/../includes/db.php';

class Projeto
{
    public static function listarPorUsuario(int $usuarioId): array
    {
        $pdo = getConnection();
        $sql = "
            SELECT p.id, p.titulo, p.descricao, pm.role,
                   u.nome AS criado_por_nome, p.criado_em,
                   (SELECT COUNT(*) FROM projeto_membros WHERE projeto_id = p.id) AS total_membros
            FROM projetos p
            INNER JOIN projeto_membros pm ON pm.projeto_id = p.id AND pm.usuario_id = :uid
            INNER JOIN usuarios u ON u.id = p.criado_por
            ORDER BY p.criado_em DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uid' => $usuarioId]);
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id): array|false
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM projetos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: false;
    }

    public static function criar(string $titulo, string $descricao, int $usuarioId): int
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO projetos (titulo, descricao, criado_por) VALUES (:titulo, :descricao, :criado_por)");
        $stmt->execute([
            'titulo'     => $titulo,
            'descricao'  => $descricao,
            'criado_por' => $usuarioId,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function atualizar(int $id, string $titulo, string $descricao): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE projetos SET titulo = :titulo, descricao = :descricao WHERE id = :id");
        $stmt->execute([
            'titulo'    => $titulo,
            'descricao' => $descricao,
            'id'        => $id,
        ]);
    }

    public static function deletar(int $id): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM projetos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
