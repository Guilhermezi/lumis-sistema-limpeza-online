<?php

/**
 * Dados públicos da página inicial.
 * A ordenação por id é usada enquanto o catálogo não possui um campo próprio
 * para definir quais serviços devem ficar em destaque.
 */
function buscarServicosHome(PDO $pdo, int $limite = 3): array
{
    $limite = max(1, min(12, $limite));
    $sql = "SELECT id_servico, nome_servico, descricao_servico, tipo, preco, periodo
        FROM Servicos
        ORDER BY id_servico
        LIMIT {$limite}";
    return $pdo->query($sql)->fetchAll();
}

function buscarAvaliacoesHome(PDO $pdo, int $limite = 6): array
{
    $limite = max(1, min(12, $limite));
    $sql = "SELECT av.nota_avaliacao, av.comentario_avaliacao,
            cl.nome AS cliente, p.nome AS profissional,
            COALESCE(itens.servico, 'Serviço de limpeza') AS servico
        FROM Avaliacao av
        INNER JOIN Cliente cl ON cl.id_cliente = av.id_cliente
        INNER JOIN Profissionais p ON p.id_profissional = av.id_profissional
        INNER JOIN Contratacao c ON c.id_contratacao = av.id_contratacao
        LEFT JOIN (
            SELECT ic.id_contratacao, MIN(s.nome_servico) AS servico
            FROM ItemContratacao ic
            INNER JOIN Servicos s ON s.id_servico = ic.id_servico
            GROUP BY ic.id_contratacao
        ) itens ON itens.id_contratacao = c.id_contratacao
        WHERE av.comentario_avaliacao IS NOT NULL
          AND TRIM(av.comentario_avaliacao) <> ''
        ORDER BY c.data_contratacao DESC, av.id_avaliacao DESC
        LIMIT {$limite}";
    return $pdo->query($sql)->fetchAll();
}

function nomePublicoHome(string $nome): string
{
    $partes = preg_split('/\s+/u', trim($nome), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if (!$partes) return 'Cliente Lumis';
    if (count($partes) === 1) return $partes[0];
    return $partes[0] . ' ' . mb_strtoupper(mb_substr($partes[count($partes) - 1], 0, 1, 'UTF-8'), 'UTF-8') . '.';
}

function iniciaisHome(string $nome): string
{
    $partes = preg_split('/\s+/u', trim($nome), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if (!$partes) return '?';
    $primeira = mb_substr($partes[0], 0, 1, 'UTF-8');
    $ultima = count($partes) > 1 ? mb_substr($partes[count($partes) - 1], 0, 1, 'UTF-8') : '';
    return mb_strtoupper($primeira . $ultima, 'UTF-8');
}
