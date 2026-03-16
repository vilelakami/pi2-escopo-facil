<!-- Modal: Novo Projeto -->
<div class="modal-overlay" id="modal-novo-projeto">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="modal-back-btn" id="modal-close-btn">
                <img src="/assets/icon/arrow-left.svg" alt="Voltar">
            </button>
            <h2 class="modal-title">Novo projeto</h2>
        </div>

        <form class="modal-form" id="form-novo-projeto">
            <div class="form-group">
                <label for="projeto-titulo">Título<span class="required">*</span></label>
                <input type="text" id="projeto-titulo" name="titulo" placeholder="Descreva o nome do projeto" required>
            </div>

            <div class="form-group form-group--textarea">
                <label for="projeto-descricao">Descrição<span class="required">*</span></label>
                <textarea id="projeto-descricao" name="descricao" placeholder="Descreva e detalhe o objetivo do projeto" required></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn-cancelar" id="modal-cancelar-btn">Cancelar</button>
                <button type="submit" class="modal-btn-salvar">
                    <img src="/assets/icon/plus.svg" alt="+" class="modal-btn-icon"> Adicionar projeto 
                </button>
            </div>
        </form>
    </div>
</div>
