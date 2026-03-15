<?php
$page = $_GET['page'] ?? 'dashboard';

// Páginas de autenticação (layout próprio, sem sidebar)
$authPages = ['login', 'cadastro', 'esqueci-senha', 'redefinir-senha', 'confirmacao'];
if (in_array($page, $authPages) && file_exists(__DIR__ . '/pages/auth/' . $page . '.php')) {
    include __DIR__ . '/pages/auth/' . $page . '.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escopo Fácil</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/layout.css">
    <?php if (file_exists(__DIR__ . '/assets/css/pages/' . $page . '.css')): ?>
        <link rel="stylesheet" href="/assets/css/pages/<?= $page ?>.css">
    <?php endif; ?>
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <main class="main">
            <?php
                $allowed = ['dashboard', 'projetos', 'tarefas', 'membros', 'configuracao'];
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
    <script src="/assets/js/app.js"></script>
    <?php if (file_exists(__DIR__ . '/assets/js/pages/' . $page . '.js')): ?>
        <script src="/assets/js/pages/<?= $page ?>.js"></script>
    <?php endif; ?>
</body>
</html>
