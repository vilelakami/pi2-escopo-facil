<p align="center">
  <img src="assets/images/Logo.png" alt="Logo Escopo Facil" width="280">
</p>

<p align="center">
  <strong>Organize tarefas, acompanhe o progresso e conclua projetos com clareza.</strong>
</p>

<p align="center">
  <img alt="status" src="https://img.shields.io/badge/status-finalizado-2ea44f?style=for-the-badge">
  <img alt="JavaScript" src="https://img.shields.io/badge/JavaScript-frontend-F7DF1E?style=for-the-badge&logo=javascript&logoColor=000">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-database-4479A1?style=for-the-badge&logo=mysql&logoColor=white">
</p>

## Sobre

O **Escopo Fácil** é uma solução web para gerenciamento de projetos voltada a equipes que buscam mais organização, colaboração e controle sobre suas entregas. Em um mercado cada vez mais orientado por produtividade, prazos e trabalho em equipe, a ferramenta oferece uma forma simples de visualizar o progresso dos projetos e manter todos os envolvidos alinhados.

Com ele, os usuários conseguem montar projetos, adicionar membros, organizar tarefas em um kanban e acompanhar checklists. Além disso, o sistema conta com uma configuração de perfil completa, com a possibilidade de personalizar o avatar e gerenciar seus dados.

A proposta do projeto é atender à necessidade de centralizar informações, reduzindo ruídos na comunicação e apoiando a tomada de decisão com base no andamento real de tarefas.

## Funcionalidades

- 🔐 Cadastro, login e logout com sessão.
- ✅ Confirmação de email por token.
- 👤 Edição de perfil e troca de senha.
- 🔁 Recuperação de senha por link temporário.
- 🎨 Personalização de avatar com cores e ícones.
- 📁 CRUD de projetos.
- 👥 Gerenciamento de membros por projeto.
- 📌 Kanban de tarefas por projeto.
- 🧩 Checklist interno nas tarefas.
- 🖱️ Drag and drop no quadro de tarefas.
- 🔎 Busca e filtros em telas do sistema.
- 🍞 Feedback visual com mensagens/toasts.
- 📚 Documentação didática para estudantes.

## Tecnologias

| Camada | Tecnologias |
|---|---|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP, PDO |
| Banco de dados | MySQL |
| Ambiente local | XAMPP |
| Organização | Actions, Controllers, Models, Includes e Pages |

## Como o projeto funciona

O Escopo Fácil usa uma estrutura simples, pensada para facilitar o aprendizado:

```text
actions/      Recebem POSTs e chamadas do navegador
controllers/  Validam regras e coordenam o fluxo
models/       Conversam com o banco de dados
includes/     Guardam utilitários compartilhados
pages/        Renderizam as telas
partials/     Componentes reutilizados no layout
assets/       CSS, JS, imagens, vídeos e ícones
database/     Schema, seed e migrations
docs/         Documentação do projeto
```
```text
Usuário envia formulário
        ↓
action recebe o POST
        ↓
controller valida a regra
        ↓
model salva ou busca no banco
        ↓
controller redireciona o usuário
```

## Site institucional

```text
https://magenta-potato-409547.framer.app/
```

## Protótipo no Figma

[Escopo facil](https://www.figma.com/design/r6XBWNskGGCUPOhLBtpp5D/Escopo-facil?node-id=373-112&t=WbsunolnTIE2uQLY-1)

## Objetivo acadêmico

Este projeto foi desenvolvido como trabalho acadêmico, com foco em aprendizado prático de:

- Organização de código;
- Programação backend com PHP;
- Banco de dados relacional MySQL;
- CRUD completo;
- Autenticação;
- Segurança básica;
- Experiência visual;
- Colaboração em equipe usando Git/GitHub.

## Problema que o projeto resolve

Durante o desenvolvimento de projetos em equipe, é comum que informações fiquem espalhadas em conversas, arquivos soltos, planilhas e anotações individuais. Isso dificulta saber quem está fazendo cada tarefa, quais entregas estão atrasadas, quais etapas já foram concluídas e qual é o estado real do projeto.

O **Escopo Fácil** propõe centralizar essas informações em uma única plataforma. A ideia é ajudar equipes pequenas, estudantes e grupos de trabalho a organizarem projetos, dividirem responsabilidades e acompanharem o andamento das tarefas de forma visual.

## Principais fluxos do sistema

1. **Cadastro e confirmação de email:** o usuário cria uma conta e confirma o cadastro por meio de um link enviado por email.
2. **Login:** após confirmar o email, o usuário acessa o sistema com email e senha.
3. **Dashboard:** a tela inicial mostra um resumo dos projetos e tarefas vinculados ao usuário logado.
4. **Projetos:** o usuário cria, visualiza, edita e gerencia projetos.
5. **Membros:** administradores podem adicionar ou remover pessoas dos projetos.
6. **Tarefas:** cada projeto possui um quadro Kanban com tarefas separadas por status.
7. **Checklist:** as tarefas podem ter itens internos para acompanhar etapas menores.
8. **Configuração:** o usuário pode atualizar dados pessoais, senha e avatar.
9. **Recuperação de senha:** o sistema gera um link temporário para redefinir a senha com segurança.

## CRUD no projeto

CRUD significa **Create, Read, Update e Delete**, ou seja: criar, listar, atualizar e deletar dados.

No Escopo Fácil, o CRUD aparece principalmente em:

| Entidade | Create | Read | Update | Delete |
|---|---|---|---|---|
| Projetos | Criar projeto | Listar projetos do usuário | Editar título e descrição | Excluir projeto |
| Tarefas | Criar tarefa | Listar tarefas por projeto | Editar tarefa e status | Excluir tarefa |
| Membros | Adicionar membro | Listar membros | Alterar papel | Remover membro |
| Checklist | Criar item | Listar itens | Marcar como concluído | Excluir item |
| Usuário | Cadastro | Buscar perfil | Atualizar dados/avatar | Não aplicado nesta versão |

## Banco de dados

O banco de dados usa MySQL e foi modelado para representar usuários, projetos, membros, tarefas, checklists e tokens de segurança.

Tabelas principais:

| Tabela | Responsabilidade |
|---|---|
| `usuarios` | Guarda dados de login, perfil, cargo, avatar e confirmação de email. |
| `projetos` | Guarda os projetos criados pelos usuários. |
| `projeto_membros` | Relaciona usuários aos projetos e define quem é admin ou membro. |
| `tarefas` | Guarda as tarefas de cada projeto, com prioridade, status e prazo. |
| `checklist_items` | Guarda itens menores dentro das tarefas. |
| `tokens_redefinicao` | Guarda tokens temporários para recuperação de senha. |
| `tokens_confirmacao_email` | Guarda tokens para confirmação de cadastro. |

O relacionamento mais importante é entre `projetos`, `projeto_membros` e `tarefas`. Ele permite que o sistema mostre apenas os projetos e tarefas que pertencem ao usuário logado.

## Autenticação e segurança

O projeto possui recursos básicos de segurança importantes para uma aplicação web:

- Senhas armazenadas com `password_hash`.
- Validação de senha com `password_verify`.
- Sessão PHP para manter o usuário logado.
- Proteção de páginas internas com `auth_guard`.
- Confirmação de email por token.
- Recuperação de senha por link temporário.
- Uso de `PDO` com prepared statements para reduzir risco de SQL Injection.
- Uso de `htmlspecialchars` em saídas importantes para reduzir risco de XSS.

## Organização visual e experiência do usuário

A interface foi construída para ser simples e direta. O objetivo visual é facilitar o uso por pessoas que precisam acompanhar projetos no dia a dia.

Alguns pontos importantes da experiência:

- Dashboard com indicadores rápidos.
- Cards de projetos com identificação de admin ou membro.
- Kanban para visualizar o andamento das tarefas.
- Modais para criar e editar informações sem sair da tela.
- Feedback por mensagens e toasts.
- Personalização de avatar para tornar o perfil mais reconhecível.

## Documentação complementar

Além deste README, o projeto possui materiais auxiliares dentro da pasta `docs/`, com explicações mais detalhadas para estudantes e integrantes do grupo.

Arquivos úteis:

| Arquivo | Conteúdo |
|---|---|
| `docs/guia-completo-do-projeto.md` | Explicação didática do projeto de ponta a ponta. |
| `docs/DATABASE.md` | Informações sobre banco de dados. |
| `docs/Frontend.md` | Informações sobre a estrutura visual/frontend. |
| `docs/implementacao.md` | Registro de fases e orientações de implementação. |

## Possíveis melhorias futuras

Como evolução do projeto, seria possível implementar:

- Painel administrativo.
- Notificações reais dentro do sistema.
- Envio de convites para membros por email.
- Controle mais avançado de permissões.
- Filtros por prazo, prioridade e responsável.
- Relatórios de produtividade por projeto.
- Testes automatizados no backend.
---

<p align="center">
  <strong>Feito com amor, café e muitas iterações.</strong> ❤️
</p>
