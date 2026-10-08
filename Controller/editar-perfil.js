(function () {
    'use strict';

    const script = document.currentScript || Array.from(document.scripts).find(item => /\/editar-perfil\.js(?:\?|$)/.test(item.src));
    const endpoint = new URL('api/atualizar-perfil.php', script?.src || window.location.href).href;

    function mascaraTelefone(input) {
        let valor = input.value.replace(/\D/g, '').slice(0, 11);
        if (valor.length > 10) valor = `(${valor.slice(0, 2)}) ${valor.slice(2, 7)}-${valor.slice(7)}`;
        else if (valor.length > 6) valor = `(${valor.slice(0, 2)}) ${valor.slice(2, 6)}-${valor.slice(6)}`;
        else if (valor.length > 2) valor = `(${valor.slice(0, 2)}) ${valor.slice(2)}`;
        else if (valor) valor = `(${valor}`;
        input.value = valor;
    }

    function iniciar() {
        const form = document.getElementById('profile-edit-form');
        if (!form) return;

        const message = document.getElementById('profile-edit-message');
        const submit = form.querySelector('button[type="submit"]');
        const phone = form.querySelector('[name="telefone"]');
        const photo = form.querySelector('[name="foto"]');
        const preview = document.getElementById('profile-photo-preview');

        phone?.addEventListener('input', () => mascaraTelefone(phone));
        photo?.addEventListener('change', function () {
            const file = this.files?.[0];
            if (!file || !file.type.startsWith('image/')) return;
            const url = URL.createObjectURL(file);
            preview.innerHTML = '';
            const image = document.createElement('img');
            image.src = url;
            image.alt = 'Prévia da nova foto';
            image.onload = () => URL.revokeObjectURL(url);
            preview.appendChild(image);
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            form.querySelectorAll('.invalid').forEach(item => item.classList.remove('invalid'));
            message.hidden = true;
            message.classList.remove('success');

            if (!form.reportValidity()) return;
            submit.disabled = true;
            submit.setAttribute('aria-busy', 'true');

            try {
                const data = new FormData(form);
                const response = await fetch(endpoint, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'X-CSRF-Token': data.get('_csrf') || '' },
                    body: data
                });
                const result = await response.json();

                if (!result.ok) {
                    Object.keys(result.campos || {}).forEach(name => form.querySelector(`[name="${name}"]`)?.closest('.profile-edit-field, .profile-photo-copy')?.classList.add('invalid'));
                    message.textContent = result.erro === 'email-em-uso'
                        ? 'Este e-mail já está sendo usado por outra conta.'
                        : result.erro?.startsWith('foto-')
                            ? 'Não foi possível usar essa foto. Confira o formato e o limite de 5 MB.'
                            : 'Confira os campos informados e tente novamente.';
                    message.hidden = false;
                    return;
                }

                message.textContent = result.mensagem || 'Perfil atualizado com sucesso.';
                message.classList.add('success');
                message.hidden = false;
                setTimeout(() => { window.location.href = result.redirect; }, 700);
            } catch (error) {
                message.textContent = 'Não foi possível conectar ao servidor. Tente novamente.';
                message.hidden = false;
            } finally {
                submit.disabled = false;
                submit.removeAttribute('aria-busy');
            }
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', iniciar);
    else iniciar();
})();
