// Carrossel responsivo das avaliações reais exibidas na página inicial.
document.addEventListener('DOMContentLoaded', () => {
    const component = document.querySelector('.testimonial-carousel');
    if (!component) return;

    const viewport = component.querySelector('.testimonial-viewport');
    const track = component.querySelector('.carousel');
    const slides = Array.from(component.querySelectorAll('.testimonial-slide'));
    const previousButton = component.querySelector('.prev');
    const nextButton = component.querySelector('.next');
    const pauseButton = component.querySelector('.testimonial-pause');
    const progress = component.querySelector('.testimonial-progress-value');

    if (!viewport || !track || slides.length === 0) return;

    const autoplayDelay = 6000;
    const swipeThreshold = 45;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let currentIndex = 0;
    let itemsPerView = 1;
    let autoplayTimer = null;
    let pausedByUser = reducedMotion.matches;
    let pointerStartX = null;

    const calculateItemsPerView = () => {
        if (window.innerWidth >= 1050) return 3;
        if (window.innerWidth >= 700) return 2;
        return 1;
    };

    const maximumIndex = () => Math.max(0, slides.length - itemsPerView);

    const updatePauseButton = () => {
        if (!pauseButton) return;
        pauseButton.setAttribute('aria-pressed', String(pausedByUser));
        pauseButton.setAttribute('aria-label', pausedByUser
            ? 'Retomar rotação automática'
            : 'Pausar rotação automática');
        pauseButton.innerHTML = pausedByUser
            ? '<i class="ri-play-mini-fill" aria-hidden="true"></i>'
            : '<i class="ri-pause-mini-fill" aria-hidden="true"></i>';
    };

    const render = (animate = true) => {
        currentIndex = Math.min(Math.max(currentIndex, 0), maximumIndex());
        track.style.transition = animate ? '' : 'none';
        track.style.transform = `translateX(-${slides[currentIndex].offsetLeft}px)`;

        if (!animate) {
            // Força o navegador a aplicar a posição antes de restaurar a animação.
            void track.offsetWidth;
            track.style.transition = '';
        }

        slides.forEach((slide, index) => {
            const visible = index >= currentIndex && index < currentIndex + itemsPerView;
            slide.setAttribute('aria-hidden', String(!visible));
        });

        if (progress) {
            const viewedUntil = Math.min(currentIndex + itemsPerView, slides.length);
            progress.style.width = `${(viewedUntil / slides.length) * 100}%`;
        }

        const hasMovement = maximumIndex() > 0;
        if (previousButton) previousButton.disabled = !hasMovement;
        if (nextButton) nextButton.disabled = !hasMovement;
        if (pauseButton) pauseButton.disabled = !hasMovement;
    };

    const stopAutoplay = () => {
        window.clearInterval(autoplayTimer);
        autoplayTimer = null;
    };

    const goTo = (index) => {
        const lastIndex = maximumIndex();
        if (lastIndex === 0) return;
        currentIndex = index > lastIndex ? 0 : index < 0 ? lastIndex : index;
        render();
    };

    const next = () => goTo(currentIndex + 1);
    const previous = () => goTo(currentIndex - 1);

    const startAutoplay = () => {
        stopAutoplay();
        const interactionPaused = component.matches(':hover')
            || component.contains(document.activeElement)
            || document.hidden;
        if (pausedByUser || interactionPaused || maximumIndex() === 0) return;
        autoplayTimer = window.setInterval(next, autoplayDelay);
    };

    const restartAutoplay = () => {
        stopAutoplay();
        startAutoplay();
    };

    previousButton?.addEventListener('click', () => {
        previous();
        restartAutoplay();
    });

    nextButton?.addEventListener('click', () => {
        next();
        restartAutoplay();
    });

    pauseButton?.addEventListener('click', () => {
        pausedByUser = !pausedByUser;
        updatePauseButton();
        pausedByUser ? stopAutoplay() : startAutoplay();
    });

    viewport.addEventListener('keydown', (event) => {
        if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
        event.preventDefault();
        event.key === 'ArrowLeft' ? previous() : next();
        restartAutoplay();
    });

    viewport.addEventListener('pointerdown', (event) => {
        pointerStartX = event.clientX;
    });

    viewport.addEventListener('pointerup', (event) => {
        if (pointerStartX === null) return;
        const distance = event.clientX - pointerStartX;
        pointerStartX = null;
        if (Math.abs(distance) < swipeThreshold) return;
        distance > 0 ? previous() : next();
        restartAutoplay();
    });

    viewport.addEventListener('pointercancel', () => {
        pointerStartX = null;
    });

    component.addEventListener('mouseenter', stopAutoplay);
    component.addEventListener('mouseleave', startAutoplay);
    component.addEventListener('focusin', stopAutoplay);
    component.addEventListener('focusout', (event) => {
        if (!component.contains(event.relatedTarget)) startAutoplay();
    });

    document.addEventListener('visibilitychange', () => {
        document.hidden ? stopAutoplay() : startAutoplay();
    });

    window.addEventListener('resize', () => {
        const newItemsPerView = calculateItemsPerView();
        if (newItemsPerView === itemsPerView) return;
        itemsPerView = newItemsPerView;
        render(false);
        restartAutoplay();
    });

    itemsPerView = calculateItemsPerView();
    updatePauseButton();
    render(false);
    startAutoplay();
});
