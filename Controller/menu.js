(function () {
    'use strict';

    function iniciarMenu() {
        const botao = document.getElementById('menuToggle');
        const menu = document.getElementById('menu');
        if (!botao || !menu || botao.dataset.menuInicializado === '1') return;

        botao.dataset.menuInicializado = '1';

        function definirAberto(aberto) {
            botao.classList.toggle('open', aberto);
            menu.classList.toggle('active', aberto);
            botao.setAttribute('aria-expanded', String(aberto));
            botao.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
            document.body.style.overflow = aberto ? 'hidden' : '';
        }

        function alternarMenu() {
            definirAberto(!menu.classList.contains('active'));
        }

        botao.addEventListener('click', alternarMenu);
        botao.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                alternarMenu();
            }
        });

        menu.addEventListener('click', function (event) {
            if (event.target.closest('a')) definirAberto(false);
        });

        document.addEventListener('click', function (event) {
            if (menu.classList.contains('active') && !menu.contains(event.target) && !botao.contains(event.target)) {
                definirAberto(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && menu.classList.contains('active')) {
                definirAberto(false);
                botao.focus();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768 && menu.classList.contains('active')) definirAberto(false);
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', iniciarMenu);
    else iniciarMenu();
})();
