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
