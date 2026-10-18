document.addEventListener('DOMContentLoaded', function() {
    // Alternância entre login e cadastro
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');
    const mobileRegisterTab = document.getElementById('mobile-register-tab');
    const mobileLoginTab = document.getElementById('mobile-login-tab');

    function atualizarAbas(cadastroAtivo) {
        mobileRegisterTab?.classList.toggle('active', cadastroAtivo);
        mobileLoginTab?.classList.toggle('active', !cadastroAtivo);
        mobileRegisterTab?.setAttribute('aria-selected', String(cadastroAtivo));
        mobileLoginTab?.setAttribute('aria-selected', String(!cadastroAtivo));
    }

    if (registerBtn) {
        registerBtn.addEventListener('click', () => {
            container.classList.add('active');
            atualizarAbas(true);
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            container.classList.remove('active');
            atualizarAbas(false);
        });
    }

    if (mobileRegisterTab) {
        mobileRegisterTab.addEventListener('click', () => {
            container.classList.add('active');
            atualizarAbas(true);
        });
    }

    if (mobileLoginTab) {
        mobileLoginTab.addEventListener('click', () => {
            container.classList.remove('active');
            atualizarAbas(false);
        });
    }
});
