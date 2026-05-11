-- =============================================
-- Seed: Popular banco com dados de teste
-- Senha padrão para todos: 123456
-- =============================================

USE escopo_facil;

-- Limpa dados existentes (ordem respeita FKs)
DELETE FROM tokens_redefinicao;
DELETE FROM tarefas;
DELETE FROM projeto_membros;
DELETE FROM projetos;
DELETE FROM usuarios;

-- Reset auto_increment
ALTER TABLE usuarios AUTO_INCREMENT = 1;
ALTER TABLE projetos AUTO_INCREMENT = 1;
ALTER TABLE projeto_membros AUTO_INCREMENT = 1;
ALTER TABLE tarefas AUTO_INCREMENT = 1;

-- ---------------------------------------------
-- 20 Usuários (senha: 123456)
-- ---------------------------------------------
INSERT INTO usuarios (nome, email, senha, cargo) VALUES
('Natan Oliveira',     'natan@email.com',     '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'product-owner'),
('Kami Vilela',        'kami@email.com',       '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-frontend'),
('Guilherme Santos',   'guilherme@email.com',  '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-backend'),
('Nicolas Ferreira',   'nicolas@email.com',    '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-fullstack'),
('Ana Silva',          'ana@email.com',        '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'ui-ux-designer'),
('Bruno Costa',        'bruno@email.com',      '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'scrum-master'),
('Carla Mendes',       'carla@email.com',      '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'tech-lead'),
('Diego Almeida',      'diego@email.com',      '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'qa-testes'),
('Elisa Rocha',        'elisa@email.com',      '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-frontend'),
('Felipe Martins',     'felipe@email.com',     '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-backend'),
('Gabriela Lima',      'gabriela@email.com',   '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-fullstack'),
('Henrique Barros',    'henrique@email.com',   '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'product-owner'),
('Isabela Duarte',     'isabela@email.com',    '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'ui-ux-designer'),
('João Pedro Ramos',   'joao@email.com',       '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'scrum-master'),
('Larissa Nunes',      'larissa@email.com',    '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-frontend'),
('Marcos Teixeira',    'marcos@email.com',     '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-backend'),
('Natália Souza',      'natalia@email.com',    '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'tech-lead'),
('Otávio Pereira',     'otavio@email.com',     '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'qa-testes'),
('Patrícia Gomes',     'patricia@email.com',   '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-fullstack'),
('Rafael Cardoso',     'rafael@email.com',     '$2y$12$n4cyvxoci7onXEUOsQVwiOUmEf./o4gjIiMlXg1o9kISttCa5EeCG', 'dev-frontend');

-- ---------------------------------------------
-- 4 Projetos
-- ---------------------------------------------
INSERT INTO projetos (titulo, descricao, criado_por) VALUES
('Escopo Fácil',         'Plataforma de gestão de projetos com Kanban para equipes ágeis.', 1),
('App de Finanças',      'Aplicativo mobile para controle de gastos pessoais.',             4),
('Portal do Aluno',      'Sistema web para acompanhamento acadêmico.',                     7),
('E-commerce Sustentável','Loja virtual focada em produtos sustentáveis.',                  12);

-- ---------------------------------------------
-- Membros dos projetos
-- ---------------------------------------------
-- Projeto 1: Escopo Fácil (criador = Natan, id 1)
INSERT INTO projeto_membros (projeto_id, usuario_id, role) VALUES
(1, 1,  'admin'),
(1, 2,  'membro'),
(1, 3,  'membro'),
(1, 4,  'membro'),
(1, 5,  'membro'),
(1, 6,  'membro');

-- Projeto 2: App de Finanças (criador = Nicolas, id 4)
INSERT INTO projeto_membros (projeto_id, usuario_id, role) VALUES
(2, 4,  'admin'),
(2, 9,  'membro'),
(2, 10, 'membro'),
(2, 11, 'membro'),
(2, 15, 'membro');

-- Projeto 3: Portal do Aluno (criador = Carla, id 7)
INSERT INTO projeto_membros (projeto_id, usuario_id, role) VALUES
(3, 7,  'admin'),
(3, 8,  'membro'),
(3, 13, 'membro'),
(3, 14, 'membro'),
(3, 16, 'membro'),
(3, 17, 'membro');

-- Projeto 4: E-commerce Sustentável (criador = Henrique, id 12)
INSERT INTO projeto_membros (projeto_id, usuario_id, role) VALUES
(4, 12, 'admin'),
(4, 18, 'membro'),
(4, 19, 'membro'),
(4, 20, 'membro'),
(4, 1,  'membro');

-- ---------------------------------------------
-- Tarefas de exemplo (Projeto 1: Escopo Fácil)
-- ---------------------------------------------
INSERT INTO tarefas (projeto_id, titulo, descricao, prioridade, status, prazo, criado_por) VALUES
(1, 'Criar tela de login',           'Implementar formulário de login com validação.',         3, 3, '2026-04-10', 1),
(1, 'Montar sidebar',                'Componente de navegação lateral responsivo.',             2, 3, '2026-04-12', 2),
(1, 'CRUD de projetos',              'Backend completo para criar, editar e deletar projetos.', 3, 2, '2026-04-20', 3),
(1, 'Kanban de tarefas',             'Implementar drag-and-drop nas colunas do Kanban.',        3, 2, '2026-04-25', 4),
(1, 'Tela de configurações',         'Página para editar perfil do usuário.',                   1, 1, '2026-05-01', 5),
(1, 'Sistema de notificações',       'Alertas quando tarefa for atribuída ou prazo vencer.',    2, 1, '2026-05-10', 1);

-- Tarefas (Projeto 2)
INSERT INTO tarefas (projeto_id, titulo, descricao, prioridade, status, prazo, criado_por) VALUES
(2, 'Modelagem do banco',            'Definir tabelas de transações e categorias.',             3, 3, '2026-04-08', 4),
(2, 'Tela de dashboard',             'Gráficos de gastos por categoria.',                       2, 2, '2026-04-22', 9),
(2, 'Importar extrato bancário',     'Parse de CSV/OFX para cadastro automático.',              2, 1, '2026-05-05', 10);

-- Tarefas (Projeto 3)
INSERT INTO tarefas (projeto_id, titulo, descricao, prioridade, status, prazo, criado_por) VALUES
(3, 'Autenticação com RA',           'Login via registro acadêmico + senha.',                   3, 2, '2026-04-18', 7),
(3, 'Painel de notas',               'Exibir notas por disciplina e semestre.',                 2, 1, '2026-04-30', 13),
(3, 'Calendário acadêmico',          'Integrar datas de provas e entregas.',                    1, 1, '2026-05-15', 14);
