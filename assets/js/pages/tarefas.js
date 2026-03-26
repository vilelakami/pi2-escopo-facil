{
// ================= JS DA BARRA DE PESQUISA ========================
// armazenando o input e a msg de 'tarefa nao encontrada' e também o icone de lupa
const searchInput = document.querySelector('.search-input-wrapper input');
const noTasksMessage = document.getElementById('no-tasks-message');
const searchIcon = document.querySelector('.search-input-wrapper img');
const btnNovaTarefa = document.querySelector('.btn-new-task-main');
const modal = document.querySelector('.modal-overlay');
// armazenando o botão de '+ nova tarefa', o modal, o botão de cancelar e o de salvar
const btnCancelar = document.querySelector('#closeModal');
const btnSalvar = document.querySelector('#saveTask');
const btnVoltar = document.querySelector('.btn-back');
// armazenando inputs:
const inputTitulo = document.querySelector('#taskTitle');
const inputDescricao = document.querySelector('#taskDescription');
const inputPrioridade = document.querySelector('#taskPriority');
const inputStatus = document.querySelector('#taskStatus');
const inputPrazo = document.querySelector('#taskDate');
// armazenando icone da data
const iconCalendario = document.querySelector('.modal-date .icon');
// armazenando icone de prioridade
const iconPrioridade = document.querySelector('.modal-priority .icon');
//armazenando icone de status
const iconStatus = document.querySelector('.modal-status img');

// função que filtra a pesquisa
function Filter() {
    // pega o que a pessoa digitou converte em minusculo e armazena
    const tipoPesquisa = searchInput.value.toLowerCase();
    // armazena todos os cards em tarefas
    const tarefas = document.querySelectorAll('.task-card');
    // contador bool que verifica se o card foi encontrado
    let encontrouAlguma = false;

    // for que percorre o array de tarefas
    tarefas.forEach(tarefa => {
        // pega o titulo da tarefa, descrição, prioridade ou prazo e armazena
        const titulo = tarefa.querySelector('.task-description h3').innerText.toLowerCase();
        const descricao = tarefa.querySelector('.task-description p').innerText.toLowerCase();
        const prioridade = tarefa.querySelector(".priority-tag p").innerText.toLowerCase();
        const prazo = tarefa.querySelector('.input-deadline')

        //compra se o prazo tem valor no input ou placeholder
        const valorPrazo = (prazo.value || prazo.placeholder).toLowerCase();

        //inclui o tipo de pesquisa a toas as variaveis
        const temPrazo = valorPrazo.includes(tipoPesquisa);
        const temTexto = (titulo.includes(tipoPesquisa) || descricao.includes(tipoPesquisa));
        const temPrioridade = prioridade.includes(tipoPesquisa);

        // se o campo de pesquisa estiver vazio, ou se encontrar o titulo, descrição, prioridade ou prazo
        if  (tipoPesquisa === "" || temPrazo || temPrioridade || temTexto){
            //a tarefa aparece
            tarefa.style.display = "block";
            //e o contador é true   
            encontrouAlguma = true;
            // caso contrario nao aparece nada
        } else {
            tarefa.style.display = "none";
        }
    });

    // se a variavel encontrouAlguma for 'false', exibe a mensagem na tela  
    if (noTasksMessage) {
        noTasksMessage.style.display = encontrouAlguma ? "none" : "block";
    }
}

//esse evento do input ocorre em tempo real de digitação
searchInput.addEventListener('input', Filter);
//esse evento transforma o icone de lupa em 'enter'
searchIcon.addEventListener('click', Filter);


// ==================== JS DO MODAL ============================
//arrow function que abre o modal
btnNovaTarefa.addEventListener('click', () => {
    //adiciona uma classe ao modal-overlay como 'active' pra ela aparecer
    modal.classList.add('active');
});

//arrow function que fecha o modal
btnCancelar.addEventListener('click', () => {
    // remove a classe 'active' e fecha o modal
    modal.classList.remove('active');
});

// se o ícone existe
if(btnVoltar){
    // e se for clicado
    btnVoltar.addEventListener('click', () => {
        // remove a classe 'active' e fecha o modal
        modal.classList.remove('active');
    });
}

//arrow function que o usuário clicar no fundo escuro
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.remove('active');
    }
});

//se existe o icone e o input da data
if(iconCalendario && inputPrazo){
    //faz a arrow function, assim que clicar no ícone de calendário as datas são exibidas
    iconCalendario.addEventListener('click', () => {
        inputPrazo.showPicker();
    });
}

//criando arrow function pra mudar os icones da prioridade
inputPrioridade.addEventListener('change', () => {
    // variável que armazena o valor da prioridade -> '1,2,3'
    const valor = inputPrioridade.value;
    // variavel que armazena o caminho desse icone
    let caminho = "";

    // objeto que mapeia o valor e seu respectivo icone
    const mapaDeIcones = {
        "1": "assets/icon/grafico-baixa.svg",
        "2": "assets/icon/grafico-media.svg",
        "3": "assets/icon/grafico-alta.svg"
    };

    //caminho recebe o o valor e os ícones
    caminho = mapaDeIcones[valor];

    //verifica se o caminho existe
    if(caminho){
        //exibe o icone corretamente
        iconPrioridade.src = caminho;
    }
});

//criando arrow function para mudar os ícones de status
inputStatus.addEventListener('change', () => {
    const valor = inputStatus.value;
    let caminho = "";

    const mapaDeIcones = {
        "1": "assets/icon/a-fazer.svg",
        "2": "assets/icon/loading.svg",
        "3": "assets/icon/concluido.svg"
    };

    caminho = mapaDeIcones[valor];

    if(caminho){
        iconStatus.src = caminho;
    }
});

// ========================= JS DO BOTÃO SALVAR/MODAL ========================
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
        const mes = String(hoje.getMonth() + 1).padStart(2, '0'); // Janeiro é 0
        const ano = hoje.getFullYear();
        dataExibicao = `${dia}/${mes}/${ano}`;
    } else {
        const partes = prazo.split('-'); // Converte de YYYY-MM-DD para DD/MM/YYYY
        dataExibicao = `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    // 2. Criar o elemento do card
    const novoCard = document.createElement('div');
    novoCard.classList.add('task-card');

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

    // 5. Adicionar funcionalidade ao botão de excluir que acabamos de criar
    const btnExcluir = novoCard.querySelector('.btn-delete');
    btnExcluir.addEventListener('click', () => {
        novoCard.remove();
    });

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
}