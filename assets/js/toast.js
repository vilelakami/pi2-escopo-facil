/**
 * Toast — sistema de notificações leve, sem dependências.
 * API pública: toast(mensagem, { type, description, duration })
 * Auto-lê ?sucesso= e ?erro= da URL ao carregar a página.
 */
(function () {
    const DURATION = 4000;

    // Mapa de mensagens para os params de URL usados pelo backend
    const MESSAGES = {
        sucesso: {
            'perfil-atualizado':  'Dados atualizados com sucesso.',
            'senha-alterada':     'Senha alterada com sucesso.',
            'avatar-atualizado':  'Avatar atualizado com sucesso.',
            'email-confirmado':   'Email confirmado. Faça seu login.',
            'verifique-email':    'Conta criada! Verifique seu email.',
            'projeto-criado':     'Projeto criado com sucesso.',
            'projeto-atualizado': 'Projeto atualizado com sucesso.',
            'membro-adicionado':  'Membro adicionado ao projeto.',
            'membro-removido':    'Membro removido do projeto.',
            'tarefa-criada':      'Tarefa criada com sucesso.',
            'tarefa-atualizada':  'Tarefa atualizada com sucesso.',
            'tarefa-removida':    'Tarefa removida.',
            '1':                  'Alteração salva com sucesso.',
        },
        erro: {
            'campos-obrigatorios':   'Preencha todos os campos obrigatórios.',
            'email-invalido':        'Informe um email válido.',
            'email-em-uso':          'Este email já está em uso.',
            'cargo-invalido':        'Selecione um cargo válido.',
            'senha-curta':           'A senha deve ter pelo menos 8 caracteres.',
            'senhas-diferentes':     'As senhas não conferem.',
            'senha-atual-incorreta': 'Senha atual incorreta.',
            'credenciais-invalidas': 'Email ou senha incorretos.',
            'email-nao-confirmado':  'Confirme seu email antes de entrar.',
            'token-email-invalido':  'Link de confirmação inválido ou expirado.',
            'token-email-ausente':   'Link de confirmação ausente.',
            'token-invalido':        'Link expirado ou inválido.',
            'avatar-invalido':       'Selecione um ícone válido.',
            'nao-autorizado':        'Sem permissão para esta ação.',
            'titulo-vazio':          'O título é obrigatório.',
            'prioridade-invalida':   'Prioridade inválida.',
            'status-invalido':       'Status inválido.',
            'tarefa-nao-encontrada': 'Tarefa não encontrada.',
            'metodo-invalido':       'Método de requisição inválido.',
            'projeto-nao-encontrado':'Projeto não encontrado.',
            'termos-obrigatorios':   'Aceite os termos para criar a conta.',
        },
    };

    // ── Container ────────────────────────────────────────────────

    function getContainer() {
        let el = document.getElementById('toast-container');
        if (!el) {
            el = document.createElement('div');
            el.id = 'toast-container';
            document.body.appendChild(el);
        }
        return el;
    }

    // ── SVG icons ────────────────────────────────────────────────

    const ICONS = {
        success: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        info:    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        close:   '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    };

    // ── Core ─────────────────────────────────────────────────────

    function show(message, options) {
        options = options || {};
        var type        = options.type        || 'info';
        var description = options.description || null;
        var duration    = options.duration    !== undefined ? options.duration : DURATION;

        var el = document.createElement('div');
        el.className = 'toast toast--' + type;

        el.innerHTML =
            '<div class="toast-icon">' + (ICONS[type] || ICONS.info) + '</div>' +
            '<div class="toast-body">' +
                '<p class="toast-title">' + _esc(message) + '</p>' +
                (description ? '<p class="toast-desc">' + _esc(description) + '</p>' : '') +
            '</div>' +
            '<button class="toast-close" aria-label="Fechar">' + ICONS.close + '</button>' +
            (duration > 0 ? '<div class="toast-progress" style="animation-duration:' + duration + 'ms"></div>' : '');

        var closeBtn = el.querySelector('.toast-close');
        closeBtn.addEventListener('click', function () { dismiss(el); });

        getContainer().appendChild(el);

        if (duration > 0) {
            setTimeout(function () { dismiss(el); }, duration);
        }

        return el;
    }

    function dismiss(el) {
        if (!el || el.classList.contains('toast--out')) return;
        el.classList.add('toast--out');
        setTimeout(function () { el.remove(); }, 230);
    }

    function _esc(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── API pública ──────────────────────────────────────────────

    window.toast = function (message, options) {
        return show(message, Object.assign({ type: 'info' }, options || {}));
    };

    window.toast.success = function (message, options) {
        return show(message, Object.assign({ type: 'success' }, options || {}));
    };

    window.toast.error = function (message, options) {
        return show(message, Object.assign({ type: 'error' }, options || {}));
    };

    // ── Auto-flash via URL params ────────────────────────────────

    document.addEventListener('DOMContentLoaded', function () {
        var params = new URLSearchParams(window.location.search);

        var sucesso = params.get('sucesso');
        var erro    = params.get('erro');

        if (sucesso) {
            var msg = MESSAGES.sucesso[sucesso] || 'Operação realizada com sucesso.';
            toast.success(msg);
        }

        if (erro) {
            var errMsg = MESSAGES.erro[erro] || 'Ocorreu um erro. Tente novamente.';
            toast.error(errMsg);
        }

        // Limpa os params da URL sem recarregar a página
        if (sucesso || erro) {
            params.delete('sucesso');
            params.delete('erro');
            // mantém outros params como page=, projeto_id=, etc.
            var novaUrl = window.location.pathname +
                (params.toString() ? '?' + params.toString() : '');
            history.replaceState(null, '', novaUrl);
        }
    });
})();
