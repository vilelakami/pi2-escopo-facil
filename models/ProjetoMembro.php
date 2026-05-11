<?php

require_once __DIR__ . '/../includes/db.php';

class ProjetoMembro
{
    public static function listar(int $projetoId): array
    {
        $pdo = getConnection();
        $sql = "
            SELECT pm.id, u.id AS usuario_id, u.nome, u.email, u.cargo, u.avatar,
                   pm.role, pm.adicionado_em
            FROM projeto_membros pm
            INNER JOIN usuarios u ON u.id = pm.usuario_id
            WHERE pm.projeto_id = :projeto_id
            ORDER BY pm.adicionado_em ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['projeto_id' => $projetoId]);
        return $stmt->fetchAll();
    }

    public static function adicionar(int $projetoId, int $usuarioId, string $role = 'membro'): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO projeto_membros (projeto_id, usuario_id, role) VALUES (:projeto_id, :usuario_id, :role)");
        $stmt->execute([
            'projeto_id' => $projetoId,
            'usuario_id' => $usuarioId,
            'role'       => $role,
        ]);
    }

    public static function remover(int $projetoId, int $usuarioId): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM projeto_membros WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id");
        $stmt->execute([
            'projeto_id' => $projetoId,
            'usuario_id' => $usuarioId,
        ]);
    }

    public static function alterarRole(int $projetoId, int $usuarioId, string $role): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE projeto_membros SET role = :role WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id");
        $stmt->execute([
            'role'       => $role,
            'projeto_id' => $projetoId,
            'usuario_id' => $usuarioId,
        ]);
    }

    public static function isAdmin(int $projetoId, int $usuarioId): bool
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM projeto_membros WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id AND role = 'admin' LIMIT 1");
        $stmt->execute([
            'projeto_id' => $projetoId,
            'usuario_id' => $usuarioId,
        ]);
        return (bool) $stmt->fetch();
    }

    public static function jaEMembro(int $projetoId, int $usuarioId): bool
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM projeto_membros WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id LIMIT 1");
        $stmt->execute([
            'projeto_id' => $projetoId,
            'usuario_id' => $usuarioId,
        ]);
        return (bool) $stmt->fetch();
    }
}
