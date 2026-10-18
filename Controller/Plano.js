document.addEventListener('DOMContentLoaded', function() {
    // Animação de contagem das estatisticas
    function animateNumber(el) {
        const target = parseFloat(el.dataset.target);
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
    
    const numbers = document.querySelectorAll('.plans-stat-number .number');
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
