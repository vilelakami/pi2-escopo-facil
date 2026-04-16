<?php
// Auto-detect base path for XAMPP/subdirectory compatibility
$docRoot  = realpath($_SERVER['DOCUMENT_ROOT']);
$projRoot = realpath(__DIR__);
$basePath = str_replace('\\', '/', str_replace($docRoot, '', $projRoot));
define('BASE_URL', rtrim($basePath, '/'));

// ── Conexão com o Banco de Dados ──
define('DB_HOST', 'localhost');
define('DB_NAME', 'escopo_facil');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function getConnection(): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    return new PDO($dsn, DB_USER, DB_PASS, $options);
}
