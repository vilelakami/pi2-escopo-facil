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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css">
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

    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>

    <?php if (file_exists(__DIR__ . '/assets/js/pages/' . $page . '.js')): ?>
        <script src="<?= BASE_URL ?>/assets/js/pages/<?= $page ?>.js?v=<?= time()?>.js"></script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
