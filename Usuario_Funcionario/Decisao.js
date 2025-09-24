document.addEventListener('DOMContentLoaded', function() {
            // Elementos do DOM
            const menuToggle = document.getElementById('menuToggle');
            const menu = document.getElementById('menu');
            const authModal = document.getElementById('auth-modal');
            const openAuthModalBtn = document.getElementById('openAuthModal');
            const closeModalBtn = document.querySelector('.close-modal');
            const btnLogin = document.getElementById('btn-login');
            const btnCadastro = document.getElementById('btn-cadastro');
            const mainContent = document.getElementById('main-content');
            const loginTypeContent = document.getElementById('login-type-content');
            const showLoginLink = document.getElementById('show-login');
            const showCadastroLink = document.getElementById('show-cadastro');

            // Menu mobile toggle
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

            // Modal de autenticação
            // Abrir modal
            openAuthModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                authModal.style.display = 'block';
            });

            // Fechar modal
            closeModalBtn.addEventListener('click', function() {
                authModal.style.display = 'none';
            });

            // Fechar modal ao clicar fora
            window.addEventListener('click', function(e) {
                if (e.target === authModal) {
                    authModal.style.display = 'none';
                }
            });

            // Botão Login no modal
            btnLogin.addEventListener('click', function() {
                authModal.style.display = 'none';
                mainContent.classList.add('hidden');
                loginTypeContent.classList.remove('hidden');
            });

            // Botão Cadastro no modal
            btnCadastro.addEventListener('click', function() {
                authModal.style.display = 'none';
                loginTypeContent.classList.add('hidden');
                mainContent.classList.remove('hidden');
            });

            // Alternar entre login e cadastro nas páginas
            showLoginLink.addEventListener('click', function(e) {
                e.preventDefault();
                mainContent.classList.add('hidden');
                loginTypeContent.classList.remove('hidden');
            });

            showCadastroLink.addEventListener('click', function(e) {
                e.preventDefault();
                loginTypeContent.classList.add('hidden');
                mainContent.classList.remove('hidden');
            });
        });