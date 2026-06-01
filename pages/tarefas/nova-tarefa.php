<!-- ===== CARD DETAIL MODAL ===== -->
<div class="card-detail-overlay" id="cardDetailOverlay">
    <div class="card-detail">
        <button class="card-detail-close" id="closeCardDetail">✕</button>

        <div class="card-detail-content">
            <div class="card-detail-main">
                <input class="card-detail-title-input" id="detailTitle" type="text" placeholder="Título da tarefa">

                <div class="card-detail-section">
                    <span class="card-detail-label">Descrição</span>
                    <textarea class="card-detail-textarea" id="detailDescription" placeholder="Adicione uma descrição detalhada..."></textarea>
                </div>
            </div>

            <div class="card-detail-sidebar">
                <div class="card-detail-section">
                    <span class="card-detail-label">Prioridade</span>
                    <div class="custom-select" id="detailPriority" data-value="1">
                        <div class="custom-select-trigger">
                            <img class="custom-select-icon" src="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg" alt="prioridade">
                            <span class="custom-select-text">Baixa</span>
                            <span class="custom-select-arrow"></span>
                        </div>
                        <ul class="custom-select-options">
                            <li data-value="1" data-icon="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg"><img src="<?= BASE_URL ?>/assets/icon/grafico-baixa.svg" alt="baixa"> Baixa</li>
                            <li data-value="2" data-icon="<?= BASE_URL ?>/assets/icon/grafico-media.svg"><img src="<?= BASE_URL ?>/assets/icon/grafico-media.svg" alt="média"> Média</li>
                            <li data-value="3" data-icon="<?= BASE_URL ?>/assets/icon/grafico-alta.svg"><img src="<?= BASE_URL ?>/assets/icon/grafico-alta.svg" alt="alta"> Alta</li>
                        </ul>
                    </div>
                </div>

                <div class="card-detail-section">
                    <span class="card-detail-label">Status</span>
                    <div class="custom-select" id="detailStatus" data-value="1">
                        <div class="custom-select-trigger">
                            <img class="custom-select-icon" src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="status">
                            <span class="custom-select-text">A Fazer</span>
                            <span class="custom-select-arrow"></span>
                        </div>
                        <ul class="custom-select-options">
                            <li data-value="1" data-icon="<?= BASE_URL ?>/assets/icon/a-fazer.svg"><img src="<?= BASE_URL ?>/assets/icon/a-fazer.svg" alt="a fazer"> A Fazer</li>
                            <li data-value="2" data-icon="<?= BASE_URL ?>/assets/icon/loading.svg"><img src="<?= BASE_URL ?>/assets/icon/loading.svg" alt="em andamento"> Em Andamento</li>
                            <li data-value="3" data-icon="<?= BASE_URL ?>/assets/icon/concluido.svg"><img src="<?= BASE_URL ?>/assets/icon/concluido.svg" alt="concluído"> Concluído</li>
                        </ul>
                    </div>
                </div>

                <div class="card-detail-section">
                    <span class="card-detail-label">Prazo</span>
                    <div class="input-wrapper">
                        <img class="icon" src="<?= BASE_URL ?>/assets/icon/calendar.svg" alt="calendário">
                        <input type="date" id="detailPrazo" class="card-detail-date-input">
                    </div>
                </div>

                <div class="card-detail-actions">
                    <button id="saveCardDetail" class="btn-save-detail">Salvar alterações</button>
                    <button id="deleteCardDetail" class="btn-delete-detail">Excluir tarefa</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modalOverlay">

    <div class="modal">

        <form action="<?= BASE_URL ?>/actions/tarefas/criar.php" method="POST" id="formNovaTarefa">

            <input type="hidden" name="projeto_id" value="<?= $projetoId ?? 0 ?>">
            <input type="hidden" name="taskPriority" id="hiddenPriority" value="1">
            <input type="hidden" name="taskStatus" id="hiddenStatus" value="1">

            <div class="modal-header">
                <img class="btn-back" src="<?= BASE_URL ?>/assets/icon/arrow.svg" alt="seta para a esquerda">
                <h2 id="modalTitulo">Nova Tarefa</h2>
            </div>

            <div class="modal-body">

                <div class="modal-title">
                    <label>Título<span class="required">*</span></label>
                    <input type="text" name="taskTitle" id="taskTitle" placeholder="Ex: Criar tela de login" required>
                </div>

                <div class="modal-description">
                    <label>Descrição<span class="required">*</span></label>
                    <textarea name="taskDescription" id="taskDescription" placeholder="Descreva e detalhe o objetivo da tarefa" required></textarea>
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
                            <input type="date" name="taskDate" id="taskDate" required>
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

            <div class="modal-footer">
                <button type="button" id="closeModal">Cancelar</button>
                <button type="button" class="btn-save" id="saveTask">
                    <img src="<?= BASE_URL ?>/assets/icon/plus.svg" alt="+">
                    Salvar Tarefa
                </button>
            </div>

        </form> </div> </div> 

