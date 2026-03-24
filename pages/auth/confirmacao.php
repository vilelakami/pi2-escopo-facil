<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação — Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/confirmacao.css">
</head>

<body>

    <?php ob_start(); ?>

    <section class="confirmacao">
        <div class="confirmacao-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Fácil" class="confirmacao-logo">
            <img src="<?= BASE_URL ?>/assets/images/senhaAlterada.png" alt="Escopo Fácil" class="confirmacao-img">
        </div>
        <div class="confirmacao-title">
            <h1 class="title">Senha alterada com sucesso!</h1>
            <p class="subtitle">Pronto! Agora você já pode usar sua nova senha para acessar a conta.</p>
        </div>
        <a href="<?= BASE_URL ?>/pages/auth/login.php" class="btn-primary">Voltar para o login</a>
    </section>

    <?php $authContent = ob_get_clean(); ?>
    <?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script src="<?= BASE_URL ?>/assets/js/auth/confirmacao.js"></script>
</body>

</html>