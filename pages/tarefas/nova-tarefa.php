<!-- criando o modal overlay para manter o fundo opaco -->
<div class="modal-overlay" id="modalOverlay">

    <!-- todo o modal e seu conteúdo -->
    <div class="modal">

        <!-- cabeçalho do modal, ícone e título -->
        <div class="modal-header">
            <img class="btn-back" src="<?= BASE_URL ?>/assets/icon/arrow.svg" alt="seta para a esquerda">
            <h2 id="modalTitulo">Nova Tarefa</h2>
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

                    <div class="custom-select" id="taskPriority" data-value="1">
                        <div class="custom-select-trigger">
                            <img class="custom-select-icon" src="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg" alt="prioridade">
                            <span class="custom-select-text">Baixa</span>
                            <span class="custom-select-arrow"></span>
                        </div>
                        <ul class="custom-select-options">
                            <li data-value="1" data-icon="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg">
                                <img src="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg" alt="baixa"> Baixa
                            </li>
                            <li data-value="2" data-icon="<?= BASE_URL ?>/assets/icon/grafico-media.svg">
                                <img src="<?= BASE_URL ?>/assets/icon/grafico-media.svg" alt="média"> Média
                            </li>
                            <li data-value="3" data-icon="<?= BASE_URL ?>/assets/icon/grafico-alta.svg">
                                <img src="<?= BASE_URL ?>/assets/icon/grafico-alta.svg" alt="alta"> Alta
                            </li>
                        </ul>
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

                    <div class="custom-select" id="taskStatus" data-value="1">
                        <div class="custom-select-trigger">
                            <img class="custom-select-icon" src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="status">
                            <span class="custom-select-text">A Fazer</span>
                            <span class="custom-select-arrow"></span>
                        </div>
                        <ul class="custom-select-options">
                            <li data-value="1" data-icon="<?= BASE_URL ?>/assets/icon/a-fazer.svg">
                                <img src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="a fazer"> A Fazer
                            </li>
                            <li data-value="2" data-icon="<?= BASE_URL ?>/assets/icon/loading.svg">
                                <img src="<?= BASE_URL ?>/assets/icon/loading.svg" alt="em andamento"> Em Andamento
                            </li>
                            <li data-value="3" data-icon="<?= BASE_URL ?>/assets/icon/concluido.svg">
                                <img src="<?= BASE_URL ?>/assets/icon/concluido.svg" alt="concluído"> Concluído
                            </li>
                        </ul>
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