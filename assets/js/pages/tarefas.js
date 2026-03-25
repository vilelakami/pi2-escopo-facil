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
//dinâmica do botão salvar tarefa
}