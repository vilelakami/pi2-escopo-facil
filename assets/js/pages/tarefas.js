// armazenando o input e a msg de 'tarefa nao encontrada' e também o icone de lupa
const searchInput = document.querySelector('.search-input-wrapper input');
const noTasksMessage = document.getElementById('no-tasks-message');
const searchIcon = document.querySelector('.search-input-wrapper img');

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