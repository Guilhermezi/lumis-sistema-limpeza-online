// Menu hamburguer functionality
        document.getElementById('menuToggle').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('active');
            this.classList.toggle('open');
            
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
                document.getElementById('menu').classList.remove('active');
                document.getElementById('menuToggle').classList.remove('open');
                document.body.style.overflow = 'auto'; // Restaura a rolagem
            });
        });
        
        // Fechar o menu ao clicar fora dele
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('menu');
            const menuToggle = document.getElementById('menuToggle');
            
            if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('active')) {
                menu.classList.remove('active');
                menuToggle.classList.remove('open');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Prevenir que cliques dentro do menu fechem ele
        document.getElementById('menu').addEventListener('click', function(event) {
            event.stopPropagation();
        });