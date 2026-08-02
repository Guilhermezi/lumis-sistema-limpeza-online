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

    // Alternância entre os modos "Cadastre-se" e "Entrar"
    let isLogin = false;
    const title = document.getElementById('decisionTitle');
    const togglePrefix = document.getElementById('decisionTogglePrefix');
    const toggleLink = document.getElementById('decisionToggleLink');

    function setMode(login) {
        isLogin = login;
        title.dataset.i18n = login ? 'decisao-entrar-como' : 'decisao-cadastre-se-como';
        togglePrefix.dataset.i18n = login ? 'decisao-nao-tem-conta' : 'decisao-ja-tem-conta';
        toggleLink.dataset.i18n = login ? 'decisao-cadastre-se' : 'decisao-faca-login';
        if (window.LumisI18n) window.LumisI18n.aplicar();
    }

    toggleLink.addEventListener('click', function() {
        setMode(!isLogin);
    });
});
