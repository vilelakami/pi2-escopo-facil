<?php require __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php $page = $_GET['page'] ?? 'dashboard'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
    <?php if (file_exists(__DIR__ . '/assets/css/pages/' . $page . '.css')): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages/<?= $page ?>.css">
    <?php endif; ?>
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <main class="main">
            <?php
                $allowed = ['dashboard', 'tarefas', 'membros', 'configuracao'];
                if (in_array($page, $allowed)) {
                    include __DIR__ . '/pages/' . $page . '.php';
                } else {
                    echo '<h1 class="page-title">Página não encontrada</h1>';
                }
            ?>
        </main>
    </div>

    <script src="<?= BASE_URL ?>./assets/js/app.js"></script>
    
    <?php include __DIR__ . '/pages/modal.php'; ?>

    <?php if (file_exists(__DIR__ . '/assets/js/pages/' . $page . '.js')): ?>
        <script src="<?= BASE_URL ?>/assets/js/pages/<?= $page ?>.js"></script>
    <?php endif; ?>
</body>
</html>
