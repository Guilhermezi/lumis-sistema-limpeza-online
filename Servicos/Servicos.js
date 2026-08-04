document.addEventListener('DOMContentLoaded', function() {

    // ============ HAMBURGER MENU ============
    const menuToggle = document.getElementById('alternarMenu');
    const menu = document.getElementById('menu');

    menuToggle.addEventListener('click', function() {
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

    document.addEventListener('click', function(event) {
        if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('ativo')) {
            menu.classList.remove('ativo');
            menuToggle.classList.remove('aberto');
            document.body.style.overflow = 'auto';
        }
    });

    menu.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    // ============ FAVORITE BUTTON ============
    document.querySelectorAll('.cartao-favorito').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.toggle('ativo');
            const icon = this.querySelector('i');
            if (this.classList.contains('ativo')) {
                icon.className = 'ri-heart-fill';
            } else {
                icon.className = 'ri-heart-line';
            }
        });
    });

    // ============ CATEGORY FILTER ============
    document.querySelectorAll('.cartao-categoria').forEach(card => {
        card.addEventListener('click', function(e) {
            const category = this.dataset.categoria;
            const checkboxes = document.querySelectorAll('input[name="category"]');
            checkboxes.forEach(cb => cb.checked = cb.value === category);
            filterServices();
        });
    });

    // ============ SEARCH TAGS ============
    document.querySelectorAll('.tag-busca').forEach(tag => {
        tag.addEventListener('click', function() {
            const filter = this.dataset.filtro;
            document.getElementById('campoBusca').value = '';
            const checkboxes = document.querySelectorAll('input[name="category"]');
            checkboxes.forEach(cb => cb.checked = cb.value === filter);
            filterServices();
            document.getElementById('servicos').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // ============ SEARCH INPUT ============
    document.getElementById('campoBusca').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.cartao-servico').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? '' : 'none';
        });
        updateResultsCount();
    });

    // ============ FILTER CHECKBOXES ============
    document.querySelectorAll('.filtro-check input').forEach(cb => {
        cb.addEventListener('change', filterServices);
    });

    // ============ PRICE RANGE ============
    const priceRange = document.getElementById('faixaPreco');
    const priceValue = document.getElementById('valorPreco');
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = 'R$ ' + this.value;
            filterServices();
        });
    }

    // ============ RATING FILTER ============
    document.querySelectorAll('.botao-avaliacao').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.botao-avaliacao').forEach(b => b.classList.remove('ativo'));
            this.classList.add('ativo');
            filterServices();
        });
    });

    // ============ CITY FILTER ============
    document.getElementById('filtroCidade').addEventListener('change', filterServices);

    // ============ CLEAR FILTERS ============
    document.getElementById('limparFiltros').addEventListener('click', function() {
        document.querySelectorAll('.filtro-check input').forEach(cb => cb.checked = false);
        document.querySelectorAll('.botao-avaliacao').forEach(b => b.classList.remove('ativo'));
        document.querySelector('.botao-avaliacao[data-avaliacao="0"]').classList.add('ativo');
        if (priceRange) {
            priceRange.value = 500;
            priceValue.textContent = 'R$ 500';
        }
        document.getElementById('filtroCidade').value = '';
        document.getElementById('campoBusca').value = '';
        document.querySelectorAll('.cartao-servico').forEach(card => card.style.display = '');
        updateResultsCount();
    });

    // ============ SORT ============
    document.getElementById('selecaoOrdem').addEventListener('change', function() {
        const grid = document.getElementById('gradeServicos');
        const cards = Array.from(grid.querySelectorAll('.cartao-servico'));
        const sortBy = this.value;

        cards.sort((a, b) => {
            if (sortBy === 'avaliacao') {
                return parseFloat(b.dataset.avaliacao) - parseFloat(a.dataset.avaliacao);
            } else if (sortBy === 'preco-menor') {
                return parseInt(a.dataset.preco) - parseInt(b.dataset.preco);
            } else if (sortBy === 'preco-maior') {
                return parseInt(b.dataset.preco) - parseInt(a.dataset.preco);
            }
            return 0;
        });

        cards.forEach(card => grid.appendChild(card));
    });

    // ============ FILTER LOGIC ============
    function filterServices() {
        const checkedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(cb => cb.value);
        const maxPrice = parseInt(priceRange.value);
        const activeRating = parseFloat(document.querySelector('.botao-avaliacao.ativo')?.dataset.avaliacao || 0);

        document.querySelectorAll('.cartao-servico').forEach(card => {
            const category = card.dataset.categoria;
            const price = parseInt(card.dataset.preco);
            const rating = parseFloat(card.dataset.avaliacao);

            const matchCategory = checkedCategories.length === 0 || checkedCategories.includes(category);
            const matchPrice = price <= maxPrice;
            const matchRating = rating >= activeRating;

            card.style.display = (matchCategory && matchPrice && matchRating) ? '' : 'none';
        });

        updateResultsCount();
    }

    function updateResultsCount() {
        const visible = document.querySelectorAll('.cartao-servico:not([style*="display: none"])').length;
        document.querySelector('.contagem-resultados').textContent = `${visible} serviço${visible !== 1 ? 's' : ''} encontrado${visible !== 1 ? 's' : ''}`;
    }

    // ============ LOAD MORE (simulated) ============
    document.getElementById('carregarMais')?.addEventListener('click', function() {
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
            const target = parseInt(counter.getAttribute('data-alvo'));
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
            document.querySelectorAll('.servicos-hero .hero-conteudo, .trust-bar .trust-item, .cartao-categoria, .cartao-servico, .como-etapa, .depoimento-cartao, .cta-conteudo').forEach(el => {
                el.classList.add('revelado');
            });
            animateCounters();
            return;
        }

        // Hero
        gsap.to('.servicos-hero .hero-conteudo', {
            opacity: 1, y: 0, duration: 0.9, ease: 'power3.out',
            onComplete: () => document.querySelector('.servicos-hero .hero-conteudo')?.classList.add('revelado')
        });

        // Trust bar
        gsap.to('.trust-bar .trust-item', {
            opacity: 1, y: 0, duration: 0.6, stagger: 0.1, ease: 'power3.out', delay: 0.4,
            onComplete: animateCounters
        });

        // Categories
        gsap.to('.cartao-categoria', {
            scrollTrigger: { trigger: '.grade-categorias', start: 'top 85%', once: true },
            opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: 'power3.out'
        });

        // Service cards
        gsap.utils.toArray('.cartao-servico').forEach((card, i) => {
            gsap.to(card, {
                scrollTrigger: { trigger: card, start: 'top 90%', once: true },
                opacity: 1, y: 0, duration: 0.6, ease: 'power3.out', delay: i * 0.05
            });
        });

        // How steps
        gsap.to('.como-etapa', {
            scrollTrigger: { trigger: '.como-grade', start: 'top 85%', once: true },
            opacity: 1, y: 0, duration: 0.6, stagger: 0.15, ease: 'power3.out'
        });

        // Testimonials
        gsap.to('.depoimento-cartao', {
            scrollTrigger: { trigger: '.depoimentos-grade', start: 'top 85%', once: true },
            opacity: 1, y: 0, duration: 0.6, stagger: 0.1, ease: 'power3.out'
        });

        // CTA
        gsap.to('.cta-conteudo', {
            scrollTrigger: { trigger: '.cta-secao', start: 'top 85%', once: true },
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
