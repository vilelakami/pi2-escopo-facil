<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/MembroController.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /?page=projetos');
    exit;
}

(new MembroController())->remover();
