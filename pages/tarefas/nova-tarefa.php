<!-- criando o modal overlay para manter o fundo opaco -->
<div class="modal-overlay" id="modalOverlay">

    <!-- todo o modal e seu conteúdo -->
    <div class="modal">

        <!-- cabeçalho do modal, ícone e título -->
        <div class="modal-header">
            <img class="btn-back" src="<?= BASE_URL ?>/assets/icon/arrow.svg" alt="seta para a esquerda">
            <h2>Nova Tarefa</h2>
        </div>

        <!-- corpo do modal, campo de título, descrição, campo de prioridade (contém prioridade e prazo), e campo de status -->
        <div class="modal-body">

            <div class="modal-title">
                <label>Título<span class="required">*</span></label>
                <input type="text" name="taskTitle" id="taskTitle" placeholder="Ex: Criar tela de login">
            </div>

            <div class="modal-description">
                <label>Descrição<span class="required">*</span></label>
                <textarea name="taskDescription" id="taskDescription" placeholder="Descreva e detalhe o objetivo da tarefa"></textarea>
            </div>

            <div class="modal-row">

                <div class="modal-priority">
                    <label>Prioridade<span class="required">*</span></label>

                    <div class="modal-select">
                        <img class="icon" src="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg" alt="baixa prioridade">

                        <select class="select-wrapper" name="taskPriority" id="taskPriority">
                            <option value="1">Baixa</option>
                            <option value="2">Média</option>
                            <option value="3">Alta</option>
                        </select>
                    </div>
                </div>

                <div class="modal-date">
                    <label>Prazo<span class="required">*</span></label>

                    <div class="input-wrapper">
                        <img class="icon" src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                        <input type="date" name="taskDate" id="taskDate">
                    </div>
                </div>

            </div>

            <div class="modal-row">

                <div class="modal-status">
                    <label>Status<span class="required">*</span></label>

                    <div class="modal-select">
                        <img src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="status: a fazer">

                        <select class="select-wrapper" name="taskStatus" id="taskStatus">
                            <option value="1">A Fazer</option>
                            <option value="2">Em Andamento</option>
                            <option value="3">Concluído</option>
                        </select>
                    </div>
                </div>

            </div>

        </div>

        <!-- footer do modal, botôes: cancelar e salvar tarefa -->
        <div class="modal-footer">
            <button id="closeModal">Cancelar</button>
            <button class="btn-save" id="saveTask">
                <img src="assets/icon/plus.svg" alt="+">
                Salvar Tarefa
            </button>
        </div>

    </div>

</div>