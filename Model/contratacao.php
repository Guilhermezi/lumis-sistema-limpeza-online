<?php

/**
 * Registra uma solicitação completa de serviço.
 * O preço e a duração são sempre relidos do banco; valores enviados pelo
 * navegador nunca são usados para calcular ou gravar a contratação.
 */
function criarContratacao(PDO $pdo, int $clienteId, array $dados): array
{
    $servicoId = filter_var($dados['servico_id'] ?? null, FILTER_VALIDATE_INT);
    $dataTexto = trim((string) ($dados['data'] ?? ''));
    $horaTexto = trim((string) ($dados['hora'] ?? ''));
    $banheiros = filter_var($dados['banheiros'] ?? 1, FILTER_VALIDATE_INT);
    $quartos = filter_var($dados['quartos'] ?? 1, FILTER_VALIDATE_INT);
    $cep = preg_replace('/\D/', '', (string) ($dados['cep'] ?? ''));
    $rua = trim((string) ($dados['rua'] ?? ''));
    $numero = trim((string) ($dados['numero'] ?? ''));
    $complemento = trim((string) ($dados['complemento'] ?? ''));
    $bairro = trim((string) ($dados['bairro'] ?? ''));
    $cidade = trim((string) ($dados['cidade'] ?? ''));
    $estado = mb_strtoupper(trim((string) ($dados['estado'] ?? '')), 'UTF-8');
    $metodo = (string) ($dados['metodo_pagamento'] ?? '');
    $metodos = [
        'pix' => 'PIX',
        'cartao' => 'Cartão',
        'boleto' => 'Boleto',
        'na-hora' => 'Pagamento no atendimento',
    ];

    if (!$servicoId) throw new InvalidArgumentException('servico-invalido');
    if (!preg_match('/^\d{2}:\d{2}$/', $horaTexto)) throw new InvalidArgumentException('horario-invalido');
    if (!$banheiros || $banheiros < 1 || $banheiros > 20) throw new InvalidArgumentException('quantidade-invalida');
    if (!$quartos || $quartos < 1 || $quartos > 30) throw new InvalidArgumentException('quantidade-invalida');
    if (strlen($cep) !== 8 || $rua === '' || $numero === '' || $bairro === '' || $cidade === '' || !preg_match('/^[A-Z]{2}$/', $estado)) {
        throw new InvalidArgumentException('endereco-invalido');
    }
    if (!isset($metodos[$metodo])) throw new InvalidArgumentException('pagamento-invalido');

    $data = DateTimeImmutable::createFromFormat('!Y-m-d', $dataTexto);
    if (!$data || $data->format('Y-m-d') !== $dataTexto) throw new InvalidArgumentException('data-invalida');
    $hoje = new DateTimeImmutable('today');
    if ($data < $hoje || $data > $hoje->modify('+180 days')) throw new InvalidArgumentException('data-invalida');

    $pdo->beginTransaction();
    try {
        $servicoStmt = $pdo->prepare(
            'SELECT id_servico, nome_servico, preco, periodo FROM Servicos WHERE id_servico = :id LIMIT 1 FOR UPDATE'
        );
        $servicoStmt->execute(['id' => $servicoId]);
        $servico = $servicoStmt->fetch();
        if (!$servico) throw new InvalidArgumentException('servico-invalido');

        preg_match('/\d+/', (string) ($servico['periodo'] ?? ''), $duracaoEncontrada);
        $duracao = max(1, min(12, (int) ($duracaoEncontrada[0] ?? 4)));
        $inicio = DateTimeImmutable::createFromFormat('!H:i', $horaTexto);
        if (!$inicio || $inicio->format('H:i') !== $horaTexto) throw new InvalidArgumentException('horario-invalido');
        $fim = $inicio->modify("+{$duracao} hours");
        if ($inicio->format('H:i') < '07:00' || $fim->format('H:i') > '20:00' || $fim->format('Y-m-d') !== '1970-01-01') {
            throw new InvalidArgumentException('horario-invalido');
        }

        $profissionais = $pdo->prepare(
            "SELECT p.id_profissional, p.nome,
                    COALESCE(AVG(av.nota_avaliacao), 0) AS media
             FROM Especialidade e
             INNER JOIN Profissionais p ON p.id_profissional = e.id_profissional
             LEFT JOIN Avaliacao av ON av.id_profissional = p.id_profissional
             WHERE e.id_servico = :servico
             GROUP BY p.id_profissional, p.nome, p.verificado
             ORDER BY p.verificado DESC, media DESC, p.id_profissional"
        );
        $profissionais->execute(['servico' => $servicoId]);
        $profissional = null;
        foreach ($profissionais->fetchAll() as $candidato) {
            $conflito = $pdo->prepare(
                "SELECT id_agendamento FROM Agendamento
                 WHERE id_profissional = :profissional
                   AND data_agenda = :data
                   AND hora_inicio_agenda < :fim
                   AND hora_fim_agenda > :inicio
                   AND LOWER(status_agenda) NOT LIKE 'cancel%'
                 LIMIT 1 FOR UPDATE"
            );
            $conflito->execute([
                'profissional' => $candidato['id_profissional'],
                'data' => $dataTexto,
                'inicio' => $inicio->format('H:i:s'),
                'fim' => $fim->format('H:i:s'),
            ]);
            if (!$conflito->fetch()) {
                $profissional = $candidato;
                break;
            }
        }
        if (!$profissional) throw new RuntimeException('sem-profissional');

        $endereco = $pdo->prepare(
            "INSERT INTO Endereco (id_cliente, rua, numero, complemento, bairro, cidade, estado, cep)
             VALUES (:cliente, :rua, :numero, :complemento, :bairro, :cidade, :estado, :cep)
             ON DUPLICATE KEY UPDATE rua = VALUES(rua), numero = VALUES(numero),
                 complemento = VALUES(complemento), bairro = VALUES(bairro),
                 cidade = VALUES(cidade), estado = VALUES(estado), cep = VALUES(cep)"
        );
        $endereco->execute([
            'cliente' => $clienteId,
            'rua' => mb_substr($rua, 0, 100),
            'numero' => mb_substr($numero, 0, 10),
            'complemento' => $complemento === '' ? null : mb_substr($complemento, 0, 50),
            'bairro' => mb_substr($bairro, 0, 50),
            'cidade' => mb_substr($cidade, 0, 60),
            'estado' => $estado,
            'cep' => substr($cep, 0, 5) . '-' . substr($cep, 5),
        ]);

        $valor = (float) $servico['preco'];
        $statusContratacao = $metodo === 'na-hora' ? 'Agendado' : 'Aguardando pagamento';
        $contratacao = $pdo->prepare(
            "INSERT INTO Contratacao
             (status_contratacao, data_contratacao, hora_inicio_contratacao,
              hora_fim_contratacao, valor_total, id_cliente, id_profissional)
             VALUES (:status, :data, :inicio, :fim, :valor, :cliente, :profissional)"
        );
        $contratacao->execute([
            'status' => $statusContratacao,
            'data' => $dataTexto,
            'inicio' => $inicio->format('H:i:s'),
            'fim' => $fim->format('H:i:s'),
            'valor' => $valor,
            'cliente' => $clienteId,
            'profissional' => $profissional['id_profissional'],
        ]);
        $contratacaoId = (int) $pdo->lastInsertId();

        $pdo->prepare(
            "INSERT INTO ItemContratacao
             (id_contratacao, id_servico, qtde_banheiros, qtde_quartos, qtde_horas, subtotal)
             VALUES (:contratacao, :servico, :banheiros, :quartos, :horas, :subtotal)"
        )->execute([
            'contratacao' => $contratacaoId,
            'servico' => $servicoId,
            'banheiros' => $banheiros,
            'quartos' => $quartos,
            'horas' => $duracao,
            'subtotal' => $valor,
        ]);

        $pdo->prepare(
            "INSERT INTO Pagamentos
             (status_pagamento, valor_pagamento, forma_pagamento, id_contratacao)
             VALUES ('Pendente', :valor, :forma, :contratacao)"
        )->execute([
            'valor' => $valor,
            'forma' => $metodos[$metodo],
            'contratacao' => $contratacaoId,
        ]);

        $pdo->prepare(
            "INSERT INTO Agendamento
             (data_agenda, hora_inicio_agenda, hora_fim_agenda, status_agenda,
              id_contratacao, id_profissional)
             VALUES (:data, :inicio, :fim, 'Aguardando confirmação', :contratacao, :profissional)"
        )->execute([
            'data' => $dataTexto,
            'inicio' => $inicio->format('H:i:s'),
            'fim' => $fim->format('H:i:s'),
            'contratacao' => $contratacaoId,
            'profissional' => $profissional['id_profissional'],
        ]);

        $pdo->commit();
        return [
            'id' => $contratacaoId,
            'codigo' => 'LUM-' . str_pad((string) $contratacaoId, 6, '0', STR_PAD_LEFT),
            'servico' => $servico['nome_servico'],
            'profissional' => $profissional['nome'],
            'data' => $dataTexto,
            'hora' => $inicio->format('H:i'),
            'valor' => $valor,
            'pagamento' => $metodos[$metodo],
        ];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $e;
    }
}
