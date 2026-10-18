document.addEventListener('DOMContentLoaded', () => {
    const csrf = window.LumisProfileCsrf || '';
    const t = (key) => window.LumisI18n?.texto(key) || key;
    const post = async (url, payload) => {
        const response = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-Token': csrf,
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json();
        if (!response.ok || !data.ok) throw new Error(data.erro || t('acao-erro-agendamento'));
        return data;
    };

    document.querySelectorAll('[data-favorite-contract]').forEach((button) => {
        button.addEventListener('click', async () => {
            button.disabled = true;
            try {
                const data = await post('../../Controller/api/toggle-favorito.php', {
                    contratacao_id: Number(button.dataset.favoriteContract),
                });
                button.dataset.favorito = data.favorito ? '1' : '0';
                button.setAttribute('aria-pressed', data.favorito ? 'true' : 'false');
                const key = data.favorito ? 'perfil-profissional-favoritado' : 'perfil-favoritar-profissional';
                button.innerHTML = `<i class="ri-heart-${data.favorito ? 'fill' : 'line'}"></i> <span data-i18n="${key}">${t(key)}</span>`;
            } catch (_) {
                window.alert(t('acao-favorito-erro'));
            } finally {
                button.disabled = false;
            }
        });
    });

    document.querySelectorAll('[data-review-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = form.querySelector('.profile-action-message');
            const button = form.querySelector('[type="submit"]');
            button.disabled = true;
            try {
                const fields = new FormData(form);
                await post('../../Controller/api/criar-avaliacao.php', {
                    contratacao_id: Number(fields.get('contratacao_id')),
                    nota: Number(fields.get('nota')),
                    comentario: fields.get('comentario') || '',
                });
                message.textContent = t('acao-avaliacao-sucesso');
                message.hidden = false;
                window.setTimeout(() => window.location.reload(), 700);
            } catch (_) {
                message.textContent = t('acao-avaliacao-erro');
                message.hidden = false;
                button.disabled = false;
            }
        });
    });

    document.querySelectorAll('.pro-service-action').forEach((button) => {
        button.addEventListener('click', async () => {
            const acao = button.dataset.action;
            const confirmacoes = {
                recusar: t('acao-confirmar-recusa'),
                cancelar: t('acao-confirmar-cancelamento'),
                concluir: t('acao-confirmar-conclusao'),
            };
            if (confirmacoes[acao] && !window.confirm(confirmacoes[acao])) return;
            const card = button.closest('.visit-content');
            const message = card?.querySelector('.profile-action-message');
            button.disabled = true;
            try {
                await post('../../Controller/api/atualizar-status-agendamento.php', {
                    contratacao_id: Number(button.dataset.contractId),
                    acao,
                });
                if (message) {
                    const mensagens = {
                        confirmar: t('acao-mensagem-confirmado'),
                        concluir: t('acao-mensagem-concluido'),
                        recusar: t('acao-mensagem-recusado'),
                        cancelar: t('acao-mensagem-cancelado'),
                    };
                    message.textContent = mensagens[acao];
                    message.hidden = false;
                }
                window.setTimeout(() => window.location.reload(), 700);
            } catch (_) {
                if (message) {
                    message.textContent = t('acao-erro-agendamento');
                    message.hidden = false;
                }
                button.disabled = false;
            }
        });
    });

    document.querySelectorAll('.client-cancel-booking').forEach((button) => {
        button.addEventListener('click', async () => {
            if (!window.confirm(t('acao-confirmar-cancelamento-cliente'))) return;
            const card = button.closest('.profile-order');
            const message = card?.querySelector('.profile-action-message');
            button.disabled = true;
            try {
                await post('../../Controller/api/atualizar-status-agendamento.php', {
                    contratacao_id: Number(button.dataset.contractId),
                    acao: 'cancelar',
                });
                if (message) {
                    message.textContent = t('acao-cancelado-simulado');
                    message.hidden = false;
                }
                window.setTimeout(() => window.location.reload(), 900);
            } catch (_) {
                if (message) {
                    message.textContent = t('acao-erro-cancelar');
                    message.hidden = false;
                }
                button.disabled = false;
            }
        });
    });
});
