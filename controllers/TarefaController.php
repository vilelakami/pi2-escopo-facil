    <?php

    require_once __DIR__ . '/../includes/session.php';
    require_once __DIR__ . '/../models/Tarefa.php';
    require_once __DIR__ . '/../models/ProjetoMembro.php';

    class TarefaController
    {
        private const PRIORIDADES_VALIDAS = [1, 2, 3];
        private const STATUS_VALIDOS = [1, 2, 3];

        public function criar(): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirecionar(0, 'metodo-invalido');
            }

            // A tela ainda usa alguns nomes antigos em ingles; por isso aceitamos os dois formatos.
            $projetoId = (int) ($_POST['projeto_id'] ?? 0);
            $titulo = trim($_POST['titulo'] ?? ($_POST['taskTitle'] ?? ''));
            $descricao = trim($_POST['descricao'] ?? ($_POST['taskDescription'] ?? ''));
            $prioridade = (int) ($_POST['prioridade'] ?? ($_POST['taskPriority'] ?? 1));
            $status = (int) ($_POST['status'] ?? ($_POST['taskStatus'] ?? 1));
            $prazo = $this->normalizarPrazo($_POST['prazo'] ?? ($_POST['taskDate'] ?? null));
            $usuarioId = usuarioLogado();

            $this->validarBase($projetoId, (int) $usuarioId, $titulo, $prioridade, $status);

            Tarefa::criar($projetoId, $titulo, $descricao, $prioridade, $status, $prazo, (int) $usuarioId);
            $this->redirecionar($projetoId);
        }

        public function editar(): void
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirecionar(0, 'metodo-invalido');
            }

            // A edicao tambem aceita o formato usado pelo JavaScript do kanban.
            $tarefaId = (int) ($_POST['tarefa_id'] ?? ($_POST['id'] ?? 0));
            $titulo = trim($_POST['titulo'] ?? ($_POST['taskTitle'] ?? ''));
            $descricao = trim($_POST['descricao'] ?? ($_POST['taskDescription'] ?? ''));
            $prioridade = (int) ($_POST['prioridade'] ?? ($_POST['taskPriority'] ?? 1));
            $status = (int) ($_POST['status'] ?? ($_POST['taskStatus'] ?? 1));
            $prazo = $this->normalizarPrazo($_POST['prazo'] ?? ($_POST['taskDate'] ?? null));
            $usuarioId = usuarioLogado();

            if (!$tarefaId || !$usuarioId) {
                header('Location: /index.php');
                exit;
            }

            $tarefa = Tarefa::buscarPorId($tarefaId);
            if (!$tarefa) {
                $this->redirecionar(0, 'tarefa-nao-encontrada');
            }

            $projetoId = (int) $tarefa['projeto_id'];
            $this->validarBase($projetoId, (int) $usuarioId, $titulo, $prioridade, $status);

            Tarefa::atualizar($tarefaId, $titulo, $descricao, $prioridade, $status, $prazo);
            $this->redirecionar($projetoId);
        }

        public function deletar(): void
        {  
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirecionar(0, 'metodo-invalido');
            }

            $tarefaId = (int) ($_POST['tarefa_id'] ?? ($_POST['id'] ?? 0));
            $usuarioId = usuarioLogado();

            if (!$tarefaId || !$usuarioId) {
                header('Location: /index.php');
                exit;
            }

            $tarefa = Tarefa::buscarPorId($tarefaId);
            if (!$tarefa) {
                $this->redirecionar(0, 'tarefa-nao-encontrada');
            }

            $projetoId = (int) $tarefa['projeto_id'];
            if (!ProjetoMembro::jaEMembro($projetoId, (int) $usuarioId)) {
                $this->redirecionar($projetoId, 'nao-autorizado');
            }

            Tarefa::deletar($tarefaId);
            $this->redirecionar($projetoId);
        }

        private function validarBase(int $projetoId, int $usuarioId, string $titulo, int $prioridade, int $status): void
        {
            // Regras comuns para criar e editar tarefas.
            if (!$projetoId || !$usuarioId) {
                header('Location: /index.php');
                exit;
            }

            if (!ProjetoMembro::jaEMembro($projetoId, $usuarioId)) {
                $this->redirecionar($projetoId, 'nao-autorizado');
            }

            if ($titulo === '') {
                $this->redirecionar($projetoId, 'titulo-vazio');
            }

            if (!in_array($prioridade, self::PRIORIDADES_VALIDAS, true)) {
                $this->redirecionar($projetoId, 'prioridade-invalida');
            }

            if (!in_array($status, self::STATUS_VALIDOS, true)) {
                $this->redirecionar($projetoId, 'status-invalido');
            }
        }

        private function normalizarPrazo(?string $prazo): ?string
        {
            // Campo vazio vira null no banco; datas invalidas tambem sao descartadas.
            $prazo = trim((string) $prazo);

            if ($prazo === '') {
                return null;
            }

            $data = DateTime::createFromFormat('Y-m-d', $prazo);
            if ($data && $data->format('Y-m-d') === $prazo) {
                return $prazo;
            }

            return null;
        }

        private function redirecionar(int $projetoId, ?string $erro = null): void
        {
            // Centraliza o retorno para manter todas as actions no mesmo padrao.
            $baseUrl = defined('BASE_URL') ? BASE_URL : '';
            $url = $baseUrl . '/?page=tarefas';

            if ($projetoId > 0) {
                $url .= '&projeto_id=' . $projetoId;
            }

            if ($erro) {
                $url .= '&erro=' . urlencode($erro);
            }

            header('Location: ' . $url);
            exit;
        }
    }
