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
        
        //Carrossel da home
        document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.querySelector(".services-grid");
    const cards = document.querySelectorAll(".service-card");
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");

    let currentIndex = 0;
    let autoplayInterval;
    const totalCards = cards.length;

    const getCardWidth = () => cards[0].offsetWidth + 32; // card width + gap

    const scrollToCard = (index) => {
      const scrollAmount = getCardWidth() * index;
      carousel.scrollTo({
        left: scrollAmount,
        behavior: "smooth"
      });
    };

    const goToNext = () => {
      currentIndex = (currentIndex + 1) % totalCards;
      scrollToCard(currentIndex);
    };

    const goToPrev = () => {
      currentIndex = (currentIndex - 1 + totalCards) % totalCards;
      scrollToCard(currentIndex);
    };

    nextBtn.addEventListener("click", () => {
      goToNext();
      resetAutoplay(); // para evitar avanço duplo após clique
    });

    prevBtn.addEventListener("click", () => {
      goToPrev();
      resetAutoplay();
    });

    // Autoplay a cada 5 segundos
    const startAutoplay = () => {
      autoplayInterval = setInterval(goToNext, 5000);
    };

    const stopAutoplay = () => {
      clearInterval(autoplayInterval);
    };

    const resetAutoplay = () => {
      stopAutoplay();
      startAutoplay();
    };

    // Iniciar autoplay
    startAutoplay();

    // Parar autoplay se o usuário interagir com o carrossel manualmente
    carousel.addEventListener("mouseenter", stopAutoplay);
    carousel.addEventListener("mouseleave", startAutoplay);

    // Recalcular posição no resize
    window.addEventListener("resize", () => {
      scrollToCard(currentIndex);
    });
  });