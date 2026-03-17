<!-- modal de 'nova tarefa' -->
 <!-- adicionando um overlay pra destacar o modal -->
<div class="modal-overlay" id="modalOverlay">

    <!-- caixinha do forms -->
    <div class="modal">

        <div class="modal-header">
            <img src="assets/icon/arrow.svg" alt="seta para a esquerda">
            <h2>Nova Tarefa</h2>
        </div>

        <div class="modal-body">
            <div class="modal-title">
                <label>Título*</label>
                <input type="text" name="taskTitle" id="taskTitle" placeholder="Ex: Criar tela de login">
            </div>

            <div class="modal-description">
                <label>Descrição*</label>
                <textarea name="taskDescription" id="taskDescription" placeholder="Descreva e detalhe o objetivo da tarefa"></textarea>
            </div>

            <div class="modal-row">
                <div class="modal-priority">
                    <label>Prioridade*</label>
                    <div class="modal-select">
                        <img src="assets/icon/grafico-baixa.svg" alt="baixa prioridade">

                        <select class="task-priority" name="taskPriority" id="taskPriority">
                            <option value="1">Baixa</option>
                            <option value="2">Média</option>
                            <option value="3">Alta</option>

                        </select>
                    </div>
                </div>

                <div class="modal-date">
                    <label>Prazo*</label>
                    <input type="date" name="taskDate" id="taskDate">
                </div>
            </div>

            <div class="modal-row">
                <div class="modal-status">
                    <label>Status*</label>

                    <img src="assets/icon/a-fazer.svg" alt="a fazer">

                    <select name="taskStatus" id="taskStatus">
                        <option value="1">A Fazer</option>
                        <option value="2">Em Andamento</option>
                        <option value="3">Concluído</option>
                    </select>
                </div>
            </div>

        </div>
        <!-- footer -->
        <div class="modal-footer">
            <button class="btn-cancel" id="closeModal">cancelar</button>
            <button class="btn-save" id="saveTask">+ Salvar Tarefa</button>
        </div>
    </div>
</div>