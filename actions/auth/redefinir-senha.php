<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/TokenRedefinicao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php?page=redefinir-senha&erro=metodo-invalido');
    exit;
}

$token = trim($_POST['token'] ?? '');
$novaSenha = $_POST['nova_senha'] ?? ($_POST['senha'] ?? '');
$confirmarSenha = $_POST['confirmar_nova_senha'] ?? ($_POST['confirmar_senha'] ?? ($_POST['confirmar-senha'] ?? ''));
$redirectToken = $token !== '' ? '&token=' . urlencode($token) : '';

if ($token === '') {
    header('Location: ' . BASE_URL . '/index.php?page=redefinir-senha&erro=token-ausente');
    exit;
}

$tokenRow = TokenRedefinicao::validar($token);
if (!$tokenRow) {
    header('Location: ' . BASE_URL . '/index.php?page=redefinir-senha&erro=token-invalido');
    exit;
}

// Mantemos o token na URL quando ha erro para o usuario nao perder o formulario.
if ($novaSenha === '' || $confirmarSenha === '') {
    header('Location: ' . BASE_URL . '/index.php?page=redefinir-senha&erro=campos-obrigatorios' . $redirectToken);
    exit;
}

if (strlen($novaSenha) < 8) {
    header('Location: ' . BASE_URL . '/index.php?page=redefinir-senha&erro=senha-curta' . $redirectToken);
    exit;
}

if ($novaSenha !== $confirmarSenha) {
    header('Location: ' . BASE_URL . '/index.php?page=redefinir-senha&erro=senhas-diferentes' . $redirectToken);
    exit;
}

// A senha ja entra no banco com hash; nunca salvamos a senha pura.
Usuario::alterarSenha((int) $tokenRow['usuario_id'], password_hash($novaSenha, PASSWORD_BCRYPT));
TokenRedefinicao::marcarUsado((int) $tokenRow['id']);

header('Location: ' . BASE_URL . '/index.php?page=confirmacao');
exit;
