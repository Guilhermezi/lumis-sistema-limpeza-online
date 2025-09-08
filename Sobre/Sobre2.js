document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.item');
    const indicators = document.querySelectorAll('.indicator');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    const backBtn = document.getElementById('back');
    
    let currentIndex = 0;
    let autoPlayInterval;
    
    // Função para atualizar o carrossel
    function updateCarousel() {
        // Remover a classe active de todos os itens e indicadores
        items.forEach(item => item.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Adicionar a classe active ao item e indicador atual
        items[currentIndex].classList.add('active');
        indicators[currentIndex].classList.add('active');
    }
    
    // Avançar para o próximo slide
    function nextSlide() {
        currentIndex = (currentIndex + 1) % items.length;
        updateCarousel();
        resetAutoPlay();
    }
    
    // Voltar para o slide anterior
    function prevSlide() {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        updateCarousel();
        resetAutoPlay();
    }
    
    // Ir para um slide específico
    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
        resetAutoPlay();
    }
    
    // Configurar auto-play
    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, 5000); // Avança a cada 5 segundos
    }
    
    // Parar auto-play
    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    // Reiniciar auto-play
    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }
    
    // Event listeners para os botões
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
    backBtn.addEventListener('click', () => goToSlide(0));
    
    // Event listeners para os indicadores
    indicators.forEach(indicator => {
        indicator.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            goToSlide(index);
        });
    });
    
    // Pausar auto-play quando o mouse estiver sobre o carrossel
    const carousel = document.querySelector('.carousel');
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);
    
    // Iniciar auto-play e inicializar o carrossel
    startAutoPlay();
    updateCarousel();
});