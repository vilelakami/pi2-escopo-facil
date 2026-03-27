# Banco de Dados — Escopo Fácil

Documentação completa do banco de dados MySQL do projeto **Escopo Fácil**.

---

## Diagrama de Relacionamento (ER)

```
┌──────────────┐       ┌───────────────────┐       ┌──────────────┐
│   usuarios   │──1:N──│ projeto_membros    │──N:1──│   projetos   │
└──────────────┘       └───────────────────┘       └──────────────┘
       │                                                   │
       │                                                   │
       └────────────────────┐               ┌──────────────┘
                            │               │
                       ┌────┴───────────────┴────┐
                       │        tarefas          │
                       └─────────────────────────┘
                                   │
                            ┌──────┴──────┐
                            │ tokens_     │
                            │ redefinicao │
                            └─────────────┘
                            (ligado a usuarios)
```

---

## Tabelas

### 1. `usuarios`

Armazena os dados de todos os usuários/cadastros do sistema.

| Coluna         | Tipo              | Restrições                        | Descrição                          |
|----------------|-------------------|-----------------------------------|------------------------------------|
| `id`           | INT UNSIGNED      | PK, AUTO_INCREMENT                | Identificador único                |
| `nome`         | VARCHAR(150)      | NOT NULL                          | Nome completo                      |
| `email`        | VARCHAR(255)      | NOT NULL, UNIQUE                  | E-mail (usado no login)           |
| `senha`        | VARCHAR(255)      | NOT NULL                          | Senha com hash (bcrypt)           |
| `cargo`        | ENUM(...)         | NOT NULL                          | Cargo profissional                 |
| `avatar`       | VARCHAR(500)      | DEFAULT NULL                      | Caminho da imagem de perfil       |
| `criado_em`    | DATETIME          | DEFAULT CURRENT_TIMESTAMP         | Data de criação da conta          |
| `atualizado_em`| DATETIME          | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Última atualização |

**Valores do ENUM `cargo`:**
- `dev-frontend`
- `dev-backend`
- `dev-fullstack`
- `ui-ux-designer`
- `product-owner`
- `scrum-master`
- `tech-lead`
- `qa-testes`

---

### 2. `projetos`

Armazena os projetos criados no sistema.

| Coluna         | Tipo              | Restrições                        | Descrição                          |
|----------------|-------------------|-----------------------------------|------------------------------------|
| `id`           | INT UNSIGNED      | PK, AUTO_INCREMENT                | Identificador único                |
| `titulo`       | VARCHAR(200)      | NOT NULL                          | Nome do projeto                    |
| `descricao`    | TEXT              | DEFAULT NULL                      | Descrição/objetivos do projeto    |
| `criado_por`   | INT UNSIGNED      | NOT NULL, FK → usuarios.id       | Usuário que criou o projeto       |
| `criado_em`    | DATETIME          | DEFAULT CURRENT_TIMESTAMP         | Data de criação                    |
| `atualizado_em`| DATETIME          | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Última atualização |

---

### 3. `projeto_membros`

Tabela associativa que liga usuários a projetos com um papel específico (admin ou membro).

| Coluna         | Tipo              | Restrições                        | Descrição                          |
|----------------|-------------------|-----------------------------------|------------------------------------|
| `id`           | INT UNSIGNED      | PK, AUTO_INCREMENT                | Identificador único                |
| `projeto_id`   | INT UNSIGNED      | NOT NULL, FK → projetos.id       | Projeto associado                  |
| `usuario_id`   | INT UNSIGNED      | NOT NULL, FK → usuarios.id       | Usuário membro                     |
| `role`         | ENUM('admin','membro') | NOT NULL, DEFAULT 'membro'   | Papel do usuário no projeto       |
| `adicionado_em`| DATETIME          | DEFAULT CURRENT_TIMESTAMP         | Data em que foi adicionado        |

**Restrição única:** `(projeto_id, usuario_id)` — um usuário não pode estar duplicado no mesmo projeto.

---

### 4. `tarefas`

Armazena as tarefas (cards do Kanban) dentro de cada projeto.

| Coluna         | Tipo              | Restrições                        | Descrição                          |
|----------------|-------------------|-----------------------------------|------------------------------------|
| `id`           | INT UNSIGNED      | PK, AUTO_INCREMENT                | Identificador único                |
| `projeto_id`   | INT UNSIGNED      | NOT NULL, FK → projetos.id       | Projeto ao qual pertence          |
| `titulo`       | VARCHAR(200)      | NOT NULL                          | Título da tarefa                   |
| `descricao`    | TEXT              | DEFAULT NULL                      | Descrição detalhada                |
| `prioridade`   | TINYINT UNSIGNED  | NOT NULL, DEFAULT 1               | 1 = Baixa, 2 = Média, 3 = Alta   |
| `status`       | TINYINT UNSIGNED  | NOT NULL, DEFAULT 1               | 1 = A Fazer, 2 = Em Andamento, 3 = Concluído |
| `prazo`        | DATE              | DEFAULT NULL                      | Data limite                        |
| `criado_por`   | INT UNSIGNED      | DEFAULT NULL, FK → usuarios.id   | Quem criou a tarefa               |
| `criado_em`    | DATETIME          | DEFAULT CURRENT_TIMESTAMP         | Data de criação                    |
| `atualizado_em`| DATETIME          | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Última atualização |

---

### 5. `tokens_redefinicao`

Armazena tokens temporários para o fluxo de redefinição de senha.

| Coluna         | Tipo              | Restrições                        | Descrição                          |
|----------------|-------------------|-----------------------------------|------------------------------------|
| `id`           | INT UNSIGNED      | PK, AUTO_INCREMENT                | Identificador único                |
| `usuario_id`   | INT UNSIGNED      | NOT NULL, FK → usuarios.id       | Usuário que solicitou o reset     |
| `token`        | VARCHAR(255)      | NOT NULL, UNIQUE                  | Token aleatório seguro (hex/UUID) |
| `expira_em`    | DATETIME          | NOT NULL                          | Validade do token                  |
| `usado`        | TINYINT(1)        | NOT NULL, DEFAULT 0               | 0 = não usado, 1 = já utilizado   |
| `criado_em`    | DATETIME          | DEFAULT CURRENT_TIMESTAMP         | Data de criação                    |

---

## Relacionamentos

| Relação                              | Tipo   | Descrição                                                 |
|--------------------------------------|--------|-----------------------------------------------------------|
| `usuarios` → `projetos`             | 1:N    | Um usuário pode criar vários projetos                    |
| `usuarios` → `projeto_membros`      | 1:N    | Um usuário pode participar de vários projetos            |
| `projetos` → `projeto_membros`      | 1:N    | Um projeto pode ter vários membros                       |
| `projetos` → `tarefas`              | 1:N    | Um projeto pode ter várias tarefas                       |
| `usuarios` → `tarefas`              | 1:N    | Um usuário pode criar várias tarefas                     |
| `usuarios` → `tokens_redefinicao`   | 1:N    | Um usuário pode ter vários tokens de redefinição         |

---

## Valores de Referência

### Prioridades (`tarefas.prioridade`)

| Valor | Label   | Cor sugerida |
|-------|---------|-------------|
| 1     | Baixa   | Verde       |
| 2     | Média   | Amarelo     |
| 3     | Alta    | Vermelho    |

### Status (`tarefas.status`)

| Valor | Label         | Coluna Kanban   |
|-------|---------------|-----------------|
| 1     | A Fazer       | col-1           |
| 2     | Em Andamento  | col-2           |
| 3     | Concluído     | col-3           |

### Roles (`projeto_membros.role`)

| Valor    | Permissões                                                    |
|----------|---------------------------------------------------------------|
| `admin`  | Criar/editar/excluir projeto, gerenciar membros, CRUD tarefas |
| `membro` | Visualizar projeto, CRUD tarefas (próprias)                   |

---

## SQL de Criação

```sql
-- =============================================
-- Banco de Dados: Escopo Fácil
-- Motor: MySQL 8.0+
-- Charset: utf8mb4
-- =============================================

CREATE DATABASE IF NOT EXISTS escopo_facil
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE escopo_facil;

-- ---------------------------------------------
-- 1. Tabela de Usuários
-- ---------------------------------------------
CREATE TABLE usuarios (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(150) NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    senha         VARCHAR(255) NOT NULL,
    cargo         ENUM(
                    'dev-frontend',
                    'dev-backend',
                    'dev-fullstack',
                    'ui-ux-designer',
                    'product-owner',
                    'scrum-master',
                    'tech-lead',
                    'qa-testes'
                  ) NOT NULL,
    avatar        VARCHAR(500) DEFAULT NULL,
    criado_em     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- 2. Tabela de Projetos
-- ---------------------------------------------
CREATE TABLE projetos (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(200) NOT NULL,
    descricao     TEXT DEFAULT NULL,
    criado_por    INT UNSIGNED NOT NULL,
    criado_em     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_projetos_criador
        FOREIGN KEY (criado_por) REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- 3. Tabela Associativa: Membros dos Projetos
-- ---------------------------------------------
CREATE TABLE projeto_membros (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    projeto_id    INT UNSIGNED NOT NULL,
    usuario_id    INT UNSIGNED NOT NULL,
    role          ENUM('admin', 'membro') NOT NULL DEFAULT 'membro',
    adicionado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uk_projeto_usuario (projeto_id, usuario_id),

    CONSTRAINT fk_pm_projeto
        FOREIGN KEY (projeto_id) REFERENCES projetos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_pm_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- 4. Tabela de Tarefas (Kanban)
-- ---------------------------------------------
CREATE TABLE tarefas (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    projeto_id    INT UNSIGNED NOT NULL,
    titulo        VARCHAR(200) NOT NULL,
    descricao     TEXT DEFAULT NULL,
    prioridade    TINYINT UNSIGNED NOT NULL DEFAULT 1
                    COMMENT '1=Baixa, 2=Média, 3=Alta',
    status        TINYINT UNSIGNED NOT NULL DEFAULT 1
                    COMMENT '1=A Fazer, 2=Em Andamento, 3=Concluído',
    prazo         DATE DEFAULT NULL,
    criado_por    INT UNSIGNED DEFAULT NULL,
    criado_em     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_tarefas_projeto
        FOREIGN KEY (projeto_id) REFERENCES projetos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_tarefas_criador
        FOREIGN KEY (criado_por) REFERENCES usuarios(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- 5. Tabela de Tokens de Redefinição de Senha
-- ---------------------------------------------
CREATE TABLE tokens_redefinicao (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT UNSIGNED NOT NULL,
    token       VARCHAR(255) NOT NULL UNIQUE,
    expira_em   DATETIME NOT NULL,
    usado       TINYINT(1) NOT NULL DEFAULT 0,
    criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tokens_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- Índices adicionais para performance
-- ---------------------------------------------
CREATE INDEX idx_tarefas_projeto_status ON tarefas(projeto_id, status);
CREATE INDEX idx_tarefas_prazo          ON tarefas(prazo);
CREATE INDEX idx_tokens_expira          ON tokens_redefinicao(expira_em);
CREATE INDEX idx_tokens_token           ON tokens_redefinicao(token);
```

---

## Tutorial: Como montar o banco de dados no projeto

### Pré-requisitos

- **XAMPP** (ou WAMP/MAMP) instalado e rodando
- **MySQL 8.0+** (já vem com o XAMPP)
- **phpMyAdmin** (já vem com o XAMPP) ou acesso ao terminal MySQL

---

### Passo 1 — Iniciar o MySQL no XAMPP

1. Abra o **XAMPP Control Panel**
2. Clique em **Start** ao lado de **Apache**
3. Clique em **Start** ao lado de **MySQL**
4. Verifique que ambos estão com status **verde**

---

### Passo 2 — Criar o banco de dados

#### Opção A: Via phpMyAdmin (interface gráfica)

1. Abra o navegador e acesse: `http://localhost/phpmyadmin`
2. Clique na aba **SQL** no topo
3. Copie e cole todo o bloco SQL da seção [SQL de Criação](#sql-de-criação) acima
4. Clique em **Executar**
5. Pronto! As 5 tabelas foram criadas

#### Opção B: Via terminal MySQL

```bash
# Acesse o MySQL (senha padrão do XAMPP geralmente é vazia)
mysql -u root -p

# Cole e execute o SQL de criação completo
# ou importe o arquivo .sql se preferir:
mysql -u root -p < caminho/para/escopo_facil.sql
```

---

### Passo 3 — Configurar a conexão no projeto

Edite o arquivo `config.php` na raiz do projeto e adicione a conexão com o banco:

```php
<?php
// Auto-detect base path for XAMPP/subdirectory compatibility
$docRoot  = realpath($_SERVER['DOCUMENT_ROOT']);
$projRoot = realpath(__DIR__);
$basePath = str_replace('\\', '/', str_replace($docRoot, '', $projRoot));
define('BASE_URL', rtrim($basePath, '/'));

// ── Conexão com o Banco de Dados ──
define('DB_HOST', 'localhost');
define('DB_NAME', 'escopo_facil');
define('DB_USER', 'root');
define('DB_PASS', '');       // senha padrão do XAMPP
define('DB_CHARSET', 'utf8mb4');

function getConnection(): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    return new PDO($dsn, DB_USER, DB_PASS, $options);
}
```

---

### Passo 4 — Testar a conexão

Crie um arquivo temporário `teste-db.php` na raiz do projeto:

```php
<?php
require_once __DIR__ . '/config.php';

try {
    $pdo = getConnection();
    echo "✅ Conexão com o banco 'escopo_facil' funcionando!";
} catch (PDOException $e) {
    echo "❌ Erro na conexão: " . $e->getMessage();
}
```

Acesse no navegador: `http://localhost/escopo-facil/teste-db.php`

> **Importante:** Delete o arquivo `teste-db.php` depois de confirmar que está funcionando.

---

### Passo 5 — Inserir dados de teste (opcional)

```sql
USE escopo_facil;

-- Usuário de teste
INSERT INTO usuarios (nome, email, senha, cargo) VALUES
('Natan Oliveira', 'natan@email.com', '$2y$10$examplehashhere', 'product-owner'),
('Kami', 'kami@email.com', '$2y$10$examplehashhere', 'dev-frontend');

-- Projeto de teste
INSERT INTO projetos (titulo, descricao, criado_por) VALUES
('Escopo Fácil', 'Projeto principal do PI com foco em gestão de tarefas e colaboração.', 1);

-- Adicionar membros ao projeto
INSERT INTO projeto_membros (projeto_id, usuario_id, role) VALUES
(1, 1, 'admin'),
(1, 2, 'membro');

-- Tarefas de teste
INSERT INTO tarefas (projeto_id, titulo, descricao, prioridade, status, prazo, criado_por) VALUES
(1, 'Criar tela de Login', 'Montar a estrutura inicial da tela de acesso com validação visual dos campos', 3, 3, '2025-07-10', 1),
(1, 'Montar sidebar', 'Criar componente de navegação lateral responsiva', 2, 2, '2025-07-15', 1),
(1, 'Adicionar foto', 'Implementar upload e exibição de foto de perfil do usuário', 1, 1, '2026-02-19', 2);
```

> **Nota:** As senhas devem ser geradas com `password_hash('sua_senha', PASSWORD_BCRYPT)` em PHP. Os hashes acima são apenas exemplos — gere os reais no código.

---

### Resumo da estrutura

```
escopo_facil (database)
├── usuarios            → Cadastro e autenticação
├── projetos            → Projetos criados
├── projeto_membros     → Associação usuário ↔ projeto (com role)
├── tarefas             → Cards do Kanban
└── tokens_redefinicao  → Redefinição de senha
```
