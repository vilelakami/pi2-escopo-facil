<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação — Escopo Fácil</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/auth/auth.css">
    <link rel="stylesheet" href="/assets/css/auth/confirmacao.css">
</head>
<body>

<?php ob_start(); ?>

    <!-- Confirmação content -->h1
    <h1>Confirmação de cadastro</h1>

<?php $authContent = ob_get_clean(); ?>
<?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script src="/assets/js/auth/confirmacao.js"></script>
</body>
</html>
