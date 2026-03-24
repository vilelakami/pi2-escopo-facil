<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro — Escopo Fácil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/auth.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth/cadastro.css">
</head>

<body>

    <?php ob_start(); ?>

    <section class="cadastro">
        <div class="cadastro-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Escopo Fácil" class="cadastro-logo">
            <h1 class="cadastro-title">Crie sua conta</h1>
        </div>

        <form class="cadastro-form" action="#" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Nome<span class="required">*</span></label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
                </div>
                <div class="form-group">
                    <label for="cargo">Cargo<span class="required">*</span></label>
                    <div class="input-select-wrapper">
                        <select id="cargo" name="cargo" required>
                            <option value="" disabled selected>Selecione seu cargo</option>
                            <option value="dev-frontend">Desenvolvedor Frontend</option>
                            <option value="dev-backend">Desenvolvedor Backend</option>
                            <option value="dev-fullstack">Desenvolvedor Full Stack</option>
                            <option value="ui-ux-designer">UI/UX Designer</option>
                            <option value="product-owner">Product Owner</option>
                            <option value="scrum-master">Scrum Master</option>
                            <option value="tech-lead">Tech Lead</option>
                            <option value="qa-testes">QA / Testes</option>
                        </select>
                    </div>
                </div>
            </div>

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

            <div class="form-group">
                <label for="confirmar-senha">Confirmação de senha<span class="required">*</span></label>
                <div class="input-password-wrapper">
                    <input type="password" id="confirmar-senha" name="confirmar_senha" placeholder="Confirme sua senha" minlength="8" required>
                    <button type="button" class="btn-toggle-password" data-target="confirmar-senha" aria-label="Mostrar senha">
                        <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                    </button>
                </div>
            </div>

            <div class="form-terms">
                <input type="radio" id="termos" name="termos" required>
                <label for="termos">Para prosseguir você aceita os <a href="#">Termos e condições</a></label>
            </div>

            <button type="submit" class="btn-primary">Criar conta</button>
        </form>

        <p class="cadastro-footer">
            Você já tem uma conta? <a href="<?= BASE_URL ?>/pages/auth/login.php">Fazer login</a>
        </p>
        </div>
    </section>
    <?php $authContent = ob_get_clean(); ?>
    <?php include __DIR__ . '/../../partials/auth-layout.php'; ?>

    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="<?= BASE_URL ?>/assets/js/auth/cadastro.js"></script>
</body>

</html>