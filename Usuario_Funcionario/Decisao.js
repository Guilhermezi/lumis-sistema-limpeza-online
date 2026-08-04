document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('alternarMenu');
    const menu = document.getElementById('menu');
    
    menuToggle.addEventListener('click', function() {
        this.classList.toggle('aberto');
        menu.classList.toggle('ativo');
        
        // Impede a rolagem da página quando o menu está aberto
        if (menu.classList.contains('ativo')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    });
    
    // Fechar o menu ao clicar em um item (útil para mobile)
    document.querySelectorAll('#menu a').forEach(item => {
        item.addEventListener('click', () => {
            menu.classList.remove('ativo');
            menuToggle.classList.remove('aberto');
            document.body.style.overflow = 'auto';
        });
    });
    
    // Fechar o menu ao clicar fora dele
    document.addEventListener('click', function(event) {
        if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('ativo')) {
            menu.classList.remove('ativo');
            menuToggle.classList.remove('aberto');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Prevenir que cliques dentro do menu fechem ele
    menu.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    // Alternância entre os modos "Cadastre-se" e "Entrar"
    let isLogin = false;
    const title = document.getElementById('tituloDecisao');
    const togglePrefix = document.getElementById('prefixoDecisaoToggle');
    const toggleLink = document.getElementById('linkDecisaoToggle');

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
