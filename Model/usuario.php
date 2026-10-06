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

    // Grava o usuario no banco e devolve o id criado; null = email ja cadastrado
    // $dados = os campos que vieram do formulário, já com data-nascimento e demais
    // $cfg   = a configuração do tipo, que vem de configTipo('cliente') ou configTipo('profissional')
    //          Ela diz QUAL tabela gravar e QUAIS colunas aquele tipo tem.
    //          Por isso o "array $cfg": se o tipo não existir no mapa, configTipo devolve
    //          null e o PHP solta TypeError aqui ANTES de qualquer SQL ser montado.
    function cadastrar(PDO $pdo, array $dados, array $cfg){
        // junta as colunas comuns com as específicas deste tipo
        $permitidas = array_merge($cfg['comuns'], $cfg['especificos']);
        // mantém só as que existem no tipo E chegaram no formulário (preserva a ordem do mapa)
        $colunas = array_values(array_intersect($permitidas, array_keys($dados)));

        $senha = password_hash($dados['senha'] ?? '', PASSWORD_DEFAULT);

        // telefone fica só com dígitos: "(11) 98765-4321" vira "11987654321"
        $telefone = preg_replace('/\D/', '', $dados['telefone'] ?? '');

        // guarda, por coluna, o valor exato que será enviado ao banco
        $valores = [];
        foreach ($colunas as $coluna){
            // senha e telefone já tratadas acima: entram prontas, sem passar pelo trim
            if ($coluna === 'senha'){ 
                $valores[$coluna] = $senha; 
                continue; 
                }
            if ($coluna === 'telefone'){ 
                $valores[$coluna] = $telefone; 
                continue; 
                }

            // (string) converte para texto: se o campo vier null, vira '' em vez de dar erro no trim
            $valor = trim((string) ($dados[$coluna] ?? ''));
            // vazio vira NULL (coluna opcional fica NULL de verdade no banco);
            // com texto, usa o valor JÁ TRIMADO, senão o nome entraria com espaço das pontas
            $valores[$coluna] = $valor === '' ? null : $valor;
        }

        // rede de segurança: se nenhuma coluna foi montada, ou a senha não entrou, para
        // aqui com exceção legível em vez de gerar "INSERT INTO Cliente ()", que o
        // banco recusaria com mensagem confusa. Não deveria chegar aqui, mas se chegar, quebra limpo
        if (!$colunas || !isset($valores['senha'])){
            throw new InvalidArgumentException('cadastro sem senha');
        }

        // transforma cada nome de coluna no marcador do PDO:
        //   nome, email, telefone  ->  :nome, :email, :telefone
        // no SQL eles são só "espaços em branco"; o valor viaja fora, pelo execute()
        $marcadores = array_map(fn($c) => ':' . $c, array_keys($valores));

        // monta o INSERT com dois pares paralelos: nomes de coluna e marcadores.
        // Só o nome da TABELA entra interpolado na string, e ele nunca vem do usuário:
        // vem de $cfg, que só tem 'Cliente' e 'Profissionais', escritos à mão no mapa
        $sql = 'INSERT INTO ' . $cfg['tabela'] . ' (' . implode(', ', array_keys($valores)) . ') VALUES (' . implode(', ', $marcadores) . ')';
        // resultado: INSERT INTO Cliente (nome, email, telefone, senha) VALUES (:nome, :email, :telefone, :senha)

        try {
            // prepare() envia o SQL ao banco; execute() envia os dados de $valores,
            // cada um ligado ao marcador pelo nome da coluna
            $stmt = $pdo->prepare($sql);
            $stmt->execute($valores);
            // pergunta ao banco qual id do auto_increment este INSERT gerou, já como int
            return (int) $pdo->lastInsertId();
        } catch (PDOException $e){
            // 1062 = violação de chave única, ou seja, email repetido.
            // viramos null para o endpoint responder "email-em-uso" em vez de erro 500
            if (($e->errorInfo[1] ?? null) === 1062){
                return null;
            }
            // qualquer outro erro do banco sobe normalmente
            throw $e;
        }
    }

    // Busca um usuário pelo id e devolve a linha do banco; null se não existir
    function buscarUsuario(PDO $pdo, int $id, string $tipo){
        $cfg = configTipo($tipo);

        if ($cfg === null){
            return null;
        }

        // nome da tabela e da chave primária vêm do mapa, nunca do usuário
        $sql = 'SELECT * FROM ' . $cfg['tabela'] . ' WHERE ' . $cfg['pk'] . ' = :id LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }