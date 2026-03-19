<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/login.css">
</head>
<body>

<?php ob_start(); ?>

    <!-- Login form content -->
     <h1>Login</h1>

<?php $authContent = ob_get_clean(); ?>
<?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script src="<?= BASE_URL ?>/assets/js/auth/login.js"></script>
</body>
</html>
