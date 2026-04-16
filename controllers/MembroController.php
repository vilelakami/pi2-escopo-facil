<?php
// RESPONSABILIDADE: recebe dados do $_POST, valida, decide o que fazer, chama o Model, redireciona
// Não acessa banco diretamente. Não renderiza HTML.
//
// Deve fazer require do model: ProjetoMembro.php
//
// Métodos que devem existir aqui:
//
// adicionar(): void
//   - lê $_POST['projeto_id'] e $_POST['email']
//   - verifica se o usuário logado é admin do projeto via ProjetoMembro::isAdmin()
//   - busca o usuário pelo email na tabela usuarios
//   - se não encontrar: redireciona com erro
//   - verifica se já é membro via ProjetoMembro::jaEMembro()
//   - se já for: redireciona com erro
//   - chama ProjetoMembro::adicionar()
//   - redireciona para /?page=projetos
//
// remover(): void
//   - lê $_POST['projeto_id'] e $_POST['membro_id']
//   - admin pode remover qualquer membro
//   - membro comum só pode remover a si mesmo (sair do projeto)
//   - se nenhuma das condições: redireciona com erro
//   - chama ProjetoMembro::remover()
//   - redireciona para /?page=projetos
