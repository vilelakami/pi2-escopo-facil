// Login - Scripts

const mensagensLogin = {
    'campos-obrigatorios': 'Preencha email e senha.',
    'email-invalido': 'Informe um email valido.',
    'credenciais-invalidas': 'Email ou senha invalidos.',
    'email-nao-confirmado': 'Confirme seu email antes de acessar.',
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-toggle-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const input = document.getElementById(targetId);
            const icon = btn.querySelector('img');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.src = (window.BASE_URL || '') + '/assets/icon/eye.svg';
                icon.alt = 'Ocultar senha';
                btn.setAttribute('aria-label', 'Ocultar senha');
            } else {
                input.type = 'password';
                icon.src = (window.BASE_URL || '') + '/assets/icon/eye-off.svg';
                icon.alt = 'Mostrar senha';
                btn.setAttribute('aria-label', 'Mostrar senha');
            }
        });
    });

    const form = document.querySelector('[data-login-form]');
    const content = document.querySelector('[data-login-content]');
    const animation = document.querySelector('[data-login-animation]');
    const feedback = document.querySelector('[data-login-feedback]');
    const submitButton = document.querySelector('[data-login-submit]');
    const video = animation?.querySelector('video');

    if (!form || !content || !animation || !submitButton || !video) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        esconderFeedback(feedback);
        submitButton.disabled = true;
        submitButton.classList.add('is-loading');
        submitButton.textContent = 'Verificando...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
            });
            const data = await response.json();

            if (!response.ok || !data.sucesso) {
                mostrarFeedback(feedback, data.erro);
                return;
            }

            trocarFormularioPorAnimacao(content, animation, video, data.redirect);
        } catch (error) {
            mostrarFeedback(feedback, 'erro-conexao');
        } finally {
            if (!animation.classList.contains('is-visible')) {
                submitButton.disabled = false;
                submitButton.classList.remove('is-loading');
                submitButton.textContent = 'Entrar na conta';
            }
        }
    });
});

function mostrarFeedback(feedback, erro) {
    if (!feedback) return;

    feedback.textContent = mensagensLogin[erro] || 'Nao foi possivel entrar agora. Tente novamente.';
    feedback.hidden = false;
}

function esconderFeedback(feedback) {
    if (!feedback) return;

    feedback.textContent = '';
    feedback.hidden = true;
}

function trocarFormularioPorAnimacao(content, animation, video, redirectUrl) {
    content.classList.add('is-leaving');

    setTimeout(() => {
        content.hidden = true;
        animation.hidden = false;

        requestAnimationFrame(() => {
            animation.classList.add('is-visible');
            video.currentTime = 0;

            const playPromise = video.play();
            if (playPromise) {
                playPromise.catch(() => redirecionarParaDashboard(redirectUrl));
            }
        });

        redirecionarQuandoVideoTerminar(video, redirectUrl);
    }, 250);
}

function redirecionarQuandoVideoTerminar(video, redirectUrl) {
    const redirecionar = () => redirecionarParaDashboard(redirectUrl);

    video.addEventListener('ended', redirecionar, { once: true });
    video.addEventListener('error', redirecionar, { once: true });
}

function redirecionarParaDashboard(redirectUrl) {
    if (window.loginRedirecionou) return;

    window.loginRedirecionou = true;
    window.location.href = redirectUrl || (window.BASE_URL || '') + '/index.php?page=dashboard';
}
