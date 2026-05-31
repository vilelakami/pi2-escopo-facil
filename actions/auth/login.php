<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    header('Location: ' . BASE_URL . '/index.php?page=login&erro=campos-obrigatorios');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/index.php?page=login&erro=email-invalido');
    exit;
}

$usuario = Usuario::buscarPorEmail($email);

// Mensagem generica evita revelar se o email existe no sistema.
if (!$usuario || !password_verify($senha, $usuario['senha'])) {
    header('Location: ' . BASE_URL . '/index.php?page=login&erro=credenciais-invalidas');
    exit;
}

// Contas novas precisam confirmar o email antes do primeiro acesso.
if (isset($usuario['email_verificado']) && (int) $usuario['email_verificado'] !== 1) {
    header('Location: ' . BASE_URL . '/index.php?page=login&erro=email-nao-confirmado');
    exit;
}

autenticarUsuario((int) $usuario['id']);

header('Location: ' . BASE_URL . '/index.php?page=dashboard');
exit;
