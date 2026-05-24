<?php
// conexão com o banco e variáveis globais
require_once __DIR__ . '/../../config.php';

// pegar o id do projeto vindo da url
$projeto_id = isset($_GET['projeto_id']) ? (int)$_GET['projeto_id'] : 1;

global $pdo;

if(!isset($pdo)){
    die("Erro: A conexão com o banco de dados (\$pdo) não foi inicializada no config.php.");
}

$nomeProjeto = "Projeto Sem Nome";
// buscando o nome do projeto atual
try {
    $stmtProjeto = $pdo->prepare("SELECT titulo FROM projetos WHERE id = :id");
    $stmtProjeto->execute([':id' => $projeto_id]);
    $projeto = $stmtProjeto->fetch(PDO::FETCH_ASSOC);
    $nomeProjeto = $projeto ? $projeto['titulo'] : "Projeto não encontrado.";
} catch (PDOException $e){
    $nomeProjeto = 'Erro: ' . $e->getMessage();
}

// buscando as tarefas do projet0
$tarefas = [];
try{
    $stmtTarefas = $pdo->prepare("SELECT id, titulo, descricao, prioridade, status, prazo, criado_em FROM tarefas WHERE projeto_id = :projeto_id");
    $stmtTarefas->execute([':projeto_id' => $projeto_id]);
    $tarefas = $stmtTarefas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}

function renderizarCardTarefa($tarefa) {
    $prioridadeClasse = 'low';
    $prioridadeTexto = 'Baixa';
    $prioridadeGrafico = 'grafico-baixa.svg';

    if ($tarefa['prioridade'] == 2) {
        $prioridadeClasse = 'medium';
        $prioridadeTexto = 'Média';
        $prioridadeGrafico = 'grafico-media.svg';
    } elseif ($tarefa['prioridade'] == 3) {
        $prioridadeClasse = 'high';
        $prioridadeTexto = 'Alta';
        $prioridadeGrafico = 'grafico-alta.svg';
    }

    // Formata a data para o padrão brasileiro de forma segura se houver prazo
    $dataFormatada = $tarefa['prazo'] ? date('d/m/Y', strtotime($tarefa['prazo'])) : 'Sem prazo';
    ?>
    <div class="task-card" draggable="true" data-id="<?= $tarefa['id'] ?>">
        <div class="task-description">
            <h3><?= htmlspecialchars($tarefa['titulo']) ?></h3>
            <div>
                <p><?= htmlspecialchars($tarefa['descricao']) ?></p>
            </div>
            <div class="task-status">
                <div class="priority-tag <?= $prioridadeClasse ?>">
                    <p>Prioridade: <?= $prioridadeTexto ?></p>
                    <img src="<?= BASE_URL ?>/assets/icon/<?= $prioridadeGrafico ?>" alt="gráfico de prioridade">
                </div>
                <div class="datetime-priority">
                    <p>Prazo:</p>
                    <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                    <input 
                        type="text" 
                        value="<?= $dataFormatada ?>" 
                        class="input-deadline" 
                        readonly
                    >
                </div>
            </div>
            <div class="task-btn">
                <a href="editar-tarefa.php?id=<?= $tarefa['id'] ?>" class="btn-edit">
                    <img src="<?= BASE_URL ?>/assets/icon/edit.svg" alt="editar"> Editar
                </a>
                <a href="excluir-tarefa.php?id=<?= $tarefa['id'] ?>" class="btn-delete" onclick="return confirm('Deseja excluir esta tarefa?')">
                    Excluir
                </a>
            </div>
        </div>
    </div>
    <?php
}
?>

<section class="page-content">

    <div class="page-header">
        <h1 class="headline">Projeto: <?= htmlspecialchars($nomeProjeto)?></h1>

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
            <input 
                type="text" 
                placeholder="Busque por prioridade, data ou título da tarefa"
            >
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
            <?php
            foreach ($tarefas as $tarefa){
                if($tarefa['status'] == 1){ 
                    renderizarCardTarefa($tarefa);
                }
            }
            ?>
        </div>

        <div class="kanban-column" id="col-2">
            <div class="task-title">
                <h3 class="title">Em Andamento</h3>
                <img src="<?= BASE_URL ?>/assets/icon/loading.svg" alt="Carregando">
            </div>
            <?php
            foreach($tarefas as $tarefa){
                if($tarefa['status'] == 2){
                    renderizarCardTarefa($tarefa);
                }
            }
            ?>
        </div>

        <div class="kanban-column" id="col-3">
            <div class="task-title">
                <h3 class="title">Concluído</h3>
                <img src="<?= BASE_URL ?>/assets/icon/concluido.svg" alt="Concluído">
            </div>
            <?php
            foreach($tarefas as $tarefa){
                if($tarefa['status'] == 3){
                    renderizarCardTarefa($tarefa);
                }
            }
            ?>
        </div>

    </div>

    <div id="no-tasks-message" style="display: none; text-align: center; padding: 20px; color: #666;">
        <p>Nenhuma tarefa encontrada com este termo.</p>
    </div>

</section>

<?php include __DIR__ . '/nova-tarefa.php'; ?>
<script src="<?= BASE_URL ?>/assets/js/pages/tarefas.js"></script>
