<?php
    // Mapa dos tipos de usuário: cada tipo aponta para sua tabela e suas colunas
    function tiposSuportados(){
        return [
            'cliente' => [
                'tabela'      => 'Cliente',
                'pk'          => 'id_cliente',
                'perfil'      => 'perfil.php',
                'comuns'      => ['nome', 'email', 'telefone', 'data_nascimento', 'senha', 'foto'],
                'especificos' => [],
            ],
            'profissional' => [
                'tabela'      => 'Profissionais',
                'pk'          => 'id_profissional',
                'perfil'      => 'perfil-profissional.php',
                'comuns'      => ['nome', 'email', 'telefone', 'data_nascimento', 'senha', 'foto'],
                'especificos' => ['experiencia', 'valor_minimo', 'regiao_atuacao', 'verificado'],
            ],
        ];
    }

    // Busca a configuração de um tipo; devolve null se o tipo não existir
    function configTipo($tipo){
        $tipos = tiposSuportados();               // carrega o mapa completo
        return $tipos[$tipo] ?? null;             // devolve só a configuração pedida
    }

    // Limite de caracteres de cada coluna, conforme definido no banco
    function limite($coluna){
        return [                                   // lista dos limites por nome de coluna
            'nome'           => 100,
            'email'          => 100,
            'telefone'       => 15,
            'experiencia'    => 50,
            'regiao_atuacao' => 100,
            'foto'           => 255,
        ][$coluna] ?? null;                       // indexa pelo nome e devolve o limite
    }

    // Confere os dados do cadastro; devolve array vazio se estiver tudo certo
    function validarCadastro($dados, $cfg){
        $erros = [];                              // começa sem nenhum erro registrado

        // ---- NOME ----
        $nome = trim($dados['nome'] ?? '');       // tira espaços das pontas; ?? '' evita campo ausente
        if ($nome === ''){                        // comparação estrita: '' e 0 não se confundem
            $erros['nome'] = 'obrigatorios';
        } elseif (mb_strlen($nome) < 2 || mb_strlen($nome) > limite('nome')){
            $erros['nome'] = 'nome-invalido';     // mb_strlen conta caracteres, strlen contaria bytes
        }

        // ---- EMAIL ----
        $email = trim($dados['email'] ?? '');
        if ($email === ''){
            $erros['email'] = 'obrigatorios';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){   // valida o formato do email
            $erros['email'] = 'email-invalido';
        }

        // ---- TELEFONE ----
        $telefone = preg_replace('/\D/', '', $dados['telefone'] ?? '');   // \D remove tudo que não é dígito
        if ($telefone === ''){
            $erros['telefone'] = 'obrigatorios';
        } elseif (mb_strlen($telefone) < 10 || mb_strlen($telefone) > 11){ // celular: 11, fixo: 10
            $erros['telefone'] = 'telefone-invalido';
        }

        // ---- SENHA ----
        $senha = $dados['senha'] ?? '';
        if ($senha === ''){
            $erros['senha'] = 'obrigatorios';
        } elseif (mb_strlen($senha) < 6){         // mínimo de 6 caracteres
            $erros['senha'] = 'senha-curta';
        }

        // ---- CONFIRMAÇÃO DE SENHA ----
        $confirmar = $dados['senha_confirma'] ?? '';
        if ($confirmar !== '' && $senha !== $confirmar){               // !== compara valor e tipo
            $erros['senha_confirma'] = 'senhas-diferentes';
        }

        // ---- DATA DE NASCIMENTO ----
        $nascimento = trim($dados['data_nascimento'] ?? '');
        if ($nascimento !== ''){                   // campo é opcional: vazio não é erro
            $data = DateTime::createFromFormat('Y-m-d', $nascimento);
            // createFromFormat rola 31/02 para 03/03 sem avisar, então comparamos de volta
            if (!$data || $data->format('Y-m-d') !== $nascimento || $data > new DateTime('today')){
                $erros['data_nascimento'] = 'nascimento-invalido';
            }
        }

        // ---- CAMPOS ESPECÍFICOS DO TIPO ----
        foreach (['experiencia', 'regiao_atuacao'] as $coluna){       // valida cada coluna do tipo
            if (!in_array($coluna, $cfg['especificos'], true)){       // true = comparação estrita
                continue;                                             // campo não existe neste tipo: pula
            }
            $valor = trim($dados[$coluna] ?? '');
            if ($coluna === 'regiao_atuacao' && $valor === ''){       // regiao_atuacao é NOT NULL no banco
                $erros[$coluna] = 'obrigatorios';
            } elseif ($valor !== '' && mb_strlen($valor) > limite($coluna)){
                $erros[$coluna] = $coluna . '-longa';                 // impede truncamento silencioso
            }
        }

        return $erros;                              // array vazio = cadastrou tudo certo
    }