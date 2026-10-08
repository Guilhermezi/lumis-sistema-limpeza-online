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
