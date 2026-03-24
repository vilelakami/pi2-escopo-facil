<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha — Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/esqueci-senha.css?v=<?= time() ?>">
</head>

<body>

    <?php ob_start(); ?>

    <section class="esqueci-senha">
        <div class="esqueci-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Fácil" class="esqueci-logo">
            <h1 class="esqueci-title">Recuperar acesso</h1>
            <p class="subtitle">Digite seu e-mail cadastrado para receber instruções de redefinição de senha.</p>
        </div>
        <form class="esqueci-form" action="#" method="POST">
            <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="seuemail@senac.edu.br" required>
            </div>
            <button type="submit" class="btn-primary">Redefinir senha</button>
        </form>
        <p class="esqueci-footer">
            Lembrou a senha?<a href="<?= BASE_URL ?>/pages/auth/cadastro.php"> Voltar para o login</a>
        </p>
    </section>

    <?php $authContent = ob_get_clean(); ?>
    <?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script src="<?= BASE_URL ?>/assets/js/auth/esqueci-senha.js?v=<?= time() ?>"></script>
</body>

</html>