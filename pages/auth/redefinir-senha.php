<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha — Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/redefinir-senha.css">
</head>
<body>

<?php ob_start(); ?>

    <!-- Redefinir senha form content -->
     <h1>Redefinir senha</h1>

<?php $authContent = ob_get_clean(); ?>
<?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script src="<?= BASE_URL ?>/assets/js/auth/redefinir-senha.js"></script>
</body>
</html>
