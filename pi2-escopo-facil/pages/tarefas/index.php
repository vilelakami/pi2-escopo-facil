<?php
// 1. Puxa as configurações e a função de conexão
require_once __DIR__ . '/../../config.php';
$pdo = getConnection();

/**
 * 2. BUSCA DE DADOS: Filtramos por status para cada coluna do Kanban
 */

// Tarefas 'A Fazer'
$stmtTodo = $pdo->prepare("SELECT * FROM tarefas WHERE status = 'a-fazer' ORDER BY criado_em DESC");
$stmtTodo->execute();
$tarefasTodo = $stmtTodo->fetchAll();

// Tarefas 'Em Andamento'
$stmtDoing = $pdo->prepare("SELECT * FROM tarefas WHERE status = 'em-andamento' ORDER BY criado_em DESC");
$stmtDoing->execute();
$tarefasDoing = $stmtDoing->fetchAll();

// Tarefas 'Concluído'
$stmtDone = $pdo->prepare("SELECT * FROM tarefas WHERE status = 'concluido' ORDER BY criado_em DESC");
$stmtDone->execute();
$tarefasDone = $stmtDone->fetchAll();

/**
 * 3. FUNÇÃO AUXILIAR: Define as classes de cor do CSS
 */
function getPriorityClass($priority) {
    $p = strtolower($priority);
    if ($p == 'alta') return 'high';
    if ($p == 'média' || $p == 'media') return 'medium';
    return 'low';
}
?>

<section class="page-content">

    <div class="page-header">
        <h1 class="headline">Projeto: Sistema de PI</h1>

        <div class="header-actions">
            <div class="notification-icon">
                <img src="<?= BASE_URL ?>/assets/icon/bells.svg" alt="notificações">
            </div>

            <div class="member-profile">
                <img src="<?= BASE_URL ?>/assets/icon/avatar.svg" alt="foto de perfil" class="avatar">
                <div class="member-info">
                    <p class="member-name">Natan Oliveira</p>
                    <p class="member-category">Membro</p>
                </div>
            </div>
        </div>
    </div>

    <div class="task-search-container">
        <div class="search-input-wrapper">
            <img src="<?= BASE_URL ?>/assets/icon/search.svg" alt="lupa de pesquisa">
            <input type="text" placeholder="Busque por prioridade, data ou título da tarefa">
        </div>

        <button class="btn-new-task-main">
            <img src="<?= BASE_URL ?>/assets/icon/plus.svg" alt="+">
            Nova tarefa
        </button>
    </div>

    <div class="kanban">

        <div class="kanban-column" id="col-1">
            <div class="task-title">
                <h3 class="title">A Fazer</h3>
                <img src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="A Fazer">
            </div>

            <?php foreach ($tarefasTodo as $t): ?>
                <div class="task-card" draggable="true">
                    <div class="task-description">
                        <h3><?= htmlspecialchars($t['titulo']) ?></h3>
                        <p><?= htmlspecialchars($t['descricao']) ?></p>

                        <div class="task-status">
                            <div class="priority-tag <?= getPriorityClass($t['prioridade']) ?>">
                                <p>Prioridade: <?= htmlspecialchars($t['prioridade']) ?></p>
                                <img src="<?= BASE_URL ?>/assets/icon/grafico-<?= getPriorityClass($t['prioridade']) ?>.svg" alt="ícone">
                            </div>

                            <div class="datetime-priority">
                                <p>Prazo:</p>
                                <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                                <input type="text" value="<?= date('d/m/Y', strtotime($t['prazo'])) ?>" class="input-deadline" readonly>
                            </div>
                        </div>

                        <div class="task-btn">
                            <button class="btn-edit">Editar</button>
                            <button class="btn-delete">Excluir</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="kanban-column" id="col-2">
            <div class="task-title">
                <h3 class="title">Em Andamento</h3>
                <img src="<?= BASE_URL ?>/assets/icon/loading.svg" alt="Carregando">
            </div>

            <?php foreach ($tarefasDoing as $t): ?>
                <div class="task-card" draggable="true">
                    <div class="task-description">
                        <h3><?= htmlspecialchars($t['titulo']) ?></h3>
                        <p><?= htmlspecialchars($t['descricao']) ?></p>
                        <div class="task-status">
                            <div class="priority-tag <?= getPriorityClass($t['prioridade']) ?>">
                                <p>Prioridade: <?= htmlspecialchars($t['prioridade']) ?></p>
                                <img src="<?= BASE_URL ?>/assets/icon/grafico-<?= getPriorityClass($t['prioridade']) ?>.svg" alt="ícone">
                            </div>
                            <div class="datetime-priority">
                                <p>Prazo:</p>
                                <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                                <input type="text" value="<?= date('d/m/Y', strtotime($t['prazo'])) ?>" class="input-deadline" readonly>
                            </div>
                        </div>
                        <div class="task-btn">
                            <button class="btn-edit">Editar</button>
                            <button class="btn-delete">Excluir</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="kanban-column" id="col-3">
            <div class="task-title">
                <h3 class="title">Concluído</h3>
                <img src="<?= BASE_URL ?>/assets/icon/concluido.svg" alt="Concluído">
            </div>

            <?php foreach ($tarefasDone as $t): ?>
                <div class="task-card" draggable="true">
                    <div class="task-description">
                        <h3><?= htmlspecialchars($t['titulo']) ?></h3>
                        <p><?= htmlspecialchars($t['descricao']) ?></p>
                        <div class="task-status">
                            <div class="priority-tag <?= getPriorityClass($t['prioridade']) ?>">
                                <p>Prioridade: <?= htmlspecialchars($t['prioridade']) ?></p>
                                <img src="<?= BASE_URL ?>/assets/icon/grafico-<?= getPriorityClass($t['prioridade']) ?>.svg" alt="ícone">
                            </div>
                            <div class="datetime-priority">
                                <p>Prazo:</p>
                                <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                                <input type="text" value="<?= date('d/m/Y', strtotime($t['prazo'])) ?>" class="input-deadline" readonly>
                            </div>
                        </div>
                        <div class="task-btn">
                            <button class="btn-edit">Editar</button>
                            <button class="btn-delete">Excluir</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <div id="no-tasks-message" style="display: none; text-align: center; padding: 20px; color: #666;">
        <p>Nenhuma tarefa encontrada com este termo.</p>
    </div>

</section>

<?php include __DIR__ . '/nova-tarefa.php'; ?>

<script src="<?= BASE_URL ?>/assets/js/pages/tarefas.js"></script>