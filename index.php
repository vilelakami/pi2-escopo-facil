<?php
require __DIR__ . '/config.php';
require __DIR__ . '/includes/session.php';
$page = $_GET['page'] ?? 'login';

// Páginas de autenticação (layout próprio, sem sidebar)
$authPages = ['login', 'cadastro', 'esqueci-senha', 'redefinir-senha', 'confirmacao'];
if ($page === 'confirmar-email') {
    require __DIR__ . '/actions/auth/confirmar-email.php';
    exit;
}

if (in_array($page, $authPages) && file_exists(__DIR__ . '/pages/auth/' . $page . '.php')) {
    if (estaLogado() && in_array($page, ['login', 'cadastro'], true)) {
        header('Location: ' . BASE_URL . '/index.php?page=dashboard');
        exit;
    }

    include __DIR__ . '/pages/auth/' . $page . '.php';
    exit;
}

$allowed = ['dashboard', 'projetos', 'tarefas', 'membros', 'configuracao'];
if (in_array($page, $allowed, true) && !estaLogado()) {
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit;
}

// Carrega dados do usuário logado para o user-menu global
$_currentUser = [];
if (estaLogado()) {
    require_once __DIR__ . '/models/Usuario.php';
    require_once __DIR__ . '/includes/avatar.php';
    $_currentUser = Usuario::buscarPorId((int) $_SESSION['usuario_id']) ?: [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escopo Fácil</title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/icon/logo/Vector%20(3).svg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/avatar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/toast.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user-menu.css">
    <?php if (file_exists(__DIR__ . '/assets/css/pages/' . $page . '.css')): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/<?= $page ?>.css?v=<?= time() ?>">
    <?php endif; ?>
</head>
<body data-base-url="<?= BASE_URL ?>" data-usuario-id="<?= $_SESSION['usuario_id'] ?? '' ?>">
    <div class="layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <main class="main">
            <?php
                if (in_array($page, $allowed)) {
                    $pageFile = __DIR__ . '/pages/' . $page . '.php';
                    $pageDirFile = __DIR__ . '/pages/' . $page . '/index.php';
                    if (file_exists($pageDirFile)) {
                        include $pageDirFile;
                    } elseif (file_exists($pageFile)) {
                        include $pageFile;
                    } else {
                        echo '<h1 class="page-title">Página não encontrada</h1>';
                    }
                } else {
                    echo '<h1 class="page-title">Página não encontrada</h1>';
                }
            ?>
        </main>
    </div>

    <?php if (!empty($_currentUser)): ?>
    <div id="user-menu-dropdown" class="user-menu-dropdown" hidden>
        <div class="user-menu-header">
            <?= renderAvatar($_currentUser['avatar'] ?? '', $_currentUser['nome'] ?? '', 'user-menu-avatar') ?>
            <div class="user-menu-info">
                <span class="user-menu-nome"><?= htmlspecialchars($_currentUser['nome'] ?? '') ?></span>
                <span class="user-menu-email"><?= htmlspecialchars($_currentUser['email'] ?? '') ?></span>
            </div>
        </div>
        <div class="user-menu-divider"></div>
        <a href="<?= BASE_URL ?>/index.php?page=configuracao" class="user-menu-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            Configurações
        </a>
        <a href="<?= BASE_URL ?>/actions/auth/logout.php" class="user-menu-item user-menu-item--danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Sair
        </a>
    </div>
    <?php endif; ?>

    <script src="<?= BASE_URL ?>/assets/js/toast.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/avatar.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/avatar-picker.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/user-menu.js"></script>

    <?php if (file_exists(__DIR__ . '/assets/js/pages/' . $page . '.js')): ?>
        <script src="<?= BASE_URL ?>/assets/js/pages/<?= $page ?>.js?v=<?= time() ?>"></script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
