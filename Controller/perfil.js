document.addEventListener('DOMContentLoaded', function () {

    // ============ REVEAL ANIMATIONS (entrada em cascata, sem ocultar conteúdo) ============
    function animateCounters() {
        document.querySelectorAll('.stat-number').forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
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
        const targets = document.querySelectorAll('.profile-hero, .stats-bar .stat-card, .card');
        targets.forEach((el, i) => {
            setTimeout(() => el.classList.add('revealed'), 60 * i);
        });
        setTimeout(animateCounters, 400);
    }

    try {
        initReveal();
    } catch (e) {
        document.querySelectorAll('.profile-hero, .stats-bar .stat-card, .card').forEach(el => el.classList.add('revealed'));
    }

    // ============ HAMBURGER MENU ============
    const menuToggle = document.getElementById('menuToggle');
    const menu = document.getElementById('menu');

    menuToggle.addEventListener('click', function () {
        this.classList.toggle('open');
        menu.classList.toggle('active');
        document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : 'auto';
    });

    document.querySelectorAll('#menu a').forEach(item => {
        item.addEventListener('click', () => {
            menu.classList.remove('active');
            menuToggle.classList.remove('open');
            document.body.style.overflow = 'auto';
        });
    });

    document.addEventListener('click', function (event) {
        if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('active')) {
            menu.classList.remove('active');
            menuToggle.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    });

    menu.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    // ============ VISIT ACTIONS ============
    document.querySelectorAll('.btn-confirm').forEach(button => {
        button.addEventListener('click', function () {
            const visitItem = this.closest('.visit-item');
            visitItem.classList.add('confirmed');
            const status = visitItem.querySelector('.visit-status');
            if (status) {
                status.textContent = 'Confirmada';
                status.classList.remove('status-upcoming');
                status.classList.add('status-confirmed');
            }
        });
    });

    document.querySelectorAll('.btn-cancel').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm('Tem certeza que deseja cancelar esta visita?')) {
                const visitItem = this.closest('.visit-item');
                visitItem.style.transition = 'all 0.35s ease';
                visitItem.style.transform = 'translateX(-100%)';
                visitItem.style.opacity = '0';
                setTimeout(() => visitItem.remove(), 400);
            }
        });
    });

    // ============ REVIEW TOGGLE ============
    document.querySelectorAll('.review-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const reviewItem = this.closest('.review-item');
            reviewItem.classList.toggle('expanded');
            this.innerHTML = reviewItem.classList.contains('expanded')
                ? 'Ler menos <i class="ri-arrow-up-s-line"></i>'
                : 'Ler mais <i class="ri-arrow-down-s-line"></i>';
        });
    });

});
