// Cadastro — Scripts

document.addEventListener('DOMContentLoaded', () => {
    // Toggle de visibilidade da senha
    document.querySelectorAll('.btn-toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const input = document.getElementById(targetId);
            const icon = btn.querySelector('img');

            if (input.type === 'password') {
                input.type = 'text';
                icon.src = '/assets/icon/eye.svg';
                icon.alt = 'Ocultar senha';
            } else {
                input.type = 'password';
                icon.src = '/assets/icon/eye-off.svg';
                icon.alt = 'Mostrar senha';
            }
        });
    });
});
