// Configuração — Scripts

document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = document.body.dataset.baseUrl || '';

    // Toggle de visibilidade da senha
    document.querySelectorAll('.btn-toggle-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const input = document.getElementById(targetId);
            const icon = btn.querySelector('img');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.src = baseUrl + '/assets/icon/eye.svg';
                icon.alt = 'Ocultar senha';
                btn.setAttribute('aria-label', 'Ocultar senha');
            } else {
                input.type = 'password';
                icon.src = baseUrl + '/assets/icon/eye-off.svg';
                icon.alt = 'Mostrar senha';
                btn.setAttribute('aria-label', 'Mostrar senha');
            }
        });
    });

    // Botões Cancelar — resetam o form pai
    document.querySelectorAll('.configuracao-btn-cancelar').forEach((btn) => {
        btn.addEventListener('click', () => {
            const form = btn.closest('form');
            if (form) form.reset();
        });
    });
});
