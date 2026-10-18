document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('serviceCheckout');
    if (!modal) return;

    const steps = Array.from(modal.querySelectorAll('[data-checkout-step]'));
    const detailsForm = document.getElementById('checkoutDetailsForm');
    const confirmButton = document.getElementById('checkoutConfirm');
    const errorBox = document.getElementById('checkoutError');
    let selectedService = null;
    let selectedPayment = null;
    let csrfToken = '';
    let cepRequest = null;
    let lastCep = '';

    const cepInput = document.getElementById('checkoutCep');
    const cepStatus = document.getElementById('checkoutCepStatus');
    const cepIcon = document.getElementById('checkoutCepIcon');
    const streetInput = document.getElementById('checkoutStreet');
    const districtInput = document.getElementById('checkoutDistrict');
    const cityInput = document.getElementById('checkoutCity');
    const stateInput = document.getElementById('checkoutState');

    // Evita manter dados de um CEP anterior enquanto uma nova busca é feita.
    const clearAddressFromCep = () => {
        streetInput.value = '';
        districtInput.value = '';
        cityInput.value = '';
        stateInput.value = '';
    };

    const setCepStatus = (status, message) => {
        cepStatus.textContent = message;
        cepStatus.dataset.status = status;
        cepIcon.className = status === 'loading'
            ? 'ri-loader-4-line checkout-cep-loading'
            : status === 'success'
                ? 'ri-check-line'
                : status === 'error'
                    ? 'ri-error-warning-line'
                    : 'ri-search-line';
    };

    const findAddressByCep = async () => {
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length !== 8) {
            lastCep = '';
            setCepStatus('idle', 'Digite os 8 números para buscar o endereço.');
            return;
        }
        if (cep === lastCep) return;

        if (lastCep && cep !== lastCep) clearAddressFromCep();
        cepRequest?.abort();
        cepRequest = new AbortController();
        setCepStatus('loading', 'Buscando endereço...');

        try {
            // O ViaCEP devolve logradouro, bairro, cidade e UF a partir dos 8 números.
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`, {
                signal: cepRequest.signal,
                headers: { Accept: 'application/json' }
            });
            if (!response.ok) throw new Error('consulta-indisponivel');
            const address = await response.json();
            if (address.erro) throw new Error('cep-nao-encontrado');

            streetInput.value = address.logradouro || '';
            districtInput.value = address.bairro || '';
            cityInput.value = address.localidade || '';
            stateInput.value = address.uf || '';
            const complementInput = document.getElementById('checkoutComplement');
            if (!complementInput.value && address.complemento) complementInput.value = address.complemento;
            lastCep = cep;
            const location = [address.localidade, address.uf].filter(Boolean).join(' - ') || 'Endereço encontrado';
            setCepStatus('success', `${location}. Informe apenas o número.`);
            const firstIncompleteField = [streetInput, districtInput, cityInput, stateInput].find((input) => !input.value);
            (firstIncompleteField || document.getElementById('checkoutNumber')).focus();
        } catch (error) {
            if (error.name === 'AbortError') return;
            lastCep = '';
            const message = error.message === 'cep-nao-encontrado'
                ? 'CEP não encontrado. Confira os números ou preencha o endereço manualmente.'
                : 'Não foi possível consultar o CEP. Você ainda pode preencher o endereço manualmente.';
            setCepStatus('error', message);
        }
    };

    const money = (value) => Number(value).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

    const localDate = (date) => {
        const offset = date.getTimezoneOffset() * 60000;
        return new Date(date.getTime() - offset).toISOString().slice(0, 10);
    };

    const showStep = (step) => {
        steps.forEach((element) => {
            element.hidden = element.dataset.checkoutStep !== String(step);
        });
        modal.querySelector('.checkout-dialog')?.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const showError = (message) => {
        errorBox.textContent = message;
        errorBox.hidden = false;
    };

    const resetCheckout = () => {
        detailsForm?.reset();
        lastCep = '';
        cepRequest?.abort();
        setCepStatus('idle', 'Digite os 8 números para buscar o endereço.');
        selectedPayment = null;
        errorBox.hidden = true;
        modal.querySelectorAll('.checkout-payment').forEach((button) => button.classList.remove('active'));
        if (confirmButton) {
            confirmButton.disabled = false;
            confirmButton.textContent = 'Confirmar agendamento';
        }
    };

    const closeCheckout = () => {
        modal.hidden = true;
        document.body.classList.remove('checkout-open');
        resetCheckout();
    };

    const prepareService = (button) => {
        selectedService = {
            id: Number(button.dataset.serviceId),
            name: button.dataset.serviceName,
            price: Number(button.dataset.servicePrice),
            period: button.dataset.servicePeriod
        };
        document.getElementById('checkoutServiceName').textContent = selectedService.name;
        document.getElementById('checkoutSummaryService').textContent = selectedService.name;
        document.getElementById('checkoutSummaryPeriod').textContent = selectedService.period;
        document.getElementById('checkoutSummaryPrice').textContent = money(selectedService.price);
        document.getElementById('checkoutDuration').textContent = `Duração prevista: ${selectedService.period}.`;
        document.getElementById('checkoutPaymentService').textContent = selectedService.name;
        document.getElementById('checkoutPaymentPrice').textContent = money(selectedService.price);

        const dateInput = document.getElementById('checkoutDate');
        const today = new Date();
        const lastDay = new Date();
        lastDay.setDate(lastDay.getDate() + 180);
        dateInput.min = localDate(today);
        dateInput.max = localDate(lastDay);
    };

    const checkSession = async () => {
        try {
            const response = await fetch('../../../Controller/api/sessao.php', {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin'
            });
            const data = await response.json();
            csrfToken = data.csrf || '';
            if (!data.logado) {
                document.getElementById('checkoutAuthTitle').textContent = 'Entre para agendar';
                document.getElementById('checkoutAuthText').textContent = 'O agendamento está disponível para contas de cliente.';
                showStep('auth');
                return;
            }
            if (data.usuario?.tipo !== 'cliente') {
                document.getElementById('checkoutAuthTitle').textContent = 'Use uma conta de cliente';
                document.getElementById('checkoutAuthText').textContent = 'Contas profissionais não podem contratar serviços.';
                showStep('auth');
                return;
            }
            showStep(1);
        } catch (error) {
            document.getElementById('checkoutAuthTitle').textContent = 'Não foi possível iniciar';
            document.getElementById('checkoutAuthText').textContent = 'Confira sua conexão e tente novamente.';
            showStep('auth');
        }
    };

    document.querySelectorAll('.service-book-button').forEach((button) => {
        button.addEventListener('click', async () => {
            resetCheckout();
            prepareService(button);
            document.getElementById('checkoutAuthTitle').textContent = 'Verificando sua sessão...';
            document.getElementById('checkoutAuthText').textContent = 'Só um instante.';
            showStep('auth');
            modal.hidden = false;
            document.body.classList.add('checkout-open');
            await checkSession();
        });
    });

    modal.querySelectorAll('[data-checkout-close]').forEach((button) => {
        button.addEventListener('click', closeCheckout);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.hidden) closeCheckout();
    });

    cepInput?.addEventListener('input', (event) => {
        const digits = event.target.value.replace(/\D/g, '').slice(0, 8);
        event.target.value = digits.length > 5 ? `${digits.slice(0, 5)}-${digits.slice(5)}` : digits;
        if (digits.length === 8) findAddressByCep();
        else {
            if (lastCep) clearAddressFromCep();
            lastCep = '';
            cepRequest?.abort();
            setCepStatus('idle', 'Digite os 8 números para buscar o endereço.');
        }
    });

    cepInput?.addEventListener('blur', findAddressByCep);

    document.getElementById('checkoutState')?.addEventListener('input', (event) => {
        event.target.value = event.target.value.replace(/[^a-z]/gi, '').slice(0, 2).toUpperCase();
    });

    detailsForm?.addEventListener('submit', (event) => {
        event.preventDefault();
        if (!detailsForm.checkValidity()) {
            detailsForm.reportValidity();
            return;
        }
        showStep(2);
    });

    document.getElementById('checkoutBack')?.addEventListener('click', () => showStep(1));

    modal.querySelectorAll('.checkout-payment').forEach((button) => {
        button.addEventListener('click', () => {
            modal.querySelectorAll('.checkout-payment').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            selectedPayment = button.dataset.payment;
            errorBox.hidden = true;
        });
    });

    const errorMessages = {
        'sessao-expirada': 'Sua sessão expirou. Entre novamente para continuar.',
        'apenas-clientes': 'O agendamento está disponível apenas para contas de cliente.',
        'servico-invalido': 'O serviço selecionado não está mais disponível.',
        'data-invalida': 'Escolha uma data válida dentro dos próximos seis meses.',
        'horario-invalido': 'Esse horário não comporta a duração completa do serviço.',
        'quantidade-invalida': 'Revise a quantidade de quartos e banheiros.',
        'endereco-invalido': 'Preencha corretamente todos os campos obrigatórios do endereço.',
        'pagamento-invalido': 'Escolha uma forma de pagamento.',
        'sem-profissional': 'Não há profissional disponível para esse serviço no horário escolhido.',
        servidor: 'Não foi possível registrar o pedido agora. Tente novamente.'
    };

    confirmButton?.addEventListener('click', async () => {
        if (!selectedPayment) {
            showError('Escolha uma forma de pagamento.');
            return;
        }

        const payload = {
            servico_id: selectedService.id,
            data: document.getElementById('checkoutDate').value,
            hora: document.getElementById('checkoutTime').value,
            quartos: Number(document.getElementById('checkoutRooms').value),
            banheiros: Number(document.getElementById('checkoutBathrooms').value),
            cep: document.getElementById('checkoutCep').value,
            cidade: document.getElementById('checkoutCity').value,
            estado: document.getElementById('checkoutState').value,
            rua: document.getElementById('checkoutStreet').value,
            numero: document.getElementById('checkoutNumber').value,
            bairro: document.getElementById('checkoutDistrict').value,
            complemento: document.getElementById('checkoutComplement').value,
            metodo_pagamento: selectedPayment
        };

        confirmButton.disabled = true;
        confirmButton.textContent = 'Registrando...';
        errorBox.hidden = true;

        try {
            const response = await fetch('../../../Controller/api/criar-contratacao.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok || !data.ok) throw new Error(data.erro || 'servidor');

            const pedido = data.pedido;
            const formattedDate = new Date(`${pedido.data}T12:00:00`).toLocaleDateString('pt-BR');
            const details = document.getElementById('checkoutSuccessDetails');
            details.replaceChildren();
            [
                ['Pedido', pedido.codigo],
                ['Serviço', pedido.servico],
                ['Profissional', pedido.profissional],
                ['Data', `${formattedDate} às ${pedido.hora}`],
                ['Pagamento', pedido.pagamento],
                ['Valor', money(pedido.valor)]
            ].forEach(([label, value]) => {
                const line = document.createElement('div');
                line.className = 'checkout-summary-line';
                const text = document.createElement('span');
                const strong = document.createElement('strong');
                text.textContent = label;
                strong.textContent = value;
                line.append(text, strong);
                details.appendChild(line);
            });
            showStep(3);
        } catch (error) {
            showError(errorMessages[error.message] || errorMessages.servidor);
        } finally {
            confirmButton.disabled = false;
            confirmButton.textContent = 'Confirmar agendamento';
        }
    });
});
