(function () {
    var dropdown = document.getElementById('user-menu-dropdown');
    if (!dropdown) return;

    var activeTrigger = null;

    function open(trigger) {
        var rect = trigger.getBoundingClientRect();
        var dropW = 240;
        var left  = rect.right - dropW;
        var top   = rect.bottom + 8;

        // Garante que não saia da tela pela esquerda
        if (left < 8) left = 8;

        dropdown.style.top  = top + 'px';
        dropdown.style.left = left + 'px';
        dropdown.hidden = false;
        trigger.classList.add('user-menu-trigger--active');
        activeTrigger = trigger;
    }

    function close() {
        dropdown.hidden = true;
        if (activeTrigger) {
            activeTrigger.classList.remove('user-menu-trigger--active');
            activeTrigger = null;
        }
    }

    // Delega cliques em qualquer .user-menu-trigger da página
    document.addEventListener('click', function (e) {
        var trigger = e.target.closest('.user-menu-trigger');
        if (trigger) {
            e.stopPropagation();
            if (!dropdown.hidden && activeTrigger === trigger) {
                close();
            } else {
                open(trigger);
            }
            return;
        }

        // Clique fora fecha
        if (!dropdown.hidden && !dropdown.contains(e.target)) {
            close();
        }
    });

    // Fecha com Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') close();
    });
})();
