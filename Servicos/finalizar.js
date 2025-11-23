document.addEventListener('DOMContentLoaded', function() {
    // ELEMENTOS DO DOM
    const finalizarBtn = document.getElementById('finalizar-pedido');
    const modal = document.getElementById('modal-pagamento');
    const fecharBtn = document.getElementById('fechar-modal');
    const cancelarBtn = document.getElementById('cancelar-pagamento');
    const confirmarBtn = document.getElementById('confirmar-pagamento');
    const metodosPagamento = document.querySelectorAll('.metodo-pagamento');
    
    let metodoSelecionado = null;

    // ABRE MODAL AO CLICAR EM FINALIZAR PEDIDO
    if (finalizarBtn) {
        finalizarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'flex';
        });
    }

    // FECHA MODAL
    function fecharModal() {
        modal.style.display = 'none';
        resetarSelecao();
    }

    if (fecharBtn) fecharBtn.addEventListener('click', fecharModal);
    if (cancelarBtn) cancelarBtn.addEventListener('click', fecharModal);

    // FECHA AO CLICAR FORA
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                fecharModal();
            }
        });
    }

    // SELECIONA MÉTODO DE PAGAMENTO
    metodosPagamento.forEach(botao => {
        botao.addEventListener('click', function() {
            metodosPagamento.forEach(btn => btn.classList.remove('ativo'));
            this.classList.add('ativo');
            metodoSelecionado = this.dataset.metodo;
        });
    });

    // CONFIRMA PAGAMENTO
    if (confirmarBtn) {
        confirmarBtn.addEventListener('click', function() {
            if (!metodoSelecionado) {
                alert('Por favor, selecione um método de pagamento');
                return;
            }
            
            const metodosNomes = {
                'pix': 'PIX',
                'credito': 'Cartão de Crédito',
                'debito': 'Cartão de Débito',
                'boleto': 'Boleto Bancário'
            };
            
            alert(`Pagamento confirmado via ${metodosNomes[metodoSelecionado]}!`);
            
            // Limpa o carrinho após confirmar
            localStorage.removeItem('carrinho');
            
            // Redireciona
            window.location.href = '../Servicos/Servicos.html';
            
            fecharModal();
        });
    }

    // RESETA SELEÇÃO
    function resetarSelecao() {
        metodosPagamento.forEach(btn => btn.classList.remove('ativo'));
        metodoSelecionado = null;
    }
});
