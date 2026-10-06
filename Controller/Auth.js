(function () {
    'use strict';

    // Este script entra no <head> pelo partial do header, entao le document.currentScript
    // no topo do IIFE: nesse ponto ele ainda aponta para o proprio Auth.js.
    // (Se for importado/delay, currentScript vira null e o caminho abaixo quebra.)
    const caminhoApi = new URL('api/', document.currentScript.src).href;
    const ENDPOINT = {
        sessao: new URL('sessao.php', caminhoApi).href,
        login: new URL('login.php', caminhoApi).href,
        cadastro: new URL('cadastro.php', caminhoApi).href,
        logout: new URL('logout.php', caminhoApi).href
    };

    const PREFIXO_ERRO = 'auth-erro-';
    const MIN_SENHA = 6;

    let usuario = null;

    // chave = nome completo da traducao (ex.: 'auth-erro-email-invalido'), sem prefixo montado aqui
    function traduzirMensagem(el, chave, fallback) {
        el.removeAttribute('data-i18n');
        // limpa o texto anterior ANTES de tentar traduzir: sem isso, uma chave que nao
        // existe no dicionario deixaria a mensagem antiga no lugar e ela passaria
        // pelo teste de baixo como se tivesse sido traduzida
        el.textContent = '';

        if (chave && window.LumisI18n) {
            el.setAttribute('data-i18n', chave);
            window.LumisI18n.aplicar();
            if (el.textContent.trim()) return;   // traduziu mesmo
        }

        el.removeAttribute('data-i18n');
        el.textContent = fallback || '';
    }

    async function pedir(url, opcoes) {
        let resposta;
        try {
            resposta = await fetch(url, Object.assign({ credentials: 'same-origin' }, opcoes));
        } catch (erro) {
            return { ok: false, erro: 'servidor' };
        }
        try {
            return await resposta.json();
        } catch (erro) {
            return { ok: false, erro: resposta.ok ? 'generico' : 'servidor' };
        }
    }

    function coletar(form) {
        const dados = {};
        new FormData(form).forEach((valor, chave) => {
            dados[chave] = typeof valor === 'string' ? valor.trim() : valor;
        });
        return dados;
    }

    function soDigitos(valor) {
        return String(valor || '').replace(/\D/g, '');
    }

    function validar(form, dados) {
        const ehCadastro = form.getAttribute('data-auth-form') === 'cadastro';
        const erros = {};

        Object.keys(dados).forEach(campo => {
            if (dados[campo] === '' && form.querySelector('[name="' + campo + '"]')?.required) {
                erros[campo] = 'obrigatorios';
            }
        });

        if (dados.email && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(dados.email)) {
            erros.email = 'email-invalido';
        }

        if (dados.senha && dados.senha.length < MIN_SENHA) {
            erros.senha = 'senha-curta';
        }

        if (ehCadastro && dados.senha && dados.senha_confirma && dados.senha !== dados.senha_confirma) {
            erros.senha_confirma = 'senhas-diferentes';
        }

        if (dados.telefone && soDigitos(dados.telefone).length < 10) {
            erros.telefone = 'telefone-invalido';
        }

        return erros;
    }

    function limparErrosCampos(form) {
        form.querySelectorAll('.auth-field').forEach(campo => {
            campo.classList.remove('invalido');
        });
    }

    function marcarErrosCampos(form, campos) {
        Object.keys(campos || {}).forEach(nome => {
            const input = form.querySelector('[name="' + nome + '"]');
            if (input?.closest('.auth-field')) {
                input.closest('.auth-field').classList.add('invalido');
            }
        });
    }

    function boxMensagem(form) {
        return form.parentElement.querySelector('[data-auth-mensagem]');
    }

    function mostrar(form, codigo, fallback, campos) {
        const box = boxMensagem(form);
        if (!box) return;

        box.hidden = false;
        box.classList.toggle('erro', !codigo || codigo === 'servidor' ? false : true);
        box.classList.toggle('sucesso', !codigo);
        traduzirMensagem(box, codigo ? PREFIXO_ERRO + codigo : null, fallback);

        if (campos) {
            marcarErrosCampos(form, campos);
            const primeiro = Object.keys(campos)[0];
            const input = form.querySelector('[name="' + primeiro + '"]');
            if (input) input.focus();
        }
    }

    function esconder(form) {
        const box = boxMensagem(form);
        if (box) {
            box.hidden = true;
            box.removeAttribute('data-i18n');
            box.textContent = '';
        }
    }

    function carregarBotao(botao, ativo) {
        if (!botao) return;
        if (ativo) {
            botao.dataset.rotulo = botao.dataset.rotulo || botao.textContent.trim();
            botao.disabled = true;
            botao.classList.add('carregando');
        } else {
            botao.disabled = false;
            botao.classList.remove('carregando');
        }
    }

    function destinoFinal(form, resposta) {
        if (resposta?.redirect) return resposta.redirect;
        return form.getAttribute('data-auth-redirecionar') || '../perfil.php';
    }

    async function enviar(form) {
        const dados = coletar(form);
        const botao = form.querySelector('button[type="submit"]');

        limparErrosCampos(form);
        esconder(form);

        const erros = validar(form, dados);
        if (Object.keys(erros).length) {
            mostrar(form, 'obrigatorios', null, erros);
            return;
        }

        dados.tipo = form.getAttribute('data-auth-tipo') || 'cliente';
        dados._formulario = form.getAttribute('data-auth-form');

        const ehCadastro = dados._formulario === 'cadastro';
        carregarBotao(botao, true);

        const resposta = await pedir(ehCadastro ? ENDPOINT.cadastro : ENDPOINT.login, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });

        carregarBotao(botao, false);

        if (resposta?.ok) {
            // sucesso usa o prefixo auth-ok-, nao auth-erro-: sao chaves diferentes no dicionario
            const box = boxMensagem(form);
            if (box) {
                box.hidden = false;
                box.classList.remove('erro');
                box.classList.add('sucesso');
                traduzirMensagem(box, ehCadastro ? 'auth-ok-cadastro' : 'auth-ok-login');
            }
            window.location.href = destinoFinal(form, resposta);
            return;
        }

        mostrar(form, resposta?.erro || 'generico', resposta?.mensagem, resposta?.campos);
    }

    function mascaraTelefone(evento) {
        const input = evento.target;
        let v = soDigitos(input.value).slice(0, 11);

        if (v.length > 10) v = '(' + v.slice(0, 2) + ') ' + v.slice(2, 7) + '-' + v.slice(7);
        else if (v.length > 6) v = '(' + v.slice(0, 2) + ') ' + v.slice(2, 6) + '-' + v.slice(6);
        else if (v.length > 2) v = '(' + v.slice(0, 2) + ') ' + v.slice(2);
        else if (v.length > 0) v = '(' + v;

        input.value = v;
    }

    function atualizarHeader() {
        const link = document.getElementById('authLoginLink');
        const box = document.getElementById('authUserBox');
        const nome = document.getElementById('authUserName');

        if (nome && usuario?.nome) {
            nome.textContent = usuario.nome.split(' ')[0];
        }

        if (!link || !box) return;

        if (usuario) {
            link.hidden = true;
            box.hidden = false;
        } else {
            link.hidden = false;
            box.hidden = true;
        }
    }

    async function carregarSessao() {
        const resposta = await pedir(ENDPOINT.sessao, { method: 'GET' });

        // resposta.ok === false = a chamada falhou; nesse caso nao tocamos no header,
        // senao um erro de rede deslogaria a pessoa na tela com a sessao ainda valida
        if (!resposta || resposta.ok === false) return null;

        usuario = resposta.logado ? resposta.usuario : null;
        atualizarHeader();
        return usuario;
    }

    async function sair() {
        carregarBotao(document.getElementById('authLogoutBtn'), true);
        const resposta = await pedir(ENDPOINT.logout, { method: 'POST' });
        if (resposta?.ok) {
            window.location.href = resposta.redirect || document.body.getAttribute('data-auth-home') || '../index.php';
        } else {
            carregarBotao(document.getElementById('authLogoutBtn'), false);
        }
    }

    // guarda de rota: so roda em pagina protegida, e reaproveita a sessao ja carregada
    function guardarRota(atual) {
        const exigido = document.body.getAttribute('data-auth-requer');
        if (!exigido) return;

        const entrada = document.body.getAttribute('data-auth-login');

        if (!atual) {
            if (entrada) {
                window.location.replace(entrada + (entrada.includes('?') ? '&' : '?') + 'motivo=login');
            }
            return;
        }

        if (exigido !== 'qualquer' && atual.tipo !== exigido) {
            if (entrada) window.location.replace(entrada);
        }
    }

    function aplicarMascaras() {
        document.querySelectorAll('[data-mask-phone]').forEach(input => {
            input.addEventListener('input', mascaraTelefone);
        });
    }

    function ligarFormularios() {
        document.querySelectorAll('form[data-auth-form]').forEach(form => {
            form.addEventListener('submit', evento => {
                evento.preventDefault();
                enviar(form);
            });

            form.addEventListener('input', () => {
                limparErrosCampos(form);
                const box = boxMensagem(form);
                if (box && !box.classList.contains('sucesso')) esconder(form);
            });
        });

        document.getElementById('authLogoutBtn')?.addEventListener('click', sair);
    }

    function iniciar() {
        ligarFormularios();
        aplicarMascaras();

        // a sessao e sempre buscada, em qualquer pagina, para o header mostrar o usuario certo;
        // a guarda reaproveita essa mesma promessa em vez de fazer uma segunda chamada
        const sessao = carregarSessao();
        if (document.body.getAttribute('data-auth-requer')) {
            sessao.then(guardarRota);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciar);
    } else {
        iniciar();
    }
})();