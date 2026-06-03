<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/mailer.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/TokenRedefinicao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php?page=esqueci-senha');
    exit;
}

$email = trim($_POST['email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/index.php?page=esqueci-senha&erro=email-invalido');
    exit;
}

$usuario = Usuario::buscarPorEmail($email);
$params = 'sucesso=1';

// Mesmo se o email nao existir, a tela mostra sucesso para nao expor usuarios cadastrados.
if ($usuario) {
    $token = TokenRedefinicao::criar((int) $usuario['id']);
    $tokenUrl = appUrl('/index.php?page=redefinir-senha&token=' . urlencode($token));
    $emailEnviado = enviarEmailRedefinicaoSenha($email, $usuario['nome'], $tokenUrl);

    if ($emailEnviado) {
        $params .= '&email_enviado=1';
    }

    // Em ambiente local, permite testar o fluxo sem envio real de email.
    if (($_ENV['MAIL_DEBUG_TOKEN_URL'] ?? 'true') === 'true') {
        $params .= '&token_url=' . urlencode($tokenUrl);
    }
}

header('Location: ' . BASE_URL . '/index.php?page=esqueci-senha&' . $params);
exit;
