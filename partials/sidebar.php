<?php $page = $_GET['page'] ?? 'dashboard'; ?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="<?= BASE_URL ?>/assets/images/Logo.png" alt="Escopo Fácil">
    </div>

    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="sidebar-link <?= $page === 'dashboard' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/dashboard.svg" alt=""> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=tarefas" class="sidebar-link <?= $page === 'tarefas' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/tarefas.svg" alt=""> Tarefas
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=membros" class="sidebar-link <?= $page === 'membros' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/membros.svg" alt=""> Membros
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=configuracao" class="sidebar-link <?= $page === 'configuracao' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/configurações.svg" alt=""> Configuração
        </a>
    </nav>
</aside>
