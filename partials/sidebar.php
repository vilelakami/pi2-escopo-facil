<?php $page = $_GET['page'] ?? 'dashboard'; ?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="/assets/images/Logo.png" alt="Escopo Fácil">
    </div>

    <nav class="sidebar-nav">
        <a href="/index.php?page=dashboard" class="sidebar-link <?= $page === 'dashboard' ? 'active' : '' ?>">
            <img src="/assets/icon/dashboard.svg" alt=""> Dashboard
        </a>
        <a href="/index.php?page=projetos" class="sidebar-link <?= $page === 'projetos' ? 'active' : '' ?>">
            <img src="/assets/icon/projetos.svg" alt=""> Projetos
        </a>
        <a href="/index.php?page=tarefas" class="sidebar-link <?= $page === 'tarefas' ? 'active' : '' ?>">
            <img src="/assets/icon/tarefas.svg" alt=""> Tarefas
        </a>
        <a href="/index.php?page=membros" class="sidebar-link <?= $page === 'membros' ? 'active' : '' ?>">
            <img src="/assets/icon/membros.svg" alt=""> Membros
        </a>
        <a href="/index.php?page=configuracao" class="sidebar-link <?= $page === 'configuracao' ? 'active' : '' ?>">
            <img src="/assets/icon/configurações.svg" alt=""> Configuração
        </a>
    </nav>
</aside>
