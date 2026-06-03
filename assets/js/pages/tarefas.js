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

function abrirModalNovo(statusInicial = '1') {
    cardEditando = null;
    modalTitulo.textContent = 'Nova Tarefa';
    limparCamposModal();
    setCustomSelectValue(selectStatus, String(statusInicial));
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
        if (opt.dataset.value === value) {
            const icon = select.querySelector('.custom-select-icon');
            const text = select.querySelector('.custom-select-text');
            if (icon) icon.src = opt.dataset.icon || '';
            if (text) text.textContent = opt.textContent.trim();
        }
    });
}

// =====================
// CUSTOM SELECT TOGGLE
// =====================

document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.custom-select-trigger');
    const option = e.target.closest('.custom-select-options li');

    if (trigger) {
        const select = trigger.closest('.custom-select');
        document.querySelectorAll('.custom-select.open').forEach((s) => {
            if (s !== select) s.classList.remove('open');
        });
        select.classList.toggle('open');
        e.stopPropagation();
        return;
    }

    if (option) {
        const select = option.closest('.custom-select');
        setCustomSelectValue(select, option.dataset.value);
        select.classList.remove('open');
        e.stopPropagation();
        return;
    }

    document.querySelectorAll('.custom-select.open').forEach((s) => s.classList.remove('open'));
});

// =====================
// CARD DETAIL MODAL
// =====================

const cardDetailOverlay = document.getElementById('cardDetailOverlay');
const detailTitleInput = document.getElementById('detailTitle');
const detailDescriptionInput = document.getElementById('detailDescription');
const detailPrioritySelect = document.getElementById('detailPriority');
const detailStatusSelect = document.getElementById('detailStatus');
const detailPrazoInput = document.getElementById('detailPrazo');
const btnCloseDetail = document.getElementById('closeCardDetail');
const btnSaveDetail = document.getElementById('saveCardDetail');
const btnDeleteDetail = document.getElementById('deleteCardDetail');

let cardDetalhe = null;

function abrirDetalhe(card) {
    cardDetalhe = card;
    detailTitleInput.value = card.dataset.titulo || '';
    detailDescriptionInput.value = card.dataset.descricao || '';
    detailPrazoInput.value = card.dataset.prazo || '';
    setCustomSelectValue(detailPrioritySelect, card.dataset.prioridade || '1');
    setCustomSelectValue(detailStatusSelect, card.dataset.status || '1');
    cardDetailOverlay.classList.add('active');
    carregarChecklist(card.dataset.id);
}

function fecharDetalhe() {
    cardDetailOverlay.classList.remove('active');
    cardDetalhe = null;
    if (checklistList) checklistList.innerHTML = '';
}

btnCloseDetail?.addEventListener('click', fecharDetalhe);

cardDetailOverlay?.addEventListener('click', (e) => {
    if (e.target === cardDetailOverlay) fecharDetalhe();
});

btnSaveDetail?.addEventListener('click', () => {
    if (!cardDetalhe) return;

    const formData = new FormData();
    formData.append('projeto_id', projetoId);
    formData.append('tarefa_id', cardDetalhe.dataset.id);
    formData.append('titulo', detailTitleInput.value.trim());
    formData.append('descricao', detailDescriptionInput.value.trim());
    formData.append('prioridade', detailPrioritySelect.dataset.value || '1');
    formData.append('status', detailStatusSelect.dataset.value || '1');
    formData.append('prazo', detailPrazoInput.value || '');

    postTarefa('editar', formData)
        .then((res) => {
            if (!res.ok) throw new Error();
            recarregar();
        })
        .catch(() => alert('Erro ao salvar'));
});

btnDeleteDetail?.addEventListener('click', () => {
    if (!cardDetalhe) return;
    if (!confirm('Deseja excluir esta tarefa?')) return;

    const formData = new FormData();
    formData.append('tarefa_id', cardDetalhe.dataset.id);

    fetch(baseUrl + '/actions/tarefas/deletar.php', { method: 'POST', body: formData })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) recarregar();
            else alert(data.message || 'Erro ao excluir');
        })
        .catch(() => alert('Erro ao excluir'));
});

document.addEventListener('click', (e) => {
    if (e.target.closest('.btn-add-card')) return;
    const card = e.target.closest('.task-card');
    if (card) abrirDetalhe(card);
});

// =====================
// EVENTOS
// =====================

searchInput2?.addEventListener('input', filtrarTarefas);
searchIcon2?.addEventListener('click', filtrarTarefas);
btnNovaTarefa2?.addEventListener('click', () => abrirModalNovo('1'));

document.querySelectorAll('.btn-add-card').forEach((btn) => {
    btn.addEventListener('click', () => abrirModalNovo(btn.dataset.status));
});
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
// DRAG AND DROP
// =====================

let placeholder = null;

function criarPlaceholder() {
    const el = document.createElement('div');
    el.className = 'drag-placeholder';
    return el;
}

function elementoAposCursor(coluna, y) {
    const cards = [...coluna.querySelectorAll('.task-card:not(.is-dragging)')];
    return cards.reduce((closest, card) => {
        const box = card.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return { offset, element: card };
        }
        return closest;
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

function limparDrag() {
    placeholder?.remove();
    placeholder = null;
    document.querySelectorAll('.kanban-column').forEach((c) => c.classList.remove('is-drag-over'));
}

document.addEventListener('dragstart', (e) => {
    const card = e.target.closest('.task-card');
    if (!card) return;
    cardArrastado = card;
    placeholder = criarPlaceholder();
    requestAnimationFrame(() => card.classList.add('is-dragging'));
});

document.addEventListener('dragend', (e) => {
    const card = e.target.closest('.task-card');
    if (!card) return;
    card.classList.remove('is-dragging');
    limparDrag();
});

document.querySelectorAll('.kanban-column').forEach((coluna) => {

    coluna.addEventListener('dragover', (e) => {
        e.preventDefault();
        if (!cardArrastado || !placeholder) return;

        coluna.classList.add('is-drag-over');

        const depois = elementoAposCursor(coluna, e.clientY);
        const btn = coluna.querySelector('.btn-add-card');

        if (depois) {
            coluna.insertBefore(placeholder, depois);
        } else {
            coluna.insertBefore(placeholder, btn);
        }
    });

    coluna.addEventListener('dragleave', (e) => {
        if (!coluna.contains(e.relatedTarget)) {
            coluna.classList.remove('is-drag-over');
        }
    });

    coluna.addEventListener('drop', (e) => {
        e.preventDefault();
        if (!cardArrastado) return;

        const statusDestino = coluna.dataset.status;
        const statusOrigem = cardArrastado.dataset.status;
        const colunaOrigem = cardArrastado.parentElement;
        const proximoIrmao = cardArrastado.nextSibling;

        placeholder?.replaceWith(cardArrastado);
        cardArrastado.dataset.status = statusDestino;
        limparDrag();

        postTarefa('editar', montarFormDataDoCard(cardArrastado, statusDestino))
            .catch(() => {
                cardArrastado.dataset.status = statusOrigem;
                colunaOrigem?.insertBefore(cardArrastado, proximoIrmao);
                alert('Erro ao mover tarefa');
            });
    });

});

// =====================
// CHECKLIST
// =====================

const checklistList        = document.getElementById('checklistList');
const checklistInput       = document.getElementById('checklistInput');
const checklistAddBtn      = document.getElementById('checklistAddBtn');
const checklistFraction    = document.getElementById('checklistFraction');
const checklistProgressFill = document.getElementById('checklistProgressFill');

function escapeHtmlJS(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function postChecklist(action, data) {
    return fetch(`${baseUrl}/actions/checklist/${action}.php`, { method: 'POST', body: data })
        .then((res) => res.json());
}

function renderChecklistItem(item) {
    const li = document.createElement('li');
    li.className = 'checklist-item' + (item.concluido == 1 ? ' done' : '');
    li.dataset.id = item.id;
    li.innerHTML = `
        <label class="checklist-item-label">
            <input type="checkbox" class="checklist-check" ${item.concluido == 1 ? 'checked' : ''}>
            <span class="checklist-item-text">${escapeHtmlJS(item.texto)}</span>
        </label>
        <button type="button" class="checklist-delete-btn" title="Remover">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    `;
    return li;
}

function updateChecklistUI() {
    if (!checklistList) return;
    const items = checklistList.querySelectorAll('.checklist-item');
    const total = items.length;
    const done  = checklistList.querySelectorAll('.checklist-item.done').length;
    const pct   = total > 0 ? Math.round((done / total) * 100) : 0;

    if (checklistFraction) checklistFraction.textContent = `${done}/${total}`;
    if (checklistProgressFill) checklistProgressFill.style.width = `${pct}%`;

    if (cardDetalhe) {
        cardDetalhe.dataset.checklistTotal = total;
        cardDetalhe.dataset.checklistDone  = done;
        const badge    = cardDetalhe.querySelector('.checklist-badge');
        const countEl  = badge?.querySelector('.checklist-badge-count');
        if (badge)   badge.dataset.total = total;
        if (badge)   badge.dataset.done  = done;
        if (countEl) countEl.textContent = `${done}/${total}`;
    }
}

function carregarChecklist(tarefaId) {
    if (!checklistList) return;
    checklistList.innerHTML = '<li class="checklist-loading">Carregando...</li>';

    fetch(`${baseUrl}/actions/checklist/listar.php?tarefa_id=${tarefaId}`)
        .then((res) => res.json())
        .then((data) => {
            checklistList.innerHTML = '';
            if (data.success) {
                data.items.forEach((item) => checklistList.appendChild(renderChecklistItem(item)));
            }
            updateChecklistUI();
        })
        .catch(() => { checklistList.innerHTML = ''; });
}

function adicionarChecklistItem() {
    if (!cardDetalhe) return;
    const texto = checklistInput?.value.trim();
    if (!texto) return;

    const formData = new FormData();
    formData.append('tarefa_id', cardDetalhe.dataset.id);
    formData.append('texto', texto);

    postChecklist('criar', formData).then((data) => {
        if (data.success && data.item) {
            checklistList.appendChild(renderChecklistItem(data.item));
            updateChecklistUI();
            checklistInput.value = '';
            checklistInput.focus();
        }
    });
}

checklistAddBtn?.addEventListener('click', adicionarChecklistItem);

checklistInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); adicionarChecklistItem(); }
});

checklistList?.addEventListener('change', (e) => {
    const checkbox = e.target.closest('.checklist-check');
    if (!checkbox || !cardDetalhe) return;
    const li = checkbox.closest('.checklist-item');
    if (!li) return;

    const formData = new FormData();
    formData.append('item_id', li.dataset.id);
    formData.append('tarefa_id', cardDetalhe.dataset.id);

    postChecklist('toggle', formData).then((data) => {
        if (data.success) {
            li.classList.toggle('done', data.concluido == 1);
            checkbox.checked = data.concluido == 1;
            updateChecklistUI();
        } else {
            checkbox.checked = !checkbox.checked;
        }
    });
});

checklistList?.addEventListener('click', (e) => {
    const btn = e.target.closest('.checklist-delete-btn');
    if (!btn || !cardDetalhe) return;
    const li = btn.closest('.checklist-item');
    if (!li) return;

    const formData = new FormData();
    formData.append('item_id', li.dataset.id);
    formData.append('tarefa_id', cardDetalhe.dataset.id);

    postChecklist('deletar', formData).then((data) => {
        if (data.success) { li.remove(); updateChecklistUI(); }
    });
});