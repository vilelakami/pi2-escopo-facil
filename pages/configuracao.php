<?php
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/avatar.php';
require_once __DIR__ . '/../models/Usuario.php';

$usuario = Usuario::buscarPorId((int) usuarioLogado());

if (!$usuario) {
    header('Location: ' . BASE_URL . '/?page=login');
    exit;
}

$avatarStr = $usuario['avatar'] ?? '';
$sucesso = $_GET['sucesso'] ?? '';
$erro = $_GET['erro'] ?? '';

$mensagensSucesso = [
    'perfil-atualizado' => 'Dados atualizados com sucesso.',
    'senha-alterada'    => 'Senha alterada com sucesso.',
    'avatar-atualizado' => 'Avatar atualizado com sucesso.',
    '1'                 => 'Alteracao salva com sucesso.',
];

$mensagensErro = [
    'campos-obrigatorios'  => 'Preencha todos os campos obrigatorios.',
    'email-invalido'       => 'Informe um email valido.',
    'email-em-uso'         => 'Este email ja esta em uso.',
    'cargo-invalido'       => 'Selecione um cargo valido.',
    'senha-curta'          => 'A nova senha deve ter pelo menos 8 caracteres.',
    'senhas-diferentes'    => 'A confirmacao precisa ser igual a nova senha.',
    'senha-atual-incorreta'=> 'A senha atual esta incorreta.',
    'erro-ao-atualizar'    => 'Nao foi possivel atualizar os dados.',
    'avatar-invalido'      => 'Avatar invalido.',
];

function cargoSelecionado(string $cargoUsuario, string $cargoOption): string
{
    return $cargoUsuario === $cargoOption ? 'selected' : '';
}
?>

<section class="configuracao">

    <!-- Header -->
    <div class="configuracao-header">
        <h1 class="configuracao-title">Configuração</h1>
        <div class="configuracao-header-right">
            <div class="configuracao-notification">
                <img src="<?= BASE_URL ?>/assets/icon/bell.svg" alt="Notificações">
                <span class="notification-dot"></span>
            </div>
            <div class="configuracao-user user-menu-trigger" style="padding:6px 8px;border-radius:8px">
                <?= renderAvatar($avatarStr, $usuario['nome'], 'configuracao-avatar') ?>
                <div class="configuracao-user-info">
                    <span class="configuracao-user-name"><?= htmlspecialchars($usuario['nome']) ?></span>
                    <span class="configuracao-user-role"><?= htmlspecialchars($usuario['cargo']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="configuracao-body">

        <!-- Card: Dados pessoais -->
        <div class="configuracao-card">
            <div class="configuracao-card-header">
                <h2 class="configuracao-card-title">Dados pessoais</h2>
                <p class="configuracao-card-desc">Atualize suas informações de perfil.</p>
            </div>

            <form class="configuracao-form" action="<?= BASE_URL ?>/actions/usuario/atualizar.php" method="POST">
                <div class="configuracao-form-row">
                    <div class="form-group">
                        <label for="config-nome">Nome<span class="required">*</span></label>
                        <input type="text" id="config-nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="config-cargo">Cargo<span class="required">*</span></label>
                        <div class="input-select-wrapper">
                            <select id="config-cargo" name="cargo">
                                <option value="" disabled>Selecione seu cargo</option>
                                <option value="dev-frontend" <?= cargoSelecionado($usuario['cargo'], 'dev-frontend') ?>>Desenvolvedor Frontend</option>
                                <option value="dev-backend" <?= cargoSelecionado($usuario['cargo'], 'dev-backend') ?>>Desenvolvedor Backend</option>
                                <option value="dev-fullstack" <?= cargoSelecionado($usuario['cargo'], 'dev-fullstack') ?>>Desenvolvedor Full Stack</option>
                                <option value="ui-ux-designer" <?= cargoSelecionado($usuario['cargo'], 'ui-ux-designer') ?>>UI/UX Designer</option>
                                <option value="product-owner" <?= cargoSelecionado($usuario['cargo'], 'product-owner') ?>>Product Owner</option>
                                <option value="scrum-master" <?= cargoSelecionado($usuario['cargo'], 'scrum-master') ?>>Scrum Master</option>
                                <option value="tech-lead" <?= cargoSelecionado($usuario['cargo'], 'tech-lead') ?>>Tech Lead</option>
                                <option value="qa-testes" <?= cargoSelecionado($usuario['cargo'], 'qa-testes') ?>>QA / Testes</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="config-email">Email<span class="required">*</span></label>
                    <input type="email" id="config-email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">
                </div>

                <div class="configuracao-form-actions">
                    <button type="button" class="configuracao-btn-cancelar">Cancelar</button>
                    <button type="submit" class="configuracao-btn-salvar">Salvar alterações</button>
                </div>
            </form>
        </div>

        <!-- Card: Alterar senha -->
        <div class="configuracao-card">
            <div class="configuracao-card-header">
                <h2 class="configuracao-card-title">Alterar senha</h2>
                <p class="configuracao-card-desc">Para sua segurança, recomendamos uma senha forte.</p>
            </div>

            <form class="configuracao-form" action="<?= BASE_URL ?>/actions/usuario/alterar-senha.php" method="POST">
                <div class="form-group">
                    <label for="config-senha-atual">Senha atual<span class="required">*</span></label>
                    <div class="input-password-wrapper">
                        <input type="password" id="config-senha-atual" name="senha_atual" placeholder="Digite sua senha atual">
                        <button type="button" class="btn-toggle-password" data-target="config-senha-atual" aria-label="Mostrar senha">
                            <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                        </button>
                    </div>
                </div>

                <div class="configuracao-form-row">
                    <div class="form-group">
                        <label for="config-nova-senha">Nova senha<span class="required">*</span></label>
                        <div class="input-password-wrapper">
                            <input type="password" id="config-nova-senha" name="nova_senha" placeholder="Digite a nova senha" minlength="8">
                            <button type="button" class="btn-toggle-password" data-target="config-nova-senha" aria-label="Mostrar senha">
                                <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="config-confirmar-senha">Confirmar senha<span class="required">*</span></label>
                        <div class="input-password-wrapper">
                            <input type="password" id="config-confirmar-senha" name="confirmar_nova_senha" placeholder="Confirme a nova senha" minlength="8">
                            <button type="button" class="btn-toggle-password" data-target="config-confirmar-senha" aria-label="Mostrar senha">
                                <img src="<?= BASE_URL ?>/assets/icon/eye-off.svg" alt="Mostrar senha" width="24" height="24">
                            </button>
                        </div>
                    </div>
                </div>

                <div class="configuracao-form-actions">
                    <button type="button" class="configuracao-btn-cancelar">Cancelar</button>
                    <button type="submit" class="configuracao-btn-salvar">Alterar senha</button>
                </div>
            </form>
        </div>

        <!-- Card: Avatar -->
        <div class="configuracao-card">
            <div class="configuracao-card-header">
                <h2 class="configuracao-card-title">Avatar</h2>
                <p class="configuracao-card-desc">Escolha uma cor e um padrão para seu perfil.</p>
            </div>

            <form class="configuracao-form" action="<?= BASE_URL ?>/actions/usuario/atualizar-avatar.php" method="POST">
                <?= avatarPickerHtml($avatarStr) ?>
                <div class="configuracao-form-actions">
                    <button type="button" class="configuracao-btn-cancelar">Cancelar</button>
                    <button type="submit" class="configuracao-btn-salvar">Salvar avatar</button>
                </div>
            </form>
        </div>

        <!-- Card: Zona de perigo -->
        <div class="configuracao-card configuracao-card--danger">
            <div class="configuracao-card-header">
                <h2 class="configuracao-card-title">Zona de perigo</h2>
                <p class="configuracao-card-desc">Ações irreversíveis para sua conta.</p>
            </div>

            <div class="configuracao-danger-actions">
                <div class="configuracao-danger-item">
                    <div class="configuracao-danger-info">
                        <span class="configuracao-danger-label">Excluir conta</span>
                        <span class="configuracao-danger-desc">Ao excluir sua conta, todos os seus dados serão removidos permanentemente.</span>
                    </div>
                    <button type="button" class="configuracao-btn-excluir">Excluir conta</button>
                </div>
            </div>
        </div>

    </div>
</section>
