<!-- Modal Lateral: Gerenciar Projeto -->
<div class="modal-lateral-overlay" id="modal-gerenciar-projeto">
    <div class="modal-lateral">
        <!-- Header -->
        <div class="modal-lateral-header">
            <button type="button" class="modal-back-btn" id="gerenciar-close-btn">
                <img src="<?= BASE_URL ?>/assets/icon/arrow-left.svg" alt="Voltar">
            </button>
            <h2 class="modal-title">Gerenciar projeto</h2>
        </div>

        <!-- Form -->
        <form class="modal-lateral-form" id="form-gerenciar-projeto">
            <div class="form-group">
                <label for="gerenciar-titulo">Título<span class="required">*</span></label>
                <input type="text" id="gerenciar-titulo" name="titulo" placeholder="Nome do projeto" required>
            </div>

            <div class="form-group form-group--textarea">
                <label for="gerenciar-descricao">Descrição<span class="required">*</span></label>
                <textarea id="gerenciar-descricao" name="descricao" placeholder="Descrição do projeto" required></textarea>
            </div>

            <div class="modal-lateral-row">
                <div class="form-group form-group--readonly">
                    <label>Criado por</label>
                    <input type="text" id="gerenciar-criado-por" readonly>
                </div>
                <div class="form-group form-group--readonly">
                    <label>Data de criação</label>
                    <input type="text" id="gerenciar-data-criacao" readonly>
                </div>
            </div>

            <!-- Separator -->
            <div class="modal-lateral-separator"></div>

            <!-- Membros -->
            <div class="modal-lateral-membros-header">
                <h3 class="modal-lateral-membros-title">Membros</h3>
                <button type="button" class="modal-lateral-btn-adicionar">
                    <img src="<?= BASE_URL ?>/assets/icon/plus.svg" alt="+" class="modal-btn-icon"> Adicionar membro
                </button>
            </div>

            <div class="modal-lateral-membros-grid" id="gerenciar-membros-grid">
                <!-- Preenchido via JS -->
            </div>

            <!-- Ações -->
            <div class="modal-lateral-actions">
                <button type="button" class="modal-btn-cancelar" id="gerenciar-cancelar-btn">Cancelar</button>
                <button type="submit" class="modal-btn-salvar">Salvar alterações</button>
            </div>
        </form>
    </div>
</div>

<!-- Template de card de membro (hidden, clonado via JS) -->
<template id="template-membro-card">
    <div class="membro-card">
        <div class="membro-card-top">
            <span class="membro-card-cargo"></span>
            <button type="button" class="membro-card-menu">
                <img src="<?= BASE_URL ?>/assets/icon/three-dot.svg" alt="Menu">
            </button>
        </div>
        <div class="membro-card-info">
            <img src="" alt="Avatar" class="membro-card-avatar">
            <div class="membro-card-texts">
                <span class="membro-card-nome"></span>
                <span class="membro-card-email"></span>
            </div>
        </div>
        <div class="membro-card-separator"></div>
        <div class="membro-card-bottom">
            <div class="membro-card-role-icon">
                <img src="" alt="" class="membro-card-role-img">
            </div>
            <span class="membro-card-badge"></span>
        </div>
    </div>
</template>
