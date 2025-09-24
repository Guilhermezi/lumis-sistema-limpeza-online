document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const menu = document.getElementById('menu');
            
            menuToggle.addEventListener('click', function() {
                this.classList.toggle('open');
                menu.classList.toggle('active');
                
                // Impede a rolagem da página quando o menu está aberto
                if (menu.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Fechar o menu ao clicar em um item (útil para mobile)
            document.querySelectorAll('#menu a').forEach(item => {
                item.addEventListener('click', () => {
                    menu.classList.remove('active');
                    menuToggle.classList.remove('open');
                    document.body.style.overflow = 'auto';
                });
            });
            
            // Fechar o menu ao clicar fora dele
            document.addEventListener('click', function(event) {
                if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('active')) {
                    menu.classList.remove('active');
                    menuToggle.classList.remove('open');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Prevenir que cliques dentro do menu fechem ele
            menu.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

        // Carrossel de Parceiros
        document.addEventListener("DOMContentLoaded", () => {
    const grid = document.querySelector(".parceiros-grid");
    const dots = document.querySelectorAll(".dot");

    let currentSlide = 0;

    function getSlidesPerView() {
        const width = window.innerWidth;
        if (width >= 1024) return 3;
        if (width >= 768) return 2;
        return 1;
    }

    function updateCarousel() {
        const slidesPerView = getSlidesPerView();
        const card = grid.querySelector(".parceiro-card");
        const cardStyle = getComputedStyle(card);
        const marginRight = parseFloat(cardStyle.marginRight || "0");
        const cardWidth = card.offsetWidth + marginRight;
        const offset = currentSlide * cardWidth * slidesPerView;

        grid.style.transform = `translateX(-${offset}px)`;

        dots.forEach((dot, i) => {
            dot.classList.toggle("active", i === currentSlide);
        });
    }

    dots.forEach((dot, i) => {
        dot.addEventListener("click", () => {
            currentSlide = i;
            updateCarousel();
        });
    });

    window.addEventListener("resize", () => {
        currentSlide = 0;
        updateCarousel();
    });

    updateCarousel();
});
