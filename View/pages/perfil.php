<?php
require_once __DIR__ . '/../partials/usuario-dados.php';

function estrelasPerfilCliente(int $nota): string
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= '<i class="ri-star-' . ($i <= $nota ? 'fill' : 'line') . '"></i>';
    }
    return $html;
}

function chaveStatusPerfil(string $status): string
{
    $status = mb_strtolower(trim($status), 'UTF-8');
    return [
        'aguardando confirmação' => 'status-agenda-aguardando',
        'confirmado' => 'status-agenda-confirmado',
        'recusado pelo profissional' => 'status-agenda-recusado',
        'cancelado pelo cliente' => 'status-agenda-cancelado-cliente',
        'cancelado pelo profissional' => 'status-agenda-cancelado-profissional',
        'concluído' => 'status-agenda-concluido',
        'agendado' => 'status-contratacao-agendado',
        'aguardando pagamento' => 'status-pagamento-aguardando',
        'pendente' => 'status-pagamento-pendente',
        'pago' => 'status-pagamento-pago',
    ][$status] ?? '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script>
        try {
            var temaSalvo = localStorage.getItem('tema');
            document.documentElement.setAttribute('data-theme', temaSalvo || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        } catch (e) { document.documentElement.setAttribute('data-theme', 'light'); }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/Logo_Sem_Nome.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/perfil.css">
    <script src="../../Controller/perfil.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title data-i18n="perfil-titulo">Lumis - Perfil</title>
</head>
<body data-page="perfil" data-auth-requer="cliente" data-auth-login="auth/decisao.php" data-auth-home="index.php">
<?php $base = ''; $active = ''; include __DIR__ . '/../partials/header.php'; ?>

<main class="profile-page">
    <section class="profile-hero">
        <div class="hero-cover"><div class="hero-cover-overlay"></div></div>
        <div class="hero-profile">
            <div class="hero-avatar-wrap">
                <?php if ($avatar): ?>
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Foto de <?= htmlspecialchars($nome) ?>" class="hero-avatar">
                <?php else: ?>
                    <div class="hero-avatar hero-avatar-placeholder" role="img" aria-label="Avatar de <?= htmlspecialchars($nome) ?>"><?= htmlspecialchars($iniciaisAvatar) ?></div>
                <?php endif; ?>
            </div>
            <div class="hero-info">
                <div class="hero-name-row"><h1><?= htmlspecialchars($nome) ?></h1><span class="badge badge-client"><i class="ri-user-heart-line"></i> <span data-i18n="perfil-badge-cliente">Cliente</span></span><a class="btn-edit-profile" href="editar-perfil.php"><i class="ri-edit-line"></i> <span data-i18n="perfil-editar-perfil">Editar perfil</span></a></div>
                <?php if (!empty($nascimento)): ?><div class="hero-meta"><span class="meta-item"><i class="ri-calendar-fill"></i> <span data-i18n="perfil-nascimento">Nascido em</span> <?= htmlspecialchars(formatarDataPerfil($nascimento)) ?></span></div><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="stats-bar" aria-label="Resumo do perfil">
        <?php foreach ([['ri-service-line', 'servicos'], ['ri-star-smile-line', 'avaliacoes'], ['ri-heart-3-line', 'favoritos'], ['ri-repeat-line', 'recorrentes']] as [$icone, $chave]): ?>
        <div class="stat-card"><div class="stat-icon"><i class="<?= $icone ?>"></i></div><div class="stat-info"><span class="stat-number" data-target="<?= $resumoPerfil[$chave] ?>">0</span><span class="stat-label" data-i18n="perfil-stat-<?= $chave ?>"><?= ['servicos' => 'Serviços', 'avaliacoes' => 'Avaliações', 'favoritos' => 'Favoritos', 'recorrentes' => 'Profissionais recorrentes'][$chave] ?></span></div></div>
        <?php endforeach; ?>
    </section>

    <div class="profile-layout">
        <div class="profile-main">
            <section class="card card-info">
                <div class="card-header"><h2><i class="ri-user-3-line"></i> <span data-i18n="perfil-info-titulo">Informações pessoais</span></h2></div>
                <div class="info-grid">
                    <div class="info-item"><span class="info-label" data-i18n="perfil-info-label-nome">Nome completo</span><span class="info-value"><?= htmlspecialchars($nome) ?></span></div>
                    <div class="info-item"><span class="info-label" data-i18n="perfil-info-label-email">E-mail</span><span class="info-value"><?= htmlspecialchars($email) ?></span></div>
                    <div class="info-item"><span class="info-label" data-i18n="perfil-info-label-telefone">Telefone</span><span class="info-value"><?= htmlspecialchars($telefoneFormatado) ?></span></div>
                </div>
            </section>

            <section class="card card-visits">
                <div class="card-header"><h2><i class="ri-calendar-event-line"></i> <span data-i18n="perfil-visitas-titulo">Próximas visitas</span></h2></div>
                <?php if (!$visitasPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-calendar-close-line"></i><p data-i18n="perfil-visitas-vazio">Você ainda não possui visitas agendadas.</p></div>
                <?php else: ?>
                <div class="visits-list">
                    <?php foreach ($visitasPerfil as $visita): ?>
                    <div class="visit-item">
                        <div class="visit-date-badge"><span class="visit-day"><?= (new DateTime($visita['data_agenda']))->format('d') ?></span><span class="visit-month"><?= htmlspecialchars(mesCurtoPerfil($visita['data_agenda'])) ?></span></div>
                        <div class="visit-content">
                            <div class="visit-top"><h4><?= htmlspecialchars(diaSemanaPerfil($visita['data_agenda'])) ?></h4><span class="visit-status status-upcoming"><?= htmlspecialchars($visita['status_agenda']) ?></span></div>
                            <p><?= htmlspecialchars($visita['servicos']) ?> com <?= htmlspecialchars($visita['pessoa']) ?></p>
                            <div class="visit-time"><i class="ri-time-line"></i> <?= substr($visita['hora_inicio_agenda'], 0, 5) ?> - <?= substr($visita['hora_fim_agenda'], 0, 5) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <section class="card card-orders" data-csrf="<?= htmlspecialchars($csrfPerfil, ENT_QUOTES, 'UTF-8') ?>">
                <div class="card-header"><h2><i class="ri-service-line"></i> <span data-i18n="perfil-servicos-titulo">Meus serviços</span></h2></div>
                <p class="profile-section-help" data-i18n="perfil-servicos-ajuda">Aqui você acompanha o profissional designado, salva-o nos favoritos e avalia o atendimento depois que a limpeza for concluída.</p>
                <?php if (!$contratacoesPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-sparkling-line"></i><p data-i18n="perfil-servicos-vazio">Seus agendamentos e o profissional designado aparecerão aqui.</p><a class="btn-primary btn-sm" href="empresa/servicos.php" data-i18n="perfil-servicos-escolher">Escolher um serviço</a></div>
                <?php else: ?>
                <div class="profile-orders-list">
                    <?php foreach ($contratacoesPerfil as $contratacao): ?>
                    <?php
                        $statusServico = $contratacao['status_agenda'] ?: $contratacao['status_contratacao'];
                        $servicoConcluido = stripos((string) $contratacao['status_contratacao'], 'conclu') === 0
                            || stripos((string) $contratacao['status_agenda'], 'conclu') === 0;
                    ?>
                    <article class="profile-order">
                        <div class="profile-order-heading">
                            <div><h3><?= htmlspecialchars($contratacao['servicos'] ?: 'Serviço contratado') ?></h3><p><?= htmlspecialchars(date('d/m/Y', strtotime($contratacao['data_contratacao']))) ?><?= $contratacao['hora_inicio_contratacao'] ? ' às ' . htmlspecialchars(substr($contratacao['hora_inicio_contratacao'], 0, 5)) : '' ?></p></div>
                            <span class="visit-status status-upcoming" data-i18n="<?= htmlspecialchars(chaveStatusPerfil($statusServico), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($statusServico) ?></span>
                        </div>
                        <p class="profile-order-pro"><i class="ri-user-star-line"></i> <span data-i18n="perfil-profissional-designado">Profissional designado:</span> <strong><?= htmlspecialchars($contratacao['profissional']) ?></strong></p>
                        <p class="profile-order-payment"><span data-i18n="perfil-pagamento">Pagamento:</span> <span data-i18n="<?= htmlspecialchars(chaveStatusPerfil($contratacao['status_pagamento'] ?: 'Pendente'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contratacao['status_pagamento'] ?: 'Pendente') ?></span> · R$ <?= number_format((float) $contratacao['valor_total'], 2, ',', '.') ?></p>
                        <div class="profile-order-actions">
                            <?php if ((int) ($contratacao['pode_cancelar'] ?? 0) === 1 && in_array(mb_strtolower((string) $statusServico), ['aguardando confirmação', 'confirmado'], true)): ?>
                            <button type="button" class="btn-secondary btn-sm client-cancel-booking" data-contract-id="<?= (int) $contratacao['id_contratacao'] ?>" data-i18n="perfil-cancelar-agendamento">Cancelar agendamento</button>
                            <p class="profile-action-message" role="status" hidden></p>
                            <?php endif; ?>
                            <button type="button" class="btn-secondary btn-sm profile-favorite-button" data-favorite-contract="<?= (int) $contratacao['id_contratacao'] ?>" data-favorito="<?= (int) $contratacao['favorito'] ?>" aria-pressed="<?= $contratacao['favorito'] ? 'true' : 'false' ?>">
                                <i class="ri-heart-<?= $contratacao['favorito'] ? 'fill' : 'line' ?>"></i> <span data-i18n="<?= $contratacao['favorito'] ? 'perfil-profissional-favoritado' : 'perfil-favoritar-profissional' ?>"><?= $contratacao['favorito'] ? 'Profissional favoritado' : 'Favoritar profissional' ?></span>
                            </button>
                            <?php if ($servicoConcluido && !(int) $contratacao['avaliado']): ?>
                            <form class="profile-review-form" data-review-form>
                                <input type="hidden" name="contratacao_id" value="<?= (int) $contratacao['id_contratacao'] ?>">
                                <label><span data-i18n="perfil-review-pergunta">Como foi o serviço?</span>
                                    <select name="nota" required>
                                        <option value="" data-i18n="perfil-review-escolher">Escolha uma nota</option><option value="5" data-i18n="perfil-review-excelente">★★★★★ — Excelente</option><option value="4" data-i18n="perfil-review-muito-bom">★★★★ — Muito bom</option><option value="3" data-i18n="perfil-review-bom">★★★ — Bom</option><option value="2" data-i18n="perfil-review-regular">★★ — Regular</option><option value="1" data-i18n="perfil-review-ruim">★ — Ruim</option>
                                    </select>
                                </label>
                                <label><span data-i18n="perfil-review-comentario">Comentário (opcional)</span><textarea name="comentario" maxlength="1000" rows="2" placeholder="Conte como foi sua experiência" data-i18n-placeholder="perfil-review-placeholder"></textarea></label>
                                <button type="submit" class="btn-primary btn-sm" data-i18n="perfil-review-enviar">Enviar avaliação</button>
                                <p class="profile-action-message" role="status" hidden></p>
                            </form>
                            <?php elseif ($servicoConcluido): ?>
                            <span class="profile-reviewed-label"><i class="ri-checkbox-circle-line"></i> <span data-i18n="perfil-review-concluida">Serviço avaliado</span></span>
                            <?php else: ?>
                            <span class="profile-review-hint" data-i18n="perfil-review-disponibilidade">A avaliação ficará disponível depois que o profissional concluir o serviço.</span>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <section class="card card-activity">
                <div class="card-header"><h2><i class="ri-time-line"></i> <span data-i18n="perfil-atividade-titulo">Atividade recente</span></h2></div>
                <?php if (!$atividadesPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-history-line"></i><p data-i18n="perfil-atividade-vazia">Nenhuma atividade registrada ainda.</p></div>
                <?php else: ?>
                <div class="timeline">
                    <?php foreach ($atividadesPerfil as $atividade): ?>
                    <div class="timeline-item"><div class="timeline-dot <?= $atividade['tipo'] === 'avaliacao' ? 'dot-blue' : 'dot-green' ?>"></div><div class="timeline-content">
                        <span class="timeline-date"><?= htmlspecialchars(formatarDataPerfil($atividade['data_evento'])) ?></span>
                        <?php if ($atividade['tipo'] === 'avaliacao'): ?><p>Avaliação de <?= (int) $atividade['nota'] ?> estrela(s) enviada para <strong><?= htmlspecialchars($atividade['pessoa']) ?></strong>.</p><?php else: ?><p>Contratação com <strong><?= htmlspecialchars($atividade['pessoa']) ?></strong>: <?= htmlspecialchars($atividade['detalhe']) ?>.</p><?php endif; ?>
                    </div></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
        </div>

        <div class="profile-sidebar">
            <section class="card card-plan">
                <div class="card-header"><h2><i class="ri-vip-crown-line"></i> <span data-i18n="perfil-plano-titulo">Meu plano</span></h2></div>
                <?php if (!$planoPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-price-tag-3-line"></i><p data-i18n="perfil-plano-vazio">Você ainda não solicitou um plano.</p><a class="btn-primary btn-sm" href="empresa/planos.php" data-i18n="perfil-plano-ver">Ver planos</a></div>
                <?php else: ?>
                    <div class="profile-plan-details">
                        <span class="profile-plan-label" data-i18n="perfil-plano-ultima">Última solicitação</span>
                        <h3><?= htmlspecialchars($planoPerfil['plano']) ?></h3>
                        <p><?= htmlspecialchars($planoPerfil['status_assinatura']) ?></p>
                        <div><span data-i18n="perfil-pagamento">Pagamento</span><strong data-i18n="<?= htmlspecialchars(chaveStatusPerfil($planoPerfil['status_pagamento']), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($planoPerfil['status_pagamento']) ?></strong></div>
                        <div><span data-i18n="perfil-plano-forma">Forma escolhida</span><strong><?= htmlspecialchars($planoPerfil['forma_pagamento']) ?></strong></div>
                        <div><span data-i18n="perfil-plano-valor">Valor</span><strong>R$ <?= number_format((float) $planoPerfil['valor_assinatura'], 2, ',', '.') ?></strong></div>
                        <small data-i18n="perfil-plano-simulado">Este projeto registra a solicitação, mas não realiza cobrança real.</small>
                    </div>
                    <a class="btn-secondary btn-sm profile-plan-link" href="empresa/planos.php" data-i18n="perfil-plano-consultar">Consultar planos</a>
                <?php endif; ?>
            </section>

            <section class="card card-calendar">
                <div class="card-header"><h2><i class="ri-calendar-check-line"></i> <span data-i18n="perfil-agenda-titulo">Agenda</span></h2></div>
                <div class="calendar-widget">
                    <div class="calendar-header"><h3 id="current-month">Mês</h3><div class="nav-buttons"><button id="prev-month" aria-label="Mês anterior" data-i18n-aria-label="perfil-cal-anterior"><i class="ri-arrow-left-s-line"></i></button><button id="today-btn" data-i18n="perfil-cal-hoje">Hoje</button><button id="next-month" aria-label="Próximo mês" data-i18n-aria-label="perfil-cal-proximo"><i class="ri-arrow-right-s-line"></i></button></div></div>
                    <div class="weekdays"><div data-i18n="perfil-cal-dom">Dom</div><div data-i18n="perfil-cal-seg">Seg</div><div data-i18n="perfil-cal-ter">Ter</div><div data-i18n="perfil-cal-qua">Qua</div><div data-i18n="perfil-cal-qui">Qui</div><div data-i18n="perfil-cal-sex">Sex</div><div data-i18n="perfil-cal-sab">Sáb</div></div>
                    <div class="days" id="calendar-days"></div>
                    <div class="calendar-footer"><div class="event-indicator"><div class="event-dot"></div><span data-i18n="perfil-cal-dia-evento">Dia com visita agendada</span></div></div>
                </div>
            </section>

            <section class="card card-professional">
                <div class="card-header"><h2><i class="ri-user-star-line"></i> <span data-i18n="perfil-pro-titulo">Profissional preferido</span></h2></div>
                <?php if (!$profissionalPreferido): ?>
                    <div class="profile-empty-state"><i class="ri-user-heart-line"></i><p data-i18n="perfil-pro-vazio">Você ainda não favoritou nenhum profissional.</p></div>
                <?php else: ?>
                    <div class="pro-profile">
                        <div class="pro-avatar-wrap">
                            <?php if ($profissionalPreferido['foto']): ?>
                                <img src="<?= htmlspecialchars($profissionalPreferido['foto']) ?>" alt="Foto de <?= htmlspecialchars($profissionalPreferido['nome']) ?>" class="pro-avatar">
                            <?php else: ?>
                                <div class="pro-avatar pro-avatar-placeholder" role="img" aria-label="Avatar de <?= htmlspecialchars($profissionalPreferido['nome']) ?>"><?= htmlspecialchars(iniciaisPerfil($profissionalPreferido['nome'])) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="pro-identity"><h3><?= htmlspecialchars($profissionalPreferido['nome']) ?></h3><div class="pro-rating">
                            <?php if ((int) $profissionalPreferido['total_avaliacoes'] > 0): ?><div class="stars-inline sm"><?= estrelasPerfilCliente((int) round((float) $profissionalPreferido['media'])) ?></div><span><?= number_format((float) $profissionalPreferido['media'], 1, ',', '.') ?></span><?php else: ?><span data-i18n="perfil-sem-avaliacoes">Sem avaliações</span><?php endif; ?>
                        </div></div>
                    </div>
                    <div class="pro-details">
                        <div class="pro-detail-row"><i class="ri-brush-line"></i><div><span class="detail-label" data-i18n="perfil-pro-label-especialidades">Especialidades</span><span class="detail-value"><?= htmlspecialchars($profissionalPreferido['especialidades']) ?></span></div></div>
                        <div class="pro-detail-row"><i class="ri-map-pin-line"></i><div><span class="detail-label" data-i18n="perfil-pro-label-regiao">Região</span><span class="detail-value"><?= htmlspecialchars($profissionalPreferido['regiao_atuacao']) ?></span></div></div>
                        <div class="pro-detail-row"><i class="ri-money-dollar-circle-line"></i><div><span class="detail-label" data-i18n="perfil-pro-label-valor">Valor mínimo</span><span class="detail-value"><?php if ($profissionalPreferido['valor_minimo'] === null): ?><span data-i18n="perfil-nao-informado">Não informado</span><?php else: ?>R$ <?= number_format((float) $profissionalPreferido['valor_minimo'], 2, ',', '.') ?><?php endif; ?></span></div></div>
                    </div>
                <?php endif; ?>
            </section>

            <section class="card card-reviews">
                <div class="card-header"><h2><i class="ri-chat-3-line"></i> <span data-i18n="perfil-avaliacoes-titulo">Minhas avaliações</span></h2></div>
                <?php if (!$avaliacoesPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-star-line"></i><p data-i18n="perfil-avaliacoes-vazio">Você ainda não avaliou nenhum serviço.</p></div>
                <?php else: ?>
                <div class="reviews-list">
                    <?php foreach ($avaliacoesPerfil as $avaliacao): ?>
                    <div class="review-item"><div class="review-top"><div class="review-stars"><?= estrelasPerfilCliente((int) $avaliacao['nota_avaliacao']) ?></div><span class="review-date"><?= htmlspecialchars(formatarDataPerfil($avaliacao['data_avaliacao'])) ?></span></div><p class="review-person">Profissional: <strong><?= htmlspecialchars($avaliacao['pessoa']) ?></strong></p><p class="review-text"><?= htmlspecialchars($avaliacao['comentario_avaliacao'] ?: 'Avaliação sem comentário.') ?></p></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script>window.LumisProfileEvents = <?= json_encode($datasAgendaPerfil, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;</script>
<script>window.LumisProfileCsrf = <?= json_encode($csrfPerfil) ?>;</script>
<script src="../../Controller/acoes-perfil.js"></script>
<script src="../../Controller/calendario_horas.js"></script>
<script src="../../Controller/tema-idioma.js"></script>
</body>
</html>
