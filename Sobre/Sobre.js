// sobre.js - Scripts unificados da página Sobre (menu, MVV, carrossel, serviços, animações)
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ----- Menu mobile -----
    var menuToggle = document.getElementById('alternarMenu');
    var menu = document.getElementById('menu');

    if (menuToggle && menu) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('aberto');
            menu.classList.toggle('ativo');
            document.body.style.overflow = menu.classList.contains('ativo') ? 'hidden' : 'auto';
        });

        document.querySelectorAll('#menu a').forEach(function(item) {
            item.addEventListener('click', function() {
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
    }

    // ----- Painéis Missão / Visão / Valores -----
    var paresMvv = [
        { botao: document.getElementById('botao1'), painel: document.getElementById('oculto-1') },
        { botao: document.getElementById('botao2'), painel: document.getElementById('oculto-2') },
        { botao: document.getElementById('botao3'), painel: document.getElementById('oculto-3') }
    ];

    if (paresMvv.every(function(par) { return par.botao && par.painel; })) {
        paresMvv.forEach(function(par) {
            par.botao.addEventListener('click', function() {
                var abrir = par.painel.style.display !== 'block';
                paresMvv.forEach(function(outro) { outro.painel.style.display = 'none'; });
                if (abrir) par.painel.style.display = 'block';
            });
        });
    }

    // ----- Cards de tipos de limpeza (Residencial / Comercial) -----
    var button4 = document.getElementById('botao4');
    var button5 = document.getElementById('botao5');

    if (button4 && button5) {
        var card4 = button4.closest('.servico-card');
        var card5 = button5.closest('.servico-card');
        var corpo4 = card4 ? card4.querySelector('.servico-card-corpo') : null;
        var corpo5 = card5 ? card5.querySelector('.servico-card-corpo') : null;

        if (card4 && card5 && corpo4 && corpo5) {
            function alternar(botao, card, corpo) {
                var jaAberto = corpo.classList.contains('aberto');

                [card4, card5].forEach(function(c) { c.classList.remove('ativo'); });
                [corpo4, corpo5].forEach(function(c) { c.classList.remove('aberto'); });
                [button4, button5].forEach(function(b) { b.setAttribute('aria-expanded', 'false'); });

                if (!jaAberto) {
                    card.classList.add('ativo');
                    corpo.classList.add('aberto');
                    botao.setAttribute('aria-expanded', 'true');
                }
            }

            button4.addEventListener('click', function() { alternar(button4, card4, corpo4); });
            button5.addEventListener('click', function() { alternar(button5, card5, corpo5); });
        }
    }

    // ----- Carrossel de vantagens -----
    var painel = document.querySelector('.carrossel-painel');
    var list = painel ? painel.querySelector('.carrossel-list') : null;
    var items = painel ? painel.querySelectorAll('.carrossel-item') : [];
    var indicators = painel ? painel.querySelectorAll('.indicator') : [];
    var barra = painel ? painel.querySelector('.carrossel-progresso span') : null;
    var prevBtn = document.getElementById('anterior');
    var nextBtn = document.getElementById('proximo');
    var backBtn = document.getElementById('voltar');

    if (painel && list && items.length && indicators.length && prevBtn && nextBtn && backBtn) {
        var currentIndex = 0;
        var autoPlayInterval;
        var INTERVALO = 6000;

        function updateCarousel() {
            list.style.transform = 'translateX(-' + currentIndex * 100 + '%)';
            items.forEach(function(item, i) { item.classList.toggle('ativo', i === currentIndex); });
            indicators.forEach(function(indicator, i) { indicator.classList.toggle('ativo', i === currentIndex); });
        }

        function reiniciarProgresso() {
            if (!barra) return;
            barra.style.animation = 'none';
            void barra.offsetWidth;
            barra.style.animation = '';
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % items.length;
            updateCarousel();
            resetAutoPlay();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateCarousel();
            resetAutoPlay();
        }

        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
            resetAutoPlay();
        }

        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, INTERVALO);
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        function resetAutoPlay() {
            stopAutoPlay();
            startAutoPlay();
            reiniciarProgresso();
        }

        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);
        backBtn.addEventListener('click', function() { goToSlide(0); });

        indicators.forEach(function(indicator) {
            indicator.addEventListener('click', function() {
                var index = parseInt(this.getAttribute('data-index'), 10);
                if (!isNaN(index)) goToSlide(index);
            });
        });

        painel.addEventListener('mouseenter', function() {
            stopAutoPlay();
            if (barra) barra.style.animationPlayState = 'paused';
        });

        painel.addEventListener('mouseleave', function() {
            if (barra) barra.style.animationPlayState = 'running';
            reiniciarProgresso();
            startAutoPlay();
        });

        startAutoPlay();
        updateCarousel();
        reiniciarProgresso();
    }

    // ----- Revelação de elementos ao rolar -----
    var revelaveis = document.querySelectorAll('.revelar, .revelar-esquerda, .revelar-direita, .revelar-zoom');

    if (revelaveis.length && 'IntersectionObserver' in window) {
        var observador = new IntersectionObserver(function(entradas) {
            entradas.forEach(function(entrada) {
                if (!entrada.isIntersecting) return;
                var el = entrada.target;

                el.classList.add('na-visao');

                el.addEventListener('transitionend', function limpar() {
                    el.classList.remove('revelar', 'revelar-esquerda', 'revelar-direita', 'revelar-zoom', 'na-visao');
                    el.removeEventListener('transitionend', limpar);
                });

                observador.unobserve(el);
            });
        }, { threshold: 0.18 });

        revelaveis.forEach(function(el) { observador.observe(el); });
    } else {
        revelaveis.forEach(function(el) { el.classList.add('na-visao'); });
    }

    // ----- Contadores animados de conquistas -----
    var contadores = document.querySelectorAll('.contador');

    function animarContador(el, alvo) {
        var duracao = 1600;
        var inicio = null;

        function passo(agora) {
            if (inicio === null) inicio = agora;
            var progresso = Math.min((agora - inicio) / duracao, 1);
            el.textContent = Math.floor(progresso * alvo);

            if (progresso < 1) {
                requestAnimationFrame(passo);
            } else {
                el.textContent = alvo;
            }
        }

        requestAnimationFrame(passo);
    }

    if (contadores.length && 'IntersectionObserver' in window) {
        var obsContador = new IntersectionObserver(function(entradas) {
            entradas.forEach(function(entrada) {
                if (!entrada.isIntersecting) return;
                var el = entrada.target;
                var alvo = parseInt(el.getAttribute('data-alvo') || '0', 10);
                animarContador(el, alvo);
                obsContador.unobserve(el);
            });
        }, { threshold: 0.4 });

        contadores.forEach(function(el) { obsContador.observe(el); });
    } else {
        contadores.forEach(function(el) {
            el.textContent = el.getAttribute('data-alvo') || '0';
        });
    }
});
