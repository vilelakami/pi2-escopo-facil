# Guia de Implementação — Escopo Fácil

> Este documento descreve o que falta implementar no projeto após a Fase 1 (Auth) ter sido concluída.
> Cada fase é independente e pode ser feita por pessoas diferentes.

---

## Contexto geral

O projeto usa o padrão: **Model → Controller → Action (POST) → redirect**.

Referências de padrão já implementadas:
- Model: `models/Projeto.php`
- Controller: `controllers/ProjetoController.php`
- Actions: `actions/projetos/criar.php`, `editar.php`, `deletar.php`

Utilitários disponíveis:
| Utilitário | Arquivo |
|---|---|
| Conexão com banco | `config.php` → `getConnection(): PDO` |
| ID do usuário logado | `includes/session.php` → `usuarioLogado()` |
| Guard de autenticação | `includes/auth_guard.php` — incluir no topo de toda página protegida |
| Variáveis de ambiente | `.env` (DB_HOST, DB_NAME, DB_USER, DB_PASS) |

Schema do banco: `database.sql`

---

## Fase 2 — Tarefas (CRUD completo)

> **Responsável:** outra pessoa  
> A UI do kanban já existe em `pages/tarefas/index.php` e `assets/js/pages/tarefas.js`.  
> O JS é 100% client-side hoje — nenhum dado é salvo no banco.

### Schema relevante (`database.sql`)

```sql
tarefas (
  id, projeto_id, titulo, descricao,
  prioridade ENUM(1=Baixa, 2=Média, 3=Alta),
  status     ENUM(1=A Fazer, 2=Em Andamento, 3=Concluído),
  prazo, criado_por, criado_em, atualizado_em
)
```

### Arquivos a criar

#### `models/Tarefa.php`

Seguir o mesmo padrão de `models/Projeto.php`.

Métodos necessários:
```php
Tarefa::listarPorProjeto(int $projetoId): array
Tarefa::buscarPorId(int $id): array|false
Tarefa::criar(int $projetoId, string $titulo, string $descricao, int $prioridade, int $status, string|null $prazo, int $criadoPor): int
Tarefa::atualizar(int $id, string $titulo, string $descricao, int $prioridade, int $status, string|null $prazo): void
Tarefa::deletar(int $id): void
```

#### `controllers/TarefaController.php`

Seguir o mesmo padrão de `controllers/ProjetoController.php`.

Métodos necessários:
- `criar()` — POST: valida campos, chama `Tarefa::criar()`, redireciona
- `editar()` — POST: valida, chama `Tarefa::atualizar()`, redireciona
- `deletar()` — POST: chama `Tarefa::deletar()`, redireciona

#### `actions/tarefas/criar.php`

```php
<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/TarefaController.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /?page=login'); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /?page=tarefas'); exit;
}
(new TarefaController())->criar();
```

Criar o mesmo esqueleto para `actions/tarefas/editar.php` e `actions/tarefas/deletar.php`.

### Arquivos a modificar

#### `pages/tarefas/index.php`

- Adicionar `require_once __DIR__ . '/../../includes/auth_guard.php';` no topo
- Aceitar `?projeto_id=` como parâmetro GET
- Carregar tarefas via `Tarefa::listarPorProjeto($projetoId)`
- Injetar dados no JS: `window.tarefasData = <?= json_encode($tarefas) ?>;`

#### `assets/js/pages/tarefas.js`

Substituir as operações locais por chamadas `fetch`:

```js
// Criar tarefa
fetch(BASE_URL + '/actions/tarefas/criar.php', {
  method: 'POST',
  body: new FormData(form)
})

// Editar tarefa
fetch(BASE_URL + '/actions/tarefas/editar.php', { method: 'POST', body: ... })

// Deletar tarefa
fetch(BASE_URL + '/actions/tarefas/deletar.php', { method: 'POST', body: ... })
```

#### `pages/projetos/index.php`

Links para tarefas devem passar o projeto:
```html
<a href="<?= BASE_URL ?>/index.php?page=tarefas&projeto_id=<?= $projeto['id'] ?>">Ver tarefas</a>
```

---

## Fase 3 — Configuração (perfil + troca de senha)

> A página `pages/configuracao.php` existe mas os formulários têm `action="#"` — não salvam nada.

### Arquivos a criar

#### `actions/usuario/atualizar.php`

- POST: `nome`, `cargo`, `email`
- Validar: campos obrigatórios, `filter_var($email, FILTER_VALIDATE_EMAIL)`
- Chamar `Usuario::atualizar($id, $nome, $email, $cargo)`
- Redirecionar para `?page=configuracao&sucesso=1`

#### `actions/usuario/alterar-senha.php`

- POST: `senha_atual`, `nova_senha`, `confirmar_nova_senha`
- Buscar usuário por `usuarioLogado()` → `Usuario::buscarPorId()`
- `password_verify($senhaAtual, $usuario['senha'])`
- `password_hash($novaSenha, PASSWORD_BCRYPT)`
- `Usuario::alterarSenha($id, $hash)`
- Redirecionar para `?page=configuracao&sucesso=1`

### Arquivos a modificar

#### `pages/configuracao.php`

- Adicionar `require_once auth_guard.php` no topo
- Carregar usuário logado: `Usuario::buscarPorId(usuarioLogado())`
- Substituir dados hardcoded pelos dados reais do banco
- Corrigir actions dos formulários:
  - Dados pessoais → `action="<?= BASE_URL ?>/actions/usuario/atualizar.php"`
  - Troca de senha → `action="<?= BASE_URL ?>/actions/usuario/alterar-senha.php"`
- Exibir feedback via `$_GET['sucesso']` ou `$_GET['erro']`

### Dependência

Esta fase depende do `models/Usuario.php` que deve ser criado como parte da Fase 1 (Auth).  
Métodos necessários: `buscarPorId()`, `atualizar()`, `alterarSenha()`.

---

## Fase 4 — Recuperação de senha (token, sem email)

> As páginas `esqueci-senha.php` e `redefinir-senha.php` já existem com UI pronta mas `action="#"`.  
> O schema `tokens_redefinicao` já está no banco.

### Schema relevante (`database.sql`)

```sql
tokens_redefinicao (
  id, usuario_id, token VARCHAR(64),
  expira_em DATETIME, usado TINYINT(1), criado_em
)
```

### Arquivos a criar

#### `models/TokenRedefinicao.php`

```php
TokenRedefinicao::criar(int $usuarioId): string        // gera token aleatório, insere no banco, retorna o token
TokenRedefinicao::validar(string $token): array|false  // retorna row se token existe, não expirou e não foi usado
TokenRedefinicao::marcarUsado(int $id): void
```

Gerar token com: `bin2hex(random_bytes(32))`  
Expiração: 1 hora — `date('Y-m-d H:i:s', strtotime('+1 hour'))`

#### `actions/auth/esqueci-senha.php`

- POST: `email`
- Checar `Usuario::buscarPorEmail($email)`
- Se existir: `TokenRedefinicao::criar($usuario['id'])` → retorna token
- **Sem email por ora:** exibir URL do reset direto na tela para teste
  - URL: `BASE_URL . '/index.php?page=redefinir-senha&token=' . $token`
- Se email não existir: mesma resposta genérica (não revelar se email existe)

#### `actions/auth/redefinir-senha.php`

- POST: `token` (hidden field), `nova_senha`, `confirmar_nova_senha`
- `TokenRedefinicao::validar($token)` — se inválido → redirect com erro
- Validar senhas coincidem e têm >= 8 chars
- `Usuario::alterarSenha($usuario_id, password_hash(...))`
- `TokenRedefinicao::marcarUsado($tokenRow['id'])`
- Redirecionar para `?page=confirmacao`

### Arquivos a modificar

#### `pages/auth/esqueci-senha.php`

- Corrigir `action` para `<?= BASE_URL ?>/actions/auth/esqueci-senha.php`
- Exibir URL do token após submit (bloco PHP com `$_GET['token_url']`)
- Exibir erro via `$_GET['erro']`

#### `pages/auth/redefinir-senha.php`

- Corrigir `action` para `<?= BASE_URL ?>/actions/auth/redefinir-senha.php`
- Ler token da URL: `$token = $_GET['token'] ?? ''`
- Adicionar `<input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">`
- Exibir erro via `$_GET['erro']`

---

## Verificação por fase

| Fase | Como testar |
|---|---|
| 2 — Tarefas | Abrir projeto → clicar em tarefas → criar tarefa → recarregar página → tarefa persiste no banco |
| 3 — Configuração | Editar nome/cargo → recarregar → dados atualizados; trocar senha → logout → login com nova senha funciona |
| 4 — Reset senha | Submeter email → copiar URL exibida → acessar URL → definir nova senha → login com nova senha funciona |

---

## Migracoes e configuracoes adicionadas

### Confirmacao de email no cadastro

O cadastro agora cria usuarios com email pendente de confirmacao.

Campos adicionados na tabela `usuarios`:
```sql
email_verificado TINYINT(1) NOT NULL DEFAULT 0
email_verificado_em DATETIME DEFAULT NULL
```

Tabela adicionada:
```sql
tokens_confirmacao_email (
  id, usuario_id, token,
  expira_em, usado, criado_em
)
```

Quem ja possui o banco criado localmente deve rodar:
```bash
php scripts/migrar-confirmacao-email.php
```

Esse script:
- adiciona os campos de confirmacao na tabela `usuarios`, se ainda nao existirem;
- marca usuarios existentes como confirmados;
- cria a tabela `tokens_confirmacao_email`, se ainda nao existir;
- cria o indice `idx_tokens_email_expira`, se ainda nao existir.

### Variaveis de email

Adicionar no `.env`:
```env
MAIL_FROM=no-reply@escopofacil.local
MAIL_FROM_NAME=Escopo Facil
MAIL_DEBUG_TOKEN_URL=true
MAIL_DEBUG_CONFIRMATION_URL=true
```

Em ambiente local/XAMPP, o PHP normalmente nao envia email sem SMTP configurado. Por isso, as variaveis `MAIL_DEBUG_*` ficam como `true` para exibir as URLs de teste na tela.

Em producao, depois de configurar envio real de email, usar:
```env
MAIL_DEBUG_TOKEN_URL=false
MAIL_DEBUG_CONFIRMATION_URL=false
```

### Fluxos de email

Confirmacao de cadastro:
1. Usuario cria conta.
2. Sistema gera token em `tokens_confirmacao_email`.
3. Sistema envia link de confirmacao por email.
4. Ao clicar no link, o usuario e redirecionado para o login com a mensagem:
   `Confirmacao realizada com sucesso! Faca o login para continuar.`

Recuperacao de senha:
1. Usuario informa email.
2. Sistema gera token em `tokens_redefinicao`.
3. Sistema envia link de redefinicao por email.
4. Ao redefinir a senha, o token e marcado como usado.
