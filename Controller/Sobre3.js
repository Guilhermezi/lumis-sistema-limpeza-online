document.addEventListener('DOMContentLoaded', function() {

    const button4 = document.getElementById('button4');
    const button5 = document.getElementById('button5');

    const oculto4 = document.getElementById('oculto4');
    const oculto5 = document.getElementById('oculto5');

    function esconderTodos() {
        oculto4.style.display = 'none';
        oculto5.style.display = 'none';
    }

    button4.addEventListener('click', function() {
        if (oculto4.style.display === 'flex') {
            oculto4.style.display = 'none';
        } else {
            esconderTodos();
            oculto4.style.display = 'flex';
        }
    });

    button5.addEventListener('click', function() {
        if (oculto5.style.display === 'flex') {
            oculto5.style.display = 'none';
        } else {
            esconderTodos();
            oculto5.style.display = 'flex';
        }
    });
});