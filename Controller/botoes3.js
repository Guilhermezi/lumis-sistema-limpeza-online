
document.addEventListener('DOMContentLoaded', function() {
    // CORREÇÃO: IDs corretos dos elementos
    const button1 = document.getElementById('button10');
    const button2 = document.getElementById('button11');
    const button3 = document.getElementById('button12');
    
    // CORREÇÃO: IDs corretos dos conteúdos
    const oculto10 = document.getElementById('oculto10');
    const oculto11 = document.getElementById('oculto11');
    const oculto12 = document.getElementById('oculto12');
    
    // Função para esconder todos os conteúdos
    function esconderTodos() {
        if (oculto10) oculto10.style.display = 'none';
        if (oculto11) oculto11.style.display = 'none';
        if (oculto12) oculto12.style.display = 'none';
    }
    
    // Adicionar event listeners apenas se os elementos existirem
    if (button1 && oculto10) {
        button1.addEventListener('click', function() {
            if (oculto10.style.display === 'block') {
                oculto10.style.display = 'none';
            } else {
                esconderTodos();
                oculto10.style.display = 'block';
            }
        });
    }
    
    if (button2 && oculto11) {
        button2.addEventListener('click', function() {
            if (oculto11.style.display === 'block') {
                oculto11.style.display = 'none';
            } else {
                esconderTodos();
                oculto11.style.display = 'block';
            }
        });
    }
    
    if (button3 && oculto12) {
        button3.addEventListener('click', function() {
            if (oculto12.style.display === 'block') {
                oculto12.style.display = 'none';
            } else {
                esconderTodos();
                oculto12.style.display = 'block';
            }
        });
    }
});

// CÓDIGO DO MENU - VERSÃO OTIMIZADA E ISOLADA
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se já inicializamos o menu para evitar duplicação
    if (window.menuInitialized) return;
    window.menuInitialized = true;
    
    const menuToggle = document.getElementById('menuToggle');
    const menu = document.getElementById('menu');
    
    // Verificar se os elementos existem
    if (!menuToggle || !menu) {
        console.warn('Elementos do menu não encontrados');
        return;
    }
    
    function toggleMenu() {
        const isOpening = !menu.classList.contains('active');
        
        menuToggle.classList.toggle('open');
        menu.classList.toggle('active');
        
        document.body.style.overflow = isOpening ? 'hidden' : 'auto';
    }
    
    function closeMenu() {
        menuToggle.classList.remove('open');
        menu.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    // Event listener principal
    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleMenu();
    });
    
    // Fechar ao clicar nos links
    document.querySelectorAll('#menu a').forEach(item => {
        // Remover listeners anteriores para evitar duplicação
        item.removeEventListener('click', closeMenu);
        item.addEventListener('click', closeMenu);
    });
    
    // Fechar ao clicar fora
    document.addEventListener('click', function(event) {
        if (!menu.contains(event.target) && 
            !menuToggle.contains(event.target) && 
            menu.classList.contains('active')) {
            closeMenu();
        }
    });
    
    // Prevenir propagação dentro do menu
    menu.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});