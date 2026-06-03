CREATE TABLE checklist_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    tarefa_id   INT UNSIGNED NOT NULL,
    texto       VARCHAR(500) NOT NULL,
    concluido   TINYINT(1) NOT NULL DEFAULT 0,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_checklist_tarefa FOREIGN KEY (tarefa_id)
        REFERENCES tarefas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_checklist_tarefa ON checklist_items(tarefa_id);
