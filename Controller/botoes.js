function setupSelecionarBotoes(containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    const links = container.querySelectorAll('a');

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault(); // evita redirecionamento por enquanto
            links.forEach(l => l.classList.remove('selected')); // remove dos outros
            link.classList.add('selected'); // adiciona ao clicado
        });
    });
}

// Aplica nos dois grupos de botões
setupSelecionarBotoes('.botao');
setupSelecionarBotoes('.Tipo_Pesado');
setupSelecionarBotoes('.Tipo_Pesado2');
setupSelecionarBotoes('.Proximo');

