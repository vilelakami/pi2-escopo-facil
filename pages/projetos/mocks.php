<?php
// Mock de dados — substituir por dados do banco futuramente

$usuario = [
    'nome' => 'Natan Oliveira',
    'role' => 'Membro',
    'avatar' => BASE_URL . '/assets/images/Avatar (1).png',
];

$projetos = [
    [
        'titulo' => 'Escopo fácil',
        'descricao' => 'Projeto principal do PI com foco em gestão de tarefas e colaboração.',
        'role' => 'admin',
        'membros_extra' => 0,
        'criado_por' => 'Natan Oliveira',
        'data_criacao' => '12/06/2025',
        'membros' => [
            [
                'nome' => 'Natan Oliveira',
                'email' => 'natan@email.com',
                'cargo' => 'Product Owner',
                'role' => 'admin',
                'avatar' => BASE_URL . '/assets/images/natan.png',
            ],
            [
                'nome' => 'Kami Vilela',
                'email' => 'kami@email.com',
                'cargo' => 'Desenvolvedor Frontend',
                'role' => 'membro',
                'avatar' => BASE_URL . '/assets/images/kami.png',
            ],
            [
                'nome' => 'Guilherme Santos',
                'email' => 'guilherme@email.com',
                'cargo' => 'Desenvolvedor Backend',
                'role' => 'membro',
                'avatar' => BASE_URL . '/assets/images/guilherme.png',
            ],
            [
                'nome' => 'Nicolas Ferreira',
                'email' => 'nicolas@email.com',
                'cargo' => 'Designer UI/UX',
                'role' => 'membro',
                'avatar' => BASE_URL . '/assets/images/nicolas.png',
            ],
        ],
    ],
    [
        'titulo' => 'Escopo fácil',
        'descricao' => 'Montar a estrutura inicial da tela de acesso com validação visual dos campos',
        'role' => 'membro',
        'membros_extra' => 4,
        'criado_por' => 'Kami Vilela',
        'data_criacao' => '10/06/2025',
        'membros' => [],
    ],
    [
        'titulo' => 'App Financeiro',
        'descricao' => 'Aplicativo de controle financeiro pessoal com relatórios mensais.',
        'role' => 'membro',
        'membros_extra' => 2,
        'criado_por' => 'Guilherme Santos',
        'data_criacao' => '05/06/2025',
        'membros' => [],
    ],
    [
        'titulo' => 'Landing Page',
        'descricao' => 'Criação da landing page institucional com foco em conversão.',
        'role' => 'membro',
        'membros_extra' => 5,
        'criado_por' => 'Nicolas Ferreira',
        'data_criacao' => '01/06/2025',
        'membros' => [],
    ],
    [
        'titulo' => 'Dashboard Admin',
        'descricao' => 'Painel administrativo para gerenciamento de usuários e métricas.',
        'role' => 'membro',
        'membros_extra' => 3,
        'criado_por' => 'Natan Oliveira',
        'data_criacao' => '28/05/2025',
        'membros' => [],
    ],
    [
        'titulo' => 'E-commerce',
        'descricao' => 'Plataforma de vendas online com carrinho e integração de pagamento.',
        'role' => 'membro',
        'membros_extra' => 7,
        'criado_por' => 'Kami Vilela',
        'data_criacao' => '20/05/2025',
        'membros' => [],
    ],
];
