CREATE DATABASE IF NOT EXISTS escopo_facil
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE escopo_facil;

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

CREATE TABLE tarefas (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    projeto_id    INT UNSIGNED NOT NULL,
    titulo        VARCHAR(200) NOT NULL,
    descricao     TEXT DEFAULT NULL,
    prioridade    TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=Baixa, 2=Media, 3=Alta',
    status        TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=A Fazer, 2=Em Andamento, 3=Concluido',
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

CREATE INDEX idx_tarefas_projeto_status ON tarefas(projeto_id, status);
CREATE INDEX idx_tarefas_prazo          ON tarefas(prazo);
CREATE INDEX idx_tokens_expira          ON tokens_redefinicao(expira_em);
