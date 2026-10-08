/* ============================================
   menu.js - menu hambúrguer do site (mobile)
   --------------------------------------------
   Este é o ÚNICO lugar onde o menu hambúrguer vive.
   Antes ele estava copiado dentro de 16 arquivos JS,
   o que significava: cada correção precisava ser feita
   16 vezes, e em Sobre2.js estava duplicado, registrando
   o mesmo listener duas vezes.

   Como usar: carregue este arquivo em qualquer página que
   tenha #menuToggle e #menu no HTML.

   Deixa de rodar sozinho se a página não tiver o markup,
   então páginas sem menu podem carregá-lo sem quebrar.
   ============================================ */

document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const menu = document.getElementById('menu');

    // página sem menu hambúrguer: nada a fazer
    if (!menuToggle || !menu) return;

    menuToggle.addEventListener('click', function () {
        this.classList.toggle('open');
        menu.classList.toggle('active');

        // travar o scroll da página só enquanto o menu estiver aberto,
        // senão o fundo rola embaixo e dá a sensação de bug
        if (menu.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    });

    // ao clicar num item do menu, fecha e volta a permitir o scroll
    menu.querySelectorAll('a').forEach(item => {
        item.addEventListener('click', () => {
            menu.classList.remove('active');
            menuToggle.classList.remove('open');
            document.body.style.overflow = 'auto';
        });
    });

    // clicar fora do menu também fecha
    document.addEventListener('click', function (event) {
        if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('active')) {
            menu.classList.remove('active');
            menuToggle.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    });

    // evita que o clique dentro do menu caia no listener de "clicar fora"
    // e feche o menu na hora que o usuário selecionou algo
    menu.addEventListener('click', function (event) {
        event.stopPropagation();
    });
});