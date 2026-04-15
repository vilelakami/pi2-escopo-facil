<?php
// RESPONSABILIDADE: única camada que fala com a tabela `projeto_membros`
// Não valida regra de negócio. Só executa SQL e retorna dados.
//
// Métodos que devem existir aqui:
//
// listar(int $projetoId): array
//   - retorna todos os membros de um projeto
//   - faz JOIN com usuarios para pegar nome, email, cargo, avatar
//   - retorna array com: id, nome, email, cargo, avatar, role, adicionado_em
//
// adicionar(int $projetoId, int $usuarioId, string $role): void
//   - insere um usuário como membro do projeto
//   - role padrão: 'membro'
//
// remover(int $projetoId, int $usuarioId): void
//   - remove o vínculo entre usuário e projeto
//
// isAdmin(int $projetoId, int $usuarioId): bool
//   - retorna true se o usuário tem role = 'admin' neste projeto
//
// jaEMembro(int $projetoId, int $usuarioId): bool
//   - retorna true se o usuário já está vinculado ao projeto
