<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escopo Fácil</title>
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/layout.css">
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <main class="main">
            <?php
                $page = $_GET['page'] ?? 'dashboard';
                $allowed = ['dashboard', 'tarefas', 'membros', 'configuracao'];
                if (in_array($page, $allowed)) {
                    include __DIR__ . '/pages/' . $page . '.php';
                } else {
                    echo '<h1 class="page-title">Página não encontrada</h1>';
                }
            ?>
        </main>
    </div>
    <script src="/assets/js/app.js"></script>
</body>
</html>
