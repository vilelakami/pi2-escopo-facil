<!-- modal de 'nova tarefa' -->
 <!-- adicionando um overlay pra destacar o modal -->
<div class="modal-overlay" id="modalOverlay">

    <!-- caixinha do forms -->
    <div class="modal">

        <div class="modal-header">
            <img class="btn-back" src="<?= BASE_URL ?>/assets/icon/arrow.svg" alt="seta para a esquerda">
            <h2>Nova Tarefa</h2>
        </div>

        <!-- corpo do forms -->
        <div class="modal-body">

            <!-- adicionando span pra mudar a cor do * -->
            <div class="modal-title">
                <label>Título<span class="required">*</span></label>
                <input type="text" name="taskTitle" id="taskTitle" placeholder="Ex: Criar tela de login">
            </div>

            <div class="modal-description">
                <label>Descrição<span class="required">*</span></label>
                <textarea name="taskDescription" id="taskDescription" placeholder="Descreva e detalhe o objetivo da tarefa"></textarea>
            </div>

            <!-- separando prioridade e prazo inline -->
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

            <!-- separando status -->
            <div class="modal-row">

                <div class="modal-status">
                    <label>Status<span class="required">*</span></label>

                    <div class="modal-select">

                        <img src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="status: a fazer" >

                        <select class="select-wrapper" name="taskStatus" id="taskStatus">
                            <option value="1">A Fazer</option>
                            <option value="2">Em Andamento</option>
                            <option value="3">Concluído</option>
                        </select>
                    </div>

                </div>

            </div>
            
        <!-- footer -->
        <div class="modal-footer">
            <button id="closeModal">Cancelar</button>
            <button class="btn-save" id="saveTask"> <img src="assets/icon/plus.svg" alt="+">
             Salvar Tarefa</button>
        </div>

    </div>

</div>