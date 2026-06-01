<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Usuario.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/?page=configuracao');
    exit;
}

$usuarioId = usuarioLogado();
$senhaAtual = $_POST['senha_atual'] ?? '';
$novaSenha = $_POST['nova_senha'] ?? '';
$confirmarSenha = $_POST['confirmar_nova_senha'] ?? ($_POST['confirmar_senha'] ?? '');

if (!$usuarioId || $senhaAtual === '' || $novaSenha === '' || $confirmarSenha === '') {
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=campos-obrigatorios');
    exit;
}

if (strlen($novaSenha) < 8) {
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=senha-curta');
    exit;
}

if ($novaSenha !== $confirmarSenha) {
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=senhas-diferentes');
    exit;
}

$usuario = Usuario::buscarPorId((int) $usuarioId);

if (!$usuario) {
    header('Location: ' . BASE_URL . '/?page=login');
    exit;
}

if (!password_verify($senhaAtual, $usuario['senha'])) {
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=senha-atual-incorreta');
    exit;
}

$hash = password_hash($novaSenha, PASSWORD_BCRYPT);
Usuario::alterarSenha((int) $usuarioId, $hash);

header('Location: ' . BASE_URL . '/?page=configuracao&sucesso=senha-alterada');
exit;
