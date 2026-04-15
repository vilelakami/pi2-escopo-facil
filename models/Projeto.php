<?php
// RESPONSABILIDADE: única camada que fala com a tabela `projetos`
// Não valida regra de negócio. Só executa SQL e retorna dados.
//
// Métodos que devem existir aqui:
//
// listarPorUsuario(int $usuarioId): array
//   - busca todos os projetos onde o usuário é membro
//   - faz JOIN com projeto_membros para pegar o role do usuário em cada projeto
//   - faz JOIN com usuarios para pegar o nome de quem criou
//   - retorna array de projetos com: id, titulo, descricao, role, criado_por, data_criacao, total_membros
//
// buscarPorId(int $id): array|false
//   - retorna um projeto pelo id ou false se não existir
//
// criar(string $titulo, string $descricao, int $usuarioId): int
//   - insere na tabela projetos
//   - retorna o id do novo projeto (lastInsertId)
//
// atualizar(int $id, string $titulo, string $descricao): void
//   - atualiza titulo e descricao do projeto
//
// deletar(int $id): void
//   - deleta o projeto (CASCADE no banco já cuida dos membros e tarefas)
