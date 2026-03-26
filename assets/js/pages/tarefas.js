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
const inputPrioridade = document.querySelector('#taskPriority');
const inputStatus = document.querySelector('#taskStatus');
const inputPrazo = document.querySelector('#taskDate');

// Armazenando ícones do modal:
const iconCalendario = document.querySelector('.modal-date .icon');
const iconPrioridade = document.querySelector('.modal-priority .icon');
const iconStatus = document.querySelector('.modal-status img');

// Variável de arrastar card
let cardArrastado = null;

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
    //adiciona uma classe ao modal-overlay como 'active' pra ela aparecer
    modal.classList.add('active');
});

//arrow function que fecha o modal
btnCancelar.addEventListener('click', () => {
    // remove a classe 'active' e fecha o modal
    modal.classList.remove('active');
    limparCamposModal();
});

// se o ícone existe
if(btnVoltar){
    // e se for clicado
    btnVoltar.addEventListener('click', () => {
        // remove a classe 'active' e fecha o modal
        modal.classList.remove('active');
        limparCamposModal();
    });
}

//arrow function que o usuário clicar no fundo escuro
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.remove('active');
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

// +-IA: criando arrow function pra mudar os icones da prioridade
inputPrioridade.addEventListener('change', () => {
    const valor = inputPrioridade.value;
    const mapaDeIcones = {
        "1": "assets/icon/grafico-baixa.svg",
        "2": "assets/icon/grafico-media.svg",
        "3": "assets/icon/grafico-alta.svg"
    };

    const caminho = mapaDeIcones[valor];
    if(caminho) iconPrioridade.src = caminho;
});

// +-IA: criando arrow function para mudar os ícones de status
inputStatus.addEventListener('change', () => {
    const valor = inputStatus.value;
    const mapaDeIcones = {
        "1": "assets/icon/a-fazer.svg",
        "2": "assets/icon/loading.svg",
        "3": "assets/icon/concluido.svg"
    };

    const caminho = mapaDeIcones[valor];
    if(caminho) iconStatus.src = caminho;
});

// ========================= JS DO BOTÃO SALVAR/MODAL ========================
// IA
btnSalvar.onclick = function () {
    const titulo = inputTitulo.value;
    const descricao = inputDescricao.value;
    const prioridade = inputPrioridade.value;
    const status = inputStatus.value;
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
        const partes = prazo.split('-'); // Converte de YYYY-MM-DD para DD/MM/YYYY
        dataExibicao = `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    // 2. Criar o elemento do card
    const novoCard = document.createElement('div');
    novoCard.classList.add('task-card');
    novoCard.setAttribute('draggable', 'true');

    // 3. Configurar classes e ícones de acordo com a prioridade
    const infoPrio = {
        "1": { texto: "Baixa", classe: "low", icone: "grafico-baixa.svg" },
        "2": { texto: "Média", classe: "medium", icone: "grafico-media.svg" },
        "3": { texto: "Alta", classe: "high", icone: "grafico-alta.svg" }
    };
    const prio = infoPrio[prioridade];

    novoCard.innerHTML = `
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
    
    // 6. Inserir na coluna correta
    const colunaDestino = document.querySelector(`#col-${status}`);
    if (colunaDestino) {
        colunaDestino.appendChild(novoCard);
        
        // 7. Fechar modal e limpar
        modal.classList.remove('active');
        limparCamposModal();
    } else {
        alert("Coluna não encontrada!");
    }
};

// ==================== JS LIMPAR MODAL ============================

function limparCamposModal() {
    inputTitulo.value = "";
    inputDescricao.value = "";
    inputPrazo.value = "";
    inputPrioridade.value = "1";
    inputStatus.value = "1";
    
    // reseta os ícones
    iconPrioridade.src = "assets/icon/grafico-baixa.svg";
    iconStatus.src = "assets/icon/a-fazer.svg";
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