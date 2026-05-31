<?php

require_once __DIR__ . '/../includes/db.php';

class TokenRedefinicao
{
    public static function criar(int $usuarioId): string
    {
        // Token temporario para permitir a troca de senha sem estar logado.
        $pdo = getConnection();
        $token = bin2hex(random_bytes(32));
        $expiraEm = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare("
            INSERT INTO tokens_redefinicao (usuario_id, token, expira_em)
            VALUES (:usuario_id, :token, :expira_em)
        ");
        $stmt->execute([
            'usuario_id' => $usuarioId,
            'token' => $token,
            'expira_em' => $expiraEm,
        ]);

        return $token;
    }

    public static function validar(string $token): array|false
    {
        // Impede reuso de link e bloqueia tokens expirados.
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            SELECT *
            FROM tokens_redefinicao
            WHERE token = :token
              AND usado = 0
              AND expira_em > NOW()
            LIMIT 1
        ");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch() ?: false;
    }

    public static function marcarUsado(int $id): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE tokens_redefinicao SET usado = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
