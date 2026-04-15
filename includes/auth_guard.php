<?php

require_once __DIR__ . '/session.php';

if (!estaLogado()) {
    header('Location: /index.php');
    exit;
}