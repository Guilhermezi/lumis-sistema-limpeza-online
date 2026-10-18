document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('servicesGrid');
    const cards = grid ? Array.from(grid.querySelectorAll('.service-card')) : [];
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.querySelector('.search-btn');
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    const resultCount = document.querySelector('.results-count');
    const emptyResult = document.getElementById('filterEmpty');
    const sortSelect = document.getElementById('sortSelect');

    const normalizeText = (value) => value
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim();

    const selectedCategories = () => Array.from(
        document.querySelectorAll('input[name="category"]:checked'),
        (checkbox) => checkbox.value
    );

    const updateResultCount = (visible) => {
        if (resultCount) {
            resultCount.dataset.count = String(visible);
            const key = visible === 1 ? 'servicos-resultados-um' : 'servicos-resultados-varios';
            resultCount.textContent = window.LumisI18n?.texto(key, { count: visible })
                || `${visible} serviço${visible === 1 ? '' : 's'} encontrado${visible === 1 ? '' : 's'}`;
        }
        if (emptyResult) emptyResult.hidden = visible !== 0 || cards.length === 0;
    };

    const filterServices = () => {
        const query = normalizeText(searchInput?.value || '');
        const categories = selectedCategories();
        const maximumPrice = Number(priceRange?.value || Number.POSITIVE_INFINITY);
        const minimumRating = Number(document.querySelector('.rating-btn.active')?.dataset.rating || 0);
        let visible = 0;

        cards.forEach((card) => {
            const matchesSearch = query === '' || normalizeText(card.textContent).includes(query);
            const matchesCategory = categories.length === 0 || categories.includes(card.dataset.category);
            const matchesPrice = Number(card.dataset.price) <= maximumPrice;
            const matchesRating = Number(card.dataset.rating) >= minimumRating;
            const show = matchesSearch && matchesCategory && matchesPrice && matchesRating;
            card.hidden = !show;
            if (show) visible++;
        });

        updateResultCount(visible);
    };

    const selectOnlyCategory = (category) => {
        document.querySelectorAll('input[name="category"]').forEach((checkbox) => {
            checkbox.checked = checkbox.value === category;
        });
        if (searchInput) searchInput.value = '';
        filterServices();
    };

    document.querySelectorAll('.category-card').forEach((categoryCard) => {
        categoryCard.addEventListener('click', () => selectOnlyCategory(categoryCard.dataset.category));
    });

    document.querySelectorAll('.search-tag').forEach((tag) => {
        tag.addEventListener('click', () => {
            selectOnlyCategory(tag.dataset.filter);
            document.getElementById('servicos')?.scrollIntoView({ behavior: 'smooth' });
        });
    });

    searchInput?.addEventListener('input', filterServices);
    searchButton?.addEventListener('click', () => {
        filterServices();
        document.getElementById('servicos')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.querySelectorAll('input[name="category"]').forEach((checkbox) => {
        checkbox.addEventListener('change', filterServices);
    });

    priceRange?.addEventListener('input', () => {
        if (priceValue) priceValue.textContent = `R$ ${Number(priceRange.value).toLocaleString('pt-BR')}`;
        filterServices();
    });

    document.querySelectorAll('.rating-btn').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.rating-btn').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            filterServices();
        });
    });

    document.getElementById('clearFilters')?.addEventListener('click', () => {
        document.querySelectorAll('input[name="category"]').forEach((checkbox) => {
            checkbox.checked = false;
        });
        document.querySelectorAll('.rating-btn').forEach((button) => button.classList.remove('active'));
        document.querySelector('.rating-btn[data-rating="0"]')?.classList.add('active');
        if (priceRange) {
            priceRange.value = priceRange.max;
            if (priceValue) priceValue.textContent = `R$ ${Number(priceRange.max).toLocaleString('pt-BR')}`;
        }
        if (searchInput) searchInput.value = '';
        if (sortSelect) sortSelect.value = 'relevancia';
        cards.sort((a, b) => Number(a.dataset.order) - Number(b.dataset.order));
        cards.forEach((card) => grid?.appendChild(card));
        filterServices();
    });

    sortSelect?.addEventListener('change', () => {
        const sortBy = sortSelect.value;
        cards.sort((a, b) => {
            if (sortBy === 'avaliacao') return Number(b.dataset.rating) - Number(a.dataset.rating);
            if (sortBy === 'preco-menor') return Number(a.dataset.price) - Number(b.dataset.price);
            if (sortBy === 'preco-maior') return Number(b.dataset.price) - Number(a.dataset.price);
            return Number(a.dataset.order) - Number(b.dataset.order);
        });
        cards.forEach((card) => grid?.appendChild(card));
    });

    // Anima os números que vieram do banco sem depender de bibliotecas externas.
    document.querySelectorAll('.trust-number').forEach((counter) => {
        const target = Number(counter.dataset.target || 0);
        if (target === 0) {
            counter.textContent = '0';
            return;
        }
        const startedAt = performance.now();
        const duration = 900;
        const update = (now) => {
            const progress = Math.min((now - startedAt) / duration, 1);
            counter.textContent = Math.round(target * progress).toLocaleString('pt-BR');
            if (progress < 1) requestAnimationFrame(update);
        };
        requestAnimationFrame(update);
    });

    // O CSS inicia estes elementos ocultos para animação; esta classe os revela.
    document.querySelectorAll('.services-hero .hero-content, .trust-bar .trust-item, .category-card, .service-card, .testimonial-card, .cta-content')
        .forEach((element) => element.classList.add('revealed'));

    filterServices();
});
