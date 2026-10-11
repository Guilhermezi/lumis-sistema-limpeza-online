<?php

/**
 * Consultas e formatadores do catálogo público de serviços.
 * Os totais e avaliações são calculados a partir das contratações registradas.
 */
function buscarCatalogoServicos(PDO $pdo): array
{
    $sql = "SELECT s.id_servico, s.nome_servico, s.descricao_servico, s.tipo,
                   s.preco, s.imagem, s.periodo,
                   COUNT(DISTINCT av.id_avaliacao) AS total_avaliacoes,
                   COALESCE(ROUND(AVG(av.nota_avaliacao), 1), 0) AS avaliacao_real,
                   COUNT(DISTINCT c.id_contratacao) AS total_contratacoes
            FROM Servicos s
            LEFT JOIN ItemContratacao ic ON ic.id_servico = s.id_servico
            LEFT JOIN Contratacao c ON c.id_contratacao = ic.id_contratacao
            LEFT JOIN Avaliacao av ON av.id_contratacao = c.id_contratacao
            GROUP BY s.id_servico, s.nome_servico, s.descricao_servico, s.tipo,
                     s.preco, s.imagem, s.periodo
            ORDER BY s.id_servico";

    return $pdo->query($sql)->fetchAll();
}

function buscarEstatisticasCatalogo(PDO $pdo): array
{
    $sql = "SELECT
        (SELECT COUNT(*) FROM Profissionais) AS profissionais,
        (SELECT COUNT(*) FROM Contratacao WHERE LOWER(status_contratacao) LIKE 'conclu%') AS concluidos,
        (SELECT COUNT(*) FROM Avaliacao WHERE nota_avaliacao = 5) AS avaliacoes_cinco,
        (SELECT COALESCE(ROUND(100 * AVG(nota_avaliacao >= 4)), 0) FROM Avaliacao) AS satisfacao";

    $dados = $pdo->query($sql)->fetch() ?: [];
    return array_map('intval', array_merge([
        'profissionais' => 0,
        'concluidos' => 0,
        'avaliacoes_cinco' => 0,
        'satisfacao' => 0,
    ], $dados));
}

function buscarDepoimentosCatalogo(PDO $pdo, int $limite = 3): array
{
    $limite = max(1, min(6, $limite));
    $sql = "SELECT av.nota_avaliacao, av.comentario_avaliacao, cl.nome AS cliente,
                   COALESCE(MIN(s.nome_servico), 'Serviço de limpeza') AS servico
            FROM Avaliacao av
            INNER JOIN Cliente cl ON cl.id_cliente = av.id_cliente
            INNER JOIN Contratacao c ON c.id_contratacao = av.id_contratacao
            LEFT JOIN ItemContratacao ic ON ic.id_contratacao = c.id_contratacao
            LEFT JOIN Servicos s ON s.id_servico = ic.id_servico
            WHERE av.comentario_avaliacao IS NOT NULL
              AND TRIM(av.comentario_avaliacao) <> ''
            GROUP BY av.id_avaliacao, av.nota_avaliacao, av.comentario_avaliacao,
                     cl.nome, c.data_contratacao
            ORDER BY c.data_contratacao DESC, av.id_avaliacao DESC
            LIMIT {$limite}";

    return $pdo->query($sql)->fetchAll();
}

function agruparCategoriasCatalogo(array $servicos): array
{
    $categorias = [];
    foreach ($servicos as $servico) {
        $tipo = (string) $servico['tipo'];
        $categorias[$tipo] = ($categorias[$tipo] ?? 0) + 1;
    }
    ksort($categorias);
    return $categorias;
}

function rotuloCategoriaCatalogo(string $tipo): string
{
    return [
        'carros' => 'Carros',
        'comercial' => 'Comercial',
        'estofados' => 'Estofados',
        'residencial' => 'Residencial',
        'vidros' => 'Vidros',
    ][$tipo] ?? ucfirst(str_replace('-', ' ', $tipo));
}

function iconeCategoriaCatalogo(string $tipo): string
{
    return [
        'carros' => 'ri-car-line',
        'comercial' => 'ri-building-2-line',
        'estofados' => 'ri-armchair-line',
        'residencial' => 'ri-home-9-line',
        'vidros' => 'ri-window-line',
    ][$tipo] ?? 'ri-sparkling-2-line';
}

function descricaoCategoriaCatalogo(string $tipo): string
{
    return [
        'carros' => 'Limpeza interna e externa',
        'comercial' => 'Escritórios, lojas e empresas',
        'estofados' => 'Sofás, cadeiras e tapetes',
        'residencial' => 'Casas, apartamentos e kitnets',
        'vidros' => 'Janelas e superfícies de vidro',
    ][$tipo] ?? 'Serviços de limpeza disponíveis';
}

function imagemCatalogo(?string $imagem): string
{
    $arquivo = basename(str_replace('\\', '/', (string) $imagem));
    $caminho = dirname(__DIR__) . '/View/img/' . $arquivo;
    return $arquivo !== '' && is_file($caminho) ? $arquivo : 'Home_cidade.png';
}

function nomePublicoCatalogo(string $nome): string
{
    $partes = preg_split('/\s+/u', trim($nome), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if (!$partes) return 'Cliente Lumis';
    if (count($partes) === 1) return $partes[0];
    return $partes[0] . ' ' . mb_strtoupper(mb_substr(end($partes), 0, 1, 'UTF-8'), 'UTF-8') . '.';
}

function inicialCatalogo(string $nome): string
{
    return mb_strtoupper(mb_substr(trim($nome), 0, 1, 'UTF-8'), 'UTF-8') ?: '?';
}
