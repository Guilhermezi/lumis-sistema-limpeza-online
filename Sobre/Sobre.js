// Sobre.js
document.addEventListener('DOMContentLoaded', function() {
    // Obter referências aos botões
    const button1 = document.getElementById('button1');
    const button2 = document.getElementById('button2');
    const button3 = document.getElementById('button3');
    
    // Obter referências aos conteúdos ocultos
    const oculto1 = document.getElementById('oculto1');
    const oculto2 = document.getElementById('oculto2');
    const oculto3 = document.getElementById('oculto3');
    
    // Função para esconder todos os conteúdos
    function esconderTodos() {
        oculto1.style.display = 'none';
        oculto2.style.display = 'none';
        oculto3.style.display = 'none';
    }
    
    // Adicionar event listeners aos botões
    button1.addEventListener('click', function() {
        if (oculto1.style.display === 'block') {
            oculto1.style.display = 'none';
        } else {
            esconderTodos();
            oculto1.style.display = 'block';
        }
    });
    
    button2.addEventListener('click', function() {
        if (oculto2.style.display === 'block') {
            oculto2.style.display = 'none';
        } else {
            esconderTodos();
            oculto2.style.display = 'block';
        }
    });
    
    button3.addEventListener('click', function() {
        if (oculto3.style.display === 'block') {
            oculto3.style.display = 'none';
        } else {
            esconderTodos();
            oculto3.style.display = 'block';
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
