document.addEventListener('DOMContentLoaded', function () {

    // ============ REVEAL ANIMATIONS (entrada em cascata, sem ocultar conteúdo) ============
    function animateCounters() {
        document.querySelectorAll('.numero-estatistica').forEach(counter => {
            const target = parseInt(counter.getAttribute('data-alvo'));
            if (!target) return;
            const duration = 1500;
            const step = target / (duration / 16);
            let current = 0;
            const update = () => {
                current += step;
                if (current >= target) {
                    counter.textContent = target;
                    return;
                }
                counter.textContent = Math.floor(current);
                requestAnimationFrame(update);
            };
            update();
        });
    }

    function initReveal() {
        const targets = document.querySelectorAll('.perfil-hero, .barra-estatisticas .cartao-estatistica, .cartao');
        targets.forEach((el, i) => {
            setTimeout(() => el.classList.add('revelado'), 60 * i);
        });
        setTimeout(animateCounters, 400);
    }

    try {
        initReveal();
    } catch (e) {
        document.querySelectorAll('.perfil-hero, .barra-estatisticas .cartao-estatistica, .cartao').forEach(el => el.classList.add('revelado'));
    }

    // ============ HAMBURGER MENU ============
    const menuToggle = document.getElementById('alternarMenu');
    const menu = document.getElementById('menu');

    menuToggle.addEventListener('click', function () {
        this.classList.toggle('aberto');
        menu.classList.toggle('ativo');
        document.body.style.overflow = menu.classList.contains('ativo') ? 'hidden' : 'auto';
    });

    document.querySelectorAll('#menu a').forEach(item => {
        item.addEventListener('click', () => {
            menu.classList.remove('ativo');
            menuToggle.classList.remove('aberto');
            document.body.style.overflow = 'auto';
        });
    });

    document.addEventListener('click', function (event) {
        if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('ativo')) {
            menu.classList.remove('ativo');
            menuToggle.classList.remove('aberto');
            document.body.style.overflow = 'auto';
        }
    });

    menu.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    // ============ VISIT ACTIONS ============
    document.querySelectorAll('.botao-confirmar').forEach(button => {
        button.addEventListener('click', function () {
            const visitItem = this.closest('.visita-item');
            visitItem.classList.add('confirmado');
            const status = visitItem.querySelector('.visita-status');
            if (status) {
                status.textContent = 'Confirmada';
                status.classList.remove('status-proximos');
                status.classList.add('status-confirmed');
            }
        });
    });

    document.querySelectorAll('.botao-cancelar').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm('Tem certeza que deseja cancelar esta visita?')) {
                const visitItem = this.closest('.visita-item');
                visitItem.style.transition = 'all 0.35s ease';
                visitItem.style.transform = 'translateX(-100%)';
                visitItem.style.opacity = '0';
                setTimeout(() => visitItem.remove(), 400);
            }
        });
    });

    // ============ REVIEW TOGGLE ============
    document.querySelectorAll('.avaliacao-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const reviewItem = this.closest('.avaliacao-item');
            reviewItem.classList.toggle('expandido');
            this.innerHTML = reviewItem.classList.contains('expandido')
                ? 'Ler menos <i class="ri-arrow-up-s-line"></i>'
                : 'Ler mais <i class="ri-arrow-down-s-line"></i>';
        });
    });

});
