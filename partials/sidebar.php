<?php 
$page = $_GET['page'] ?? 'dashboard';
// Quando estiver vendo tarefas de um projeto, marcar "Projetos" como ativo
$ativoProjetosQuandoTarefas = ($page === 'tarefas' && !empty($_GET['projeto_id']) && (int)$_GET['projeto_id'] > 0);
$activeClass = function($pageName) use ($page, $ativoProjetosQuandoTarefas) {
    // Se está vendo tarefas com projeto_id, apenas Projetos fica ativo
    if ($ativoProjetosQuandoTarefas) {
        return $pageName === 'projetos' ? 'active' : '';
    }
    // Caso contrário, usar a lógica normal
    return $pageName === $page ? 'active' : '';
};
?>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="<?= BASE_URL ?>/assets/images/Logo.png" alt="Escopo Fácil">
    </div>

    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="sidebar-link <?= $activeClass('dashboard') ?>">
            <img src="<?= BASE_URL ?>/assets/icon/dashboard.svg" alt=""> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=projetos" class="sidebar-link <?= $activeClass('projetos') ?>">
            <img src="<?= BASE_URL ?>/assets/icon/folder-search.svg" alt=""> Projetos
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=configuracao" class="sidebar-link <?= $activeClass('configuracao') ?>">
            <img src="<?= BASE_URL ?>/assets/icon/configurações.svg" alt=""> Configuração
        </a>
    </nav>

    <div class="sidebar-banner">
        <div class="sidebar-banner-icon">
            <img src="<?= BASE_URL ?>/assets/icon/logo/Vector%20(3).svg" alt="Escopo Fácil" width="32" height="32">
        </div>
        <p class="sidebar-banner-title">Acesse nosso site</p>
        <p class="sidebar-banner-desc">Conheça planos, recursos e novidades do Escopo Fácil.</p>
        <a href="https://magenta-potato-409547.framer.app/" target="_blank" rel="noopener noreferrer" class="sidebar-banner-btn">
            Visitar site
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform:scaleX(-1);flex-shrink:0">
                <path d="M19 12H5"/><path d="M12 19L5 12L12 5"/>
            </svg>
        </a>
    </div>

    <div class="sidebar-logout">
        <a href="<?= BASE_URL ?>/actions/auth/logout.php" class="sidebar-link">
            <img src="<?= BASE_URL ?>/assets/icon/logout.svg" alt=""> Sair
        </a>
    </div>
</aside>
