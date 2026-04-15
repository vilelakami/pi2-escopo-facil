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
    const baseUrl = document.body.dataset.baseUrl || '';

    function renderMembrosGrid(projeto) {
        membrosGrid.innerHTML = '';
        if (!projeto.membros || projeto.membros.length === 0) return;

        projeto.membros.forEach(function (membro, membroIndex) {
            const clone = templateMembro.content.cloneNode(true);
            const isAdmin = membro.role === 'admin';

            const roleIcon = clone.querySelector('.membro-card-role-icon');
            const roleImg = clone.querySelector('.membro-card-role-img');
            roleIcon.classList.add(isAdmin ? 'membro-card-role-icon--admin' : 'membro-card-role-icon--membro');
            roleImg.src = baseUrl + '/assets/icon/' + (isAdmin ? 'user-key.svg' : 'user-lock.svg');

            clone.querySelector('.membro-card-cargo').textContent = membro.cargo;
            clone.querySelector('.membro-card-avatar').src = membro.avatar;
            clone.querySelector('.membro-card-avatar').alt = membro.nome;
            clone.querySelector('.membro-card-nome').textContent = membro.nome;
            clone.querySelector('.membro-card-email').textContent = membro.email;

            const badge = clone.querySelector('.membro-card-badge');
            badge.textContent = isAdmin ? 'Admin' : 'Membro';
            badge.classList.add(isAdmin ? 'membro-card-badge--admin' : 'membro-card-badge--membro');

            clone.querySelector('.membro-card-dropdown-promote').textContent =
                isAdmin ? 'Rebaixar para Membro' : 'Promover para Admin';

            membrosGrid.appendChild(clone);

            // Wire actions after append (fragment loses references)
            const card = membrosGrid.lastElementChild;
            const menuBtn = card.querySelector('.membro-card-menu');
            const dropdown = card.querySelector('.membro-card-dropdown');

            menuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.membro-card-dropdown.open').forEach(function (d) {
                    if (d !== dropdown) d.classList.remove('open');
                });
                dropdown.classList.toggle('open');
            });

            card.querySelector('.membro-card-dropdown-promote').addEventListener('click', function () {
                membro.role = isAdmin ? 'membro' : 'admin';
                dropdown.classList.remove('open');
                renderMembrosGrid(projeto);
            });

            card.querySelector('.membro-card-dropdown-remove').addEventListener('click', function () {
                projeto.membros.splice(membroIndex, 1);
                dropdown.classList.remove('open');
                renderMembrosGrid(projeto);
            });
        });
    }

    function abrirGerenciar(index) {
        const projeto = window.projetosMock[index];
        if (!projeto) return;

        document.getElementById('gerenciar-titulo').value = projeto.titulo;
        document.getElementById('gerenciar-descricao').value = projeto.descricao;
        document.getElementById('gerenciar-criado-por').value = projeto.criado_por;
        document.getElementById('gerenciar-data-criacao').value = projeto.data_criacao;

        renderMembrosGrid(projeto);
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

    // ===== Modal Lateral: Visualizar Projeto (Membro) =====
    const modalVisualizar = document.getElementById('modal-visualizar-projeto');
    const btnVisualizarClose = document.getElementById('visualizar-close-btn');
    const btnVisualizarFechar = document.getElementById('visualizar-fechar-btn');
    const btnVisualizarSair = document.getElementById('visualizar-sair-btn');
    const membrosGridVisualizar = document.getElementById('visualizar-membros-grid');
    const templateMembroVisualizar = document.getElementById('template-membro-card-visualizar');

    function abrirVisualizar(index) {
        const projeto = window.projetosMock[index];
        if (!projeto) return;

        document.getElementById('visualizar-titulo').value = projeto.titulo;
        document.getElementById('visualizar-descricao').value = projeto.descricao;
        document.getElementById('visualizar-criado-por').value = projeto.criado_por;
        document.getElementById('visualizar-data-criacao').value = projeto.data_criacao;

        membrosGridVisualizar.innerHTML = '';
        if (projeto.membros && projeto.membros.length > 0) {
            projeto.membros.forEach(function (membro) {
                const clone = templateMembroVisualizar.content.cloneNode(true);
                const isAdmin = membro.role === 'admin';
                const baseUrl = document.body.dataset.baseUrl || '';

                const roleIcon = clone.querySelector('.membro-card-role-icon');
                const roleImg = clone.querySelector('.membro-card-role-img');
                roleIcon.classList.add(isAdmin ? 'membro-card-role-icon--admin' : 'membro-card-role-icon--membro');
                roleImg.src = baseUrl + '/assets/icon/' + (isAdmin ? 'user-key.svg' : 'user-lock.svg');

                clone.querySelector('.membro-card-cargo').textContent = membro.cargo;
                clone.querySelector('.membro-card-avatar').src = membro.avatar;
                clone.querySelector('.membro-card-avatar').alt = membro.nome;
                clone.querySelector('.membro-card-nome').textContent = membro.nome;
                clone.querySelector('.membro-card-email').textContent = membro.email;

                const badge = clone.querySelector('.membro-card-badge');
                badge.textContent = isAdmin ? 'Admin' : 'Membro';
                badge.classList.add(isAdmin ? 'membro-card-badge--admin' : 'membro-card-badge--membro');

                membrosGridVisualizar.appendChild(clone);
            });
        } else {
            membrosGridVisualizar.innerHTML = '<p class="modal-sem-membros">Nenhum membro visível.</p>';
        }

        modalVisualizar.classList.add('active');
    }

    function fecharVisualizar() {
        modalVisualizar.classList.remove('active');
    }

    document.querySelectorAll('.projeto-btn-detalhes').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const index = parseInt(this.getAttribute('data-index'), 10);
            abrirVisualizar(index);
        });
    });

    btnVisualizarClose.addEventListener('click', fecharVisualizar);
    btnVisualizarFechar.addEventListener('click', fecharVisualizar);

    btnVisualizarSair.addEventListener('click', function () {
        // TODO: integrar com backend — chamar ação de sair do projeto
        fecharVisualizar();
    });

    modalVisualizar.addEventListener('click', function (e) {
        if (e.target === modalVisualizar) {
            fecharVisualizar();
        }
    });

    // Fechar qualquer dropdown de membro ao clicar fora
    document.addEventListener('click', function () {
        document.querySelectorAll('.membro-card-dropdown.open').forEach(function (d) {
            d.classList.remove('open');
        });
    });
});
