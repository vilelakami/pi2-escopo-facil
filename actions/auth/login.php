<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Usuario.php';

$loginViaAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';

function responderErroLogin(string $erro, bool $loginViaAjax): void
{
    if ($loginViaAjax) {
        http_response_code(422);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['sucesso' => false, 'erro' => $erro]);
        exit;
    }

    header('Location: ' . BASE_URL . '/index.php?page=login&erro=' . urlencode($erro));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    responderErroLogin('campos-obrigatorios', $loginViaAjax);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responderErroLogin('email-invalido', $loginViaAjax);
}

$usuario = Usuario::buscarPorEmail($email);

// Mensagem generica evita revelar se o email existe no sistema.
if (!$usuario || !password_verify($senha, $usuario['senha'])) {
    responderErroLogin('credenciais-invalidas', $loginViaAjax);
}

// Contas novas precisam confirmar o email antes do primeiro acesso.
if (isset($usuario['email_verificado']) && (int) $usuario['email_verificado'] !== 1) {
    responderErroLogin('email-nao-confirmado', $loginViaAjax);
}

autenticarUsuario((int) $usuario['id']);

if ($loginViaAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'sucesso' => true,
        'redirect' => BASE_URL . '/index.php?page=dashboard',
    ]);
    exit;
}

header('Location: ' . BASE_URL . '/index.php?page=dashboard');
exit;
