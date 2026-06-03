<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/avatar.php';
require_once __DIR__ . '/../../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php?page=configuracao');
    exit;
}

$usuarioId = usuarioLogado();
if (!$usuarioId) {
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit;
}

// parseAvatar já valida cor e ícone, caindo para o padrão se inválido
$parsed = parseAvatar(trim($_POST['avatar'] ?? ''));

Usuario::atualizarAvatar((int) $usuarioId, $parsed['color'] . ':' . $parsed['icon']);

header('Location: ' . BASE_URL . '/index.php?page=configuracao&sucesso=avatar-atualizado');
exit;
