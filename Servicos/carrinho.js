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

    // Elementos do DOM
    let tipoServico = null;
    let tipoLimpeza = null;
    let subtipoLimpeza = null;
    let adicionaisSelecionados = [];
    let banheiros = 1;
    let quartos = 2;
    let horas = 4;

    // Elementos para exibir o total
    const resumoPedido = document.getElementById('resumo-pedido');
    const valorTotalElement = document.getElementById('valor-total');

    // Inicializar o sistema
    inicializarSistema();

    function inicializarSistema() {
        // Inicialmente, não mostrar valor (0,00)
        if (valorTotalElement) {
            valorTotalElement.textContent = 'R$ 0,00';
        }
        
        // Configurar contadores
        configurarContadores();
        
        // Configurar botões de serviço
        configurarBotoesServico();
        
        // Configurar botões de tipo de limpeza
        configurarBotoesLimpeza();
        
        // Configurar seleção de adicionais
        configurarAdicionais();
        
        // Configurar botões de próxima etapa
        configurarBotoesProximo();
    }

    function configurarContadores() {
        // Configurar contador de banheiros
        const counterBanheiro = document.querySelector('.counter[data-label="banheiro"]');
        if (counterBanheiro) {
            const minusBtn = counterBanheiro.querySelector('.minus');
            const plusBtn = counterBanheiro.querySelector('.plus');
            const valueElement = counterBanheiro.querySelector('.value');
            
            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (banheiros > 1) {
                    banheiros--;
                    valueElement.textContent = banheiros;
                    atualizarTotal();
                }
            });
            
            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                banheiros++;
                valueElement.textContent = banheiros;
                atualizarTotal();
            });
        }

        // Configurar contador de quartos
        const counterQuarto = document.querySelector('.counter[data-label="quarto"]');
        if (counterQuarto) {
            const minusBtn = counterQuarto.querySelector('.minus');
            const plusBtn = counterQuarto.querySelector('.plus');
            const valueElement = counterQuarto.querySelector('.value');
            
            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (quartos > 1) {
                    quartos--;
                    valueElement.textContent = quartos;
                    atualizarTotal();
                }
            });
            
            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                quartos++;
                valueElement.textContent = quartos;
                atualizarTotal();
            });
        }

        // Configurar contador de horas
        const counterHora = document.querySelector('.counter[data-label="hora"]');
        if (counterHora) {
            const minusBtn = counterHora.querySelector('.minus');
            const plusBtn = counterHora.querySelector('.plus');
            const valueElement = counterHora.querySelector('.value');
            
            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (horas > 1) {
                    horas--;
                    valueElement.textContent = horas;
                    atualizarTotal();
                }
            });
            
            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                horas++;
                valueElement.textContent = horas;
                atualizarTotal();
            });
        }
    }

    function configurarBotoesServico() {
        const buttonResidencial = document.querySelector('.residencial');
        const buttonComercial = document.querySelector('.comercial');

        if (buttonResidencial) {
            buttonResidencial.addEventListener('click', function() {
                tipoServico = 'residencial';
                // Resetar valores específicos para residencial
                banheiros = 1;
                quartos = 2;
                // Atualizar contadores
                document.querySelector('.counter[data-label="banheiro"] .value').textContent = banheiros;
                document.querySelector('.counter[data-label="quarto"] .value').textContent = quartos;
                atualizarTotal();
            });
        }

        if (buttonComercial) {
            buttonComercial.addEventListener('click', function() {
                tipoServico = 'comercial';
                // Resetar valores específicos para comercial
                banheiros = 1;
                quartos = 1;
                // Atualizar contadores
                document.querySelector('.counter[data-label="banheiro"] .value').textContent = banheiros;
                document.querySelector('.counter[data-label="quarto"] .value').textContent = quartos;
                atualizarTotal();
            });
        }
    }

    function configurarBotoesLimpeza() {
        // Botões de limpeza residencial
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
                // O subtipo será definido quando o usuário selecionar uma opção específica
                atualizarTotal();
            });
        }

        // Botões de subtipo de limpeza pesada residencial
        const subtiposResidencial = document.querySelectorAll('#oculto3 a');
        subtiposResidencial.forEach(subtipo => {
            subtipo.addEventListener('click', function(e) {
                e.preventDefault();
                // Corrigindo a forma de obter o valor do subtipo
                const texto = this.textContent.trim().toLowerCase();
                
                // Mapear o texto para as chaves corretas
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

        // Botões de limpeza comercial
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

        // Botões de subtipo de limpeza pesada comercial
        const subtiposComercial = document.querySelectorAll('#oculto4 a');
        subtiposComercial.forEach(subtipo => {
            subtipo.addEventListener('click', function(e) {
                e.preventDefault();
                // Corrigindo a forma de obter o valor do subtipo
                const texto = this.textContent.trim().toLowerCase();
                
                // Mapear o texto para as chaves corretas
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
                // Resetar seleção
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
                // Resetar seleção
                this.selectedIndex = 0;
            });
        }
    }

    function configurarBotoesProximo() {
        const button5 = document.getElementById('button5');
        const button6 = document.getElementById('button6');
        const oculto5 = document.getElementById('oculto5');

        if (button5 && oculto5) {
            button5.addEventListener('click', function() {
                // Verificar se um serviço foi selecionado
                if (!tipoServico || !tipoLimpeza) {
                    alert('Por favor, selecione um tipo de serviço antes de continuar.');
                    return;
                }
                
                oculto5.style.display = 'block';
                oculto5.scrollIntoView({ behavior: 'smooth', block: 'start' });
                criarResumoPedido();
            });
        }

        if (button6 && oculto5) {
            button6.addEventListener('click', function() {
                // Verificar se um serviço foi selecionado
                if (!tipoServico || !tipoLimpeza) {
                    alert('Por favor, selecione um tipo de serviço antes de continuar.');
                    return;
                }
                
                oculto5.style.display = 'block';
                oculto5.scrollIntoView({ behavior: 'smooth', block: 'start' });
                criarResumoPedido();
            });
        }
    }

    function calcularTotal() {
        let total = 0;

        // Só calcular se um serviço foi selecionado
        if (tipoServico && tipoLimpeza) {
            // Calcular valor base baseado no tipo de serviço e limpeza
            if (tipoLimpeza === 'padrao') {
                total += precos[tipoServico].padrao;
            } else if (tipoLimpeza === 'pesada' && subtipoLimpeza) {
                // Verificar se o subtipo existe antes de adicionar
                if (precos[tipoServico].pesada[subtipoLimpeza]) {
                    total += precos[tipoServico].pesada[subtipoLimpeza];
                }
            }

            // Adicionar valor dos cômodos
            total += (banheiros - 1) * precos.comodos.banheiro;
            
            // Definir quantidade base de quartos baseado no tipo de serviço
            const quartosBase = tipoServico === 'residencial' ? 2 : 1;
            if (quartos > quartosBase) {
                total += (quartos - quartosBase) * precos.comodos.quarto;
            }

            // Adicionar valor das horas extras (acima de 4 horas)
            if (horas > 4) {
                total += (horas - 4) * precos.horas.adicional;
            }

            // Adicionar valor dos adicionais selecionados
            adicionaisSelecionados.forEach(adicional => {
                if (precos.adicionais[adicional]) {
                    total += precos.adicionais[adicional];
                }
            });
        }

        return total;
    }

    function atualizarTotal() {
        const total = calcularTotal();
        
        // Atualizar exibição do total se o elemento existir
        if (valorTotalElement) {
            valorTotalElement.textContent = `R$ ${total.toFixed(2)}`;
        }
    }

    function criarResumoPedido() {
        if (!resumoPedido) return;
        
        let html = `
            <div class="resumo-pedido">
                <h3>Resumo do Pedido</h3>
                <div class="itens-pedido">
        `;

        // Serviço principal
        if (tipoServico && tipoLimpeza) {
            let nomeServico = '';
            let valorServico = 0;
            
            if (tipoLimpeza === 'padrao') {
                nomeServico = `Limpeza ${tipoServico === 'residencial' ? 'Residencial' : 'Comercial'} Padrão`;
                valorServico = precos[tipoServico].padrao;
            } else if (tipoLimpeza === 'pesada' && subtipoLimpeza) {
                // Formatar o nome do subtipo para exibição
                let nomeSubtipo = subtipoLimpeza;
                if (subtipoLimpeza === 'pre_mudanca') nomeSubtipo = 'Pré-mudança';
                if (subtipoLimpeza === 'pos_obra') nomeSubtipo = 'Pós-obra';
                if (subtipoLimpeza === 'rotina') nomeSubtipo = 'Rotina';
                
                nomeServico = `Limpeza ${tipoServico === 'residencial' ? 'Residencial' : 'Comercial'} Pesada - ${nomeSubtipo}`;
                
                // Verificar se o preço existe antes de adicionar
                if (precos[tipoServico].pesada[subtipoLimpeza]) {
                    valorServico = precos[tipoServico].pesada[subtipoLimpeza];
                } else {
                    valorServico = 0;
                }
            }
            
            html += `
                <div class="item-pedido">
                    <span>${nomeServico}</span>
                    <span>R$ ${valorServico.toFixed(2)}</span>
                </div>
            `;
        }

        // Cômodos
        if (banheiros > 1) {
            const valorBanheiros = (banheiros - 1) * precos.comodos.banheiro;
            html += `
                <div class="item-pedido">
                    <span>${banheiros - 1} banheiro(s) adicional(is)</span>
                    <span>R$ ${valorBanheiros.toFixed(2)}</span>
                </div>
            `;
        }

        const quartosBase = tipoServico === 'residencial' ? 2 : 1;
        if (quartos > quartosBase) {
            const valorQuartos = (quartos - quartosBase) * precos.comodos.quarto;
            html += `
                <div class="item-pedido">
                    <span>${quartos - quartosBase} quarto(s) adicional(is)</span>
                    <span>R$ ${valorQuartos.toFixed(2)}</span>
                </div>
            `;
        }

        // Horas extras
        if (horas > 4) {
            const valorHorasExtras = (horas - 4) * precos.horas.adicional;
            html += `
                <div class="item-pedido">
                    <span>${horas - 4} hora(s) extra(s)</span>
                    <span>R$ ${valorHorasExtras.toFixed(2)}</span>
                </div>
            `;
        }

        // Adicionais
        if (adicionaisSelecionados.length > 0) {
            adicionaisSelecionados.forEach(adicional => {
                if (precos.adicionais[adicional]) {
                    // Formatar o nome do adicional para exibição
                    let nomeAdicional = adicional;
                    if (adicional === 'Lavar_roupas') nomeAdicional = 'Lavar roupas';
                    if (adicional === 'AR') nomeAdicional = 'Ar-condicionado';
                    
                    html += `
                        <div class="item-pedido">
                            <span>${nomeAdicional}</span>
                            <span>R$ ${precos.adicionais[adicional].toFixed(2)}</span>
                        </div>
                    `;
                }
            });
        }

        // Total
        const total = calcularTotal();
        html += `
                </div>
                <div class="total-pedido">
                    <strong>Total: R$ ${total.toFixed(2)}</strong>
                </div>
            </div>
        `;

        resumoPedido.innerHTML = html;
    }

    // Inicializar o sistema
    inicializarSistema();
});