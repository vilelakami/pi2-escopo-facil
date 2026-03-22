// Redefinir Senha — Scripts

document.addEventListener('DOMContentLoaded', () => {
    //// Toggle de visibilidade da senha
    document.querySelectorAll('.btn-toggle-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const input = document.getElementById(targetId);
            const icon = btn.querySelector('img');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.src = (window.BASE_URL || '') + 'assets/icon/eye.svg';
                icon.alt = 'Ocultar senha';
                btn.setAttribute('aria-label', 'Ocultar senha');
            } else {
                input.type = 'password';
                icon.src = (window.BASE_URL || '') + 'assets/icon/eye-off.svg';
                icon.alt = 'Mostrar senha';
                btn.setAttribute('aria-label', 'Mostrar senha');
            }
        });
    });
});