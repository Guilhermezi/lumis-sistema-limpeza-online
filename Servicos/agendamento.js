/* ============================================
   agendamento.js - Modal de agendamento (página Serviços)
   - Configuração por categoria (residencial/comercial/estofados/vidros)
   - Calendário com múltiplas datas, steppers, tipo, adicionais e localização
   - Carrinho com cálculo automático de preço e finalização com pagamento
   INTEGRAÇÃO BACKEND:
   O pedido completo é montado em montarPedido() e fica em window.LumisUltimoPedido.
   No passo de confirmação (confirmar()), substitua a simulação por um
   fetch() para o seu endpoint, ex.:
   fetch('/api/agendamentos', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(pedido) })
   ============================================ */
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ================================================
       CONFIGURAÇÃO POR CATEGORIA (fácil de manter/extender)
       extra = multiplicador sobre o preço base (tipo)
       preco = valor fixo (adicionais) | extra = valor por unidade (steppers)
       ================================================ */
    var CONFIG = {
        residencial: {
            steppers: [
                { id: 'quartos', icone: 'ri-bed-line', min: 1, max: 12, extra: 15,
                  label: { pt: 'Quartos', en: 'Bedrooms', es: 'Dormitorios' },
                  extraLabel: { pt: 'Quartos extras', en: 'Extra bedrooms', es: 'Dormitorios extra' } },
                { id: 'banheiros', icone: 'ri-shower-line', min: 1, max: 8, extra: 10,
                  label: { pt: 'Banheiros', en: 'Bathrooms', es: 'Baños' },
                  extraLabel: { pt: 'Banheiros extras', en: 'Extra bathrooms', es: 'Baños extra' } }
            ],
            tipo: {
                icone: 'ri-home-5-line',
                label: { pt: 'Tipo de imóvel', en: 'Property type', es: 'Tipo de inmueble' },
                options: [
                    { id: 'casa',        label: { pt: 'Casa', en: 'House', es: 'Casa' }, extra: 0 },
                    { id: 'apartamento', label: { pt: 'Apartamento', en: 'Apartment', es: 'Apartamento' }, extra: 0 },
                    { id: 'studio',      label: { pt: 'Studio', en: 'Studio', es: 'Estudio' }, extra: 0 },
                    { id: 'cobertura',   label: { pt: 'Cobertura', en: 'Penthouse', es: 'Ático' }, extra: 0.15 },
                    { id: 'casa-quintal', label: { pt: 'Casa com quintal', en: 'House with backyard', es: 'Casa con patio' }, extra: 0.2 }
                ]
            },
            adicionais: [
                { id: 'geladeira', label: { pt: 'Geladeira e freezer', en: 'Fridge and freezer', es: 'Refrigerador y congelador' }, preco: 25 },
                { id: 'forno',     label: { pt: 'Forno e cooktop', en: 'Oven and cooktop', es: 'Horno y encimera' }, preco: 30 },
                { id: 'varanda',   label: { pt: 'Varanda/sacada', en: 'Balcony', es: 'Balcón' }, preco: 20 },
                { id: 'sofas',     label: { pt: 'Higienização de sofás', en: 'Sofa cleaning', es: 'Limpieza de sofás' }, preco: 50 },
                { id: 'janelas',   label: { pt: 'Janelas internas', en: 'Indoor windows', es: 'Ventanas interiores' }, preco: 25 }
            ]
        },

        comercial: {
            empresa: true,
            steppers: [
                { id: 'salas', icone: 'ri-building-2-line', min: 1, max: 20, extra: 20,
                  label: { pt: 'Salas / Ambientes', en: 'Rooms / Areas', es: 'Salas / Áreas' },
                  extraLabel: { pt: 'Salas extras', en: 'Extra rooms', es: 'Salas extra' } },
                { id: 'banheiros', icone: 'ri-shower-line', min: 1, max: 10, extra: 10,
                  label: { pt: 'Banheiros', en: 'Bathrooms', es: 'Baños' },
                  extraLabel: { pt: 'Banheiros extras', en: 'Extra bathrooms', es: 'Baños extra' } }
            ],
            tipo: {
                icone: 'ri-store-2-line',
                label: { pt: 'Tipo de estabelecimento', en: 'Business type', es: 'Tipo de establecimiento' },
                options: [
                    { id: 'escritorio',  label: { pt: 'Escritório', en: 'Office', es: 'Oficina' }, extra: 0 },
                    { id: 'loja',        label: { pt: 'Loja', en: 'Store', es: 'Tienda' }, extra: 0 },
                    { id: 'clinica',     label: { pt: 'Clínica / Salão', en: 'Clinic / Salon', es: 'Clínica / Salón' }, extra: 0.1 },
                    { id: 'restaurante', label: { pt: 'Restaurante', en: 'Restaurant', es: 'Restaurante' }, extra: 0.15 },
                    { id: 'galpao',      label: { pt: 'Galpão / Depósito', en: 'Warehouse', es: 'Almacén' }, extra: 0.2 }
                ]
            },
            adicionais: [
                { id: 'copa',     label: { pt: 'Higienização da copa', en: 'Break room cleaning', es: 'Limpieza de la sala de descanso' }, preco: 20 },
                { id: 'recepcao', label: { pt: 'Recepção VIP', en: 'VIP reception', es: 'Recepción VIP' }, preco: 25 },
                { id: 'vidros',   label: { pt: 'Vidros e fachadas', en: 'Windows and facades', es: 'Vidrios y fachadas' }, preco: 40 },
                { id: 'carpete',  label: { pt: 'Higienização de carpetes', en: 'Carpet cleaning', es: 'Limpieza de alfombras' }, preco: 60 },
                { id: 'residuos', label: { pt: 'Descarte de resíduos', en: 'Waste disposal', es: 'Eliminación de residuos' }, preco: 30 }
            ]
        },

        estofados: {
            steppers: [
                { id: 'pecas', icone: 'ri-sofa-line', min: 1, max: 20, extra: 25,
                  label: { pt: 'Quantidade de peças', en: 'Number of pieces', es: 'Cantidad de piezas' },
                  extraLabel: { pt: 'Peças extras', en: 'Extra pieces', es: 'Piezas extra' } }
            ],
            tipo: {
                icone: 'ri-archive-line',
                label: { pt: 'Tipo de peça', en: 'Piece type', es: 'Tipo de pieza' },
                options: [
                    { id: 'sofa',    label: { pt: 'Sofá', en: 'Sofa', es: 'Sofá' }, extra: 0 },
                    { id: 'poltrona', label: { pt: 'Poltrona', en: 'Armchair', es: 'Poltrona' }, extra: 0 },
                    { id: 'colchao', label: { pt: 'Colchão', en: 'Mattress', es: 'Colchón' }, extra: 0 },
                    { id: 'tapete',  label: { pt: 'Tapete', en: 'Rug', es: 'Alfombra' }, extra: 0.1 }
                ]
            },
            adicionais: [
                { id: 'impermeabilizacao', label: { pt: 'Impermeabilização', en: 'Waterproofing', es: 'Impermeabilización' }, preco: 45 },
                { id: 'odores', label: { pt: 'Eliminação de odores', en: 'Odor removal', es: 'Eliminación de olores' }, preco: 30 },
                { id: 'acaro',  label: { pt: 'Higienização anti-ácaro', en: 'Anti-mite cleaning', es: 'Limpieza antiácaros' }, preco: 35 }
            ]
        },

        vidros: {
            steppers: [
                { id: 'ambientes', icone: 'ri-window-2-line', min: 1, max: 15, extra: 20,
                  label: { pt: 'Quantidade de ambientes', en: 'Number of areas', es: 'Cantidad de ambientes' },
                  extraLabel: { pt: 'Ambientes extras', en: 'Extra areas', es: 'Ambientes extra' } }
            ],
            tipo: {
                icone: 'ri-focus-3-line',
                label: { pt: 'Tipo de vidro', en: 'Glass type', es: 'Tipo de vidrio' },
                options: [
                    { id: 'janelas', label: { pt: 'Janelas', en: 'Windows', es: 'Ventanas' }, extra: 0 },
                    { id: 'portas',  label: { pt: 'Portas de vidro', en: 'Glass doors', es: 'Puertas de vidrio' }, extra: 0 },
                    { id: 'box',     label: { pt: 'Box de banheiro', en: 'Shower enclosure', es: 'Box de baño' }, extra: 0.1 },
                    { id: 'fachada', label: { pt: 'Fachada de vidro', en: 'Glass facade', es: 'Fachada de vidrio' }, extra: 0.2 }
                ]
            },
            adicionais: [
                { id: 'altura',  label: { pt: 'Trabalho em altura', en: 'High-rise work', es: 'Trabajo en altura' }, preco: 50 },
                { id: 'andaime', label: { pt: 'Necessita andaime', en: 'Needs scaffolding', es: 'Requiere andamio' }, preco: 70 },
                { id: 'maquina', label: { pt: 'Máquina específica', en: 'Specific machine', es: 'Máquina específica' }, preco: 40 }
            ]
        }
    };

    /* ================================================
       TEXTOS GENÉRICOS (traduções)
       ================================================ */
    var GERAL = {
        steppersTitulo:  { pt: 'Dimensões do ambiente', en: 'Property size', es: 'Tamaño del ambiente' },
        adicionaisTitulo: { pt: 'Adicionais de limpeza', en: 'Cleaning add-ons', es: 'Extras de limpieza' },
        tipoDefault:      { pt: 'Selecione uma opção', en: 'Select an option', es: 'Selecciona una opción' },
        porUnidade:       { pt: 'por unidade extra', en: 'per extra unit', es: 'por unidad extra' },
        dias:             { pt: 'dia(s)', en: 'day(s)', es: 'día(s)' },
        vazio:            { pt: 'Nenhum item ainda. Escolha datas e adicionais para ver o valor.', en: 'Nothing here yet. Pick dates and add-ons to see the price.', es: 'Nada todavía. Elige fechas y extras para ver el precio.' },
        erroDatas:        { pt: 'Selecione pelo menos uma data.', en: 'Select at least one date.', es: 'Selecciona al menos una fecha.' },
        erroEndereco:     { pt: 'Informe endereço e número.', en: 'Enter address and number.', es: 'Informa dirección y número.' },
        erroCidade:       { pt: 'Informe a cidade.', en: 'Enter the city.', es: 'Informa la ciudad.' },
        erroMetodo:       { pt: 'Selecione um método de pagamento.', en: 'Select a payment method.', es: 'Selecciona un método de pago.' },
        erroCartao:       { pt: 'Preencha os dados do cartão.', en: 'Fill in the card details.', es: 'Completa los datos de la tarjeta.' },
        pixInstr:         { pt: 'Escaneie o QR Code ou copie o código abaixo para pagar com Pix.', en: 'Scan the QR code or copy the code below to pay with Pix.', es: 'Escanea el código QR o copia el código para pagar con Pix.' },
        pixCopiar:        { pt: 'Copiar código', en: 'Copy code', es: 'Copiar código' },
        pixCopiado:       { pt: 'Código copiado!', en: 'Code copied!', es: '¡Código copiado!' },
        cartaoNum:        { pt: 'Número do cartão', en: 'Card number', es: 'Número de tarjeta' },
        cartaoNome:       { pt: 'Nome impresso no cartão', en: 'Name on card', es: 'Nombre en la tarjeta' },
        cartaoVal:        { pt: 'Validade (MM/AA)', en: 'Expiry (MM/YY)', es: 'Vencimiento (MM/AA)' },
        cartaoParcelas:   { pt: 'Parcelas', en: 'Installments', es: 'Cuotas' },
        boletoInstr:      { pt: 'Você receberá o boleto por e-mail assim que o agendamento for confirmado.', en: 'You will receive the bank slip by email once the booking is confirmed.', es: 'Recibirás el boleto por correo una vez confirmada la reserva.' },
        boletoGerar:      { pt: 'Gerar boleto', en: 'Generate slip', es: 'Generar boleto' },
        naHoraInstr:      { pt: 'Você paga no dia do serviço, em dinheiro ou cartão, diretamente ao profissional. Nossa equipe confirmará o agendamento.', en: 'Pay on the service day, cash or card, directly to the professional. Our team will confirm the booking.', es: 'Pagas el día del servicio, en efectivo o tarjeta, directamente al profesional. Nuestro equipo confirmará la reserva.' },
        enviando:         { pt: 'Enviando...', en: 'Sending...', es: 'Enviando...' },
        confirmar:        { pt: 'Confirmar agendamento', en: 'Confirm booking', es: 'Confirmar reserva' },
        codigoPedido:     { pt: 'Código do pedido', en: 'Order code', es: 'Código del pedido' },
        removido:         { pt: 'Remover', en: 'Remove', es: 'Quitar' },
        semana: {
            pt: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            en: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            es: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
        }
    };

    /* ================================================
       ESTADO
       ================================================ */
    var modal = document.getElementById('agModal');
    var cardAtual = null;
    var cfgAtual = null;
    var precoBase = 0;
    var nomeServico = '';
    var datas = {};
    var stepperValores = {};
    var tipoId = '';
    var adicionaisAtivos = {};
    var metodo = '';
    var viewAno = new Date().getFullYear();
    var viewMes = new Date().getMonth();

    var erroMsg = null;
    var erroPag = null;

    /* ================================================
       HELPERS
       ================================================ */
    function lang() {
        return (window.LumisI18n && window.LumisI18n.idiomaAtual) ? window.LumisI18n.idiomaAtual() : 'pt';
    }

    function locale() {
        var l = lang();
        return l === 'pt' ? 'pt-BR' : l === 'es' ? 'es-ES' : 'en-US';
    }

    function L(obj) {
        return (obj && (obj[lang()] || obj.pt)) || '';
    }

    function fmt(v) {
        return 'R$ ' + v.toFixed(2).replace('.', ',');
    }

    function isoDe(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    function fmtData(iso) {
        var p = iso.split('-');
        var d = new Date(+p[0], +p[1] - 1, +p[2]);
        return d.toLocaleDateString(locale(), { weekday: 'short', day: '2-digit', month: 'short' });
    }

    function gerarCodigo() {
        return 'LUM-' + Math.random().toString(36).slice(2, 8).toUpperCase();
    }

    function gerarPix() {
        var s = '00020126';
        for (var i = 0; i < 28; i++) s += Math.floor(Math.random() * 10);
        s += '0000' + Math.random().toString(36).slice(2, 10) + '52040000';
        return s;
    }

    function gerarBoleto() {
        var s = '';
        for (var i = 0; i < 5; i++) {
            for (var j = 0; j < 9; j++) s += Math.floor(Math.random() * 10);
            s += i < 4 ? '.' : ' ';
        }
        s += ' ' + Math.floor(Math.random() * 1000000) + ' ' + Math.floor(Math.random() * 90 + 10);
        return s;
    }

    /* ================================================
       ABRIR / FECHAR
       ================================================ */
    function resetEstado() {
        datas = {};
        stepperValores = {};
        tipoId = '';
        adicionaisAtivos = {};
        metodo = '';
        var ids = ['agCep', 'agCidade', 'agEndereco', 'agNumero', 'agComplemento', 'agObservacoes'];
        ids.forEach(function (id) {
            var el = document.getElementById(id);
            if (el) {
                el.value = '';
                el.classList.remove('ag-erro');
            }
        });
        if (erroMsg) { erroMsg.classList.remove('visivel'); erroMsg.textContent = ''; }
        if (erroPag) { erroPag.classList.remove('visivel'); erroPag.textContent = ''; }
    }

    function abrirModal(card) {
        cardAtual = card;
        var cfgKey = card.getAttribute('data-categoria');
        cfgAtual = CONFIG[cfgKey] || CONFIG.residencial;
        precoBase = parseInt(card.getAttribute('data-preco'), 10) || 0;
        nomeServico = card.querySelector('.cartao-corpo h3') ? card.querySelector('.cartao-corpo h3').textContent.trim() : '';

        resetEstado();

        var hoje = new Date();
        viewAno = hoje.getFullYear();
        viewMes = hoje.getMonth();

        document.getElementById('agServicoNome').textContent = nomeServico;
        document.getElementById('agEmpresaCta').hidden = !cfgAtual.empresa;

        montarSteppers();
        montarTipo();
        montarAdicionais();
        renderizarSemana();
        renderizarCalendario();
        renderizarCarrinho();
        mostrarPasso(1);

        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        var fechar = modal.querySelector('.ag-fechar');
        if (fechar) fechar.focus();
    }

    function fecharModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    function mostrarPasso(n) {
        document.querySelectorAll('.ag-passo').forEach(function (p) {
            p.hidden = p.getAttribute('data-passo') !== String(n);
        });
    }

    function renderizarSemana() {
        var dias = L(GERAL.semana);
        document.querySelectorAll('.ag-calendario-semana span').forEach(function (el, i) {
            if (dias[i]) el.textContent = dias[i];
        });
    }

    /* ================================================
       CALENDÁRIO (múltiplas datas)
       ================================================ */
    function renderizarCalendario() {
        var grid = document.getElementById('agCalendarioGrade');
        grid.innerHTML = '';
        var primeiro = new Date(viewAno, viewMes, 1);
        var inicioSemana = primeiro.getDay();
        var diasNoMes = new Date(viewAno, viewMes + 1, 0).getDate();
        var hojeIso = isoDe(new Date());

        document.getElementById('agCalendarioTitulo').textContent =
            primeiro.toLocaleDateString(locale(), { month: 'long', year: 'numeric' });

        for (var i = 0; i < inicioSemana; i++) {
            var vazio = document.createElement('span');
            vazio.className = 'ag-dia ag-dia-outro';
            grid.appendChild(vazio);
        }

        for (var dia = 1; dia <= diasNoMes; dia++) {
            var iso = isoDe(new Date(viewAno, viewMes, dia));
            var botao = document.createElement('button');
            botao.type = 'button';
            botao.className = 'ag-dia';
            botao.textContent = dia;
            if (iso === hojeIso) botao.classList.add('ag-dia-hoje');
            if (iso < hojeIso) botao.classList.add('ag-dia-desabilitado');
            if (datas[iso]) botao.classList.add('ag-dia-selecionado');
            (function (dataIso) {
                botao.addEventListener('click', function () { alternarData(dataIso); });
            })(iso);
            grid.appendChild(botao);
        }

        renderizarChips();
    }

    function alternarData(iso) {
        if (iso < isoDe(new Date())) return;
        if (datas[iso]) {
            delete datas[iso];
        } else {
            datas[iso] = true;
        }
        renderizarCalendario();
        renderizarCarrinho();
    }

    function renderizarChips() {
        var cont = document.getElementById('agDatasLista');
        cont.innerHTML = '';
        var chaves = Object.keys(datas).sort();
        chaves.forEach(function (iso) {
            var chip = document.createElement('span');
            chip.className = 'ag-data-chip';
            chip.textContent = fmtData(iso);
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('aria-label', L(GERAL.removido));
            btn.innerHTML = '&times;';
            (function (dataIso) {
                btn.addEventListener('click', function () { alternarData(dataIso); });
            })(iso);
            chip.appendChild(btn);
            cont.appendChild(chip);
        });
    }

    /* ================================================
       CONFIGURAÇÕES (steppers / tipo / adicionais)
       ================================================ */
    function montarSteppers() {
        var cont = document.getElementById('agSteppers');
        cont.innerHTML = '';
        if (!cfgAtual.steppers || !cfgAtual.steppers.length) {
            cont.style.display = 'none';
            return;
        }
        cont.style.display = '';

        var titulo = document.createElement('h3');
        titulo.innerHTML = '<i class="ri-resize-line"></i> <span>' + L(GERAL.steppersTitulo) + '</span>';
        cont.appendChild(titulo);

        cfgAtual.steppers.forEach(function (s) {
            if (stepperValores[s.id] === undefined) stepperValores[s.id] = s.min;

            var linha = document.createElement('div');
            linha.className = 'ag-stepper';

            var rotulo = document.createElement('div');
            rotulo.className = 'ag-stepper-rotulo';
            rotulo.innerHTML = '<i class="' + s.icone + '"></i><div>' + L(s.label) +
                '<small>+ ' + fmt(s.extra) + ' ' + L(GERAL.porUnidade) + '</small></div>';

            var controle = document.createElement('div');
            controle.className = 'ag-stepper-controle';

            var bMenos = document.createElement('button');
            bMenos.type = 'button';
            bMenos.textContent = '−';
            bMenos.addEventListener('click', function () {
                if (stepperValores[s.id] > s.min) {
                    stepperValores[s.id]--;
                    montarSteppers();
                    renderizarCarrinho();
                }
            });

            var valor = document.createElement('span');
            valor.className = 'ag-stepper-valor';
            valor.textContent = stepperValores[s.id];

            var bMais = document.createElement('button');
            bMais.type = 'button';
            bMais.textContent = '+';
            bMais.addEventListener('click', function () {
                if (stepperValores[s.id] < s.max) {
                    stepperValores[s.id]++;
                    montarSteppers();
                    renderizarCarrinho();
                }
            });

            controle.appendChild(bMenos);
            controle.appendChild(valor);
            controle.appendChild(bMais);
            linha.appendChild(rotulo);
            linha.appendChild(controle);
            cont.appendChild(linha);
        });
    }

    function montarTipo() {
        var cont = document.getElementById('agTipoBloco');
        cont.innerHTML = '';

        var titulo = document.createElement('h3');
        titulo.innerHTML = '<i class="' + cfgAtual.tipo.icone + '"></i> <span>' + L(cfgAtual.tipo.label) + '</span>';
        cont.appendChild(titulo);

        var sel = document.createElement('select');
        sel.className = 'ag-selecao';

        var opDefault = document.createElement('option');
        opDefault.value = '';
        opDefault.disabled = true;
        opDefault.selected = tipoId === '';
        opDefault.textContent = L(GERAL.tipoDefault);
        sel.appendChild(opDefault);

        cfgAtual.tipo.options.forEach(function (op) {
            var o = document.createElement('option');
            o.value = op.id;
            o.textContent = L(op.label) + (op.extra > 0 ? ' (+' + Math.round(op.extra * 100) + '%)' : '');
            if (tipoId === op.id) o.selected = true;
            sel.appendChild(o);
        });

        sel.addEventListener('change', function () {
            tipoId = this.value;
            renderizarCarrinho();
        });

        cont.appendChild(sel);
    }

    function montarAdicionais() {
        var cont = document.getElementById('agAdicionais');
        cont.innerHTML = '';

        var titulo = document.createElement('h3');
        titulo.innerHTML = '<i class="ri-sparkling-2-line"></i> <span>' + L(GERAL.adicionaisTitulo) + '</span>';
        cont.appendChild(titulo);

        var grid = document.createElement('div');
        grid.className = 'ag-adicionais-grid';

        cfgAtual.adicionais.forEach(function (a) {
            var label = document.createElement('label');
            label.className = 'ag-adicional';
            if (adicionaisAtivos[a.id]) label.classList.add('ativo');

            var cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.checked = !!adicionaisAtivos[a.id];
            cb.addEventListener('change', function () {
                if (cb.checked) {
                    adicionaisAtivos[a.id] = true;
                } else {
                    delete adicionaisAtivos[a.id];
                }
                label.classList.toggle('ativo', cb.checked);
                renderizarCarrinho();
            });

            var txt = document.createElement('span');
            txt.textContent = L(a.label);

            var preco = document.createElement('span');
            preco.className = 'ag-adicional-preco';
            preco.textContent = fmt(a.preco);

            label.appendChild(cb);
            label.appendChild(txt);
            label.appendChild(preco);
            grid.appendChild(label);
        });

        cont.appendChild(grid);
    }

    /* ================================================
       CÁLCULO E CARRINHO
       ================================================ */
    function calcular() {
        var porDia = precoBase;
        var linhas = [{ rotulo: nomeServico, valor: precoBase }];

        if (tipoId) {
            var t = cfgAtual.tipo.options.filter(function (o) { return o.id === tipoId; })[0];
            if (t && t.extra > 0) {
                var vTipo = precoBase * t.extra;
                porDia += vTipo;
                linhas.push({ rotulo: L(t.label) + ' (+' + Math.round(t.extra * 100) + '%)', valor: vTipo });
            }
        }

        (cfgAtual.steppers || []).forEach(function (s) {
            var extra = Math.max(0, (stepperValores[s.id] || s.min) - s.min);
            if (extra > 0) {
                var v = extra * s.extra;
                porDia += v;
                linhas.push({ rotulo: '+ ' + extra + ' ' + L(s.extraLabel), valor: v });
            }
        });

        cfgAtual.adicionais.forEach(function (a) {
            if (adicionaisAtivos[a.id]) {
                porDia += a.preco;
                linhas.push({ rotulo: L(a.label), valor: a.preco });
            }
        });

        var numDatas = Object.keys(datas).length;
        return {
            porDia: porDia,
            numDatas: numDatas,
            total: porDia * numDatas,
            linhas: linhas
        };
    }

    function renderizarCarrinho() {
        var cont = document.getElementById('agCarrinhoItens');
        cont.innerHTML = '';
        var c = calcular();

        if (c.numDatas === 0) {
            var vazio = document.createElement('p');
            vazio.className = 'ag-carrinho-vazio';
            vazio.textContent = L(GERAL.vazio);
            cont.appendChild(vazio);
        } else {
            c.linhas.forEach(function (l) {
                var div = document.createElement('div');
                div.className = 'ag-carrinho-linha';
                var span = document.createElement('span');
                span.textContent = l.rotulo;
                var b = document.createElement('b');
                b.textContent = fmt(l.valor);
                div.appendChild(span);
                div.appendChild(b);
                cont.appendChild(div);
            });

            var resumo = document.createElement('div');
            resumo.className = 'ag-carrinho-linha resumo-dia';
            var rs = document.createElement('span');
            rs.textContent = c.numDatas + ' ' + L(GERAL.dias) + ' × ' + fmt(c.porDia);
            var rb = document.createElement('b');
            rb.textContent = fmt(c.total);
            resumo.appendChild(rs);
            resumo.appendChild(rb);
            cont.appendChild(resumo);
        }

        document.getElementById('agTotal').textContent = fmt(c.total);
    }

    /* ================================================
       VALIDAÇÃO (passo 1 → passo 2)
       ================================================ */
    function mostrarErro(msg) {
        if (erroMsg) {
            erroMsg.textContent = msg;
            erroMsg.classList.add('visivel');
        }
    }

    function limparErro() {
        if (erroMsg) {
            erroMsg.textContent = '';
            erroMsg.classList.remove('visivel');
        }
    }

    function mostrarErroPag(msg) {
        if (erroPag) {
            erroPag.textContent = msg;
            erroPag.classList.add('visivel');
        }
    }

    function limparErroPag() {
        if (erroPag) {
            erroPag.textContent = '';
            erroPag.classList.remove('visivel');
        }
    }

    function validarPasso1() {
        limparErro();
        var c = calcular();
        if (c.numDatas === 0) {
            mostrarErro(L(GERAL.erroDatas));
            return false;
        }
        var endereco = document.getElementById('agEndereco').value.trim();
        var numero = document.getElementById('agNumero').value.trim();
        var cidade = document.getElementById('agCidade').value.trim();
        var ok = true;
        ['agEndereco', 'agNumero'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el.value.trim() === '') { el.classList.add('ag-erro'); ok = false; }
        });
        var elCidade = document.getElementById('agCidade');
        if (cidade === '') { elCidade.classList.add('ag-erro'); ok = false; }
        if (!ok) {
            mostrarErro(endereco === '' || numero === '' ? L(GERAL.erroEndereco) : L(GERAL.erroCidade));
            return false;
        }
        return true;
    }

    /* ================================================
       PAGAMENTO
       ================================================ */
    function selecionarMetodo(m) {
        metodo = m;
        limparErroPag();
        document.querySelectorAll('.ag-pag-opcao').forEach(function (o) {
            o.classList.toggle('ativo', o.getAttribute('data-metodo') === m);
        });
        renderizarPagDetalhes();
    }

    function renderizarPagDetalhes() {
        var cont = document.getElementById('agPagDetalhes');
        cont.innerHTML = '';

        if (metodo === 'pix') {
            var box = document.createElement('div');
            box.className = 'ag-pix-box';
            var qr = document.createElement('div');
            qr.className = 'ag-qr';
            for (var i = 0; i < 441; i++) {
                var cel = document.createElement('span');
                cel.className = 'ag-qr-c' + (Math.random() > 0.45 ? ' cheio' : '');
                qr.appendChild(cel);
            }
            var info = document.createElement('div');
            info.className = 'ag-pix-info';
            var p = document.createElement('p');
            p.textContent = L(GERAL.pixInstr);
            info.appendChild(p);
            var codigo = document.createElement('div');
            codigo.className = 'ag-pix-codigo';
            var code = document.createElement('code');
            code.textContent = gerarPix();
            var copiar = document.createElement('button');
            copiar.type = 'button';
            copiar.className = 'ag-pix-copiar';
            copiar.textContent = L(GERAL.pixCopiar);
            copiar.addEventListener('click', function () {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(code.textContent);
                }
                copiar.classList.add('copiado');
                copiar.textContent = L(GERAL.pixCopiado);
            });
            codigo.appendChild(code);
            codigo.appendChild(copiar);
            info.appendChild(codigo);
            box.appendChild(qr);
            box.appendChild(info);
            cont.appendChild(box);
        } else if (metodo === 'cartao') {
            var c = calcular();
            var grid = document.createElement('div');
            grid.className = 'ag-cartao-grid';
            grid.innerHTML =
                '<input class="ag-campo ag-span-2" id="agCartaoNum" placeholder="' + L(GERAL.cartaoNum) + '" inputmode="numeric">' +
                '<input class="ag-campo ag-span-2" id="agCartaoNome" placeholder="' + L(GERAL.cartaoNome) + '">' +
                '<input class="ag-campo" id="agCartaoVal" placeholder="' + L(GERAL.cartaoVal) + '" inputmode="numeric">' +
                '<input class="ag-campo" id="agCartaoCvv" placeholder="CVV" inputmode="numeric">';
            var sel = document.createElement('select');
            sel.className = 'ag-selecao ag-span-2';
            sel.id = 'agCartaoParcelas';
            for (var n = 1; n <= 12; n++) {
                var o = document.createElement('option');
                o.value = n;
                o.textContent = n + 'x ' + L(GERAL.cartaoParcelas) + ' — R$ ' + (c.total / n).toFixed(2).replace('.', ',');
                sel.appendChild(o);
            }
            grid.appendChild(sel);
            cont.appendChild(grid);

            var num = document.getElementById('agCartaoNum');
            var val = document.getElementById('agCartaoVal');
            var cvv = document.getElementById('agCartaoCvv');
            num.addEventListener('input', function () {
                var v = this.value.replace(/\D/g, '').slice(0, 16);
                this.value = v.replace(/(\d{4})(?=\d)/g, '$1 ');
            });
            val.addEventListener('input', function () {
                var v = this.value.replace(/\D/g, '').slice(0, 4);
                this.value = v.length > 2 ? v.slice(0, 2) + '/' + v.slice(2) : v;
            });
            cvv.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 4);
            });
        } else if (metodo === 'boleto') {
            var bBox = document.createElement('div');
            bBox.className = 'ag-boleto-box';
            var bp = document.createElement('p');
            bp.textContent = L(GERAL.boletoInstr);
            var bBtn = document.createElement('button');
            bBtn.type = 'button';
            bBtn.className = 'ag-pix-copiar';
            bBtn.textContent = L(GERAL.boletoGerar);
            bBtn.addEventListener('click', function () {
                if (!bBox.querySelector('.ag-boleto-codigo')) {
                    var cod = document.createElement('span');
                    cod.className = 'ag-boleto-codigo';
                    cod.textContent = gerarBoleto();
                    bBox.appendChild(cod);
                }
            });
            bBox.appendChild(bp);
            bBox.appendChild(bBtn);
            cont.appendChild(bBox);
        } else if (metodo === 'na-hora') {
            var p2 = document.createElement('p');
            p2.textContent = L(GERAL.naHoraInstr);
            cont.appendChild(p2);
        }
    }

    /* ================================================
       MONTAGEM DO PEDIDO (estrutura pronta para backend)
       ================================================ */
    function montarPedido() {
        var c = calcular();
        var t = null;
        if (tipoId) {
            t = cfgAtual.tipo.options.filter(function (o) { return o.id === tipoId; })[0] || null;
        }
        var quantidades = {};
        (cfgAtual.steppers || []).forEach(function (s) {
            quantidades[s.id] = stepperValores[s.id] || s.min;
        });
        var adicionais = cfgAtual.adicionais.filter(function (a) { return adicionaisAtivos[a.id]; })
            .map(function (a) { return { id: a.id, nome: L(a.label), preco: a.preco }; });

        return {
            servico: {
                nome: nomeServico,
                categoria: cardAtual.getAttribute('data-categoria'),
                precoBase: precoBase,
                valorPorDia: +c.porDia.toFixed(2)
            },
            datas: Object.keys(datas).sort(),
            configuracao: {
                tipo: t ? { id: t.id, nome: L(t.label) } : null,
                quantidades: quantidades,
                adicionais: adicionais
            },
            endereco: {
                cep: document.getElementById('agCep').value.trim(),
                rua: document.getElementById('agEndereco').value.trim(),
                numero: document.getElementById('agNumero').value.trim(),
                complemento: document.getElementById('agComplemento').value.trim(),
                cidade: document.getElementById('agCidade').value.trim(),
                observacoes: document.getElementById('agObservacoes').value.trim()
            },
            pagamento: { metodo: metodo },
            valores: {
                precoPorDia: +c.porDia.toFixed(2),
                numeroDeDatas: c.numDatas,
                total: +c.total.toFixed(2)
            },
            codigo: gerarCodigo(),
            criadoEm: new Date().toISOString()
        };
    }

    function renderizarSucesso(pedido) {
        var cont = document.getElementById('agSucessoInfo');
        cont.innerHTML = '';

        function linha(rotulo, valor, destaque) {
            var div = document.createElement('div');
            div.className = 'ag-carrinho-linha' + (destaque ? ' ag-pedido-codigo' : '');
            var s = document.createElement('span');
            s.textContent = rotulo;
            var b = document.createElement('b');
            b.textContent = valor;
            div.appendChild(s);
            div.appendChild(b);
            cont.appendChild(div);
        }

        linha(pedido.servico.nome, fmt(pedido.valores.precoPorDia));
        linha(pedido.valores.numeroDeDatas + ' ' + L(GERAL.dias) + ' × ' + fmt(pedido.valores.precoPorDia), fmt(pedido.valores.total));
        linha(pedido.datas.map(fmtData).join(', '), '');
        linha(L(GERAL.codigoPedido), pedido.codigo, true);
    }

    /* ================================================
       EVENTOS
       ================================================ */
    function confirmar() {
        if (!metodo) {
            mostrarErroPag(L(GERAL.erroMetodo));
            return;
        }
        if (metodo === 'cartao') {
            var num = document.getElementById('agCartaoNum');
            var nome = document.getElementById('agCartaoNome');
            var val = document.getElementById('agCartaoVal');
            var cvv = document.getElementById('agCartaoCvv');
            if (!num || !nome || !val || !cvv || !num.value.trim() || !nome.value.trim() || !val.value.trim() || !cvv.value.trim()) {
                mostrarErroPag(L(GERAL.erroCartao));
                return;
            }
        }

        var pedido = montarPedido();

        /* INTEGRAÇÃO BACKEND:
           Substitua a simulação abaixo por uma chamada real, ex.:
           fetch('/api/agendamentos', {
               method: 'POST',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify(pedido)
           }).then(r => r.json()).then(() => mostrarSucesso(pedido)); */
        window.LumisUltimoPedido = pedido;

        var btn = document.getElementById('agConfirmar');
        btn.disabled = true;
        btn.textContent = L(GERAL.enviando);

        setTimeout(function () {
            btn.disabled = false;
            btn.textContent = L(GERAL.confirmar);
            renderizarSucesso(pedido);
            mostrarPasso(3);
        }, 900);
    }

    // Botões "Agendar" dos cards de serviço
    document.querySelectorAll('.btn-agendar').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var card = this.closest('.cartao-servico');
            if (card) abrirModal(card);
        });
    });

    // Fechar (backdrop, X, Concluir)
    document.querySelectorAll('[data-ag-fechar]').forEach(function (el) {
        el.addEventListener('click', fecharModal);
    });

    // Navegação do calendário
    document.getElementById('agMesPrev').addEventListener('click', function () {
        viewMes--;
        if (viewMes < 0) { viewMes = 11; viewAno--; }
        renderizarCalendario();
    });
    document.getElementById('agMesNext').addEventListener('click', function () {
        viewMes++;
        if (viewMes > 11) { viewMes = 0; viewAno++; }
        renderizarCalendario();
    });

    // Finalizar (passo 1 → passo 2)
    document.getElementById('agFinalizar').addEventListener('click', function () {
        if (!validarPasso1()) return;
        var c = calcular();
        document.getElementById('agPagServico').textContent = nomeServico + ' · ' + c.numDatas + ' ' + L(GERAL.dias);
        document.getElementById('agPagTotal').textContent = fmt(c.total);
        metodo = '';
        document.querySelectorAll('.ag-pag-opcao').forEach(function (o) { o.classList.remove('ativo'); });
        renderizarPagDetalhes();
        mostrarPasso(2);
        var conteudo = modal.querySelector('.ag-modal-content');
        if (conteudo) conteudo.scrollTop = 0;
    });

    // Métodos de pagamento
    document.querySelectorAll('.ag-pag-opcao').forEach(function (op) {
        op.addEventListener('click', function () {
            selecionarMetodo(this.getAttribute('data-metodo'));
        });
    });

    // Voltar (passo 2 → passo 1)
    document.getElementById('agVoltar').addEventListener('click', function () {
        mostrarPasso(1);
    });

    // Confirmar agendamento
    document.getElementById('agConfirmar').addEventListener('click', confirmar);

    // Esc
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && !modal.hidden) fecharModal();
    });

    // Limpar estado de erro dos inputs ao digitar
    ['agEndereco', 'agNumero', 'agCidade'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function () {
                this.classList.remove('ag-erro');
                limparErro();
            });
        }
    });

    // Máscara de CEP
    var cepEl = document.getElementById('agCep');
    if (cepEl) {
        cepEl.addEventListener('input', function () {
            var v = this.value.replace(/\D/g, '').slice(0, 8);
            this.value = v.length > 5 ? v.slice(0, 5) + '-' + v.slice(5) : v;
        });
    }

    // Elementos de mensagem de erro (carrinho e pagamento)
    erroMsg = document.createElement('p');
    erroMsg.id = 'agErroMsg';
    erroMsg.className = 'ag-erro-msg';
    var cartEl = document.querySelector('.ag-carrinho');
    if (cartEl) cartEl.appendChild(erroMsg);

    erroPag = document.createElement('p');
    erroPag.id = 'agErroPag';
    erroPag.className = 'ag-erro-msg';
    var acoesPag = document.querySelector('.ag-pag-acoes');
    if (acoesPag) acoesPag.parentNode.insertBefore(erroPag, acoesPag);
});
