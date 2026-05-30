<?php $page = $_GET['page'] ?? 'dashboard'; ?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="<?= BASE_URL ?>/assets/images/Logo.png" alt="Escopo Fácil">
    </div>

    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="sidebar-link <?= $page === 'dashboard' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/dashboard.svg" alt=""> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=projetos" class="sidebar-link <?= $page === 'projetos' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/folder-search.svg" alt=""> Projetos
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=tarefas" class="sidebar-link <?= $page === 'tarefas' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/tarefas.svg" alt=""> Tarefas
        </a>
<a href="<?= BASE_URL ?>/index.php?page=configuracao" class="sidebar-link <?= $page === 'configuracao' ? 'active' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/icon/configurações.svg" alt=""> Configuração
        </a>
    </nav>

    <div class="sidebar-banner">
        <div class="sidebar-banner-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.75">
                <circle cx="12" cy="12" r="10"/>
                <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10A15.3 15.3 0 0 1 12 2z"/>
            </svg>
        </div>
        <p class="sidebar-banner-title">Acesse nosso site</p>
        <p class="sidebar-banner-desc">Conheça planos, recursos e novidades do Escopo Fácil.</p>
        <a href="https://magenta-potato-409547.framer.app/" target="_blank" rel="noopener noreferrer" class="sidebar-banner-btn">
            Visitar site →
        </a>
    </div>

    <div class="sidebar-logout">
        <a href="<?= BASE_URL ?>/index.php?page=login" class="sidebar-link">
            <img src="<?= BASE_URL ?>/assets/icon/logout.svg" alt=""> Sair
        </a>
    </div>
</aside>
