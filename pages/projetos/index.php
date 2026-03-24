<?php include __DIR__ . '/mocks.php'; ?>

<div class="projetos">
    <!-- Header -->
    <div class="projetos-header">
        <h1 class="projetos-title">Projetos</h1>
        <div class="projetos-header-right">
            <div class="projetos-notification">
                <img src="<?= BASE_URL ?>/assets/icon/bell.svg" alt="Notificações">
                <span class="notification-dot"></span>
            </div>
            <div class="projetos-user">
                <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="Avatar" class="projetos-avatar">
                <div class="projetos-user-info">
                    <span class="projetos-user-name"><?= htmlspecialchars($usuario['nome']) ?></span>
                    <span class="projetos-user-role"><?= htmlspecialchars($usuario['role']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de busca e botão -->
    <div class="projetos-toolbar">
        <div class="projetos-search">
            <img src="<?= BASE_URL ?>/assets/icon/search.svg" alt="Buscar" class="projetos-search-icon">
            <input type="text" placeholder="Busque por prioridade, data ou titulo da tarefa" class="projetos-search-input">
        </div>
        <button class="projetos-btn-criar">
            <img src="<?= BASE_URL ?>/assets/icon/plus.svg" alt="+" class="projetos-btn-icon"> Criar projeto
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="projetos-grid">
        <?php foreach ($projetos as $index => $projeto):
            $isAdmin = $projeto['role'] === 'admin';
            $roleClass = $isAdmin ? 'admin' : 'membro';
            $icon = $isAdmin ? BASE_URL . '/assets/icon/user-key.svg' : BASE_URL . '/assets/icon/user-lock.svg';
            $badgeText = $isAdmin ? 'Admin' : 'Membro';
        ?>
        <div class="projeto-card">
            <div class="projeto-card-top">
                <div class="projeto-card-icon projeto-card-icon--<?= $roleClass ?>">
                    <img src="<?= $icon ?>" alt="<?= $badgeText ?>">
                </div>
                <span class="projeto-badge projeto-badge--<?= $roleClass ?>"><?= $badgeText ?></span>
            </div>
            <h2 class="projeto-card-title"><?= htmlspecialchars($projeto['titulo']) ?></h2>
            <p class="projeto-card-desc"><?= htmlspecialchars($projeto['descricao']) ?></p>
            <div class="projeto-card-avatars">
                <img src="<?= BASE_URL ?>/assets/images/Avatar Group.png" alt="Membros">
                <?php if ($projeto['membros_extra'] > 0): ?>
                    <span class="projeto-card-extra">+<?= $projeto['membros_extra'] ?></span>
                <?php endif; ?>
            </div>
            <div class="projeto-card-separator"></div>
            <div class="projeto-card-actions">
                <button class="projeto-btn-ver">Ver tarefas</button>
                <?php if ($isAdmin): ?>
                    <button class="projeto-btn-gerenciar" data-index="<?= $index ?>">Gerenciar</button>
                <?php else: ?>
                    <button class="projeto-btn-sair">Sair</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/modais/novo-projeto.php'; ?>
<?php include __DIR__ . '/modais/gerenciar-projeto.php'; ?>

<script>
    window.projetosMock = <?= json_encode($projetos, JSON_UNESCAPED_UNICODE) ?>;
</script>
