<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';

header('Content-Type: application/json');

if (!usuarioLogado()) {
    echo json_encode(['erro' => 'nao-autenticado']);
    exit;
}

$q         = trim($_GET['q'] ?? '');
$projetoId = (int) ($_GET['projeto_id'] ?? 0);

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$pdo = getConnection();

// Busca por nome ou email, excluindo quem já é membro
$like = '%' . $q . '%';

$stmt = $pdo->prepare("
    SELECT u.id, u.nome, u.email, u.cargo, u.avatar
    FROM usuarios u
    WHERE (u.nome LIKE :qn OR u.email LIKE :qe)
      AND u.id NOT IN (
          SELECT pm.usuario_id FROM projeto_membros pm WHERE pm.projeto_id = :projeto_id
      )
    ORDER BY u.nome
    LIMIT 8
");
$stmt->execute([
    'qn'         => $like,
    'qe'         => $like,
    'projeto_id' => $projetoId,
]);

echo json_encode($stmt->fetchAll());
