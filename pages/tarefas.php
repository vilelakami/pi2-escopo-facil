<!-- conteúdo da página -->
<section class="page-content">

<!-- título da página -->
    <div class="page-header">
        <h1 class="headline">Projeto: Sistema de PI</h1>
        
        <!-- ações da header como, notificações e perfil -->
        <div class="header-actions">
            <div class="notification-icon">
                <img src="assets/icon/bells.svg" alt="notificações">
            </div>
            <div class="member-profile">
                <img src="assets/icon/avatar.svg" alt="foto de perfil" class="avatar">
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
            <img src="assets/icon/search.svg" alt="lupa de pesquisa"> <input type="text" placeholder="Busque por prioridade, data ou título da tarefa">
        </div>
        <button class="btn-new-task-main">+ Nova tarefa</button>
    </div>

    <!-- conteúdo KANBAN -->
    <div class="kanban">
        
        <!-- primeira coluna: Planejamento -->
        <div class="kanban-column">
            <!-- conteúdo -->
            <div class="task-card">
                <div class="task-title">
                    <h3 class="title">A Fazer</h3>
                    <h3 class="task-menu">
                        ⋯
                    </h3>
                </div>

                <div class="task-category">
                    Category
                </div>

                <div class="task-description">
                    Descrição
                </div>

                <div class="task-image"></div>

                <div class="task-footer">

                    <div class="task-users">
                        👤 👤 👤
                    </div>

                    <div class="task-actions">
                        ➕ 📅 📌 🚩
                    </div>

                    <div class="task-menu">
                        ⋯
                    </div>

                </div>

            </div>

            <button class="btn-new-task">+ Nova tarefa</button>

        </div>

        <!-- segunda coluna: Em Andamento -->
         <div class="kanban-column">
            <!-- conteúdo -->
            <div class="task-card">
                <div class="task-title">
                    <h3 class="title">Em Andamento</h3>
                    <h3 class="task-menu">
                        ⋯
                    </h3>
                </div>

                <div class="task-category">
                    Category
                </div>

                <div class="task-description">
                    Descrição
                </div>

                <!-- <div class="task-image"></div> -->

                <div class="task-footer">

                    <div class="task-users">
                        👤 👤 👤
                    </div>

                    <div class="task-actions">
                        ➕ 📅 📌 🚩
                    </div>

                    <div class="task-menu">
                        ⋯
                    </div>

                </div>

            </div>

            <button class="btn-new-task">+ Nova tarefa</button>

        </div>

        <!-- terceira coluna: Concluído -->
         <div class="kanban-column">
            <!-- conteúdo -->
            <div class="task-card">
                <div class="task-title">
                    <h3 class="title">Concluído</h3>
                    <h3 class="task-menu">
                        ⋯
                    </h3>
                </div>

                <div class="task-category">
                    Category
                </div>

                <div class="task-description">
                    Descrição
                </div>

                <!-- <div class="task-image"></div> -->

                <div class="task-footer">

                    <div class="task-users">
                        👤 👤 👤
                    </div>

                    <div class="task-actions">
                        ➕ 📅 📌 🚩
                    </div>

                    <div class="task-menu">
                        ⋯
                    </div>

                </div>

            </div>

            <button class="btn-new-task">+ Nova tarefa</button>

        </div>
    </div>
</section>