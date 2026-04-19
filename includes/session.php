<?php

 if (session_status () === PHP_SESSION_NONE) {
    session_start();
 }

 // DEV ONLY: simula usuário logado (remover quando login estiver pronto)
 if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['usuario_id'] = 1; // Natan Oliveira
 }

 function usuarioLogado () {
    return $_SESSION['usuario_id'] ?? false;
 }

 function estaLogado () {
    return isset($_SESSION['usuario_id']);
}   

