<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/TokenConfirmacaoEmail.php';

$token = trim($_GET['token'] ?? '');

if ($token === '') {
    header('Location: ' . BASE_URL . '/index.php?page=login&erro=token-email-ausente');
    exit;
}

$tokenRow = TokenConfirmacaoEmail::validar($token);

if (!$tokenRow) {
    header('Location: ' . BASE_URL . '/index.php?page=login&erro=token-email-invalido');
    exit;
}

// Depois da confirmacao, o token e marcado como usado para nao funcionar de novo.
Usuario::confirmarEmail((int) $tokenRow['usuario_id']);
TokenConfirmacaoEmail::marcarUsado((int) $tokenRow['id']);

header('Location: ' . BASE_URL . '/index.php?page=login&sucesso=email-confirmado');
exit;
