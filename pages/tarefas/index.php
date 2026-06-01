<?php
require_once __DIR__ . '/../../includes/auth_guard.php';
require_once __DIR__ . '/../../models/Projeto.php';
require_once __DIR__ . '/../../models/ProjetoMembro.php';
require_once __DIR__ . '/../../models/Tarefa.php';
require_once __DIR__ . '/../../models/Usuario.php';

$usuarioId = (int) usuarioLogado();
$projetoId = (int) ($_GET['projeto_id'] ?? 0);
$usuario = Usuario::buscarPorId($usuarioId);
$projeto = $projetoId ? Projeto::buscarPorId($projetoId) : false;
$isMembro = $projetoId ? ProjetoMembro::jaEMembro($projetoId, $usuarioId) : false;
$tarefas = ($projeto && $isMembro) ? Tarefa::listarPorProjeto($projetoId) : [];
require_once __DIR__ . '/../../includes/avatar.php';
$avatarStr = $usuario['avatar'] ?? '';

$prioridades = [
    1 => ['texto' => 'Baixa', 'classe' => 'low', 'icone' => 'grafico-baixa.svg'],
    2 => ['texto' => 'Media', 'classe' => 'medium', 'icone' => 'grafico-media.svg'],
    3 => ['texto' => 'Alta', 'classe' => 'high', 'icone' => 'grafico-alta.svg'],
];

function tarefasPorStatus(array $tarefas, int $status): array
{
    return array_values(array_filter($tarefas, fn ($tarefa) => (int) $tarefa['status'] === $status));
}

function formatarPrazo(?string $prazo): string
{
    if (!$prazo) {
        return 'Sem prazo';
    }

    return date('d/m/Y', strtotime($prazo));
}
?>

<section class="page-content" data-projeto-id="<?= $projetoId ?>">

    <div class="page-header">
        <h1 class="headline">
            <?= $projeto ? 'Projeto: ' . htmlspecialchars($projeto['titulo']) : 'Tarefas' ?>
        </h1>


        <div class="header-actions">
            <div class="notification-icon">
                <img src="<?= BASE_URL ?>/assets/icon/bells.svg" alt="notificacoes">
            </div>

            <div class="member-profile user-menu-trigger" style="padding:6px 8px;border-radius:8px">
                <?= renderAvatar($avatarStr, $usuario['nome'] ?? 'U', 'avatar') ?>

                <div class="member-info">
                    <p class="member-name"><?= htmlspecialchars($usuario['nome'] ?? 'Usuario') ?></p>
                    <p class="member-category"><?= htmlspecialchars($usuario['cargo'] ?? 'Membro') ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$projetoId): ?>
        <div class="no-project-message">
            <p>Nenhum projeto selecionado.</p>
            <p>Acesse a página <strong>Projetos</strong> e clique em <strong>"Ver tarefas"</strong> para começar.</p>
            <a href="<?= BASE_URL ?>/index.php?page=projetos" class="no-project-btn">Ir para Projetos</a>
        </div>
    <?php elseif (!$projeto): ?>
        <div class="no-project-message">
            <p>Projeto nao encontrado.</p>
        </div>
    <?php elseif (!$isMembro): ?>
        <div class="no-project-message">
            <p>Voce nao tem acesso as tarefas deste projeto.</p>
        </div>
    <?php else: ?>
        <div class="task-search-container">
            <div class="search-input-wrapper">
                <img src="<?= BASE_URL ?>/assets/icon/search.svg" alt="lupa de pesquisa">
                <input
                    type="text"
                    placeholder="Busque por prioridade, data ou titulo da tarefa"
                >
            </div>


            <button class="btn-new-task-main">
                <img src="<?= BASE_URL ?>/assets/icon/plus.svg" alt="+">
                Nova tarefa
            </button>
        </div>

        <div class="kanban">
            <?php
                $colunas = [
                    1 => ['titulo' => 'A Fazer', 'icone' => 'a-fazer.svg', 'alt' => 'A Fazer'],
                    2 => ['titulo' => 'Em Andamento', 'icone' => 'loading.svg', 'alt' => 'Em Andamento'],
                    3 => ['titulo' => 'Concluido', 'icone' => 'concluido.svg', 'alt' => 'Concluido'],
                ];
            ?>

            <?php foreach ($colunas as $status => $coluna): ?>
                <div class="kanban-column" id="col-<?= $status ?>" data-status="<?= $status ?>">
                    <div class="task-title">
                        <h3 class="title"><?= $coluna['titulo'] ?></h3>
                        <img src="<?= BASE_URL ?>/assets/icon/<?= $coluna['icone'] ?>" alt="<?= $coluna['alt'] ?>">
                    </div>

                    <?php foreach (tarefasPorStatus($tarefas, $status) as $tarefa):
                        $prioridadeId = (int) $tarefa['prioridade'];
                        $prioridade = $prioridades[$prioridadeId] ?? $prioridades[1];
                    ?>
                        <div
                            class="task-card"
                            draggable="true"
                            data-id="<?= (int) $tarefa['id'] ?>"
                            data-titulo="<?= htmlspecialchars($tarefa['titulo']) ?>"
                            data-descricao="<?= htmlspecialchars($tarefa['descricao'] ?? '') ?>"
                            data-prioridade="<?= $prioridadeId ?>"
                            data-status="<?= (int) $tarefa['status'] ?>"
                            data-prazo="<?= htmlspecialchars($tarefa['prazo'] ?? '') ?>"
                        >
                            <div class="task-description">
                                <h3><?= htmlspecialchars($tarefa['titulo']) ?></h3>

                                <div class="background-description">
                                    <p><?= nl2br(htmlspecialchars($tarefa['descricao'] ?? '')) ?></p>
                                </div>

                                <div class="task-status">
                                    <div class="priority-tag <?= $prioridade['classe'] ?>">
                                        <p>Prioridade: <?= $prioridade['texto'] ?></p>
                                        <img src="<?= BASE_URL ?>/assets/icon/<?= $prioridade['icone'] ?>" alt="prioridade">
                                    </div>

                                    <div class="datetime-priority">
                                        <p>Prazo:</p>
                                        <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendario">
                                        <span class="deadline-text"><?= formatarPrazo($tarefa['prazo'] ?? null) ?></span>
                                    </div>
                                </div>

                                <div class="task-btn">
                                    <button class="btn-edit">
                                        <img src="<?= BASE_URL ?>/assets/icon/edit.svg" alt="editar">
                                        Editar
                                    </button>

                                    <button class="btn-delete">Excluir</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="no-tasks-message" style="display: none; text-align: center; padding: 20px; color: #666;">
            <p>Nenhuma tarefa encontrada com este termo.</p>
        </div>

        <?php include __DIR__ . '/nova-tarefa.php'; ?>
    <?php endif; ?>
</section>

<script>
    window.projetoId = <?= json_encode($projetoId) ?>;
    window.tarefasData = <?= json_encode($tarefas, JSON_UNESCAPED_UNICODE) ?>;
</script>

