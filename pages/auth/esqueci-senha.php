<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha — Escopo Fácil</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/auth/auth.css">
    <link rel="stylesheet" href="/assets/css/auth/esqueci-senha.css">
</head>
<body>

<?php ob_start(); ?>

    <!-- Esqueci senha form content -->
    <h1>Esqueci minha senha</h1>

<?php $authContent = ob_get_clean(); ?>
<?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script src="/assets/js/auth/esqueci-senha.js"></script>
</body>
</html>
