<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/avatar.php';
$pdo = getConnection();

$usuarioId   = (int) usuarioLogado();
$stmtUser    = $pdo->prepare("SELECT nome, cargo, avatar FROM usuarios WHERE id = :id");
$stmtUser->execute(['id' => $usuarioId]);
$dadosUsuario = $stmtUser->fetch();
$usuario = [
    'nome'   => $dadosUsuario['nome']   ?? 'Usuário',
    'role'   => $dadosUsuario['cargo']  ?? '',
    'avatar' => $dadosUsuario['avatar'] ?? '',
];

$stmtProjetos = $pdo->prepare("
    SELECT COUNT(DISTINCT p.id)
    FROM projetos p
    INNER JOIN projeto_membros pm ON pm.projeto_id = p.id
    WHERE pm.usuario_id = :usuario_id
");
$stmtProjetos->execute(['usuario_id' => $usuarioId]);
$totalProjetos = (int) $stmtProjetos->fetchColumn();

function contarTarefasPorStatus(PDO $pdo, int $usuarioId, int $status): int
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM tarefas t
        INNER JOIN projeto_membros pm ON pm.projeto_id = t.projeto_id
        WHERE pm.usuario_id = :usuario_id
          AND t.status = :status
    ");

    $stmt->execute([
        'usuario_id' => $usuarioId,
        'status' => $status,
    ]);

    return (int) $stmt->fetchColumn();
}

$tarefasPendentes  = contarTarefasPorStatus($pdo, $usuarioId, 1);
$tarefasFazendo    = contarTarefasPorStatus($pdo, $usuarioId, 2);
$tarefasConcluidas = contarTarefasPorStatus($pdo, $usuarioId, 3);

$totalTarefas = $tarefasPendentes + $tarefasFazendo + $tarefasConcluidas;
$progresso    = $totalTarefas > 0 ? round(($tarefasConcluidas / $totalTarefas) * 100) : 0;

$stmtRecentes = $pdo->prepare("
    SELECT t.titulo, t.status
    FROM tarefas t
    INNER JOIN projeto_membros pm ON pm.projeto_id = t.projeto_id
    WHERE pm.usuario_id = :usuario_id
    ORDER BY t.atualizado_em DESC, t.id DESC
    LIMIT 5
");
$stmtRecentes->execute(['usuario_id' => $usuarioId]);
$recentes = $stmtRecentes->fetchAll(PDO::FETCH_ASSOC);

$statusLabel = [1 => 'A Fazer', 2 => 'Em Andamento', 3 => 'Concluído'];
$statusClass = [1 => 'a-fazer', 2 => 'fazendo', 3 => 'concluido'];

$labels = [];
$concluidasPorDia = [];
for ($i = 6; $i >= 0; $i--) {
    $data = new DateTime("-$i days");
    $chave = $data->format('Y-m-d');
    $labels[] = $data->format('d/m');
    $concluidasPorDia[$chave] = 0;
}

$stmtRitmo = $pdo->prepare("
    SELECT DATE(t.atualizado_em) AS dia, COUNT(*) AS total
    FROM tarefas t
    INNER JOIN projeto_membros pm ON pm.projeto_id = t.projeto_id
    WHERE pm.usuario_id = :usuario_id
      AND t.status = 3
      AND DATE(t.atualizado_em) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(t.atualizado_em)
");
$stmtRitmo->execute(['usuario_id' => $usuarioId]);

foreach ($stmtRitmo->fetchAll(PDO::FETCH_ASSOC) as $linha) {
    if (isset($concluidasPorDia[$linha['dia']])) {
        $concluidasPorDia[$linha['dia']] = (int) $linha['total'];
    }
}

$labelsDias = json_encode($labels, JSON_UNESCAPED_UNICODE);
$performanceJson = json_encode(array_values($concluidasPorDia));
?>

<div class="page-header">
    <h1 class="headline">Olá, <?= htmlspecialchars(explode(' ', $usuario['nome'])[0]) ?> 👋</h1>
    <div class="header-actions">
        <div class="notification-icon">
            <img src="<?= BASE_URL ?>/assets/icon/bells.svg" alt="notificacoes">
        </div>
        <div class="member-profile user-menu-trigger" style="padding:6px 8px;border-radius:8px">
            <?= renderAvatar($usuario['avatar'], $usuario['nome'], 'avatar') ?>
            <div class="member-info">
                <p class="member-name"><?= htmlspecialchars($usuario['nome']) ?></p>
                <p class="member-category"><?= htmlspecialchars($usuario['role']) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Projetos Ativos</h3>
            <p class="number"><?= $totalProjetos ?></p>
        </div>
        <div class="stat-card warning">
            <h3>Tarefas Pendentes</h3>
            <p class="number"><?= $tarefasPendentes ?></p>
        </div>
        <div class="stat-card success">
            <h3>Conclusão do Escopo</h3>
            <p class="number"><?= $progresso ?>%</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $progresso ?>%;"></div>
            </div>
        </div>
    </div>

    <div class="dashboard-bottom">

        <div class="stat-card dashboard-chart-card">
            <h3>Distribuição de Tarefas</h3>
            <div class="chart-wrapper">
                <canvas id="tasksChart"></canvas>
            </div>
        </div>

        <div class="dashboard-right">

            <div class="stat-card dashboard-activity-card">
                <h3>Atividades Recentes</h3>
                <ul class="activity-list">
                    <?php foreach ($recentes as $item):
                        $s = (int) $item['status'];
                    ?>
                        <li class="activity-item">
                            <span class="activity-title"><?= htmlspecialchars($item['titulo']) ?></span>
                            <span class="status-tag <?= $statusClass[$s] ?? '' ?>"><?= $statusLabel[$s] ?? '-' ?></span>
                        </li>
                    <?php endforeach; ?>
                    <?php if (empty($recentes)): ?>
                        <li class="activity-empty">Nenhuma tarefa ainda.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="stat-card dashboard-line-card">
                <h3>Ritmo de Trabalho</h3>
                <div class="chart-wrapper">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
window.addEventListener('load', function () {
    const cores = ['#8B0836', '#64748b', '#10b981'];

    // GRÁFICO DE ROSCA (Status)
    const ctxStatus = document.getElementById('tasksChart');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Pendente', 'Em Andamento', 'Concluído'],
                datasets: [{
                    data: [<?= $tarefasPendentes ?>, <?= $tarefasFazendo ?>, <?= $tarefasConcluidas ?>],
                    backgroundColor: cores,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 13 } }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // GRÁFICO DE LINHA (Ritmo) 
    const ctxLine = document.getElementById('lineChart');
    if (ctxLine) {
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: <?= $labelsDias ?>,
                datasets: [{
                    label: 'Concluídas',
                    data: <?= $performanceJson ?>,
                    borderColor: '#8B0836',
                    backgroundColor: 'rgba(139, 8, 54, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#8B0836'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
