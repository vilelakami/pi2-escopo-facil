'use strict';

const baseUrl = document.body.dataset.baseUrl || '';
const projetoId = document.querySelector('.page-content')?.dataset.projetoId || '';

// ELEMENTOS
const searchInput2 = document.querySelector('.search-input-wrapper input');
const noTasksMessage2 = document.getElementById('no-tasks-message');
const searchIcon2 = document.querySelector('.search-input-wrapper img');
const btnNovaTarefa2 = document.querySelector('.btn-new-task-main');
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

// =====================
// FUNÇÕES 
// =====================

function postTarefa(action, data) {
    return fetch(`${baseUrl}/actions/tarefas/${action}.php`, {
        method: 'POST',
        body: data
    });
}

function recarregar() {
    window.location.reload();
}

// =====================
// FORM DATA
// =====================

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

// =====================
// MODAL
// =====================

function abrirModalNovo() {
    cardEditando = null;
    modalTitulo.textContent = 'Nova Tarefa';
    limparCamposModal();
    modal.classList.add('active');
}

function fecharModal() {
    modal.classList.remove('active');
    cardEditando = null;
    limparCamposModal();
}

function limparCamposModal() {
    inputTitulo.value = '';
    inputDescricao.value = '';
    inputPrazo.value = '';
    setCustomSelectValue(selectPrioridade, '1');
    setCustomSelectValue(selectStatus, '1');
}

// =====================
// FILTRO
// =====================

function filtrarTarefas() {
    if (!searchInput2 || !noTasksMessage2) return;

    const termo = searchInput2.value.toLowerCase();
    const tarefas = document.querySelectorAll('.task-card');

    let encontrou = false;

    tarefas.forEach((tarefa) => {
        const texto = (
            (tarefa.dataset.titulo || '') +
            (tarefa.dataset.descricao || '')
        ).toLowerCase();

        const match = texto.includes(termo);

        tarefa.style.display = match ? 'block' : 'none';
        if (match) encontrou = true;
    });

    noTasksMessage2.style.display = encontrou ? 'none' : 'block';
}

// =====================
// CUSTOM SELECT
// =====================

function setCustomSelectValue(select, value) {
    if (!select) return;

    select.dataset.value = value;

    const options = select.querySelectorAll('li');

    options.forEach((opt) => {
        opt.classList.toggle('selected', opt.dataset.value === value);
    });
}

// =====================
// EVENTOS
// =====================

searchInput2?.addEventListener('input', filtrarTarefas);
searchIcon2?.addEventListener('click', filtrarTarefas);
btnNovaTarefa2?.addEventListener('click', abrirModalNovo);
btnCancelar?.addEventListener('click', fecharModal);
btnVoltar?.addEventListener('click', fecharModal);

iconCalendario?.addEventListener('click', () => inputPrazo.showPicker());

// =====================
// SALVAR
// =====================

btnSalvar?.addEventListener('click', (e) => {
    e.preventDefault();

    if (!inputTitulo.value.trim()) {
        alert('O titulo é obrigatório');
        return;
    }

    const action = cardEditando ? 'editar' : 'criar';

    postTarefa(action, montarFormData())
        .then((res) => {
            if (!res.ok) throw new Error();
            recarregar();
        })
        .catch(() => alert('Erro ao salvar'));
});

// =====================
// DELETE
// =====================

document.addEventListener('click', (event) => {
    const btn = event.target.closest('.btn-delete');
    if (!btn) return;

    const card = btn.closest('.task-card');
    if (!card) return;

    if (!confirm('Deseja realmente excluir esta tarefa?')) return;

    const formData = new FormData();
    formData.append('tarefa_id', card.dataset.id);

    fetch(baseUrl + '/actions/tarefas/deletar.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
    console.log(data);

    if (data.success) {
        recarregar();
    } else {
        alert(data.message || 'Erro ao excluir');
    }
})
    .catch(() => alert('Erro ao excluir'));
});

// =====================
// DRAG AND DROP
// =====================

document.addEventListener('dragstart', (e) => {
    if (e.target.classList.contains('task-card')) {
        cardArrastado = e.target;
        e.target.style.opacity = '0.5';
    }
});

document.addEventListener('dragend', (e) => {
    if (e.target.classList.contains('task-card')) {
        e.target.style.opacity = '1';
    }
});

document.querySelectorAll('.kanban-column').forEach((coluna) => {

    coluna.addEventListener('dragover', (e) => e.preventDefault());

    coluna.addEventListener('drop', () => {
        if (!cardArrastado) return;

        const statusDestino = coluna.dataset.status;

        coluna.appendChild(cardArrastado);

        postTarefa('editar', montarFormDataDoCard(cardArrastado, statusDestino))
            .then(() => recarregar())
            .catch(() => alert('Erro ao mover tarefa'));
    });

});