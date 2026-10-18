document.addEventListener('DOMContentLoaded', function() {
    // Obter referências aos botões principais
    const buttonResidencial = document.querySelector('.residencial');
    const buttonComercial = document.querySelector('.comercial');
    

    // Obter referências aos conteúdos
    const oculto1 = document.getElementById('oculto1');
    const oculto2 = document.getElementById('oculto2');
    
    // Obter referências aos botões de tipo de limpeza (para ambas as seções)
    const btnLimpezaPadraoResidencial = document.querySelector('#oculto1 .botao a:first-child');
    const btnLimpezaPesadaResidencial = document.querySelector('#oculto1 .botao a:last-child');
    const oculto3 = document.getElementById('oculto3');
    
    const btnLimpezaPadraoComercial = document.querySelector('#oculto2 .botao a:first-child');
    const btnLimpezaPesadaComercial = document.querySelector('#oculto2 .botao a:last-child');
    const oculto4 = document.getElementById('oculto4');

    // Inicialmente, ocultar ambas as seções
    if (oculto1) oculto1.style.display = 'none';
    if (oculto2) oculto2.style.display = 'none';
    if (oculto3) oculto3.style.display = 'none';
    if (oculto4) oculto4.style.display = 'none';

    // Adicionar event listeners aos botões principais
    if (buttonResidencial && oculto1) {
        buttonResidencial.addEventListener('click', function() {
            if (oculto2) oculto2.style.display = 'none';
            oculto1.style.display = 'block';
            
            // Resetar seleções anteriores na seção residencial
            if (btnLimpezaPadraoResidencial) btnLimpezaPadraoResidencial.classList.remove('selected');
            if (btnLimpezaPesadaResidencial) btnLimpezaPesadaResidencial.classList.remove('selected');
            if (oculto3) oculto3.style.display = 'none';
        });
    }

    if (buttonComercial && oculto2) {
        buttonComercial.addEventListener('click', function() {
            if (oculto1) oculto1.style.display = 'none';
            oculto2.style.display = 'block';
            
            // Resetar seleções anteriores na seção comercial
            if (btnLimpezaPadraoComercial) btnLimpezaPadraoComercial.classList.remove('selected');
            if (btnLimpezaPesadaComercial) btnLimpezaPesadaComercial.classList.remove('selected');
            if (oculto4) oculto4.style.display = 'none';
        });
    }

    // Adicionar event listeners aos botões de tipo de limpeza (Residencial)
    if (btnLimpezaPadraoResidencial && btnLimpezaPesadaResidencial && oculto3) {
        btnLimpezaPadraoResidencial.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('selected');
            btnLimpezaPesadaResidencial.classList.remove('selected');
            oculto3.style.display = 'none';
        });
        
        btnLimpezaPesadaResidencial.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('selected');
            btnLimpezaPadraoResidencial.classList.remove('selected');
            oculto3.style.display = 'block';
        });
    }

    // Adicionar event listeners aos botões de tipo de limpeza (Comercial)
    if (btnLimpezaPadraoComercial && btnLimpezaPesadaComercial && oculto4) {
        btnLimpezaPadraoComercial.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('selected');
            btnLimpezaPesadaComercial.classList.remove('selected');
            oculto4.style.display = 'none';
        });
        
        btnLimpezaPesadaComercial.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('selected');
            btnLimpezaPadraoComercial.classList.remove('selected');
            oculto4.style.display = 'block';
        });
    }

    // button8
    // Obter referências aos elementos
    const checkboxEmpresa = document.getElementById('Empresa');
    const formulario = document.getElementById('oculto8');
    const elementoOculto9 = document.getElementById('oculto9');
    const labelEmpresa = document.querySelector('.checkbox-label0');
    
    // Corrigir o atributo "for" do label (se necessário)
    if (labelEmpresa && labelEmpresa.getAttribute('for') === 'geladeira') {
        labelEmpresa.setAttribute('for', 'Empresa');
    }
    
    // Inicialmente ocultar o formulário e garantir que oculto9 esteja visível
    if (formulario) formulario.style.display = 'none';
    if (elementoOculto9) {
        elementoOculto9.style.display = 'block'; // ou o valor padrão que você quer
    }
    
    // Adicionar evento ao checkbox
    if (checkboxEmpresa) {
        checkboxEmpresa.addEventListener('change', function() {
            if (this.checked) {
                // Checkbox marcado: mostrar formulário e ocultar oculto9
                if (formulario) formulario.style.display = 'block';
                if (elementoOculto9) {
                    elementoOculto9.style.display = 'none';
                }
                // Rolagem suave até o formulário
                if (formulario) formulario.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                // Checkbox desmarcado: ocultar formulário e mostrar oculto9
                if (formulario) formulario.style.display = 'none';
                if (elementoOculto9) {
                    elementoOculto9.style.display = 'block'; // ou o valor original
                }
            }
        });
    }

    // Código para os botões 5 e 6
    const button5 = document.getElementById('button5');
    const button6 = document.getElementById('button6');
    const oculto5 = document.getElementById('oculto5');

    // Inicialmente ocultar a seção oculto5
    if (oculto5) {
        oculto5.style.display = 'none';
    }

    // Adicionar event listener ao botão 5
    if (button5 && oculto5) {
        button5.addEventListener('click', function() {
            // Mostrar a seção oculto5
            oculto5.style.display = 'block';
            
            // Rolar suavemente até a seção
            oculto5.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    // Adicionar event listener ao botão 6
    if (button6 && oculto5) {
        button6.addEventListener('click', function() {
            // Mostrar a seção oculto5
            oculto5.style.display = 'block';
            
            // Rolar suavemente até a seção
            oculto5.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }
});

// total esconder
document.addEventListener('DOMContentLoaded', function() {
    // Referências aos botões principais
    const buttonResidencial = document.querySelector('.residencial');
    const buttonComercial = document.querySelector('.comercial');

    // Referência à section do resumo/pedido
    const sectionTotal = document.getElementById('oculto10');

    // Inicialmente ocultar a section
    if (sectionTotal) sectionTotal.style.display = 'none';

    // Função para mostrar a section
    function mostrarResumo() {
        if (sectionTotal) sectionTotal.style.display = 'block';
    }

    // Adicionar eventos aos botões
    if (buttonResidencial) {
        buttonResidencial.addEventListener('click', function() {
            mostrarResumo();
        });
    }

    if (buttonComercial) {
        buttonComercial.addEventListener('click', function() {
            mostrarResumo();
        });
    }
});
