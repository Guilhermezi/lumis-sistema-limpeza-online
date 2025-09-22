document.addEventListener('DOMContentLoaded', function() {
    // Obter referências aos botões
    const button1 = document.getElementById('button10');
    const button2 = document.getElementById('button11');
    const button3 = document.getElementById('button12');
    
    // Obter referências aos conteúdos ocultos
    const oculto1 = document.getElementById('oculto10');
    const oculto2 = document.getElementById('oculto11');
    const oculto3 = document.getElementById('oculto12');
    
    // Função para esconder todos os conteúdos
    function esconderTodos() {
        oculto10.style.display = 'none';
        ocult11.style.display = 'none';
        oculto12.style.display = 'none';
    }
    
    // Adicionar event listeners aos botões
    button1.addEventListener('click', function() {
        if (oculto10.style.display === 'block') {
            oculto10.style.display = 'none';
        } else {
            esconderTodos();
            oculto10.style.display = 'block';
        }
    });
    
    button2.addEventListener('click', function() {
        if (oculto11.style.display === 'block') {
            oculto11.style.display = 'none';
        } else {
            esconderTodos();
            oculto11.style.display = 'block';
        }
    });
    
    button3.addEventListener('click', function() {
        if (oculto12.style.display === 'block') {
            oculto12.style.display = 'none';
        } else {
            esconderTodos();
            oculto12.style.display = 'block';
        }
    });
});

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
