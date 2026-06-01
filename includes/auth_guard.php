<?php

require_once __DIR__ . '/session.php';

if (!estaLogado()) {
    $baseUrl = defined('BASE_URL') ? BASE_URL : '';
    header('Location: ' . $baseUrl . '/index.php?page=login');
    exit;
}
