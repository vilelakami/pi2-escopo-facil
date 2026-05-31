<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/mailer.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/TokenConfirmacaoEmail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro');
    exit;
}

// Dados enviados pelo formulario de cadastro.
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$cargo = trim($_POST['cargo'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmarSenha = $_POST['confirmar_senha'] ?? '';
$aceitouTermos = isset($_POST['termos']);

// Esta lista deve acompanhar os valores aceitos no banco para usuarios.cargo.
$cargosValidos = [
    'dev-frontend',
    'dev-backend',
    'dev-fullstack',
    'ui-ux-designer',
    'product-owner',
    'scrum-master',
    'tech-lead',
    'qa-testes',
];

if ($nome === '' || $email === '' || $cargo === '' || $senha === '' || $confirmarSenha === '') {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro&erro=campos-obrigatorios');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro&erro=email-invalido');
    exit;
}

if (!in_array($cargo, $cargosValidos, true)) {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro&erro=cargo-invalido');
    exit;
}

if (strlen($senha) < 8) {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro&erro=senha-curta');
    exit;
}

if ($senha !== $confirmarSenha) {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro&erro=senhas-diferentes');
    exit;
}

if (!$aceitouTermos) {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro&erro=termos-obrigatorios');
    exit;
}

if (Usuario::buscarPorEmail($email)) {
    header('Location: ' . BASE_URL . '/index.php?page=cadastro&erro=email-em-uso');
    exit;
}

// O usuario nasce sem email confirmado; a confirmacao libera o login depois.
$usuarioId = Usuario::criar($nome, $email, password_hash($senha, PASSWORD_BCRYPT), $cargo);
$token = TokenConfirmacaoEmail::criar($usuarioId);
$confirmacaoUrl = appUrl('/index.php?page=confirmar-email&token=' . urlencode($token));
$emailEnviado = enviarEmailConfirmacaoCadastro($email, $nome, $confirmacaoUrl);

$params = 'sucesso=verifique-email';

if ($emailEnviado) {
    $params .= '&email_enviado=1';
}

// Em ambiente local, essa URL ajuda a testar mesmo sem servidor SMTP configurado.
if (($_ENV['MAIL_DEBUG_CONFIRMATION_URL'] ?? 'true') === 'true') {
    $params .= '&confirmacao_url=' . urlencode($confirmacaoUrl);
}

header('Location: ' . BASE_URL . '/index.php?page=login&' . $params);
exit;
