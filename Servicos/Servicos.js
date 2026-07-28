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

    // ============ FAVORITE BUTTON ============
    document.querySelectorAll('.card-fav').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            if (this.classList.contains('active')) {
                icon.className = 'ri-heart-fill';
            } else {
                icon.className = 'ri-heart-line';
            }
        });
    });

    // ============ CATEGORY FILTER ============
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const category = this.dataset.category;
            const checkboxes = document.querySelectorAll('input[name="category"]');
            checkboxes.forEach(cb => cb.checked = cb.value === category);
            filterServices();
        });
    });

    // ============ SEARCH TAGS ============
    document.querySelectorAll('.search-tag').forEach(tag => {
        tag.addEventListener('click', function() {
            const filter = this.dataset.filter;
            document.getElementById('searchInput').value = '';
            const checkboxes = document.querySelectorAll('input[name="category"]');
            checkboxes.forEach(cb => cb.checked = cb.value === filter);
            filterServices();
            document.getElementById('servicos').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // ============ SEARCH INPUT ============
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.service-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? '' : 'none';
        });
        updateResultsCount();
    });

    // ============ FILTER CHECKBOXES ============
    document.querySelectorAll('.filter-check input').forEach(cb => {
        cb.addEventListener('change', filterServices);
    });

    // ============ PRICE RANGE ============
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = 'R$ ' + this.value;
            filterServices();
        });
    }

    // ============ RATING FILTER ============
    document.querySelectorAll('.rating-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.rating-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterServices();
        });
    });

    // ============ CITY FILTER ============
    document.getElementById('cityFilter').addEventListener('change', filterServices);

    // ============ CLEAR FILTERS ============
    document.getElementById('clearFilters').addEventListener('click', function() {
        document.querySelectorAll('.filter-check input').forEach(cb => cb.checked = false);
        document.querySelectorAll('.rating-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('.rating-btn[data-rating="0"]').classList.add('active');
        if (priceRange) {
            priceRange.value = 500;
            priceValue.textContent = 'R$ 500';
        }
        document.getElementById('cityFilter').value = '';
        document.getElementById('searchInput').value = '';
        document.querySelectorAll('.service-card').forEach(card => card.style.display = '');
        updateResultsCount();
    });

    // ============ SORT ============
    document.getElementById('sortSelect').addEventListener('change', function() {
        const grid = document.getElementById('servicesGrid');
        const cards = Array.from(grid.querySelectorAll('.service-card'));
        const sortBy = this.value;

        cards.sort((a, b) => {
            if (sortBy === 'avaliacao') {
                return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            } else if (sortBy === 'preco-menor') {
                return parseInt(a.dataset.price) - parseInt(b.dataset.price);
            } else if (sortBy === 'preco-maior') {
                return parseInt(b.dataset.price) - parseInt(a.dataset.price);
            }
            return 0;
        });

        cards.forEach(card => grid.appendChild(card));
    });

    // ============ FILTER LOGIC ============
    function filterServices() {
        const checkedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(cb => cb.value);
        const maxPrice = parseInt(priceRange.value);
        const activeRating = parseFloat(document.querySelector('.rating-btn.active')?.dataset.rating || 0);

        document.querySelectorAll('.service-card').forEach(card => {
            const category = card.dataset.category;
            const price = parseInt(card.dataset.price);
            const rating = parseFloat(card.dataset.rating);

            const matchCategory = checkedCategories.length === 0 || checkedCategories.includes(category);
            const matchPrice = price <= maxPrice;
            const matchRating = rating >= activeRating;

            card.style.display = (matchCategory && matchPrice && matchRating) ? '' : 'none';
        });

        updateResultsCount();
    }

    function updateResultsCount() {
        const visible = document.querySelectorAll('.service-card:not([style*="display: none"])').length;
        document.querySelector('.results-count').textContent = `${visible} serviço${visible !== 1 ? 's' : ''} encontrado${visible !== 1 ? 's' : ''}`;
    }

    // ============ LOAD MORE (simulated) ============
    document.getElementById('loadMore')?.addEventListener('click', function() {
        this.textContent = 'Carregando...';
        this.disabled = true;
        setTimeout(() => {
            this.textContent = 'Nenhum mais serviço disponível';
            this.style.opacity = '0.5';
        }, 1000);
    });

    // ============ STAT COUNTER ANIMATION ============
    function animateCounters() {
        document.querySelectorAll('.trust-number').forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            if (!target) return;
            const duration = 1800;
            const step = target / (duration / 16);
            let current = 0;
            const update = () => {
                current += step;
                if (current >= target) {
                    counter.textContent = target.toLocaleString('pt-BR');
                    return;
                }
                counter.textContent = Math.floor(current).toLocaleString('pt-BR');
                requestAnimationFrame(update);
            };
            update();
        });
    }

    // ============ GSAP REVEAL ANIMATIONS ============
    function initRevealAnimations() {
        if (typeof gsap === 'undefined') {
            document.querySelectorAll('.services-hero .hero-content, .trust-bar .trust-item, .category-card, .service-card, .how-step, .testimonial-card, .cta-content').forEach(el => {
                el.classList.add('revealed');
            });
            animateCounters();
            return;
        }

        // Hero
        gsap.to('.services-hero .hero-content', {
            opacity: 1, y: 0, duration: 0.9, ease: 'power3.out',
            onComplete: () => document.querySelector('.services-hero .hero-content')?.classList.add('revealed')
        });

        // Trust bar
        gsap.to('.trust-bar .trust-item', {
            opacity: 1, y: 0, duration: 0.6, stagger: 0.1, ease: 'power3.out', delay: 0.4,
            onComplete: animateCounters
        });

        // Categories
        gsap.to('.category-card', {
            scrollTrigger: { trigger: '.categories-grid', start: 'top 85%', once: true },
            opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: 'power3.out'
        });

        // Service cards
        gsap.utils.toArray('.service-card').forEach((card, i) => {
            gsap.to(card, {
                scrollTrigger: { trigger: card, start: 'top 90%', once: true },
                opacity: 1, y: 0, duration: 0.6, ease: 'power3.out', delay: i * 0.05
            });
        });

        // How steps
        gsap.to('.how-step', {
            scrollTrigger: { trigger: '.how-grid', start: 'top 85%', once: true },
            opacity: 1, y: 0, duration: 0.6, stagger: 0.15, ease: 'power3.out'
        });

        // Testimonials
        gsap.to('.testimonial-card', {
            scrollTrigger: { trigger: '.testimonials-grid', start: 'top 85%', once: true },
            opacity: 1, y: 0, duration: 0.6, stagger: 0.1, ease: 'power3.out'
        });

        // CTA
        gsap.to('.cta-content', {
            scrollTrigger: { trigger: '.cta-section', start: 'top 85%', once: true },
            opacity: 1, y: 0, duration: 0.7, ease: 'power3.out'
        });
    }

    // Load GSAP
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
