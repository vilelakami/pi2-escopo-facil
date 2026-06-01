<?php

require_once __DIR__ . '/../config.php';

$pdo = getConnection();

// Script idempotente: pode ser executado mais de uma vez sem duplicar colunas/tabelas.
$colunasEmailVerificado = $pdo->query("SHOW COLUMNS FROM usuarios LIKE 'email_verificado'")->fetchAll();
if (!$colunasEmailVerificado) {
    $pdo->exec("
        ALTER TABLE usuarios
        ADD COLUMN email_verificado TINYINT(1) NOT NULL DEFAULT 1 AFTER senha,
        ADD COLUMN email_verificado_em DATETIME DEFAULT NULL AFTER email_verificado
    ");
    $pdo->exec("UPDATE usuarios SET email_verificado = 1, email_verificado_em = NOW()");
}

// Guarda os tokens enviados por email para liberar o primeiro login do usuario.
$pdo->exec("
    CREATE TABLE IF NOT EXISTS tokens_confirmacao_email (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT UNSIGNED NOT NULL,
        token VARCHAR(255) NOT NULL UNIQUE,
        expira_em DATETIME NOT NULL,
        usado TINYINT(1) NOT NULL DEFAULT 0,
        criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_tokens_email_usuario
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

$indiceExpira = $pdo->query("SHOW INDEX FROM tokens_confirmacao_email WHERE Key_name = 'idx_tokens_email_expira'")->fetchAll();
if (!$indiceExpira) {
    $pdo->exec("CREATE INDEX idx_tokens_email_expira ON tokens_confirmacao_email(expira_em)");
}

echo "Migracao de confirmacao de email aplicada.\n";
