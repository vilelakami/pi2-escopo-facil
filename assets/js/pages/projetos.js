document.addEventListener('DOMContentLoaded', function () {
    // ===== Modal: Novo Projeto =====
    const btnCriar = document.querySelector('.projetos-btn-criar');
    const modal = document.getElementById('modal-novo-projeto');
    const btnClose = document.getElementById('modal-close-btn');
    const btnCancelar = document.getElementById('modal-cancelar-btn');
    const form = document.getElementById('form-novo-projeto');

    function abrirModal() {
        modal.classList.add('active');
    }

    function fecharModal() {
        modal.classList.remove('active');
        form.reset();
    }

    btnCriar.addEventListener('click', abrirModal);
    btnClose.addEventListener('click', fecharModal);
    btnCancelar.addEventListener('click', fecharModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            fecharModal();
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        // TODO: integrar com backend
        fecharModal();
    });

    // ===== Modal Lateral: Gerenciar Projeto =====
    const modalGerenciar = document.getElementById('modal-gerenciar-projeto');
    const btnGerenciarClose = document.getElementById('gerenciar-close-btn');
    const btnGerenciarCancelar = document.getElementById('gerenciar-cancelar-btn');
    const formGerenciar = document.getElementById('form-gerenciar-projeto');
    const membrosGrid = document.getElementById('gerenciar-membros-grid');
    const templateMembro = document.getElementById('template-membro-card');

    function abrirGerenciar(index) {
        const projeto = window.projetosMock[index];
        if (!projeto) return;

        document.getElementById('gerenciar-titulo').value = projeto.titulo;
        document.getElementById('gerenciar-descricao').value = projeto.descricao;
        document.getElementById('gerenciar-criado-por').value = projeto.criado_por;
        document.getElementById('gerenciar-data-criacao').value = projeto.data_criacao;

        // Render membros
        membrosGrid.innerHTML = '';
        if (projeto.membros && projeto.membros.length > 0) {
            projeto.membros.forEach(function (membro) {
                const clone = templateMembro.content.cloneNode(true);
                const isAdmin = membro.role === 'admin';

                clone.querySelector('.membro-card-cargo').textContent = membro.cargo;
                clone.querySelector('.membro-card-avatar').src = membro.avatar;
                clone.querySelector('.membro-card-avatar').alt = membro.nome;
                clone.querySelector('.membro-card-nome').textContent = membro.nome;
                clone.querySelector('.membro-card-email').textContent = membro.email;

                const badge = clone.querySelector('.membro-card-badge');
                badge.textContent = isAdmin ? 'Admin' : 'Membro';
                badge.classList.add(isAdmin ? 'membro-card-badge--admin' : 'membro-card-badge--membro');

                membrosGrid.appendChild(clone);
            });
        }

        modalGerenciar.classList.add('active');
    }

    function fecharGerenciar() {
        modalGerenciar.classList.remove('active');
    }

    // Delegate click nos botões "Gerenciar"
    document.querySelectorAll('.projeto-btn-gerenciar').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const index = parseInt(this.getAttribute('data-index'), 10);
            abrirGerenciar(index);
        });
    });

    btnGerenciarClose.addEventListener('click', fecharGerenciar);
    btnGerenciarCancelar.addEventListener('click', fecharGerenciar);

    modalGerenciar.addEventListener('click', function (e) {
        if (e.target === modalGerenciar) {
            fecharGerenciar();
        }
    });

    formGerenciar.addEventListener('submit', function (e) {
        e.preventDefault();
        // TODO: integrar com backend
        fecharGerenciar();
    });
});
