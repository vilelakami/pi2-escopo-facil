<?php
// RESPONSABILIDADE: recebe dados do $_POST, valida, decide o que fazer, chama o Model, redireciona
// Não acessa banco diretamente. Não renderiza HTML.
//
// Deve fazer require dos models que precisa: Projeto.php e ProjetoMembro.php

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Projeto.php';
require_once __DIR__ . '/../models/ProjetoMembro.php';
//
// Métodos que devem existir aqui:

class ProjetoController {
        public function criar (): void {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /?page=projetos&erro=metodo-invalido');
                exit;
            }

            // validação básica
            $titulo = trim($_POST['titulo'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $user  = usuarioLogado();
            
            // pega o id do usuário logado
            if (!$usuarioId) {
                header('Location: /index.php');
                exit;
            }

            // valida título
            if ($titulo === '') {
                header('Location: /?page=projetos&erro=titulo-vazio');
                exit;
            }

            // chama Projeto::criar() e ProjetoMembro::adicionar()
            $projetoId = Projeto::criar($titulo, $descricao, $usuarioId);
            // vincula o criador como admin do projeto
            ProjetoMembro::adicionar($projetoId, $usuarioId, 'admin');
            header('Location: /?page=projetos');
            exit;
        }

        public function editar (): void {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /?page=projetos&erro=metodo-invalido');
                exit;
            }

            // validação básica
            $projetoId = $_POST['projeto_id'] ?? null;
            $titulo = trim($_POST['titulo'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $usuarioId = usuarioLogado();

            // verifica se o usuário está logado
            if (!$projetoId || !$usuarioId) {
                header('Location: /index.php');
                exit;
            }

            // verifica se o usuário é admin do projeto
            if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
                header('Location: /?page=projetos&erro=nao-autorizado');
                exit;
            }

            // valida título
            if ($titulo === '') {
                header('Location: /?page=projetos&erro=titulo-vazio');
                exit;
            }

            // chama Projeto::atualizar()
            Projeto::atualizar($projetoId, $titulo, $descricao);
            header('Location: /?page=projetos');
            exit;
        }

        public function deletar (): void {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /?page=projetos&erro=metodo-invalido');
                exit;
            }

            $projetoId = $_POST['projeto_id'] ?? null;
            $usuarioId = usuarioLogado();

            // verifica se o usuário está logado
            if (!$projetoId || !$usuarioId) {
                header('Location: /index.php');
                exit;
            }

            // verifica se o usuário é admin do projeto
            if (!ProjetoMembro::isAdmin($projetoId, $usuarioId)) {
                header('Location: /?page=projetos&erro=nao-autorizado');
                exit;
            }

            // chama Projeto::deletar()
            Projeto::deletar($projetoId);
            header('Location: /?page=projetos');
            exit;
        }
}
