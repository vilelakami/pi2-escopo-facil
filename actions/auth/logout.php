<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/session.php';

encerrarSessao();

header('Location: ' . BASE_URL . '/index.php?page=login');
exit;
