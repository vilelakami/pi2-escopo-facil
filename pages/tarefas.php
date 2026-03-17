<!-- conteúdo da página -->
<section class="page-content">

    <!-- título da página -->
    <div class="page-header">
        <h1 class="headline">Projeto: Sistema de PI</h1>

        <!-- ações da header como notificações e perfil -->
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

    <!-- campo de pesquisa e botão de nova tarefa -->
    <div class="task-search-container">

        <!-- ícone da lupa de pesquisa -->
        <div class="search-input-wrapper">
            <img src="<?= BASE_URL ?>/assets/icon/search.svg" alt="lupa de pesquisa">
            <input
                type="text"
                placeholder="Busque por prioridade, data ou título da tarefa"
            >
        </div>

        <button class="btn-new-task-main">
            + Nova tarefa
        </button>

    </div>

    <!-- conteúdo KANBAN -->
    <div class="kanban">

        <!-- primeira coluna: A Fazer -->
        <div class="kanban-column">

            <!-- conteúdo -->
            <div class="task-card">

                <div class="task-title">
                    <h3 class="title">A Fazer</h3>
                    <img src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="A Fazer">
                </div>

                <div class="task-description">

                    <h3>Criar tela de Login</h3>

                    <div>
                        <p>
                            Montar a estrutura inicial da tela de acesso
                            com validação visual dos campos
                        </p>
                    </div>

                    <div class="task-status">

                        <div class="priority-tag low">
                            <p>Prioridade: Baixa</p>
                            <img src="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg" alt="gráfico de baixa prioridade">
                        </div>

                        <div class="datetime-priority">
                            <p>Prazo:</p>
                            <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                            <input
                                type="text"
                                placeholder="19/02/2026"
                                class="input-deadline"
                            >
                        </div>

                    </div>

                    <div class="task-btn">

                        <button class="btn-edit">
                            <img src="<?= BASE_URL ?>/assets/icon/edit.svg" alt="editar">
                            Editar
                        </button>

                        <button class="btn-delete">
                            Excluir
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <!-- segunda coluna: Em Andamento -->
        <div class="kanban-column">

        <!-- conteúdo -->
            <div class="task-card">

                <div class="task-title">
                    <h3 class="title">Em Andamento</h3>
                    <img src="<?= BASE_URL ?>/assets/icon/loading.svg" alt="Carregando">
                </div>

                <div class="task-description">

                    <h3>Criar tela de Login</h3>

                    <div class="background-description">
                        <p>
                            Montar a estrutura inicial da tela de acesso
                            com validação visual dos campos
                        </p>
                    </div>

                    <div class="task-status">

                        <div class="priority-tag medium">
                            <p>Prioridade: Média</p>
                            <img src="<?= BASE_URL ?>/assets/icon/grafico-media.svg" alt="gráfico de média prioridade">
                        </div>

                        <div class="datetime-priority">
                            <p>Prazo:</p>
                            <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                            <input
                                type="text"
                                placeholder="19/02/2026"
                                class="input-deadline"
                            >
                        </div>

                    </div>

                    <div class="task-btn">

                        <button class="btn-edit">
                            <img src="<?= BASE_URL ?>/assets/icon/edit.svg" alt="editar">
                            Editar
                        </button>

                        <button class="btn-delete">
                            Excluir
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <!-- terceira coluna: Concluído -->
        <div class="kanban-column">

        <!-- conteúdo -->
            <div class="task-card">

                <div class="task-title">
                    <h3 class="title">Concluído</h3>
                    <img src="<?= BASE_URL ?>/assets/icon/concluido.svg" alt="Concluído">
                </div>

                <div class="task-description">

                    <h3>Criar tela de Login</h3>

                    <div class="background-description">
                        <p>
                            Montar a estrutura inicial da tela de acesso
                            com validação visual dos campos
                        </p>
                    </div>

                    <div class="task-status">

                        <div class="priority-tag high">
                            <p>Prioridade: Alta</p>
                            <img src="<?= BASE_URL ?>/assets/icon/grafico-alta.svg" alt="gráfico de alta prioridade">
                        </div>

                        <div class="datetime-priority">
                            <p>Prazo:</p>
                            <img src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                            <input
                                type="text"
                                placeholder="19/02/2026"
                                class="input-deadline"
                            >
                        </div>

                    </div>

                    <div class="task-btn">

                        <button class="btn-edit">
                            <img src="<?= BASE_URL ?>/assets/icon/edit.svg" alt="editar">
                            Editar
                        </button>

                        <button class="btn-delete">
                            Excluir
                        </button>

                    </div>

                </div>
            </div>
        </div>

    </div>

</section>

<?php include __DIR__ . '/modal.php'; ?>

