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
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$cargo = trim($_POST['cargo'] ?? '');

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

if (!$usuarioId || $nome === '' || $email === '' || $cargo === '') {
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=campos-obrigatorios');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=email-invalido');
    exit;
}

if (!in_array($cargo, $cargosValidos, true)) {
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=cargo-invalido');
    exit;
}

try {
    Usuario::atualizar((int) $usuarioId, $nome, $email, $cargo);
    header('Location: ' . BASE_URL . '/?page=configuracao&sucesso=perfil-atualizado');
    exit;
} catch (PDOException $e) {
    $erro = $e->getCode() === '23000' ? 'email-em-uso' : 'erro-ao-atualizar';
    header('Location: ' . BASE_URL . '/?page=configuracao&erro=' . $erro);
    exit;
}
