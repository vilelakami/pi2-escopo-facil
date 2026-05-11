<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Projeto.php';
require_once __DIR__ . '/../../models/ProjetoMembro.php';

$usuarioId = usuarioLogado();

// Dados do usuário logado
$pdo = getConnection();
$stmtUser = $pdo->prepare("SELECT nome, cargo, avatar FROM usuarios WHERE id = :id");
$stmtUser->execute(['id' => $usuarioId]);
$dadosUsuario = $stmtUser->fetch();

$usuario = [
    'nome'   => $dadosUsuario['nome'] ?? 'Usuário',
    'role'   => $dadosUsuario['cargo'] ?? '',
    'avatar' => $dadosUsuario['avatar'] ?? BASE_URL . '/assets/images/Avatar (1).png',
];

// Fallback de avatar
if (empty($usuario['avatar'])) {
    $usuario['avatar'] = BASE_URL . '/assets/images/Avatar (1).png';
}

// Projetos do usuário logado
$projetosRaw = Projeto::listarPorUsuario($usuarioId);
$projetos = [];

foreach ($projetosRaw as $p) {
    $membros = ProjetoMembro::listar((int) $p['id']);
    $membrosFormatados = [];

    foreach ($membros as $m) {
        $avatar = $m['avatar'];
        if (empty($avatar)) {
            $avatar = BASE_URL . '/assets/images/Avatar (1).png';
        }
        $membrosFormatados[] = [
            'id'     => $m['usuario_id'],
            'nome'   => $m['nome'],
            'email'  => $m['email'],
            'cargo'  => $m['cargo'],
            'role'   => $m['role'],
            'avatar' => $avatar,
        ];
    }

    $totalMembros = (int) $p['total_membros'];
    $membrosVisiveis = 4;
    $membrosExtra = max(0, $totalMembros - $membrosVisiveis);

    $projetos[] = [
        'id'            => $p['id'],
        'titulo'        => $p['titulo'],
        'descricao'     => $p['descricao'] ?? '',
        'role'          => $p['role'],
        'membros_extra' => $membrosExtra,
        'criado_por'    => $p['criado_por_nome'],
        'data_criacao'  => date('d/m/Y', strtotime($p['criado_em'])),
        'membros'       => $membrosFormatados,
    ];
}
