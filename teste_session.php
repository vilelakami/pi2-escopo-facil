function estaLogado() {
    return isset($_SESSION['usuario_id']);
}