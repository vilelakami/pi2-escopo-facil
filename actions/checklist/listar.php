<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/ChecklistItem.php';
require_once __DIR__ . '/../../models/Tarefa.php';
require_once __DIR__ . '/../../models/ProjetoMembro.php';

$usuarioId = usuarioLogado();
if (!$usuarioId) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

$tarefaId = (int) ($_GET['tarefa_id'] ?? 0);
if (!$tarefaId) {
    echo json_encode(['success' => false, 'message' => 'tarefa_id inválido']);
    exit;
}

$tarefa = Tarefa::buscarPorId($tarefaId);
if (!$tarefa || !ProjetoMembro::jaEMembro((int) $tarefa['projeto_id'], (int) $usuarioId)) {
    echo json_encode(['success' => false, 'message' => 'Sem acesso']);
    exit;
}

echo json_encode(['success' => true, 'items' => ChecklistItem::listarPorTarefa($tarefaId)]);
