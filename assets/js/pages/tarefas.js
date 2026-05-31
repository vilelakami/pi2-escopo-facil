// Tarefas - integracao com backend

const baseUrl = document.body.dataset.baseUrl || '';
const projetoId = window.projetoId || document.querySelector('.page-content')?.dataset.projetoId || '';

const searchInput = document.querySelector('.search-input-wrapper input');
const noTasksMessage = document.getElementById('no-tasks-message');
const searchIcon = document.querySelector('.search-input-wrapper img');
const btnNovaTarefa = document.querySelector('.btn-new-task-main');
const modal = document.querySelector('.modal-overlay');
const btnCancelar = document.querySelector('#closeModal');
const btnSalvar = document.querySelector('#saveTask');
const btnVoltar = document.querySelector('.btn-back');
const inputTitulo = document.querySelector('#taskTitle');
const inputDescricao = document.querySelector('#taskDescription');
const selectPrioridade = document.querySelector('#taskPriority');
const selectStatus = document.querySelector('#taskStatus');
const inputPrazo = document.querySelector('#taskDate');
const iconCalendario = document.querySelector('.modal-date .icon');
const modalTitulo = document.querySelector('#modalTitulo');

let cardArrastado = null;
let cardEditando = null;

function postTarefa(action, data) {
    return fetch(baseUrl + '/actions/tarefas/' + action + '.php', {
        method: 'POST',
        body: data
    });
}

function montarFormData(statusOverride = null) {
    const formData = new FormData();
    formData.append('projeto_id', projetoId);
    formData.append('titulo', inputTitulo.value.trim());
    formData.append('descricao', inputDescricao.value.trim());
    formData.append('prioridade', selectPrioridade.dataset.value || '1');
    formData.append('status', statusOverride || selectStatus.dataset.value || '1');
    formData.append('prazo', inputPrazo.value || '');

    if (cardEditando?.dataset.id) {
        formData.append('tarefa_id', cardEditando.dataset.id);
    }

    return formData;
}

function montarFormDataDoCard(card, statusOverride = null) {
    const formData = new FormData();
    formData.append('projeto_id', projetoId);
    formData.append('tarefa_id', card.dataset.id);
    formData.append('titulo', card.dataset.titulo || '');
    formData.append('descricao', card.dataset.descricao || '');
    formData.append('prioridade', card.dataset.prioridade || '1');
    formData.append('status', statusOverride || card.dataset.status || '1');
    formData.append('prazo', card.dataset.prazo || '');
    return formData;
}

function recarregar() {
    window.location.reload();
}

function abrirModalNovo() {
    cardEditando = null;
    modalTitulo.textContent = 'Nova Tarefa';
    limparCamposModal();
    modal.classList.add('active');
}

function fecharModal() {
    modal.classList.remove('active');
    cardEditando = null;
    modalTitulo.textContent = 'Nova Tarefa';
    limparCamposModal();
}

function filtrarTarefas() {
    if (!searchInput || !noTasksMessage) return;

    const termo = searchInput.value.toLowerCase();
    const tarefas = document.querySelectorAll('.task-card');
    let encontrouAlguma = false;

    tarefas.forEach((tarefa) => {
        const titulo = (tarefa.dataset.titulo || '').toLowerCase();
        const descricao = (tarefa.dataset.descricao || '').toLowerCase();
        const prioridade = tarefa.querySelector('.priority-tag p')?.innerText.toLowerCase() || '';
        const prazo = tarefa.querySelector('.deadline-text')?.innerText.toLowerCase() || '';
        const encontrou = termo === '' || titulo.includes(termo) || descricao.includes(termo) || prioridade.includes(termo) || prazo.includes(termo);

        tarefa.style.display = encontrou ? 'block' : 'none';
        if (encontrou) encontrouAlguma = true;
    });

    noTasksMessage.style.display = encontrouAlguma ? 'none' : 'block';
}

function setCustomSelectValue(select, value) {
    if (!select) return;

    select.dataset.value = value;
    const options = select.querySelectorAll('.custom-select-options li');

    options.forEach((opt) => {
        opt.classList.remove('selected');
        if (opt.dataset.value === value) {
            opt.classList.add('selected');
            select.querySelector('.custom-select-text').textContent = opt.textContent.trim();
            select.querySelector('.custom-select-icon').src = opt.dataset.icon;
        }
    });
}

function resetCustomSelect(select, defaultValue) {
    setCustomSelectValue(select, defaultValue);
    select?.classList.remove('open');
}

function limparCamposModal() {
    inputTitulo.value = '';
    inputDescricao.value = '';
    inputPrazo.value = '';
    resetCustomSelect(selectPrioridade, '1');
    resetCustomSelect(selectStatus, '1');
}

if (searchInput) searchInput.addEventListener('input', filtrarTarefas);
if (searchIcon) searchIcon.addEventListener('click', filtrarTarefas);
if (btnNovaTarefa) btnNovaTarefa.addEventListener('click', abrirModalNovo);
if (btnCancelar) btnCancelar.addEventListener('click', fecharModal);
if (btnVoltar) btnVoltar.addEventListener('click', fecharModal);

if (modal) {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) fecharModal();
    });
}

if (iconCalendario && inputPrazo) {
    iconCalendario.addEventListener('click', () => inputPrazo.showPicker());
}

document.querySelectorAll('.modal .custom-select').forEach((select) => {
    const trigger = select.querySelector('.custom-select-trigger');
    const options = select.querySelectorAll('.custom-select-options li');
    const iconEl = select.querySelector('.custom-select-icon');
    const textEl = select.querySelector('.custom-select-text');

    options.forEach((opt) => {
        if (opt.dataset.value === select.dataset.value) opt.classList.add('selected');
    });

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        document.querySelectorAll('.custom-select.open').forEach((item) => {
            if (item !== select) item.classList.remove('open');
        });
        select.classList.toggle('open');
    });

    options.forEach((opt) => {
        opt.addEventListener('click', (e) => {
            e.stopPropagation();
            select.dataset.value = opt.dataset.value;
            textEl.textContent = opt.textContent.trim();
            iconEl.src = opt.dataset.icon;
            options.forEach((item) => item.classList.remove('selected'));
            opt.classList.add('selected');
            select.classList.remove('open');
        });
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('.custom-select.open').forEach((select) => select.classList.remove('open'));
});

document.addEventListener('dragstart', (e) => {
    if (e.target.classList.contains('task-card')) {
        cardArrastado = e.target;
        e.target.style.opacity = '0.5';
    }
});

document.querySelectorAll('.kanban-column').forEach((coluna) => {
    coluna.addEventListener('dragover', (e) => {
        e.preventDefault();
        coluna.classList.add('drag-over');
    });

    coluna.addEventListener('dragleave', () => {
        coluna.classList.remove('drag-over');
    });

    coluna.addEventListener('drop', () => {
        coluna.classList.remove('drag-over');
        if (!cardArrastado) return;

        const statusDestino = coluna.dataset.status || coluna.id.replace('col-', '');
        const statusAtual = cardArrastado.dataset.status;

        coluna.appendChild(cardArrastado);

        if (statusDestino !== statusAtual) {
            postTarefa('editar', montarFormDataDoCard(cardArrastado, statusDestino))
                .then((response) => {
                    if (!response.ok) throw new Error('Erro ao mover tarefa.');
                    cardArrastado.dataset.status = statusDestino;
                })
                .catch(() => {
                    alert('Nao foi possivel atualizar o status da tarefa.');
                    recarregar();
                });
        }
    });
});

document.addEventListener('dragend', (e) => {
    if (e.target.classList.contains('task-card')) {
        e.target.style.opacity = '1';
    }
});

document.addEventListener('click', (e) => {
    const btnEditar = e.target.closest('.btn-edit');
    if (!btnEditar) return;

    const card = btnEditar.closest('.task-card');
    if (!card) return;

    cardEditando = card;
    modalTitulo.textContent = 'Editar Tarefa';
    inputTitulo.value = card.dataset.titulo || '';
    inputDescricao.value = card.dataset.descricao || '';
    inputPrazo.value = card.dataset.prazo || '';
    setCustomSelectValue(selectPrioridade, card.dataset.prioridade || '1');
    setCustomSelectValue(selectStatus, card.dataset.status || '1');
    modal.classList.add('active');
});

if (btnSalvar) {
    btnSalvar.addEventListener('click', () => {
        if (inputTitulo.value.trim() === '') {
            alert('O titulo e obrigatorio!');
            return;
        }

        const action = cardEditando ? 'editar' : 'criar';
        postTarefa(action, montarFormData())
            .then((response) => {
                if (!response.ok) throw new Error('Erro ao salvar tarefa.');
                recarregar();
            })
            .catch(() => {
                alert('Nao foi possivel salvar a tarefa.');
            });
    });
}

document.addEventListener('click', (event) => {
    const btn = event.target.closest('.btn-delete');
    if (!btn) return;

    event.preventDefault();
    const card = btn.closest('.task-card');
    if (!card || !confirm('Deseja realmente excluir esta tarefa?')) return;

    const formData = new FormData();
    formData.append('tarefa_id', card.dataset.id);

    postTarefa('deletar', formData)
        .then((response) => {
            if (!response.ok) throw new Error('Erro ao excluir tarefa.');
            recarregar();
        })
        .catch(() => {
            alert('Nao foi possivel excluir a tarefa.');
        });
});
