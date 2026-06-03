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

$itemId   = (int) ($_POST['item_id'] ?? 0);
$tarefaId = (int) ($_POST['tarefa_id'] ?? 0);

if (!$itemId || !$tarefaId) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$tarefa = Tarefa::buscarPorId($tarefaId);
if (!$tarefa || !ProjetoMembro::jaEMembro((int) $tarefa['projeto_id'], (int) $usuarioId)) {
    echo json_encode(['success' => false, 'message' => 'Sem acesso']);
    exit;
}

$ok = ChecklistItem::deletar($itemId, $tarefaId);
echo json_encode(['success' => $ok]);
