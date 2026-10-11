
document.addEventListener('DOMContentLoaded', function() {
    // CORREÇÃO: IDs corretos dos elementos
    const button1 = document.getElementById('button10');
    const button2 = document.getElementById('button11');
    const button3 = document.getElementById('button12');
    
    // CORREÇÃO: IDs corretos dos conteúdos
    const oculto10 = document.getElementById('oculto10');
    const oculto11 = document.getElementById('oculto11');
    const oculto12 = document.getElementById('oculto12');
    
    // Função para esconder todos os conteúdos
    function esconderTodos() {
        if (oculto10) oculto10.style.display = 'none';
        if (oculto11) oculto11.style.display = 'none';
        if (oculto12) oculto12.style.display = 'none';
    }
    
    // Adicionar event listeners apenas se os elementos existirem
    if (button1 && oculto10) {
        button1.addEventListener('click', function() {
            if (oculto10.style.display === 'block') {
                oculto10.style.display = 'none';
            } else {
                esconderTodos();
                oculto10.style.display = 'block';
            }
        });
    }
    
    if (button2 && oculto11) {
        button2.addEventListener('click', function() {
            if (oculto11.style.display === 'block') {
                oculto11.style.display = 'none';
            } else {
                esconderTodos();
                oculto11.style.display = 'block';
            }
        });
    }
    
    if (button3 && oculto12) {
        button3.addEventListener('click', function() {
            if (oculto12.style.display === 'block') {
                oculto12.style.display = 'none';
            } else {
                esconderTodos();
                oculto12.style.display = 'block';
            }
        });
    }
});
