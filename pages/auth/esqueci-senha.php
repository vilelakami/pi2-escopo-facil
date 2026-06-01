<?php
require_once __DIR__ . '/../../config.php';

$erro = $_GET['erro'] ?? '';
$sucesso = $_GET['sucesso'] ?? '';
$tokenUrl = $_GET['token_url'] ?? '';
$emailEnviado = $_GET['email_enviado'] ?? '';

$mensagensErro = [
    'email-invalido' => 'Informe um email valido.',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/icon/logo/Vector%20(3).svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha - Escopo Facil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/toast.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/esqueci-senha.css?v=<?= time() ?>">
</head>

<body>

    <?php ob_start(); ?>

    <section class="esqueci-senha">
        <div class="esqueci-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Facil" class="esqueci-logo">
            <h1 class="esqueci-title">Recuperar acesso</h1>
            <p class="subtitle">Digite seu e-mail cadastrado para receber instrucoes de redefinicao de senha.</p>
        </div>

        <?php if ($tokenUrl): ?>
            <div class="auth-feedback auth-feedback--success" style="font-size:12px;word-break:break-all">
                URL de teste local: <a href="<?= htmlspecialchars($tokenUrl) ?>"><?= htmlspecialchars($tokenUrl) ?></a>
            </div>
        <?php endif; ?>

        <form class="esqueci-form" action="<?= BASE_URL ?>/actions/auth/esqueci-senha.php" method="POST">
            <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="seuemail@senac.edu.br" required>
            </div>
            <button type="submit" class="btn-primary">Redefinir senha</button>
        </form>
        <p class="esqueci-footer">
            Lembrou a senha?<a href="<?= BASE_URL ?>/index.php?page=login"> Voltar para o login</a>
        </p>
    </section>

    <?php $authContent = ob_get_clean(); ?>
    <?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="<?= BASE_URL ?>/assets/js/toast.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/auth/esqueci-senha.js?v=<?= time() ?>"></script>
</body>

</html>
