document.addEventListener('DOMContentLoaded', function() {

    // ============ HAMBURGER MENU ============
    const menuToggle = document.getElementById('menuToggle');
    const menu = document.getElementById('menu');

    menuToggle.addEventListener('click', function() {
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

    document.addEventListener('click', function(event) {
        if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('active')) {
            menu.classList.remove('active');
            menuToggle.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    });

    menu.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    // ============ VISIT ACTIONS ============
    document.querySelectorAll('.btn-confirm').forEach(button => {
        button.addEventListener('click', function() {
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
        button.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja cancelar esta visita?')) {
                const visitItem = this.closest('.visit-item');
                visitItem.style.transform = 'translateX(-100%)';
                visitItem.style.opacity = '0';
                visitItem.style.maxHeight = visitItem.offsetHeight + 'px';
                setTimeout(() => {
                    visitItem.style.maxHeight = '0';
                    visitItem.style.padding = '0';
                    visitItem.style.margin = '0';
                    visitItem.style.borderWidth = '0';
                }, 300);
                setTimeout(() => visitItem.remove(), 600);
            }
        });
    });

    // ============ REVIEW TOGGLE ============
    document.querySelectorAll('.review-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const reviewItem = this.closest('.review-item');
            reviewItem.classList.toggle('expanded');
            if (reviewItem.classList.contains('expanded')) {
                this.innerHTML = 'Ler menos <i class="ri-arrow-up-s-line"></i>';
            } else {
                this.innerHTML = 'Ler mais <i class="ri-arrow-down-s-line"></i>';
            }
        });
    });

    // ============ STAT COUNTER ANIMATION ============
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

    // ============ GSAP REVEAL ANIMATIONS ============
    function initRevealAnimations() {
        if (typeof gsap === 'undefined') {
            // Fallback: just reveal everything
            document.querySelectorAll('.profile-hero, .stats-bar .stat-card, .card').forEach(el => {
                el.classList.add('revealed');
            });
            animateCounters();
            return;
        }

        // Hero entrance
        gsap.to('.profile-hero', {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: 'power3.out',
            onComplete: () => document.querySelector('.profile-hero').classList.add('revealed')
        });

        // Stats bar - staggered
        gsap.to('.stats-bar .stat-card', {
            opacity: 1,
            y: 0,
            duration: 0.6,
            stagger: 0.1,
            ease: 'power3.out',
            delay: 0.3,
            onComplete: animateCounters
        });

        // Cards - scroll-triggered
        gsap.utils.toArray('.card').forEach((card, i) => {
            gsap.to(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 90%',
                    once: true
                },
                opacity: 1,
                y: 0,
                duration: 0.7,
                ease: 'power3.out',
                delay: i * 0.05,
                onComplete: () => card.classList.add('revealed')
            });
        });
    }

    // Load GSAP from CDN if not present
    if (typeof gsap === 'undefined') {
        const gsapScript = document.createElement('script');
        gsapScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js';
        gsapScript.onload = () => {
            const stScript = document.createElement('script');
            stScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js';
            stScript.onload = () => {
                gsap.registerPlugin(ScrollTrigger);
                initRevealAnimations();
            };
            document.head.appendChild(stScript);
        };
        document.head.appendChild(gsapScript);
    } else {
        initRevealAnimations();
    }
});
