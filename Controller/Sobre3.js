document.addEventListener('DOMContentLoaded', function() {

    const button4 = document.getElementById('button4');
    const button5 = document.getElementById('button5');

    const oculto4 = document.getElementById('oculto4');
    const oculto5 = document.getElementById('oculto5');

    function esconderTodos() {
        oculto4.style.display = 'none';
        oculto5.style.display = 'none';
    }

    button4.addEventListener('click', function() {
        if (oculto4.style.display === 'flex') {
            oculto4.style.display = 'none';
        } else {
            esconderTodos();
            oculto4.style.display = 'flex';
        }
    });

    button5.addEventListener('click', function() {
        if (oculto5.style.display === 'flex') {
            oculto5.style.display = 'none';
        } else {
            esconderTodos();
            oculto5.style.display = 'flex';
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
