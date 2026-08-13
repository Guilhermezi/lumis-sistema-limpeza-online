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
     
 // ====================== Carrossel da Seção de Comentários ======================
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.carousel');
    const items = document.querySelectorAll('.carousel-item');
    const dots = document.querySelectorAll('.dot');
    const nextBtn = document.querySelector('.next');
    const prevBtn = document.querySelector('.prev');
    
    let currentIndex = 0;
    let autoPlayInterval;
    const intervalTime = 5000; // 5 segundos

    // Função principal para atualizar o carrossel
    function updateCarousel() {
        // Atualiza a posição do carrossel com transição suave
        carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Atualiza os indicadores (dots)
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    // Avança para o próximo slide
    function nextSlide() {
        currentIndex = (currentIndex + 1) % items.length;
        updateCarousel();
    }

    // Volta para o slide anterior
    function prevSlide() {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        updateCarousel();
    }

    // Vai para um slide específico
    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
    }

    // Inicia o autoplay
    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, intervalTime);
    }

    // Para e reinicia o autoplay (quando há interação do usuário)
    function resetAutoPlay() {
        clearInterval(autoPlayInterval);
        startAutoPlay();
    }

    // Event listeners para os botões de navegação
    nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoPlay();
    });

    prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoPlay();
    });

    // Event listeners para os dots (indicadores)
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
            resetAutoPlay();
        });
    });

    // Inicializa o carrossel
    updateCarousel();
    startAutoPlay();
    
    // Pausa o autoplay quando o mouse está sobre o carrossel
    carousel.addEventListener('mouseenter', () => {
        clearInterval(autoPlayInterval);
    });
    
    // Retoma o autoplay quando o mouse sai do carrossel
    carousel.addEventListener('mouseleave', () => {
        startAutoPlay();
    });
});