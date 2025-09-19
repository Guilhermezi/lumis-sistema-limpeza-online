// Script para alternância entre login e cadastro
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');
        const mobileRegisterBtn = document.getElementById('mobile-register');
        const mobileLoginBtn = document.getElementById('mobile-login');

        if (registerBtn) {
            registerBtn.addEventListener('click', () => {
                container.classList.add("active");
            });
        }

        if (loginBtn) {
            loginBtn.addEventListener('click', () => {
                container.classList.remove("active");
            });
        }

        if (mobileRegisterBtn) {
            mobileRegisterBtn.addEventListener('click', () => {
                container.classList.add("active");
            });
        }

        if (mobileLoginBtn) {
            mobileLoginBtn.addEventListener('click', () => {
                container.classList.remove("active");
            });
        }

        // Menu mobile toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('nav ul');

        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                menuToggle.classList.toggle('open');
                nav.classList.toggle('active');
            });
        }