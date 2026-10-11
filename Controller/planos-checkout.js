document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('planCheckout');
    if (!modal) return;

    const steps = [...modal.querySelectorAll('[data-plan-step]')];
    const benefitsList = document.getElementById('planCheckoutBenefits');
    const errorBox = document.getElementById('planCheckoutError');
    let selectedPlan = null;
    let paymentMethod = null;
    let csrf = '';

    const money = (value) => Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    const showStep = (name) => steps.forEach((step) => { step.hidden = step.dataset.planStep !== name; });
    const close = () => {
        modal.hidden = true;
        document.body.classList.remove('plan-checkout-open');
        paymentMethod = null;
        errorBox.hidden = true;
        modal.querySelectorAll('[data-plan-payment]').forEach((button) => button.classList.remove('active'));
    };
    const open = () => {
        modal.hidden = false;
        document.body.classList.add('plan-checkout-open');
        showStep('summary');
        modal.querySelector('.plan-checkout-dialog')?.scrollTo({ top: 0 });
        modal.querySelector('[data-plan-close]')?.focus();
    };

    document.querySelectorAll('.plan-select-button').forEach((button) => button.addEventListener('click', async () => {
        selectedPlan = {
            id: Number(button.dataset.planId),
            name: button.dataset.planName,
            price: Number(button.dataset.planPrice),
            duration: button.dataset.planDuration,
            custom: button.dataset.planCustom === 'true',
            benefits: (button.dataset.planBenefits || '').split(';').map((item) => item.trim()).filter(Boolean),
        };
        document.getElementById('planCheckoutTitle').textContent = `Confira: ${selectedPlan.name}`;
        document.getElementById('planCheckoutDuration').textContent = selectedPlan.custom ? 'Plano personalizado sob consulta.' : `Plano válido por ${selectedPlan.duration}.`;
        document.getElementById('planCheckoutName').textContent = selectedPlan.name;
        document.getElementById('planCheckoutPrice').textContent = selectedPlan.custom ? 'Sob consulta' : money(selectedPlan.price);
        benefitsList.replaceChildren(...selectedPlan.benefits.map((benefit) => {
            const li = document.createElement('li');
            li.textContent = benefit;
            return li;
        }));
        document.getElementById('planContinue').hidden = selectedPlan.custom;
        document.getElementById('planCustomContact').hidden = !selectedPlan.custom;
        open();

        try {
            const response = await fetch('../../../Controller/api/sessao.php', { credentials: 'same-origin', headers: { Accept: 'application/json' } });
            const data = await response.json();
            csrf = data.csrf || '';
            if (!selectedPlan.custom && !data.logado) {
                document.getElementById('planAuthTitle').textContent = 'Entre para continuar';
                document.getElementById('planAuthText').textContent = 'A contratação de planos está disponível para contas de cliente.';
                modal.dataset.session = 'guest';
            } else if (!selectedPlan.custom && data.usuario?.tipo !== 'cliente') {
                document.getElementById('planAuthTitle').textContent = 'Use uma conta de cliente';
                document.getElementById('planAuthText').textContent = 'Contas profissionais não podem contratar planos.';
                modal.dataset.session = 'professional';
            } else if (!selectedPlan.custom && !data.usuario?.email_verificado) {
                modal.dataset.session = 'unverified';
                const authLink = modal.querySelector('[data-plan-step="auth"] > .btn-primary');
                authLink.href = '../auth/verificar-email.php';
                authLink.textContent = 'Confirmar e-mail';
            } else {
                modal.dataset.session = 'ready';
            }
        } catch (_) {
            modal.dataset.session = 'error';
        }
    }));

    document.querySelectorAll('[data-plan-close]').forEach((button) => button.addEventListener('click', close));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !modal.hidden) close(); });

    document.getElementById('planContinue')?.addEventListener('click', () => {
        if (modal.dataset.session === 'guest' || modal.dataset.session === 'professional') return showStep('auth');
        if (modal.dataset.session === 'unverified') {
            document.getElementById('planAuthTitle').textContent = 'Confirme seu e-mail';
            document.getElementById('planAuthText').textContent = 'Verifique seu e-mail antes de solicitar um plano.';
            return showStep('auth');
        }
        if (modal.dataset.session !== 'ready') {
            document.getElementById('planAuthTitle').textContent = 'Não foi possível iniciar';
            document.getElementById('planAuthText').textContent = 'Confira sua conexão e tente novamente.';
            return showStep('auth');
        }
        showStep('payment');
    });
    document.getElementById('planBack')?.addEventListener('click', () => showStep('summary'));
    modal.querySelectorAll('[data-plan-payment]').forEach((button) => button.addEventListener('click', () => {
        paymentMethod = button.dataset.planPayment;
        modal.querySelectorAll('[data-plan-payment]').forEach((item) => item.classList.toggle('active', item === button));
        errorBox.hidden = true;
    }));

    document.getElementById('planConfirm')?.addEventListener('click', async (event) => {
        if (!paymentMethod) {
            errorBox.textContent = 'Escolha uma forma de pagamento para continuar.';
            errorBox.hidden = false;
            return;
        }
        const button = event.currentTarget;
        button.disabled = true;
        button.textContent = 'Registrando...';
        errorBox.hidden = true;
        try {
            const response = await fetch('../../../Controller/api/criar-assinatura-plano.php', {
                method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-Token': csrf },
                body: JSON.stringify({ csrf, plano_id: selectedPlan.id, metodo_pagamento: paymentMethod }),
            });
            const data = await response.json();
            if (!response.ok || !data.ok) throw new Error(data.erro || 'Não foi possível registrar o plano.');
            document.getElementById('planCheckoutReceipt').textContent = `${data.assinatura.codigo} · ${data.assinatura.plano} · ${money(data.assinatura.valor)} · ${data.assinatura.pagamento}`;
            showStep('success');
        } catch (error) {
            errorBox.textContent = error.message || 'Não foi possível registrar o plano. Tente novamente.';
            errorBox.hidden = false;
        } finally {
            button.disabled = false;
            button.textContent = 'Confirmar plano';
        }
    });
});
