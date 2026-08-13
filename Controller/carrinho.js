document.addEventListener('DOMContentLoaded', function() {
    // Preços base e adicionais
    const precos = {
        residencial: {
            padrao: 120,
            pesada: {
                rotina: 180,
                pre_mudanca: 220,
                pos_obra: 250
            }
        },
        comercial: {
            padrao: 150,
            pesada: {
                rotina: 200,
                pre_mudanca: 250,
                pos_obra: 280
            }
        },
        adicionais: {
            geladeira: 25,
            janelas: 20,
            cozinha: 30,
            estofado: 35,
            externa: 40,
            vestuario: 25,
            Lavar_roupas: 45,
            Fachadas: 60,
            Banheiros: 35,
            AR: 50,
            Pisos: 55,
            Externas: 45,
            cozinhas: 40,
            eventos: 70
        },
        comodos: {
            banheiro: 30,
            quarto: 40
        },
        horas: {
            adicional: 35
        }
    };

    // Variáveis globais
    let tipoServico = null;
    let tipoLimpeza = null;
    let subtipoLimpeza = null;
    let adicionaisSelecionados = [];
    let banheiros = 1;
    let quartos = 2;
    let horas = 4;

    // Elementos do DOM
    const resumoPedido = document.getElementById('resumo-pedido');
    const valorTotalElement = document.getElementById('valor-total');

    // Inicializar o sistema
    inicializarSistema();

    function inicializarSistema() {
        if (valorTotalElement) {
            valorTotalElement.textContent = 'R$ 0,00';
        }

        configurarContadores();
        configurarBotoesServico();
        configurarBotoesLimpeza();
        configurarAdicionais();
        configurarBotoesProximo();
    }

    // ================= CONTADORES =================
    function configurarContadores() {
        // Banheiros
        document.querySelectorAll('.counter[data-label="banheiro"]').forEach(counter => {
            const minusBtn = counter.querySelector('.minus');
            const plusBtn = counter.querySelector('.plus');

            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (banheiros > 1) {
                    banheiros--;
                    atualizarContadores('banheiro', banheiros);
                    atualizarTotal();
                }
            });

            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                banheiros++;
                atualizarContadores('banheiro', banheiros);
                atualizarTotal();
            });
        });

        // Quartos
        document.querySelectorAll('.counter[data-label="quarto"]').forEach(counter => {
            const minusBtn = counter.querySelector('.minus');
            const plusBtn = counter.querySelector('.plus');

            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (quartos > 1) {
                    quartos--;
                    atualizarContadores('quarto', quartos);
                    atualizarTotal();
                }
            });

            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                quartos++;
                atualizarContadores('quarto', quartos);
                atualizarTotal();
            });
        });

        // Horas
        document.querySelectorAll('.counter[data-label="hora"]').forEach(counter => {
            const minusBtn = counter.querySelector('.minus');
            const plusBtn = counter.querySelector('.plus');

            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (horas > 1) {
                    horas--;
                    atualizarContadores('hora', horas);
                    atualizarTotal();
                }
            });

            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                horas++;
                atualizarContadores('hora', horas);
                atualizarTotal();
            });
        });
    }

    // Atualiza todos os contadores iguais (residencial + comercial)
    function atualizarContadores(tipo, valor) {
        document.querySelectorAll(`.counter[data-label="${tipo}"] .value`)
            .forEach(el => el.textContent = valor);
    }

    // ================= SERVIÇOS =================
    function configurarBotoesServico() {
        const buttonResidencial = document.querySelector('.residencial');
        const buttonComercial = document.querySelector('.comercial');

        if (buttonResidencial) {
            buttonResidencial.addEventListener('click', function() {
                tipoServico = 'residencial';
                banheiros = 1;
                quartos = 2;
                horas = 4;
                atualizarContadores('banheiro', banheiros);
                atualizarContadores('quarto', quartos);
                atualizarContadores('hora', horas);
                atualizarTotal();
            });
        }

        if (buttonComercial) {
            buttonComercial.addEventListener('click', function() {
                tipoServico = 'comercial';
                banheiros = 1;
                quartos = 1;
                horas = 4;
                atualizarContadores('banheiro', banheiros);
                atualizarContadores('quarto', quartos);
                atualizarContadores('hora', horas);
                atualizarTotal();
            });
        }
    }

    // ================= LIMPEZA =================
    function configurarBotoesLimpeza() {
        // Residencial
        const btnLimpezaPadraoResidencial = document.querySelector('#oculto1 .botao a:first-child');
        const btnLimpezaPesadaResidencial = document.querySelector('#oculto1 .botao a:last-child');

        if (btnLimpezaPadraoResidencial) {
            btnLimpezaPadraoResidencial.addEventListener('click', function(e) {
                e.preventDefault();
                tipoLimpeza = 'padrao';
                subtipoLimpeza = null;
                atualizarTotal();
            });
        }

        if (btnLimpezaPesadaResidencial) {
            btnLimpezaPesadaResidencial.addEventListener('click', function(e) {
                e.preventDefault();
                tipoLimpeza = 'pesada';
                atualizarTotal();
            });
        }

        // Subtipos Residencial
        const subtiposResidencial = document.querySelectorAll('#oculto3 a');
        subtiposResidencial.forEach(subtipo => {
            subtipo.addEventListener('click', function(e) {
                e.preventDefault();
                const texto = this.textContent.trim().toLowerCase();

                if (texto.includes('pré-mudança') || texto.includes('pre-mudanca')) {
                    subtipoLimpeza = 'pre_mudanca';
                } else if (texto.includes('pós-obra') || texto.includes('pos-obra')) {
                    subtipoLimpeza = 'pos_obra';
                } else {
                    subtipoLimpeza = 'rotina';
                }

                atualizarTotal();
            });
        });

        // Comercial
        const btnLimpezaPadraoComercial = document.querySelector('#oculto2 .botao a:first-child');
        const btnLimpezaPesadaComercial = document.querySelector('#oculto2 .botao a:last-child');

        if (btnLimpezaPadraoComercial) {
            btnLimpezaPadraoComercial.addEventListener('click', function(e) {
                e.preventDefault();
                tipoLimpeza = 'padrao';
                subtipoLimpeza = null;
                atualizarTotal();
            });
        }

        if (btnLimpezaPesadaComercial) {
            btnLimpezaPesadaComercial.addEventListener('click', function(e) {
                e.preventDefault();
                tipoLimpeza = 'pesada';
                atualizarTotal();
            });
        }

        // Subtipos Comercial
        const subtiposComercial = document.querySelectorAll('#oculto4 a');
        subtiposComercial.forEach(subtipo => {
            subtipo.addEventListener('click', function(e) {
                e.preventDefault();
                const texto = this.textContent.trim().toLowerCase();

                if (texto.includes('pré-mudança') || texto.includes('pre-mudanca')) {
                    subtipoLimpeza = 'pre_mudanca';
                } else if (texto.includes('pós-obra') || texto.includes('pos-obra')) {
                    subtipoLimpeza = 'pos_obra';
                } else {
                    subtipoLimpeza = 'rotina';
                }

                atualizarTotal();
            });
        });
    }

    // ================= ADICIONAIS =================
    function configurarAdicionais() {
        const selectAdicionaisResidencial = document.querySelector('#oculto1 select#servicoad');
        const selectAdicionaisComercial = document.querySelector('#oculto2 select#servicoad');

        if (selectAdicionaisResidencial) {
            selectAdicionaisResidencial.addEventListener('change', function() {
                const adicional = this.value;
                if (adicional && !adicionaisSelecionados.includes(adicional)) {
                    adicionaisSelecionados.push(adicional);
                    atualizarTotal();
                }
                this.selectedIndex = 0;
            });
        }

        if (selectAdicionaisComercial) {
            selectAdicionaisComercial.addEventListener('change', function() {
                const adicional = this.value;
                if (adicional && !adicionaisSelecionados.includes(adicional)) {
                    adicionaisSelecionados.push(adicional);
                    atualizarTotal();
                }
                this.selectedIndex = 0;
            });
        }
    }

    // ================= PROXIMO =================
    function configurarBotoesProximo() {
        document.querySelectorAll('.Proximo a').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                atualizarTotal();
            });
        });
    }

    // ================= TOTAL =================
    function atualizarTotal() {
        let total = 0;

        if (tipoServico && tipoLimpeza) {
            if (tipoLimpeza === 'padrao') {
                total += precos[tipoServico].padrao;
            } else if (tipoLimpeza === 'pesada' && subtipoLimpeza) {
                total += precos[tipoServico].pesada[subtipoLimpeza];
            }
        }

        total += (banheiros - 1) * precos.comodos.banheiro;
        total += (quartos - (tipoServico === 'residencial' ? 2 : 1)) * precos.comodos.quarto;
        total += (horas - 4) * precos.horas.adicional;

        adicionaisSelecionados.forEach(adicional => {
            if (precos.adicionais[adicional] !== undefined) {
                total += precos.adicionais[adicional];
            }
        });

        if (valorTotalElement) {
            valorTotalElement.textContent = `R$ ${total.toFixed(2)}`;
        }
    }
});
