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

    // Alternância entre login e cadastro
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');
    const mobileRegisterTab = document.getElementById('mobile-register-tab');
    const mobileLoginTab = document.getElementById('mobile-login-tab');

    if (registerBtn) {
        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
            mobileRegisterTab.classList.add('active');
            mobileLoginTab.classList.remove('active');
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
            mobileLoginTab.classList.add('active');
            mobileRegisterTab.classList.remove('active');
        });
    }

    if (mobileRegisterTab) {
        mobileRegisterTab.addEventListener('click', () => {
            container.classList.add('active');
            mobileRegisterTab.classList.add('active');
            mobileLoginTab.classList.remove('active');
        });
    }

    if (mobileLoginTab) {
        mobileLoginTab.addEventListener('click', () => {
            container.classList.remove('active');
            mobileLoginTab.classList.add('active');
            mobileRegisterTab.classList.remove('active');
        });
    }
});
