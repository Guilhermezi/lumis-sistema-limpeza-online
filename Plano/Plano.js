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
    
    // Animação de contagem das estatisticas
    function animateNumber(el) {
        const target = parseFloat(el.dataset.alvo);
        const duration = 1500;
        const start = performance.now();
        
        function step(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = Math.floor(target * eased);
            el.textContent = target >= 1000 ? value.toLocaleString('pt-BR') : String(value);
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }
        
        requestAnimationFrame(step);
    }
    
    const numbers = document.querySelectorAll('.planos-estatistica-numero .number');
    if (numbers.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateNumber(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });
        
        numbers.forEach(number => observer.observe(number));
    }
});
