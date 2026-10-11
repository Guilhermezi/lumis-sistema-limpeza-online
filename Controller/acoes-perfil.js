document.addEventListener('DOMContentLoaded', () => {
    const csrf = window.LumisProfileCsrf || '';
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
        if (!response.ok || !data.ok) throw new Error(data.erro || 'Não foi possível concluir esta ação.');
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
                button.innerHTML = `<i class="ri-heart-${data.favorito ? 'fill' : 'line'}"></i> ${data.favorito ? 'Profissional favoritado' : 'Favoritar profissional'}`;
            } catch (_) {
                window.alert('Não foi possível atualizar seu profissional favorito. Atualize a página e tente novamente.');
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
                message.textContent = 'Avaliação enviada. Obrigado pelo feedback!';
                message.hidden = false;
                window.setTimeout(() => window.location.reload(), 700);
            } catch (_) {
                message.textContent = 'Não foi possível enviar a avaliação. Confira os dados e tente novamente.';
                message.hidden = false;
                button.disabled = false;
            }
        });
    });

    document.querySelectorAll('.pro-service-action').forEach((button) => {
        button.addEventListener('click', async () => {
            const acao = button.dataset.action;
            const confirmacoes = {
                recusar: 'Deseja realmente recusar este agendamento?',
                cancelar: 'Deseja realmente cancelar este agendamento?',
                concluir: 'Confirmar que a limpeza foi concluída?',
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
                        confirmar: 'Agendamento confirmado. Após realizar o serviço, confirme a conclusão.',
                        concluir: 'Limpeza marcada como concluída. O cliente já pode avaliar o serviço.',
                        recusar: 'Agendamento recusado.',
                        cancelar: 'Agendamento cancelado.',
                    };
                    message.textContent = mensagens[acao];
                    message.hidden = false;
                }
                window.setTimeout(() => window.location.reload(), 700);
            } catch (_) {
                if (message) {
                    message.textContent = 'Não foi possível atualizar o agendamento. Recarregue a página e tente novamente.';
                    message.hidden = false;
                }
                button.disabled = false;
            }
        });
    });

    document.querySelectorAll('.client-cancel-booking').forEach((button) => {
        button.addEventListener('click', async () => {
            if (!window.confirm('Deseja realmente cancelar este agendamento?')) return;
            const card = button.closest('.profile-order');
            const message = card?.querySelector('.profile-action-message');
            button.disabled = true;
            try {
                await post('../../Controller/api/atualizar-status-agendamento.php', {
                    contratacao_id: Number(button.dataset.contractId),
                    acao: 'cancelar',
                });
                if (message) {
                    message.textContent = 'Agendamento cancelado. Como o pagamento é simulado, nenhum estorno real é processado.';
                    message.hidden = false;
                }
                window.setTimeout(() => window.location.reload(), 900);
            } catch (_) {
                if (message) {
                    message.textContent = 'Não foi possível cancelar. O agendamento pode já ter começado ou sido atualizado.';
                    message.hidden = false;
                }
                button.disabled = false;
            }
        });
    });
});
