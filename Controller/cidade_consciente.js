
        // Carrossel de Parceiros
        document.addEventListener("DOMContentLoaded", () => {
    const grid = document.querySelector(".parceiros-grid");
    const dots = document.querySelectorAll(".dot");

    let currentSlide = 0;

    function getSlidesPerView() {
        const width = window.innerWidth;
        if (width >= 450) return 1;
        if (width >= 770) return 2;
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
