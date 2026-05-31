<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioLogado()
{
    return $_SESSION['usuario_id'] ?? false;
}

function estaLogado()
{
    return isset($_SESSION['usuario_id']);
}

function autenticarUsuario(int $usuarioId): void
{
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = $usuarioId;
}

function encerrarSessao(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}
