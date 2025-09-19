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

            // Interações para os botões de confirmação e cancelamento
            document.querySelectorAll('.btn-confirm').forEach(button => {
                button.addEventListener('click', function() {
                    const visitCard = this.closest('.visit-card');
                    visitCard.classList.add('confirmed');
                    setTimeout(() => {
                        visitCard.style.opacity = '0.7';
                    }, 300);
                    alert('Visita confirmada com sucesso!');
                });
            });
            
            document.querySelectorAll('.btn-cancel').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Tem certeza que deseja cancelar esta visita?')) {
                        const visitCard = this.closest('.visit-card');
                        visitCard.style.transform = 'translateX(-100%)';
                        visitCard.style.opacity = '0';
                        setTimeout(() => {
                            visitCard.remove();
                        }, 500);
                    }
                });
            });

            // Botões de "Ler mais" para comentários
            document.querySelectorAll('.read-more-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const commentCard = this.closest('.comment-card');
                    commentCard.classList.toggle('expanded');
                    
                    if (commentCard.classList.contains('expanded')) {
                        this.innerHTML = 'Ler menos <i class="ri-arrow-up-s-line"></i>';
                    } else {
                        this.innerHTML = 'Ler mais <i class="ri-arrow-down-s-line"></i>';
                    }
                });
            });
        });
