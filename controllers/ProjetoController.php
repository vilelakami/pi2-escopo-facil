<?php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Projeto.php';
require_once __DIR__ . '/../models/ProjetoMembro.php';

class ProjetoController
{
    public function criar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionar('/?page=projetos&erro=metodo-invalido');
        }

        $titulo    = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $usuarioId = usuarioLogado();

        if (!$usuarioId) {
            $this->redirecionar('/?page=login');
        }

        if ($titulo === '') {
            $this->redirecionar('/?page=projetos&erro=titulo-vazio');
        }

        $projetoId = Projeto::criar($titulo, $descricao, $usuarioId);
        ProjetoMembro::adicionar($projetoId, $usuarioId, 'admin');

        $this->redirecionar('/?page=projetos');
    }

    public function editar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionar('/?page=projetos&erro=metodo-invalido');
        }

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $titulo    = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $usuarioId = usuarioLogado();

        if (!$projetoId || !$usuarioId) {
            $this->redirecionar('/?page=login');
        }

        if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
            $this->redirecionar('/?page=projetos&erro=nao-autorizado');
        }

        if ($titulo === '') {
            $this->redirecionar('/?page=projetos&erro=titulo-vazio');
        }

        Projeto::atualizar($projetoId, $titulo, $descricao);

        $this->redirecionar('/?page=projetos');
    }

    public function deletar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionar('/?page=projetos&erro=metodo-invalido');
        }

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $usuarioId = usuarioLogado();

        if (!$projetoId || !$usuarioId) {
            $this->redirecionar('/?page=login');
        }

        if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
            $this->redirecionar('/?page=projetos&erro=nao-autorizado');
        }

        Projeto::deletar($projetoId);

        $this->redirecionar('/?page=projetos');
    }

    private function redirecionar(string $path): void
    {
        $baseUrl = defined('BASE_URL') ? BASE_URL : '';
        header('Location: ' . $baseUrl . $path);
        exit;
    }
}
