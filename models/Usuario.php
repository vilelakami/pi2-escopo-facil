<?php

require_once __DIR__ . '/../includes/db.php';

class Usuario
{
    public static function buscarPorId(int $id): array|false
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: false;
    }

    public static function buscarPorEmail(string $email): array|false
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: false;
    }

    public static function criar(string $nome, string $email, string $senhaHash, string $cargo, ?string $avatar = null, bool $emailVerificado = false): int
    {
        // O controller/action ja entrega a senha com hash; o model apenas salva.
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (nome, email, senha, cargo, avatar, email_verificado, email_verificado_em)
            VALUES (:nome, :email, :senha, :cargo, :avatar, :email_verificado, :email_verificado_em)
        ");
        $stmt->execute([
            'nome'   => $nome,
            'email'  => $email,
            'senha'  => $senhaHash,
            'cargo'  => $cargo,
            'avatar' => $avatar,
            'email_verificado' => $emailVerificado ? 1 : 0,
            'email_verificado_em' => $emailVerificado ? date('Y-m-d H:i:s') : null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function confirmarEmail(int $id): void
    {
        // Marca a conta como pronta para login.
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            UPDATE usuarios
            SET email_verificado = 1, email_verificado_em = NOW()
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
    }

    public static function atualizar(int $id, string $nome, string $email, string $cargo): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            UPDATE usuarios
            SET nome = :nome, email = :email, cargo = :cargo
            WHERE id = :id
        ");
        $stmt->execute([
            'nome'  => $nome,
            'email' => $email,
            'cargo' => $cargo,
            'id'    => $id,
        ]);
    }

    public static function alterarSenha(int $id, string $senhaHash): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
        $stmt->execute([
            'senha' => $senhaHash,
            'id'    => $id,
        ]);
    }

    public static function atualizarAvatar(int $id, string $avatar): void
    {
        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE usuarios SET avatar = :avatar WHERE id = :id");
        $stmt->execute([
            'avatar' => $avatar,
            'id'     => $id,
        ]);
    }
}
