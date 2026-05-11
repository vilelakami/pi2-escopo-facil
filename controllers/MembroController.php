<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/ProjetoMembro.php';

class MembroController
{
    public function adicionar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=projetos&erro=metodo-invalido');
            exit;
        }

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $email     = trim($_POST['email'] ?? '');
        $usuarioId = usuarioLogado();

        if (!$projetoId || !$usuarioId) {
            header('Location: /index.php');
            exit;
        }

        if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
            header('Location: /?page=projetos&erro=nao-autorizado');
            exit;
        }

        if ($email === '') {
            header('Location: /?page=projetos&erro=email-vazio');
            exit;
        }

        // busca usuario pelo email
        $pdo  = getConnection();
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $alvo = $stmt->fetch();

        if (!$alvo) {
            header('Location: /?page=projetos&erro=usuario-nao-encontrado');
            exit;
        }

        if (ProjetoMembro::jaEMembro($projetoId, (int) $alvo['id'])) {
            header('Location: /?page=projetos&erro=ja-membro');
            exit;
        }

        ProjetoMembro::adicionar($projetoId, (int) $alvo['id']);
        header('Location: /?page=projetos');
        exit;
    }

    public function remover(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=projetos&erro=metodo-invalido');
            exit;
        }

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $membroId  = (int) ($_POST['membro_id'] ?? 0);
        $usuarioId = usuarioLogado();

        if (!$projetoId || !$membroId || !$usuarioId) {
            header('Location: /index.php');
            exit;
        }

        $isAdmin      = ProjetoMembro::isAdmin($projetoId, $usuarioId);
        $removendoASi = ($membroId === $usuarioId);

        // admin remove qualquer membro; membro comum só pode sair (remover a si mesmo)
        if (!$isAdmin && !$removendoASi) {
            header('Location: /?page=projetos&erro=nao-autorizado');
            exit;
        }

        ProjetoMembro::remover($projetoId, $membroId);
        header('Location: /?page=projetos');
        exit;
    }

    public function alterarRole(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=projetos&erro=metodo-invalido');
            exit;
        }

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $membroId  = (int) ($_POST['membro_id'] ?? 0);
        $novoRole  = $_POST['role'] ?? '';
        $usuarioId = usuarioLogado();

        if (!$projetoId || !$membroId || !$usuarioId) {
            header('Location: /index.php');
            exit;
        }

        if (!in_array($novoRole, ['admin', 'membro'], true)) {
            header('Location: /?page=projetos&erro=role-invalido');
            exit;
        }

        if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
            header('Location: /?page=projetos&erro=nao-autorizado');
            exit;
        }

        ProjetoMembro::alterarRole($projetoId, $membroId, $novoRole);
        header('Location: /?page=projetos');
        exit;
    }
}
