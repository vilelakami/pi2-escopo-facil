<?php

 if (session_status () === PHP_SESSION_NONE) {
    session_start();
 }

 function usuarioLogado () {
    return $_SESSION['usuario_id'] ?? false;
 }

 function estaLogado () {
    return isset($_SESSION['usuario_id']);
}   

