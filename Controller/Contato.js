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
});

// Envio do formulário de contato
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contatoForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault(); // impede a página de recarregar

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const success = document.getElementById('formSuccess');
        success.hidden = false;
        form.reset();

        setTimeout(() => {
            success.hidden = true;
        }, 5000);

        // Aqui você poderia enviar os dados via fetch/AJAX se quiser
    });
});
