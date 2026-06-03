<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/ProjetoMembro.php';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']);

function jsonErr(string $erro): void {
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'erro' => $erro]);
    exit;
}

function jsonOk(): void {
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
}

if (!usuarioLogado()) {
    $isAjax ? jsonErr('nao-autenticado') : header('Location: /?page=login') . exit();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $isAjax ? jsonErr('metodo-invalido') : header('Location: /?page=projetos') . exit();
    exit;
}

$projetoId = (int) ($_POST['projeto_id'] ?? 0);
$email     = trim($_POST['email'] ?? '');
$usuarioId = (int) usuarioLogado();

if (!$projetoId || !$usuarioId) {
    $isAjax ? jsonErr('dados-invalidos') : header('Location: /index.php') . exit();
    exit;
}

if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
    $isAjax ? jsonErr('nao-autorizado') : header('Location: /?page=projetos&erro=nao-autorizado') . exit();
    exit;
}

if ($email === '') {
    $isAjax ? jsonErr('email-vazio') : header('Location: /?page=projetos&erro=email-vazio') . exit();
    exit;
}

$pdo  = getConnection();
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
$stmt->execute(['email' => $email]);
$alvo = $stmt->fetch();

if (!$alvo) {
    $isAjax ? jsonErr('usuario-nao-encontrado') : header('Location: /?page=projetos&erro=usuario-nao-encontrado') . exit();
    exit;
}

if (ProjetoMembro::jaEMembro($projetoId, (int) $alvo['id'])) {
    $isAjax ? jsonErr('ja-membro') : header('Location: /?page=projetos&erro=ja-membro') . exit();
    exit;
}

ProjetoMembro::adicionar($projetoId, (int) $alvo['id']);

$isAjax ? jsonOk() : header('Location: /?page=projetos') . exit();
exit;
