<?php
require_once __DIR__ . '/../../config.php';

$erro = $_GET['erro'] ?? '';
$sucesso = $_GET['sucesso'] ?? '';
$confirmacaoUrl = $_GET['confirmacao_url'] ?? '';

$mensagensErro = [
    'campos-obrigatorios' => 'Preencha email e senha.',
    'email-invalido' => 'Informe um email valido.',
    'credenciais-invalidas' => 'Email ou senha invalidos.',
    'email-nao-confirmado' => 'Confirme seu email antes de acessar.',
    'token-email-ausente' => 'Token de confirmacao ausente.',
    'token-email-invalido' => 'Token de confirmacao invalido, expirado ou ja utilizado.',
];

$mensagensSucesso = [
    'cadastro-realizado' => 'Conta criada com sucesso. Entre para continuar.',
    'verifique-email' => 'Cadastro criado. Verifique seu email para confirmar a conta.',
    'email-confirmado' => 'Confirmação realizada com sucesso! Faça o login para continuar.',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Escopo Facil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/login.css?v=<?= time() ?>">
</head>

<body>

    <?php ob_start(); ?>
    <section class="login">
        <div class="login-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Facil" class="login-logo">
            <h1 class="login-title">Acesse sua conta</h1>
            <p class="subtitle">Entre com seu e-mail e senha para continuar.</p>
        </div>

        <?php if ($erro && isset($mensagensErro[$erro])): ?>
            <div class="auth-feedback auth-feedback--error">
                <?= htmlspecialchars($mensagensErro[$erro]) ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso && isset($mensagensSucesso[$sucesso])): ?>
            <div class="auth-feedback auth-feedback--success">
                <?= htmlspecialchars($mensagensSucesso[$sucesso]) ?>
            </div>
        <?php endif; ?>

        <?php if ($confirmacaoUrl): ?>
            <div class="auth-feedback auth-feedback--success">
                URL de confirmacao local: <a href="<?= htmlspecialchars($confirmacaoUrl) ?>"><?= htmlspecialchars($confirmacaoUrl) ?></a>
            </div>
        <?php endif; ?>

        <form class="login-form" action="<?= BASE_URL ?>/actions/auth/login.php" method="POST">
            <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="seuemail@senac.edu.br" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha<span class="required">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" minlength="6" required>
                    <button type="button" class="btn-toggle-password" data-target="senha" aria-label="Mostrar senha">
                        <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                    </button>
                </div>
            </div>
            <div class="esqueci-senha">
                <a href="<?= BASE_URL ?>/index.php?page=esqueci-senha">Esqueceu a senha?</a>
            </div>
            <button type="submit" class="btn-primary">Entrar na conta</button>
        </form>
        <p class="login-footer">
            Ainda nao tem uma conta?<a href="<?= BASE_URL ?>/index.php?page=cadastro"> Criar Conta</a>
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
