<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha — Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/redefinir-senha.css">
</head>

<body>

    <?php ob_start(); ?>

    <section class="redefinir">
        <div class="redefinir-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Fácil" class="redefinir-logo">
            <h1 class="redefinir-title">Redefina sua senha</h1>
            <p class="subtitle">Crie uma nova senha. Para sua segurança, ela precisa ser diferente da anterior.</p>
        </div>
        <form class="redefinir-form" action="#" method="POST">
            <div class="form-group">
                <label for="password">Senha<span class="required">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="senha" name="senha" placeholder="Confirme sua senha" minlength="8" required>
                    <button type="button" class="btn-toggle-password" data-target="senha" aria-label="Mostrar senha">
                        <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label for="confirmar-senha">Confirmação de senha<span class="required">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="confirmar-senha" name="confirmar-senha" placeholder="Confirme sua senha" minlength="8" required>
                    <button type="button" class="btn-toggle-password" data-target="confirmar-senha" aria-label="Mostrar senha">
                        <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-primary">Redefinir senha</button>
        </form>
    </section>


    <?php $authContent = ob_get_clean(); ?>
    <?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="<?= BASE_URL ?>/assets/js/auth/redefinir-senha.js?v=<?= time() ?>"></script>
</body>

</html>