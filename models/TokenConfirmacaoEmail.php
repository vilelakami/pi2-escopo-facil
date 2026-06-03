<?php

require_once __DIR__ . '/../includes/db.php';

class TokenConfirmacaoEmail
{
    public static function criar(int $usuarioId): string
    {
        // Token aleatorio enviado por email para confirmar o cadastro.
        $pdo = getConnection();
        $token = bin2hex(random_bytes(32));
        $expiraEm = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $stmt = $pdo->prepare("
            INSERT INTO tokens_confirmacao_email (usuario_id, token, expira_em)
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
        // So aceitamos tokens existentes, dentro do prazo e ainda nao usados.
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            SELECT *
            FROM tokens_confirmacao_email
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
        $stmt = $pdo->prepare("UPDATE tokens_confirmacao_email SET usado = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
