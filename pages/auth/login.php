<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/login.css?v=<?= time() ?>">
</head>

<body>

    <?php ob_start(); ?>
    <section class="login">
        <div class="login-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Fácil" class="login-logo">
            <h1 class="login-title">Acesse sua conta</h1>
            <p class="subtitle">Entre com seu e-mail e senha para continuar.</p>
        </div>
        <form class="login-form" action="#" method="POST">
            <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="seuemail@senac.edu.br" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha<span class="required">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" minlength="8" required>
                    <button type="button" class="btn-toggle-password" data-target="senha" aria-label="Mostrar senha">
                        <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                    </button>
                </div>
            </div>
            <div class="esqueci-senha">
                <a href="<?= BASE_URL ?>/pages/auth/esqueci-senha.php">Esqueceu a senha?</a>
            </div>
            <button type="submit" class="btn-primary">Entrar na conta</button>
        </form>
        <p class="login-footer">
            Ainda não tem uma conta?<a href="<?= BASE_URL ?>/pages/auth/cadastro.php"> Criar Conta</a>
        </p>
    </section>
    <?php $authContent = ob_get_clean(); ?>
    <?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="<?= BASE_URL ?>/assets/js/auth/login.js?v=<?= time() ?>"></script>
</body>

</html>