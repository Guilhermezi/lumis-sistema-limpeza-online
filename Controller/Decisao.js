document.addEventListener('DOMContentLoaded', function() {
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
