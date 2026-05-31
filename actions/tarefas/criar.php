<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/TarefaController.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/?page=tarefas');
    exit;
}

(new TarefaController())->criar();
