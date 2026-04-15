<!-- Modal Lateral: Visualizar Projeto (Membro) -->
<div class="modal-lateral-overlay" id="modal-visualizar-projeto">
    <div class="modal-lateral">
        <!-- Header -->
        <div class="modal-lateral-header">
            <button type="button" class="modal-back-btn" id="visualizar-close-btn">
                <img src="<?= BASE_URL ?>/assets/icon/arrow-left.svg" alt="Voltar">
            </button>
            <h2 class="modal-title">Detalhes do projeto</h2>
        </div>

        <!-- Conteúdo -->
        <div class="modal-lateral-form">
            <div class="form-group">
                <label>Título</label>
                <input type="text" id="visualizar-titulo" readonly>
            </div>

            <div class="form-group form-group--textarea">
                <label>Descrição</label>
                <textarea id="visualizar-descricao" readonly></textarea>
            </div>

            <div class="modal-lateral-row">
                <div class="form-group form-group--readonly">
                    <label>Criado por</label>
                    <input type="text" id="visualizar-criado-por" readonly>
                </div>
                <div class="form-group form-group--readonly">
                    <label>Data de criação</label>
                    <input type="text" id="visualizar-data-criacao" readonly>
                </div>
            </div>

            <!-- Separator -->
            <div class="modal-lateral-separator"></div>

            <!-- Membros -->
            <div class="modal-lateral-membros-header">
                <h3 class="modal-lateral-membros-title">Membros</h3>
            </div>

            <div class="modal-lateral-membros-grid" id="visualizar-membros-grid">
                <!-- Preenchido via JS -->
            </div>

            <!-- Ações -->
            <div class="modal-lateral-actions">
                <button type="button" class="modal-btn-cancelar" id="visualizar-fechar-btn">Fechar</button>
                <button type="button" class="modal-btn-sair-projeto" id="visualizar-sair-btn">Sair do projeto</button>
            </div>
        </div>
    </div>
</div>

<!-- Template de card de membro (visualização read-only) -->
<template id="template-membro-card-visualizar">
    <div class="membro-card">
        <div class="membro-card-top">
            <span class="membro-card-cargo"></span>
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
