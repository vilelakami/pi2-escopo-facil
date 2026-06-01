<?php
require_once __DIR__ . '/../config.php';
$pdo = getConnection();

// 1. Dados para os indicadores
$totalProjetos = $pdo->query("SELECT COUNT(*) FROM projetos")->fetchColumn();
$tarefasConcluidas = $pdo->query("SELECT COUNT(*) FROM tarefas WHERE status = 'concluido'")->fetchColumn();
$tarefasPendentes = $pdo->query("SELECT COUNT(*) FROM tarefas WHERE status = 'a-fazer'")->fetchColumn();
$tarefasFazendo = $pdo->query("SELECT COUNT(*) FROM tarefas WHERE status = 'fazendo'")->fetchColumn();

$counts = ['a-fazer' => $tarefasPendentes, 'fazendo' => $tarefasFazendo, 'concluido' => $tarefasConcluidas];
$totalTarefas = array_sum($counts);
$progresso = ($totalTarefas > 0) ? round(($tarefasConcluidas / $totalTarefas) * 100) : 0;

// 2. Atividades recentes
$recentes = $pdo->query("SELECT titulo, status FROM tarefas ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// 3. Dados para o gráfico de linha (Simulando 7 dias)
$labelsDias = json_encode(['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom']);
$performanceJson = json_encode([1, 3, 2, 5, 4, 3, 2]);
?>

<h1 class="page-title">Dashboard</h1>

<div class="dashboard-container">
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Projetos Ativos</h3>
            <p class="number"><?= $totalProjetos ?></p>
        </div>
        <div class="stat-card warning">
            <h3>Tarefas Pendentes</h3>
            <p class="number"><?= $counts['a-fazer'] ?></p>
        </div>
        <div class="stat-card success">
            <h3>Conclusão do Escopo</h3>
            <p class="number"><?= $progresso ?>%</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $progresso ?>%;"></div>
            </div>
        </div>
    </div>

    <div class="stat-card" style="margin-top: 25px; padding: 30px;">
        <h3>Distribuição de Tarefas</h3>
        <div style="height: 350px; width: 100%; position: relative; margin-top: 20px;">
            <canvas id="tasksChart"></canvas>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 25px;">
        
        <div class="stat-card">
            <h3>Atividades Recentes</h3>
            <ul class="activity-list" style="list-style: none; padding: 0; margin-top: 15px;">
                <?php foreach ($recentes as $item): ?>
                    <li class="activity-item" style="padding: 12px 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #1e293b;"><?= htmlspecialchars($item['titulo']) ?></strong>
                        <span class="status-tag <?= $item['status'] ?>"><?= $item['status'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="stat-card">
            <h3>Ritmo de Trabalho</h3>
            <div style="height: 250px; width: 100%; position: relative; margin-top: 15px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

    </div>

</div>

<script>
window.addEventListener('load', function() {
    const cores = ['#a31545', '#64748b', '#10b981'];

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
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 30, font: { size: 15, weight: '50' } }
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
                    borderColor: '#a31545',
                    backgroundColor: 'rgba(163, 21, 69, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#a31545'
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