<?php
require_once __DIR__ . '/../partials/usuario-profissional-dados.php';

function estrelasPerfilProfissional(int $nota): string
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= '<i class="ri-star-' . ($i <= $nota ? 'fill' : 'line') . '"></i>';
    }
    return $html;
}

function chaveStatusPerfilProfissional(string $status): string
{
    return [
        'aguardando confirmação' => 'status-agenda-aguardando', 'confirmado' => 'status-agenda-confirmado',
        'recusado pelo profissional' => 'status-agenda-recusado', 'cancelado pelo cliente' => 'status-agenda-cancelado-cliente',
        'cancelado pelo profissional' => 'status-agenda-cancelado-profissional', 'concluído' => 'status-agenda-concluido',
    ][mb_strtolower(trim($status), 'UTF-8')] ?? '';
}

$especialidadesTexto = $especialidadesPerfil ? implode(', ', $especialidadesPerfil) : 'Nenhuma especialidade cadastrada';
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
    <title data-i18n="perfil-pro-pagina-titulo">Lumis - Perfil profissional</title>
</head>
<body data-page="perfil-pro" data-auth-requer="profissional" data-auth-login="auth/decisao.php" data-auth-home="index.php">
<?php $base = ''; $active = ''; include __DIR__ . '/../partials/header.php'; ?>

<main class="profile-page">
    <section class="profile-hero">
        <div class="hero-cover hero-cover--pro"><div class="hero-cover-overlay"></div></div>
        <div class="hero-profile">
            <div class="hero-avatar-wrap">
                <?php if ($avatar): ?>
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Foto de <?= htmlspecialchars($nome) ?>" class="hero-avatar">
                <?php else: ?>
                    <div class="hero-avatar hero-avatar-placeholder" role="img" aria-label="Avatar de <?= htmlspecialchars($nome) ?>"><?= htmlspecialchars($iniciaisAvatar) ?></div>
                <?php endif; ?>
            </div>
            <div class="hero-info">
                <div class="hero-name-row"><h1><?= htmlspecialchars($nome) ?></h1><span class="badge badge-pro"><i class="ri-shield-star-line"></i> <span data-i18n="perfil-pro-badge-profissional">Profissional</span></span><a class="btn-edit-profile" href="editar-perfil.php"><i class="ri-edit-line"></i> <span data-i18n="perfil-pro-editar-perfil">Editar perfil</span></a></div>
                <div class="hero-meta"><span class="meta-item"><i class="ri-map-pin-2-fill"></i> <?= htmlspecialchars($regiao) ?></span><?php if ($verificado): ?><span class="meta-divider"></span><span class="meta-item"><i class="ri-shield-check-line"></i> <span data-i18n="perfil-pro-verificado">Conta verificada</span></span><?php endif; ?></div>
                <div class="hero-rating">
                    <?php if ($resumoPerfil['avaliacoes'] > 0): ?><div class="stars-inline"><?= estrelasPerfilProfissional((int) round($resumoPerfil['media'])) ?></div><span class="rating-score"><?= number_format($resumoPerfil['media'], 1, ',', '.') ?></span><span class="rating-count">(<?= $resumoPerfil['avaliacoes'] ?> <span data-i18n="perfil-pro-contagem-avaliacoes">avaliações</span>)</span><?php else: ?><span class="rating-count" data-i18n="perfil-sem-avaliacoes">Sem avaliações</span><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-bar" aria-label="Resumo profissional">
        <?php foreach ([['ri-service-line', 'servicos'], ['ri-star-smile-line', 'avaliacoes'], ['ri-thumb-up-line', 'aprovacao'], ['ri-repeat-line', 'recorrentes']] as [$icone, $chave]): ?>
        <div class="stat-card"><div class="stat-icon"><i class="<?= $icone ?>"></i></div><div class="stat-info"><span class="stat-number" data-target="<?= $resumoPerfil[$chave] ?>">0</span><span class="stat-label" data-i18n="perfil-pro-stat-<?= $chave ?>"><?= ['servicos' => 'Serviços', 'avaliacoes' => 'Avaliações', 'aprovacao' => '% Aprovação', 'recorrentes' => 'Clientes recorrentes'][$chave] ?></span></div></div>
        <?php endforeach; ?>
    </section>

    <div class="profile-layout">
        <div class="profile-main">
            <section class="card card-info">
                <div class="card-header"><h2><i class="ri-briefcase-line"></i> <span data-i18n="perfil-pro-info-titulo">Informações profissionais</span></h2></div>
                <div class="info-grid">
                    <div class="info-item"><span class="info-label" data-i18n="perfil-pro-info-label-especialidades">Especialidades</span><span class="info-value"><?= htmlspecialchars($especialidadesTexto) ?></span></div>
                    <div class="info-item"><span class="info-label" data-i18n="perfil-pro-info-label-regiao">Região de atuação</span><span class="info-value"><?= htmlspecialchars($regiao) ?></span></div>
                    <div class="info-item"><span class="info-label" data-i18n="perfil-pro-info-label-experiencia">Experiência</span><span class="info-value"><?= htmlspecialchars($experiencia) ?></span></div>
                    <div class="info-item"><span class="info-label" data-i18n="perfil-pro-info-label-valor">Valor mínimo</span><span class="info-value"><?= htmlspecialchars($valorMinimo) ?></span></div>
                    <div class="info-item"><span class="info-label" data-i18n="perfil-info-label-email">E-mail</span><span class="info-value"><?= htmlspecialchars($email) ?></span></div>
                    <div class="info-item"><span class="info-label" data-i18n="perfil-info-label-telefone">Telefone</span><span class="info-value"><?= htmlspecialchars($telefoneFormatado) ?></span></div>
                </div>
            </section>

            <section class="card card-visits">
                <div class="card-header"><h2><i class="ri-calendar-event-line"></i> <span data-i18n="perfil-pro-visitas-titulo">Agendamentos da equipe</span></h2></div>
                <?php if (!$visitasPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-calendar-close-line"></i><p data-i18n="perfil-pro-visitas-vazio">Novos agendamentos designados a você aparecerão aqui.</p></div>
                <?php else: ?>
                <div class="visits-list">
                    <?php foreach ($visitasPerfil as $visita): ?>
                    <div class="visit-item"><div class="visit-date-badge"><span class="visit-day"><?= (new DateTime($visita['data_agenda']))->format('d') ?></span><span class="visit-month"><?= htmlspecialchars(mesCurtoPerfil($visita['data_agenda'])) ?></span></div><div class="visit-content"><div class="visit-top"><h4><?= htmlspecialchars(diaSemanaPerfil($visita['data_agenda'])) ?></h4><span class="visit-status status-upcoming" data-i18n="<?= htmlspecialchars(chaveStatusPerfilProfissional($visita['status_agenda']), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($visita['status_agenda']) ?></span></div><p><?= htmlspecialchars($visita['servicos']) ?> <span data-i18n="perfil-pro-para">para</span> <?= htmlspecialchars($visita['pessoa']) ?></p><div class="visit-time"><i class="ri-time-line"></i> <?= substr($visita['hora_inicio_agenda'], 0, 5) ?> - <?= substr($visita['hora_fim_agenda'], 0, 5) ?></div>
                        <?php if (mb_strtolower((string) $visita['status_agenda']) === 'aguardando confirmação' && (int) $visita['pode_cancelar'] === 1): ?>
                            <button type="button" class="btn-primary btn-sm pro-service-action" data-contract-id="<?= (int) $visita['id_contratacao'] ?>" data-action="confirmar" data-i18n="perfil-pro-confirmar-agendamento">Confirmar agendamento</button>
                            <button type="button" class="btn-secondary btn-sm pro-service-action" data-contract-id="<?= (int) $visita['id_contratacao'] ?>" data-action="recusar" data-i18n="perfil-pro-recusar-agendamento">Recusar agendamento</button>
                        <?php elseif (mb_strtolower((string) $visita['status_agenda']) === 'aguardando confirmação'): ?>
                            <small class="profile-review-hint" data-i18n="perfil-pro-horario-iniciado">O horário deste agendamento já começou; entre em contato com o cliente para combinar os próximos passos.</small>
                        <?php elseif (mb_strtolower((string) $visita['status_agenda']) === 'confirmado' && (int) $visita['pode_concluir'] === 1): ?>
                            <button type="button" class="btn-primary btn-sm pro-service-action" data-contract-id="<?= (int) $visita['id_contratacao'] ?>" data-action="concluir" data-i18n="perfil-pro-confirmar-conclusao">Confirmar limpeza concluída</button>
                        <?php elseif (mb_strtolower((string) $visita['status_agenda']) === 'confirmado' && (int) $visita['pode_cancelar'] === 1): ?>
                            <button type="button" class="btn-secondary btn-sm pro-service-action" data-contract-id="<?= (int) $visita['id_contratacao'] ?>" data-action="cancelar" data-i18n="perfil-cancelar-agendamento">Cancelar agendamento</button>
                        <?php elseif (mb_strtolower((string) $visita['status_agenda']) === 'confirmado'): ?>
                            <small class="profile-review-hint" data-i18n="perfil-pro-status-confirmado-ajuda">Agendamento confirmado. Após realizar o serviço, confirme a conclusão aqui.</small>
                        <?php endif; ?>
                        <p class="profile-action-message" role="status" hidden></p></div></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <section class="card card-activity">
                <div class="card-header"><h2><i class="ri-time-line"></i> <span data-i18n="perfil-pro-atividade-titulo">Atividade recente</span></h2></div>
                <?php if (!$atividadesPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-history-line"></i><p data-i18n="perfil-pro-atividade-vazia">Nenhuma atividade profissional registrada ainda.</p></div>
                <?php else: ?>
                <div class="timeline">
                    <?php foreach ($atividadesPerfil as $atividade): ?>
                    <div class="timeline-item"><div class="timeline-dot <?= $atividade['tipo'] === 'avaliacao' ? 'dot-blue' : 'dot-green' ?>"></div><div class="timeline-content"><span class="timeline-date"><?= htmlspecialchars(formatarDataPerfil($atividade['data_evento'])) ?></span><?php if ($atividade['tipo'] === 'avaliacao'): ?><p>Avaliação de <?= (int) $atividade['nota'] ?> estrela(s) recebida de <strong><?= htmlspecialchars($atividade['pessoa']) ?></strong>.</p><?php else: ?><p>Contratação de <strong><?= htmlspecialchars($atividade['pessoa']) ?></strong>: <?= htmlspecialchars($atividade['detalhe']) ?>.</p><?php endif; ?></div></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
        </div>

        <div class="profile-sidebar">
            <section class="card card-calendar">
                <div class="card-header"><h2><i class="ri-calendar-check-line"></i> <span data-i18n="perfil-pro-agenda-titulo">Agenda</span></h2></div>
                <div class="calendar-widget"><div class="calendar-header"><h3 id="current-month">Mês</h3><div class="nav-buttons"><button id="prev-month" aria-label="Mês anterior" data-i18n-aria-label="perfil-cal-anterior"><i class="ri-arrow-left-s-line"></i></button><button id="today-btn" data-i18n="perfil-pro-cal-hoje">Hoje</button><button id="next-month" aria-label="Próximo mês" data-i18n-aria-label="perfil-cal-proximo"><i class="ri-arrow-right-s-line"></i></button></div></div><div class="weekdays"><div data-i18n="perfil-pro-cal-dom">Dom</div><div data-i18n="perfil-pro-cal-seg">Seg</div><div data-i18n="perfil-pro-cal-ter">Ter</div><div data-i18n="perfil-pro-cal-qua">Qua</div><div data-i18n="perfil-pro-cal-qui">Qui</div><div data-i18n="perfil-pro-cal-sex">Sex</div><div data-i18n="perfil-pro-cal-sab">Sáb</div></div><div class="days" id="calendar-days"></div><div class="calendar-footer"><div class="event-indicator"><div class="event-dot"></div><span data-i18n="perfil-pro-cal-dia-evento">Dia com visita agendada</span></div></div></div>
            </section>

            <section class="card card-reviews">
                <div class="card-header"><h2><i class="ri-chat-3-line"></i> <span data-i18n="perfil-pro-avaliacoes-clientes-titulo">Avaliações de clientes</span></h2></div>
                <?php if (!$avaliacoesPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-star-line"></i><p data-i18n="perfil-pro-avaliacoes-vazio">Você ainda não recebeu avaliações.</p></div>
                <?php else: ?>
                <div class="reviews-list">
                    <?php foreach ($avaliacoesPerfil as $avaliacao): ?>
                    <div class="review-item"><div class="review-top"><div class="review-stars"><?= estrelasPerfilProfissional((int) $avaliacao['nota_avaliacao']) ?></div><span class="review-date"><?= htmlspecialchars(formatarDataPerfil($avaliacao['data_avaliacao'])) ?></span></div><p class="review-person">Cliente: <strong><?= htmlspecialchars($avaliacao['pessoa']) ?></strong></p><p class="review-text"><?= htmlspecialchars($avaliacao['comentario_avaliacao'] ?: 'Avaliação sem comentário.') ?></p></div>
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
