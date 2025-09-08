// Sobre.js
document.addEventListener('DOMContentLoaded', function() {
    // Obter referências aos botões
    const button1 = document.getElementById('button1');
    const button2 = document.getElementById('button2');
    const button3 = document.getElementById('button3');
    
    // Obter referências aos conteúdos ocultos
    const oculto1 = document.getElementById('oculto1');
    const oculto2 = document.getElementById('oculto2');
    const oculto3 = document.getElementById('oculto3');
    
    // Função para esconder todos os conteúdos
    function esconderTodos() {
        oculto1.style.display = 'none';
        oculto2.style.display = 'none';
        oculto3.style.display = 'none';
    }
    
    // Adicionar event listeners aos botões
    button1.addEventListener('click', function() {
        if (oculto1.style.display === 'block') {
            oculto1.style.display = 'none';
        } else {
            esconderTodos();
            oculto1.style.display = 'block';
        }
    });
    
    button2.addEventListener('click', function() {
        if (oculto2.style.display === 'block') {
            oculto2.style.display = 'none';
        } else {
            esconderTodos();
            oculto2.style.display = 'block';
        }
    });
    
    button3.addEventListener('click', function() {
        if (oculto3.style.display === 'block') {
            oculto3.style.display = 'none';
        } else {
            esconderTodos();
            oculto3.style.display = 'block';
        }
    });
});