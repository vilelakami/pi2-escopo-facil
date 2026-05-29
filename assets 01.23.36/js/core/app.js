/**
 * App — Inicialização global do sistema
 * Roda em todas as páginas internas
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('Escopo Fácil — v0.1.0');
    // Inicializa componentes globais que existam na página
    if (typeof SidebarComponent !== 'undefined') {
        SidebarComponent.init();
    }

    if (typeof ModalComponent !== 'undefined') {
        ModalComponent.init();
    }
});
