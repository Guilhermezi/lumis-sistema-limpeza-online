// ====================== Tela de Carregamento ======================
window.addEventListener('load', function() {
    const tc = document.getElementById('tela-carregamento');
    if (tc) {
        setTimeout(function() {
            tc.classList.add('oculto');
            setTimeout(function() { tc.remove(); }, 600);
        }, 600);
    }
});

document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('alternarMenu');
            const menu = document.getElementById('menu');
            
            menuToggle.addEventListener('click', function() {
                this.classList.toggle('aberto');
                menu.classList.toggle('ativo');
                
                // Impede a rolagem da página quando o menu está aberto
                if (menu.classList.contains('ativo')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Fechar o menu ao clicar em um item (útil para mobile)
            document.querySelectorAll('#menu a').forEach(item => {
                item.addEventListener('click', () => {
                    menu.classList.remove('ativo');
                    menuToggle.classList.remove('aberto');
                    document.body.style.overflow = 'auto';
                });
            });
            
            // Fechar o menu ao clicar fora dele
            document.addEventListener('click', function(event) {
                if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('ativo')) {
                    menu.classList.remove('ativo');
                    menuToggle.classList.remove('aberto');
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
    const carousel = document.querySelector('.carrossel-comentarios-faixa');
    const items = document.querySelectorAll('.carrossel-comentario');
    const dots = document.querySelectorAll('.ponto');
    const nextBtn = document.querySelector('.proximo');
    const prevBtn = document.querySelector('.anterior');
    
    let currentIndex = 0;
    let autoPlayInterval;
    const intervalTime = 5000; // 5 segundos

    // Função principal para atualizar o carrossel
    function updateCarousel() {
        // Atualiza a posição do carrossel com transição suave
        carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Atualiza os indicadores (dots)
        dots.forEach((dot, index) => {
            dot.classList.toggle('ativo', index === currentIndex);
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