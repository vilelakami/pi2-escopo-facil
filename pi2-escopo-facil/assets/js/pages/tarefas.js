// ================= JS DA BARRA DE PESQUISA ========================

// Armazenando a barra de pesquisa, a msg de 'tarefa nao encontrada', o icone de lupa, o botão de nova tarefa e o modal
const searchInput = document.querySelector('.search-input-wrapper input');
const noTasksMessage = document.getElementById('no-tasks-message');
const searchIcon = document.querySelector('.search-input-wrapper img');
const btnNovaTarefa = document.querySelector('.btn-new-task-main');
const modal = document.querySelector('.modal-overlay');

// Armazenando o botão de cancelar do modal, o de salvar do modal e o ícone de voltar do título do modal
const btnCancelar = document.querySelector('#closeModal');
const btnSalvar = document.querySelector('#saveTask');
const btnVoltar = document.querySelector('.btn-back');

// Armazenando inputs:
const inputTitulo = document.querySelector('#taskTitle');
const inputDescricao = document.querySelector('#taskDescription');
const selectPrioridade = document.querySelector('#taskPriority');
const selectStatus = document.querySelector('#taskStatus');
const inputPrazo = document.querySelector('#taskDate');

// Armazenando ícones do modal:
const iconCalendario = document.querySelector('.modal-date .icon');

// Variável de arrastar card
let cardArrastado = null;

// Variável de edição — guarda o card sendo editado (null = modo criação)
let cardEditando = null;
const modalTitulo = document.querySelector('#modalTitulo');

// ==================== FUNÇÃO DE ARRASTAR E SOLTAR ============================
// IA
document.addEventListener('dragstart', (e) => {
    // Se o que eu peguei tem a classe task-card
    if (e.target.classList.contains('task-card')) {
        cardArrastado = e.target; // Guardo ele na variável
        e.target.style.opacity = '0.5'; // Deixo ele transparente pra dar efeito
    }
});

// Seleciona todas as colunas brancas
const colunas = document.querySelectorAll('.kanban-column');

colunas.forEach(coluna => {
    // 1. OBRIGATÓRIO: Avisar que a coluna aceita o drop
    coluna.addEventListener('dragover', (e) => {
        e.preventDefault(); // Isso "abre a porta" para o card entrar
        coluna.classList.add('drag-over'); // Opcional: muda a cor da coluna
    });

    // 2. Quando o card sai de cima da coluna sem soltar
    coluna.addEventListener('dragleave', () => {
        coluna.classList.remove('drag-over');
    });

    // 3. Quando você solta o botão do mouse
    coluna.addEventListener('drop', () => {
        coluna.classList.remove('drag-over');
        
        if (cardArrastado) {
            // O appendChild move o elemento da coluna antiga para esta nova
            coluna.appendChild(cardArrastado);
        }
    });
});

document.addEventListener('dragend', (e) => {
    if (e.target.classList.contains('task-card')) {
        e.target.style.opacity = '1'; // Volta ao normal quando solta
    }
});

// ==================== FUNÇÃO FILTRO PESQUISA ============================

function Filter() {
    // pega o que a pessoa digitou converte em minusculo e armazena
    const tipoPesquisa = searchInput.value.toLowerCase();
    // armazena todos os cards em tarefas
    const tarefas = document.querySelectorAll('.task-card');
    // contador bool que verifica se o card foi encontrado
    let encontrouAlguma = false;

    // foreach que percorre o array de tarefas
    tarefas.forEach(tarefa => {
        // pega o titulo da tarefa, descrição, prioridade ou prazo e armazena
        const titulo = tarefa.querySelector('.task-description h3').innerText.toLowerCase();
        const descricao = tarefa.querySelector('.task-description p').innerText.toLowerCase();
        const prioridade = tarefa.querySelector(".priority-tag p").innerText.toLowerCase();
        const prazo = tarefa.querySelector('.input-deadline');

        //compara se o prazo tem valor no input ou placeholder
        const valorPrazo = (prazo.value || prazo.placeholder).toLowerCase();

        //inclui o tipo de pesquisa a toas as variaveis
        const temPrazo = valorPrazo.includes(tipoPesquisa);
        const temTexto = (titulo.includes(tipoPesquisa) || descricao.includes(tipoPesquisa));
        const temPrioridade = prioridade.includes(tipoPesquisa);

        // se o campo de pesquisa estiver vazio, ou se encontrar o titulo, descrição, prioridade ou prazo
        if (tipoPesquisa === "" || temPrazo || temPrioridade || temTexto){
            //a tarefa aparece
            tarefa.style.display = "block";
            //e o contador é true   
            encontrouAlguma = true;
        } else {
            // caso contrario nao aparece nada
            tarefa.style.display = "none";
        }
    });

    // se a variavel encontrouAlguma for 'false', exibe a mensagem na tela  
    if (noTasksMessage) {
        noTasksMessage.style.display = encontrouAlguma ? "none" : "block";
    }
}

// IA: esse evento do input ocorre em tempo real de digitação
searchInput.addEventListener('input', Filter);
//esse evento transforma o icone de lupa em 'enter'
searchIcon.addEventListener('click', Filter);


// ==================== JS DO MODAL (ESTADOS E EVENTOS) ============================

//arrow function que abre o modal
btnNovaTarefa.addEventListener('click', () => {
    cardEditando = null;
    modalTitulo.textContent = 'Nova Tarefa';
    limparCamposModal();
    modal.classList.add('active');
});

//arrow function que fecha o modal
btnCancelar.addEventListener('click', () => {
    modal.classList.remove('active');
    cardEditando = null;
    modalTitulo.textContent = 'Nova Tarefa';
    limparCamposModal();
});

// se o ícone existe
if(btnVoltar){
    // e se for clicado
    btnVoltar.addEventListener('click', () => {
        modal.classList.remove('active');
        cardEditando = null;
        modalTitulo.textContent = 'Nova Tarefa';
        limparCamposModal();
    });
}

//arrow function que o usuário clicar no fundo escuro
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.remove('active');
        cardEditando = null;
        modalTitulo.textContent = 'Nova Tarefa';
        limparCamposModal();
    }
});

//se existe o icone e o input da data
if(iconCalendario && inputPrazo){
    //faz a arrow function, assim que clicar no ícone de calendário as datas são exibidas
    iconCalendario.addEventListener('click', () => {
        inputPrazo.showPicker();
    });
}

// ==================== CUSTOM SELECT (PRIORIDADE E STATUS) ============================

// Inicializa todos os custom selects do modal
document.querySelectorAll('.modal .custom-select').forEach(select => {
    const trigger = select.querySelector('.custom-select-trigger');
    const options = select.querySelectorAll('.custom-select-options li');
    const iconEl = select.querySelector('.custom-select-icon');
    const textEl = select.querySelector('.custom-select-text');

    // Marca a opção inicial como selected
    const initialValue = select.dataset.value;
    options.forEach(opt => {
        if (opt.dataset.value === initialValue) opt.classList.add('selected');
    });

    // Abre/fecha o dropdown ao clicar no trigger
    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        // Fecha outros selects abertos
        document.querySelectorAll('.custom-select.open').forEach(s => {
            if (s !== select) s.classList.remove('open');
        });
        select.classList.toggle('open');
    });

    // Ao clicar numa opção
    options.forEach(opt => {
        opt.addEventListener('click', (e) => {
            e.stopPropagation();
            select.dataset.value = opt.dataset.value;
            textEl.textContent = opt.textContent.trim();
            iconEl.src = opt.dataset.icon;

            // Atualiza classe selected
            options.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');

            select.classList.remove('open');
        });
    });
});

// Fecha custom selects ao clicar fora
document.addEventListener('click', () => {
    document.querySelectorAll('.custom-select.open').forEach(s => s.classList.remove('open'));
});

// ==================== JS DO BOTÃO EDITAR ============================

// Mapa de prioridade: texto do card → valor do custom select
const mapaPrioTexto = { "baixa": "1", "média": "2", "alta": "3" };

// Mapa de coluna → valor de status
const mapaColStatus = { "col-1": "1", "col-2": "2", "col-3": "3" };

document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-edit');
    if (!btn) return;

    const card = btn.closest('.task-card');
    if (!card) return;

    cardEditando = card;
    modalTitulo.textContent = 'Editar Tarefa';

    // Extrai dados do card
    const titulo = card.querySelector('.task-description h3').textContent.trim();
    const descricao = card.querySelector('.task-description p').textContent.trim();
    const prioTexto = card.querySelector('.priority-tag p').textContent.replace('Prioridade:', '').trim().toLowerCase();
    const prioValor = mapaPrioTexto[prioTexto] || "1";

    // Prazo — pode ser input ou span
    const prazoInput = card.querySelector('.input-deadline');
    const prazoSpan = card.querySelector('.deadline-text');
    let prazoTexto = '';
    if (prazoInput) prazoTexto = prazoInput.value || prazoInput.placeholder || '';
    else if (prazoSpan) prazoTexto = prazoSpan.textContent.trim();

    // Converte DD/MM/YYYY para YYYY-MM-DD para o input date
    let prazoDate = '';
    if (prazoTexto) {
        const partes = prazoTexto.split('/');
        if (partes.length === 3) prazoDate = `${partes[2]}-${partes[1]}-${partes[0]}`;
    }

    // Status pela coluna onde o card está
    const coluna = card.closest('.kanban-column');
    const statusValor = coluna ? (mapaColStatus[coluna.id] || "1") : "1";

    // Preenche o modal
    inputTitulo.value = titulo;
    inputDescricao.value = descricao;
    inputPrazo.value = prazoDate;

    // Atualiza custom selects
    setCustomSelectValue(selectPrioridade, prioValor);
    setCustomSelectValue(selectStatus, statusValor);

    modal.classList.add('active');
});

// Helper para setar valor de um custom select
function setCustomSelectValue(select, value) {
    select.dataset.value = value;
    const options = select.querySelectorAll('.custom-select-options li');
    options.forEach(opt => {
        opt.classList.remove('selected');
        if (opt.dataset.value === value) {
            opt.classList.add('selected');
            select.querySelector('.custom-select-text').textContent = opt.textContent.trim();
            select.querySelector('.custom-select-icon').src = opt.dataset.icon;
        }
    });
}

// ========================= JS DO BOTÃO SALVAR/MODAL ========================
// IA
btnSalvar.onclick = function () {
    const titulo = inputTitulo.value;
    const descricao = inputDescricao.value;
    const prioridade = selectPrioridade.dataset.value;
    const status = selectStatus.dataset.value;
    const prazo = inputPrazo.value;

    if (titulo.trim() === "") {
        alert("O título é obrigatório!");
        return;
    }

    let dataExibicao = "";
    if (prazo === "") {
        const hoje = new Date();
        const dia = String(hoje.getDate()).padStart(2, '0');
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const ano = hoje.getFullYear();
        dataExibicao = `${dia}/${mes}/${ano}`;
    } else {
        const partes = prazo.split('-');
        dataExibicao = `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    const infoPrio = {
        "1": { texto: "Baixa", classe: "low", icone: "grafico-baixa.svg" },
        "2": { texto: "Média", classe: "medium", icone: "grafico-media.svg" },
        "3": { texto: "Alta", classe: "high", icone: "grafico-alta.svg" }
    };
    const prio = infoPrio[prioridade];

    const cardHTML = `
        <div class="task-description">
            <h3>${titulo}</h3>
            <p>${descricao}</p>
            
            <div class="task-status">
                <div class="priority-tag ${prio.classe}">
                    <p>Prioridade: ${prio.texto}</p>
                    <img src="assets/icon/${prio.icone}" alt="prioridade">
                </div>

                <div class="datetime-priority">
                    <p>Prazo:</p>
                    <img src="assets/icon/calendar.svg" alt="calendário">
                    <span class="deadline-text">${dataExibicao}</span>
                </div>
            </div>

            <div class="task-btn">
                <button class="btn-edit">
                    <img src="assets/icon/edit.svg" alt="editar">
                    Editar
                </button>
                <button class="btn-delete">Excluir</button>
            </div>
        </div>
    `;

    const colunaDestino = document.querySelector(`#col-${status}`);

    if (cardEditando) {
        // Modo edição — atualiza o card existente
        cardEditando.innerHTML = cardHTML;

        // Se mudou de coluna, move o card
        const colunaAtual = cardEditando.closest('.kanban-column');
        if (colunaDestino && colunaAtual.id !== colunaDestino.id) {
            colunaDestino.appendChild(cardEditando);
        }

        cardEditando = null;
        modal.classList.remove('active');
        limparCamposModal();
    } else {
        // Modo criação — cria novo card
        const novoCard = document.createElement('div');
        novoCard.classList.add('task-card');
        novoCard.setAttribute('draggable', 'true');
        novoCard.innerHTML = cardHTML;

        if (colunaDestino) {
            colunaDestino.appendChild(novoCard);
            modal.classList.remove('active');
            limparCamposModal();
        } else {
            alert("Coluna não encontrada!");
        }
    }
};

// ==================== JS LIMPAR MODAL ============================

function limparCamposModal() {
    inputTitulo.value = "";
    inputDescricao.value = "";
    inputPrazo.value = "";

    // Reseta custom selects para valor padrão
    resetCustomSelect(selectPrioridade, "1");
    resetCustomSelect(selectStatus, "1");
}

function resetCustomSelect(select, defaultValue) {
    select.dataset.value = defaultValue;
    const options = select.querySelectorAll('.custom-select-options li');
    options.forEach(opt => {
        opt.classList.remove('selected');
        if (opt.dataset.value === defaultValue) {
            opt.classList.add('selected');
            select.querySelector('.custom-select-text').textContent = opt.textContent.trim();
            select.querySelector('.custom-select-icon').src = opt.dataset.icon;
        }
    });
    select.classList.remove('open');
}


// ==================== JS DE EXCLUIR CARD ========================
document.removeEventListener('click', excluirTarefaGlobal);

function excluirTarefaGlobal(event) {
    //fazendo com que o eventListener não escute mais de uma vez o click de excluir
    const btn = event.target.closest('.btn-delete');
    if (!btn) return;

    event.stopImmediatePropagation();
    event.preventDefault();

    const card = btn.closest('.task-card');

    if (card) {
        if (confirm("Deseja realmente excluir esta tarefa?")) {
            card.remove();
        }
    }
}

document.addEventListener('click', excluirTarefaGlobal);