<?php
require_once __DIR__ . '/../../config.php';

$token = $_GET['token'] ?? '';
$erro = $_GET['erro'] ?? '';

$mensagensErro = [
    'metodo-invalido' => 'Metodo invalido.',
    'token-ausente' => 'Token ausente.',
    'token-invalido' => 'Token invalido, expirado ou ja utilizado.',
    'campos-obrigatorios' => 'Preencha todos os campos.',
    'senha-curta' => 'A nova senha deve ter pelo menos 8 caracteres.',
    'senhas-diferentes' => 'A confirmacao precisa ser igual a nova senha.',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha - Escopo Facil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/redefinir-senha.css">
</head>

<body>

    <?php ob_start(); ?>

    <section class="redefinir">
        <div class="redefinir-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Facil" class="redefinir-logo">
            <h1 class="redefinir-title">Redefina sua senha</h1>
            <p class="subtitle">Crie uma nova senha. Para sua seguranca, use pelo menos 8 caracteres.</p>
        </div>

        <?php if ($erro && isset($mensagensErro[$erro])): ?>
            <div class="auth-feedback auth-feedback--error">
                <?= htmlspecialchars($mensagensErro[$erro]) ?>
            </div>
        <?php endif; ?>

        <form class="redefinir-form" action="<?= BASE_URL ?>/actions/auth/redefinir-senha.php" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <label for="senha">Nova senha<span class="required">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="senha" name="nova_senha" placeholder="Digite sua nova senha" minlength="8" required>
                    <button type="button" class="btn-toggle-password" data-target="senha" aria-label="Mostrar senha">
                        <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label for="confirmar-senha">Confirmacao de senha<span class="required">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="confirmar-senha" name="confirmar_nova_senha" placeholder="Confirme sua nova senha" minlength="8" required>
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
