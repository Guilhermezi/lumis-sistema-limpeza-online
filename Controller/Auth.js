(function () {
    'use strict';

    // Resolve a URL da API pelo próprio arquivo, com fallback para carregamento
    // deferido, quando document.currentScript pode ser nulo.
    const scriptAuth = document.currentScript
        || Array.from(document.scripts).find(script => /\/Auth\.js(?:\?|$)/.test(script.src));
    const caminhoApi = new URL('api/', scriptAuth?.src || window.location.href).href;
    const HOME = new URL('../../View/pages/index.php', caminhoApi).href;
    const ENDPOINT = {
        sessao: new URL('sessao.php', caminhoApi).href,
        login: new URL('login.php', caminhoApi).href,
        cadastro: new URL('cadastro.php', caminhoApi).href,
        logout: new URL('logout.php', caminhoApi).href,
        recuperar: new URL('solicitar-recuperacao.php', caminhoApi).href,
        redefinir: new URL('redefinir-senha.php', caminhoApi).href
    };

    const PREFIXO_ERRO = 'auth-erro-';
    const MIN_SENHA = 10;

    let usuario = null;
    let csrf = null;
    let promessaSessao = null;

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
        const tipoFormulario = form.getAttribute('data-auth-form');
        const ehCadastro = tipoFormulario === 'cadastro' || tipoFormulario === 'redefinir';
        const erros = {};

        form.querySelectorAll('[name][required]').forEach(input => {
            const vazio = input.type === 'checkbox' ? !input.checked : !String(dados[input.name] || '').trim();
            if (vazio) erros[input.name] = 'obrigatorios';
        });

        if (dados.email && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(dados.email)) {
            erros.email = 'email-invalido';
        }

        if (ehCadastro && dados.senha && (dados.senha.length < MIN_SENHA || dados.senha.length > 1024)) {
            erros.senha = 'senha-curta';
        }

        if (ehCadastro && dados.senha && dados.senha_confirma && dados.senha !== dados.senha_confirma) {
            erros.senha_confirma = 'senhas-diferentes';
        }

        if (dados.telefone && (soDigitos(dados.telefone).length < 10 || soDigitos(dados.telefone).length > 11)) {
            erros.telefone = 'telefone-invalido';
        }

        return erros;
    }

    function limparErrosCampos(form) {
        form.querySelectorAll('.auth-field').forEach(campo => {
            campo.classList.remove('invalido');
            const input = campo.querySelector('input');
            input?.removeAttribute('aria-invalid');
            input?.removeAttribute('aria-describedby');
            campo.querySelector('.auth-field-error')?.remove();
        });
    }

    function marcarErrosCampos(form, campos) {
        Object.keys(campos || {}).forEach(nome => {
            const input = form.querySelector('[name="' + nome + '"]');
            if (input?.closest('.auth-field')) {
                const campo = input.closest('.auth-field');
                campo.classList.add('invalido');
                input.setAttribute('aria-invalid', 'true');
                let detalhe = campo.querySelector('.auth-field-error');
                if (!detalhe) {
                    detalhe = document.createElement('span');
                    detalhe.className = 'auth-field-error';
                    detalhe.id = input.id + '-error';
                    campo.appendChild(detalhe);
                }
                input.setAttribute('aria-describedby', detalhe.id);
                traduzirMensagem(detalhe, PREFIXO_ERRO + campos[nome], 'Confira este campo.');
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
        box.classList.toggle('erro', Boolean(codigo));
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
            botao.setAttribute('aria-busy', 'true');
        } else {
            botao.disabled = false;
            botao.classList.remove('carregando');
            botao.removeAttribute('aria-busy');
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
            mostrar(form, erros[Object.keys(erros)[0]], null, erros);
            return;
        }

        dados.tipo = form.getAttribute('data-auth-tipo') || 'cliente';
        dados._formulario = form.getAttribute('data-auth-form');

        const ehCadastro = dados._formulario === 'cadastro';
        const ehRecuperacao = dados._formulario === 'recuperar';
        const ehRedefinicao = dados._formulario === 'redefinir';
        carregarBotao(botao, true);

        if (!csrf) {
            await (promessaSessao || carregarSessao());
        }

        const endpoint = ehCadastro ? ENDPOINT.cadastro
            : ehRecuperacao ? ENDPOINT.recuperar
                : ehRedefinicao ? ENDPOINT.redefinir
                    : ENDPOINT.login;

        const resposta = await pedir(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf || ''
            },
            body: JSON.stringify(dados)
        });

        carregarBotao(botao, false);

        if (resposta?.ok) {
            if (ehRecuperacao) {
                const box = boxMensagem(form);
                box.hidden = false;
                box.classList.remove('erro');
                box.classList.add('sucesso');
                box.textContent = resposta.mensagem;
                if (resposta.debug_url) {
                    const link = document.createElement('a');
                    link.href = resposta.debug_url;
                    link.textContent = ' Abrir link de teste';
                    link.className = 'auth-debug-link';
                    box.appendChild(link);
                }
                form.querySelector('input[name="email"]')?.setAttribute('disabled', 'disabled');
                botao.disabled = true;
                return;
            }

            if (ehRedefinicao) {
                window.location.href = resposta.redirect || 'login.php?senha=redefinida';
                return;
            }

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
        const perfil = document.getElementById('authProfileLink');

        if (nome && usuario?.nome) {
            nome.textContent = usuario.nome.split(' ')[0];
        }

        if (!link || !box) return;

        if (usuario) {
            if (perfil) {
                perfil.href = usuario.tipo === 'profissional'
                    ? perfil.dataset.profissionalUrl
                    : perfil.dataset.clienteUrl;
            }
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

        csrf = resposta.csrf || csrf;
        usuario = resposta.logado ? resposta.usuario : null;
        atualizarHeader();
        return usuario;
    }

    async function sair() {
        if (!csrf) await (promessaSessao || carregarSessao());
        carregarBotao(document.getElementById('authLogoutBtn'), true);
        const resposta = await pedir(ENDPOINT.logout, {
            method: 'POST',
            headers: { 'X-CSRF-Token': csrf || '' }
        });
        if (resposta?.ok) {
            window.location.href = resposta.redirect || document.body.getAttribute('data-auth-home') || HOME;
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

    function ligarSenhas() {
        document.querySelectorAll('input[type="password"]').forEach(input => {
            if (input.autocomplete === 'new-password') {
                input.setAttribute('minlength', String(MIN_SENHA));
                input.setAttribute('maxlength', '1024');
            }
            const campo = input.closest('.auth-field');
            if (!campo || campo.querySelector('.auth-password-toggle')) return;
            campo.classList.add('auth-field-password');
            const botao = document.createElement('button');
            botao.type = 'button';
            botao.className = 'auth-password-toggle';
            botao.setAttribute('aria-label', 'Mostrar senha');
            botao.innerHTML = '<i class="ri-eye-line" aria-hidden="true"></i>';
            botao.addEventListener('click', () => {
                const mostrar = input.type === 'password';
                input.type = mostrar ? 'text' : 'password';
                botao.setAttribute('aria-label', mostrar ? 'Ocultar senha' : 'Mostrar senha');
                botao.innerHTML = '<i class="' + (mostrar ? 'ri-eye-off-line' : 'ri-eye-line') + '" aria-hidden="true"></i>';
            });
            campo.appendChild(botao);
        });
    }

    function avisarEstadoDaPagina() {
        const params = new URLSearchParams(window.location.search);
        const form = document.querySelector('form[data-auth-form="login"]');
        if (!form) return;
        if (params.get('motivo') === 'login') mostrar(form, 'needs-login');
        if (params.get('senha') === 'redefinida') mostrar(form, null, 'Senha redefinida. Agora você já pode entrar.');
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
        ligarSenhas();
        avisarEstadoDaPagina();

        // a sessao e sempre buscada, em qualquer pagina, para o header mostrar o usuario certo;
        // a guarda reaproveita essa mesma promessa em vez de fazer uma segunda chamada
        promessaSessao = carregarSessao();
        if (document.body.getAttribute('data-auth-requer')) {
            promessaSessao.then(guardarRota);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciar);
    } else {
        iniciar();
    }
})();
