<?php
// RESPONSABILIDADE: recebe dados do $_POST, valida, decide o que fazer, chama o Model, redireciona
// Não acessa banco diretamente. Não renderiza HTML.
//
// Deve fazer require dos models que precisa: Projeto.php e ProjetoMembro.php
//
// Métodos que devem existir aqui:
//
// criar(): void
//   - lê $_POST['titulo'] e $_POST['descricao']
//   - valida se titulo não está vazio
//   - pega o id do usuário logado via $_SESSION['usuario_id']
//   - chama Projeto::criar()
//   - chama ProjetoMembro::adicionar() para vincular o criador como admin
//   - redireciona para /?page=projetos
//
// editar(): void
//   - lê $_POST['projeto_id'], $_POST['titulo'], $_POST['descricao']
//   - verifica se o usuário logado é admin do projeto via ProjetoMembro::isAdmin()
//   - se não for: redireciona com erro
//   - valida titulo
//   - chama Projeto::atualizar()
//   - redireciona para /?page=projetos
//
// deletar(): void
//   - lê $_POST['projeto_id']
//   - verifica se o usuário logado é admin via ProjetoMembro::isAdmin()
//   - se não for: redireciona com erro
//   - chama Projeto::deletar()
//   - redireciona para /?page=projetos
