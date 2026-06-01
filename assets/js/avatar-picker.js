document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.avatar-picker').forEach(function (picker) {
        const valueInput  = picker.querySelector('.avatar-picker-value');
        const previewEl   = picker.querySelector('.avatar-picker-preview-avatar');
        const previewImg  = picker.querySelector('.avatar-picker-preview-icon');
        const previewLbl  = picker.querySelector('.avatar-picker-preview-label');
        const toggleBtn   = picker.querySelector('.avatar-picker-toggle-icons');
        const panel       = picker.querySelector('.avatar-picker-icons-panel');
        const searchInput = picker.querySelector('.avatar-picker-search');
        const toggleThumb = picker.querySelector('.avatar-picker-toggle-thumb');

        // Abrir/fechar painel de ícones
        toggleBtn.addEventListener('click', function () {
            const isOpen = !panel.hidden;
            panel.hidden = isOpen;
            toggleBtn.setAttribute('aria-expanded', String(!isOpen));
        });

        // Busca de ícones
        searchInput.addEventListener('input', function () {
            const q = searchInput.value.trim().toLowerCase();
            picker.querySelectorAll('.avatar-picker-icon').forEach(function (btn) {
                const match = !q || btn.dataset.icon.includes(q);
                btn.classList.toggle('avatar-picker-icon--hidden', !match);
            });
        });

        function getState() {
            const colorBtn = picker.querySelector('.avatar-picker-color--selected');
            const iconBtn  = picker.querySelector('.avatar-picker-icon--selected');
            return {
                color: colorBtn ? colorBtn.dataset.color : AVATAR_DEFAULT.color,
                icon:  iconBtn  ? iconBtn.dataset.icon   : AVATAR_DEFAULT.icon,
            };
        }

        function refresh() {
            const { color, icon } = getState();
            const iconUrl = avatarIconUrl(icon);
            const bgCss   = avatarCssValue(color);

            valueInput.value = color + ':' + icon;
            if (previewEl)  previewEl.style.background = bgCss;
            if (previewImg) previewImg.src = iconUrl;
            if (previewLbl) previewLbl.textContent = icon;
            if (toggleThumb) toggleThumb.src = iconUrl;
        }

        // Clique em cor
        picker.querySelectorAll('.avatar-picker-color').forEach(function (btn) {
            btn.addEventListener('click', function () {
                picker.querySelectorAll('.avatar-picker-color').forEach(function (b) {
                    b.classList.remove('avatar-picker-color--selected');
                });
                btn.classList.add('avatar-picker-color--selected');
                refresh();
            });
        });

        // Clique em ícone
        picker.querySelectorAll('.avatar-picker-icon').forEach(function (btn) {
            btn.addEventListener('click', function () {
                picker.querySelectorAll('.avatar-picker-icon').forEach(function (b) {
                    b.classList.remove('avatar-picker-icon--selected');
                });
                btn.classList.add('avatar-picker-icon--selected');

                // Fecha o painel após selecionar
                panel.hidden = true;
                toggleBtn.setAttribute('aria-expanded', 'false');
                refresh();
            });
        });
    });
});
