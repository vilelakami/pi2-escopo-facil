<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/ChecklistItem.php';
require_once __DIR__ . '/../../models/Tarefa.php';
require_once __DIR__ . '/../../models/ProjetoMembro.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

$usuarioId = usuarioLogado();
if (!$usuarioId) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

$tarefaId = (int) ($_POST['tarefa_id'] ?? 0);
$texto    = trim($_POST['texto'] ?? '');

if (!$tarefaId || $texto === '') {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

if (mb_strlen($texto) > 500) {
    echo json_encode(['success' => false, 'message' => 'Texto muito longo']);
    exit;
}

$tarefa = Tarefa::buscarPorId($tarefaId);
if (!$tarefa || !ProjetoMembro::jaEMembro((int) $tarefa['projeto_id'], (int) $usuarioId)) {
    echo json_encode(['success' => false, 'message' => 'Sem acesso']);
    exit;
}

$item = ChecklistItem::criar($tarefaId, $texto);
if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Erro ao criar item']);
    exit;
}

echo json_encode(['success' => true, 'item' => $item]);
