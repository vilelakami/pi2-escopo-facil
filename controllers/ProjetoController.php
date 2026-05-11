<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Projeto.php';
require_once __DIR__ . '/../models/ProjetoMembro.php';

class ProjetoController
{
    public function criar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=projetos&erro=metodo-invalido');
            exit;
        }

        $titulo    = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $usuarioId = usuarioLogado();

        if (!$usuarioId) {
            header('Location: /index.php');
            exit;
        }

        if ($titulo === '') {
            header('Location: /?page=projetos&erro=titulo-vazio');
            exit;
        }

        $projetoId = Projeto::criar($titulo, $descricao, $usuarioId);
        ProjetoMembro::adicionar($projetoId, $usuarioId, 'admin');
        header('Location: /?page=projetos');
        exit;
    }

    public function editar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=projetos&erro=metodo-invalido');
            exit;
        }

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $titulo    = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $usuarioId = usuarioLogado();

        if (!$projetoId || !$usuarioId) {
            header('Location: /index.php');
            exit;
        }

        if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
            header('Location: /?page=projetos&erro=nao-autorizado');
            exit;
        }

        if ($titulo === '') {
            header('Location: /?page=projetos&erro=titulo-vazio');
            exit;
        }

        Projeto::atualizar($projetoId, $titulo, $descricao);
        header('Location: /?page=projetos');
        exit;
    }

    public function deletar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=projetos&erro=metodo-invalido');
            exit;
        }

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $usuarioId = usuarioLogado();

        if (!$projetoId || !$usuarioId) {
            header('Location: /index.php');
            exit;
        }

        if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
            header('Location: /?page=projetos&erro=nao-autorizado');
            exit;
        }

        Projeto::deletar($projetoId);
        header('Location: /?page=projetos');
        exit;
    }
}
