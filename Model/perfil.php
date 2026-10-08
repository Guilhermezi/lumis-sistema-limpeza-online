<?php

/**
 * Consultas usadas pelos perfis.
 *
 * Toda informação variável exibida nas páginas de perfil passa por este
 * arquivo. Assim, a view não precisa manter nomes, datas ou totais fictícios.
 */

function executarConsultaPerfil(PDO $pdo, string $sql, array $parametros = []): PDOStatement
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    return $stmt;
}

function buscarResumoPerfilCliente(PDO $pdo, int $clienteId): array
{
    $sql = "SELECT
        (SELECT COUNT(*) FROM Contratacao WHERE id_cliente = :cliente_contratacao) AS servicos,
        (SELECT COUNT(*) FROM Avaliacao WHERE id_cliente = :cliente_avaliacao) AS avaliacoes,
        (SELECT COUNT(*) FROM Favorito WHERE id_cliente = :cliente_favorito) AS favoritos,
        (SELECT COUNT(*) FROM (
            SELECT id_profissional
            FROM Contratacao
            WHERE id_cliente = :cliente_recorrente
            GROUP BY id_profissional
            HAVING COUNT(*) >= 2
        ) recorrentes) AS recorrentes";

    $linha = executarConsultaPerfil($pdo, $sql, [
        'cliente_contratacao' => $clienteId,
        'cliente_avaliacao' => $clienteId,
        'cliente_favorito' => $clienteId,
        'cliente_recorrente' => $clienteId,
    ])->fetch() ?: [];

    return array_map('intval', array_merge([
        'servicos' => 0,
        'avaliacoes' => 0,
        'favoritos' => 0,
        'recorrentes' => 0,
    ], $linha));
}

function buscarResumoPerfilProfissional(PDO $pdo, int $profissionalId): array
{
    $sql = "SELECT
        (SELECT COUNT(*) FROM Contratacao WHERE id_profissional = :pro_contratacao) AS servicos,
        (SELECT COUNT(*) FROM Avaliacao WHERE id_profissional = :pro_avaliacao) AS avaliacoes,
        (SELECT COALESCE(ROUND(AVG(nota_avaliacao), 1), 0) FROM Avaliacao WHERE id_profissional = :pro_media) AS media,
        (SELECT COALESCE(ROUND(100 * AVG(nota_avaliacao >= 4)), 0) FROM Avaliacao WHERE id_profissional = :pro_aprovacao) AS aprovacao,
        (SELECT COUNT(*) FROM (
            SELECT id_cliente
            FROM Contratacao
            WHERE id_profissional = :pro_recorrente
            GROUP BY id_cliente
            HAVING COUNT(*) >= 2
        ) recorrentes) AS recorrentes";

    $linha = executarConsultaPerfil($pdo, $sql, [
        'pro_contratacao' => $profissionalId,
        'pro_avaliacao' => $profissionalId,
        'pro_media' => $profissionalId,
        'pro_aprovacao' => $profissionalId,
        'pro_recorrente' => $profissionalId,
    ])->fetch() ?: [];

    return [
        'servicos' => (int) ($linha['servicos'] ?? 0),
        'avaliacoes' => (int) ($linha['avaliacoes'] ?? 0),
        'media' => (float) ($linha['media'] ?? 0),
        'aprovacao' => (int) ($linha['aprovacao'] ?? 0),
        'recorrentes' => (int) ($linha['recorrentes'] ?? 0),
    ];
}

function buscarEspecialidadesPerfil(PDO $pdo, int $profissionalId): array
{
    return executarConsultaPerfil($pdo, "SELECT s.nome_servico
        FROM Especialidade e
        INNER JOIN Servicos s ON s.id_servico = e.id_servico
        WHERE e.id_profissional = :profissional
        ORDER BY s.nome_servico", ['profissional' => $profissionalId])->fetchAll(PDO::FETCH_COLUMN);
}

function buscarVisitasPerfil(PDO $pdo, string $tipo, int $usuarioId, int $limite = 5): array
{
    $coluna = $tipo === 'profissional' ? 'a.id_profissional' : 'c.id_cliente';
    $pessoa = $tipo === 'profissional' ? 'cl.nome AS pessoa' : 'p.nome AS pessoa';
    $limite = max(1, min(20, $limite));

    $sql = "SELECT a.id_agendamento, a.data_agenda, a.hora_inicio_agenda,
            a.hora_fim_agenda, a.status_agenda, {$pessoa},
            COALESCE(itens.servicos, 'Serviço sem item informado') AS servicos
        FROM Agendamento a
        INNER JOIN Contratacao c ON c.id_contratacao = a.id_contratacao
        INNER JOIN Cliente cl ON cl.id_cliente = c.id_cliente
        INNER JOIN Profissionais p ON p.id_profissional = a.id_profissional
        LEFT JOIN (
            SELECT ic.id_contratacao,
                   GROUP_CONCAT(DISTINCT s.nome_servico ORDER BY s.nome_servico SEPARATOR ', ') AS servicos
            FROM ItemContratacao ic
            INNER JOIN Servicos s ON s.id_servico = ic.id_servico
            GROUP BY ic.id_contratacao
        ) itens ON itens.id_contratacao = c.id_contratacao
        WHERE {$coluna} = :usuario
          AND a.data_agenda >= CURDATE()
          AND LOWER(a.status_agenda) NOT LIKE 'cancel%'
          AND LOWER(a.status_agenda) NOT LIKE 'conclu%'
        ORDER BY a.data_agenda, a.hora_inicio_agenda
        LIMIT {$limite}";

    return executarConsultaPerfil($pdo, $sql, ['usuario' => $usuarioId])->fetchAll();
}

function buscarDatasAgendaPerfil(PDO $pdo, string $tipo, int $usuarioId): array
{
    $coluna = $tipo === 'profissional' ? 'a.id_profissional' : 'c.id_cliente';
    $sql = "SELECT DISTINCT DATE_FORMAT(a.data_agenda, '%Y-%m-%d')
        FROM Agendamento a
        INNER JOIN Contratacao c ON c.id_contratacao = a.id_contratacao
        WHERE {$coluna} = :usuario
          AND LOWER(a.status_agenda) NOT LIKE 'cancel%'
        ORDER BY a.data_agenda";

    return executarConsultaPerfil($pdo, $sql, ['usuario' => $usuarioId])->fetchAll(PDO::FETCH_COLUMN);
}

function buscarAvaliacoesPerfil(PDO $pdo, string $tipo, int $usuarioId, int $limite = 5): array
{
    $limite = max(1, min(20, $limite));
    $coluna = $tipo === 'profissional' ? 'av.id_profissional' : 'av.id_cliente';
    $pessoa = $tipo === 'profissional' ? 'cl.nome AS pessoa' : 'p.nome AS pessoa';

    $sql = "SELECT av.id_avaliacao, av.nota_avaliacao, av.comentario_avaliacao,
            c.data_contratacao AS data_avaliacao, {$pessoa}
        FROM Avaliacao av
        INNER JOIN Contratacao c ON c.id_contratacao = av.id_contratacao
        INNER JOIN Cliente cl ON cl.id_cliente = av.id_cliente
        INNER JOIN Profissionais p ON p.id_profissional = av.id_profissional
        WHERE {$coluna} = :usuario
        ORDER BY c.data_contratacao DESC, av.id_avaliacao DESC
        LIMIT {$limite}";

    return executarConsultaPerfil($pdo, $sql, ['usuario' => $usuarioId])->fetchAll();
}

function buscarProfissionalPreferidoPerfil(PDO $pdo, int $clienteId): ?array
{
    $sql = "SELECT p.id_profissional, p.nome, p.foto, p.regiao_atuacao, p.valor_minimo,
            COALESCE(notas.media, 0) AS media,
            COALESCE(notas.total, 0) AS total_avaliacoes,
            COALESCE(especialidades.nomes, 'Nenhuma especialidade cadastrada') AS especialidades
        FROM Favorito f
        INNER JOIN Profissionais p ON p.id_profissional = f.id_profissional
        LEFT JOIN (
            SELECT id_profissional, ROUND(AVG(nota_avaliacao), 1) AS media, COUNT(*) AS total
            FROM Avaliacao GROUP BY id_profissional
        ) notas ON notas.id_profissional = p.id_profissional
        LEFT JOIN (
            SELECT e.id_profissional,
                   GROUP_CONCAT(s.nome_servico ORDER BY s.nome_servico SEPARATOR ', ') AS nomes
            FROM Especialidade e
            INNER JOIN Servicos s ON s.id_servico = e.id_servico
            GROUP BY e.id_profissional
        ) especialidades ON especialidades.id_profissional = p.id_profissional
        WHERE f.id_cliente = :cliente
        ORDER BY f.data DESC
        LIMIT 1";

    $linha = executarConsultaPerfil($pdo, $sql, ['cliente' => $clienteId])->fetch();
    return $linha ?: null;
}

function buscarAtividadesPerfil(PDO $pdo, string $tipo, int $usuarioId, int $limite = 5): array
{
    $limite = max(1, min(20, $limite));
    $colunaContrato = $tipo === 'profissional' ? 'c.id_profissional' : 'c.id_cliente';
    $pessoaContrato = $tipo === 'profissional' ? 'cl.nome' : 'p.nome';
    $colunaAvaliacao = $tipo === 'profissional' ? 'av.id_profissional' : 'av.id_cliente';
    $pessoaAvaliacao = $tipo === 'profissional' ? 'cl.nome' : 'p.nome';

    $sql = "SELECT tipo, data_evento, pessoa, detalhe, nota FROM (
            SELECT 'contratacao' AS tipo, c.data_contratacao AS data_evento,
                   {$pessoaContrato} AS pessoa, c.status_contratacao AS detalhe, NULL AS nota,
                   c.id_contratacao AS ordem
            FROM Contratacao c
            INNER JOIN Cliente cl ON cl.id_cliente = c.id_cliente
            INNER JOIN Profissionais p ON p.id_profissional = c.id_profissional
            WHERE {$colunaContrato} = :usuario_contrato
            UNION ALL
            SELECT 'avaliacao' AS tipo, c.data_contratacao AS data_evento,
                   {$pessoaAvaliacao} AS pessoa, av.comentario_avaliacao AS detalhe,
                   av.nota_avaliacao AS nota, av.id_avaliacao AS ordem
            FROM Avaliacao av
            INNER JOIN Contratacao c ON c.id_contratacao = av.id_contratacao
            INNER JOIN Cliente cl ON cl.id_cliente = av.id_cliente
            INNER JOIN Profissionais p ON p.id_profissional = av.id_profissional
            WHERE {$colunaAvaliacao} = :usuario_avaliacao
        ) atividades
        ORDER BY data_evento DESC, ordem DESC
        LIMIT {$limite}";

    return executarConsultaPerfil($pdo, $sql, [
        'usuario_contrato' => $usuarioId,
        'usuario_avaliacao' => $usuarioId,
    ])->fetchAll();
}

function formatarDataPerfil(?string $data, bool $comHora = false): string
{
    if (!$data) {
        return '—';
    }

    try {
        $objeto = new DateTime($data);
        return $objeto->format($comHora ? 'd/m/Y H:i' : 'd/m/Y');
    } catch (Exception $e) {
        return '—';
    }
}

function diaSemanaPerfil(string $data): string
{
    $dias = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
    return $dias[(int) (new DateTime($data))->format('w')];
}

function mesCurtoPerfil(string $data): string
{
    $meses = [1 => 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    return $meses[(int) (new DateTime($data))->format('n')];
}

function iniciaisPerfil(string $nome): string
{
    $partes = preg_split('/\s+/u', trim($nome), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if (!$partes) {
        return '?';
    }

    $primeira = mb_substr($partes[0], 0, 1, 'UTF-8');
    $ultima = count($partes) > 1 ? mb_substr($partes[count($partes) - 1], 0, 1, 'UTF-8') : '';
    return mb_strtoupper($primeira . $ultima, 'UTF-8');
}
