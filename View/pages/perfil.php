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
    <title>Lumis - Perfil</title>
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
                <div class="hero-name-row"><h1><?= htmlspecialchars($nome) ?></h1><span class="badge badge-client"><i class="ri-user-heart-line"></i> Cliente</span><a class="btn-edit-profile" href="editar-perfil.php"><i class="ri-edit-line"></i> Editar perfil</a></div>
                <?php if (!empty($nascimento)): ?><div class="hero-meta"><span class="meta-item"><i class="ri-calendar-fill"></i> Nascido em <?= htmlspecialchars(formatarDataPerfil($nascimento)) ?></span></div><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="stats-bar" aria-label="Resumo do perfil">
        <?php foreach ([['ri-service-line', 'servicos', 'Serviços'], ['ri-star-smile-line', 'avaliacoes', 'Avaliações'], ['ri-heart-3-line', 'favoritos', 'Favoritos'], ['ri-repeat-line', 'recorrentes', 'Profissionais recorrentes']] as [$icone, $chave, $rotulo]): ?>
        <div class="stat-card"><div class="stat-icon"><i class="<?= $icone ?>"></i></div><div class="stat-info"><span class="stat-number" data-target="<?= $resumoPerfil[$chave] ?>">0</span><span class="stat-label"><?= htmlspecialchars($rotulo) ?></span></div></div>
        <?php endforeach; ?>
    </section>

    <div class="profile-layout">
        <div class="profile-main">
            <section class="card card-info">
                <div class="card-header"><h2><i class="ri-user-3-line"></i> Informações pessoais</h2></div>
                <div class="info-grid">
                    <div class="info-item"><span class="info-label">Nome completo</span><span class="info-value"><?= htmlspecialchars($nome) ?></span></div>
                    <div class="info-item"><span class="info-label">E-mail</span><span class="info-value"><?= htmlspecialchars($email) ?></span></div>
                    <div class="info-item"><span class="info-label">Telefone</span><span class="info-value"><?= htmlspecialchars($telefoneFormatado) ?></span></div>
                </div>
            </section>

            <section class="card card-visits">
                <div class="card-header"><h2><i class="ri-calendar-event-line"></i> Próximas visitas</h2></div>
                <?php if (!$visitasPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-calendar-close-line"></i><p>Você ainda não possui visitas agendadas.</p></div>
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

            <section class="card card-activity">
                <div class="card-header"><h2><i class="ri-time-line"></i> Atividade recente</h2></div>
                <?php if (!$atividadesPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-history-line"></i><p>Nenhuma atividade registrada ainda.</p></div>
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
            <section class="card card-calendar">
                <div class="card-header"><h2><i class="ri-calendar-check-line"></i> Agenda</h2></div>
                <div class="calendar-widget">
                    <div class="calendar-header"><h3 id="current-month">Mês</h3><div class="nav-buttons"><button id="prev-month" aria-label="Mês anterior"><i class="ri-arrow-left-s-line"></i></button><button id="today-btn">Hoje</button><button id="next-month" aria-label="Próximo mês"><i class="ri-arrow-right-s-line"></i></button></div></div>
                    <div class="weekdays"><div>Dom</div><div>Seg</div><div>Ter</div><div>Qua</div><div>Qui</div><div>Sex</div><div>Sáb</div></div>
                    <div class="days" id="calendar-days"></div>
                    <div class="calendar-footer"><div class="event-indicator"><div class="event-dot"></div><span>Dia com visita agendada</span></div></div>
                </div>
            </section>

            <section class="card card-professional">
                <div class="card-header"><h2><i class="ri-user-star-line"></i> Profissional preferido</h2></div>
                <?php if (!$profissionalPreferido): ?>
                    <div class="profile-empty-state"><i class="ri-user-heart-line"></i><p>Você ainda não favoritou nenhum profissional.</p></div>
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
                            <?php if ((int) $profissionalPreferido['total_avaliacoes'] > 0): ?><div class="stars-inline sm"><?= estrelasPerfilCliente((int) round((float) $profissionalPreferido['media'])) ?></div><span><?= number_format((float) $profissionalPreferido['media'], 1, ',', '.') ?></span><?php else: ?><span>Sem avaliações</span><?php endif; ?>
                        </div></div>
                    </div>
                    <div class="pro-details">
                        <div class="pro-detail-row"><i class="ri-brush-line"></i><div><span class="detail-label">Especialidades</span><span class="detail-value"><?= htmlspecialchars($profissionalPreferido['especialidades']) ?></span></div></div>
                        <div class="pro-detail-row"><i class="ri-map-pin-line"></i><div><span class="detail-label">Região</span><span class="detail-value"><?= htmlspecialchars($profissionalPreferido['regiao_atuacao']) ?></span></div></div>
                        <div class="pro-detail-row"><i class="ri-money-dollar-circle-line"></i><div><span class="detail-label">Valor mínimo</span><span class="detail-value"><?= $profissionalPreferido['valor_minimo'] === null ? 'Não informado' : 'R$ ' . number_format((float) $profissionalPreferido['valor_minimo'], 2, ',', '.') ?></span></div></div>
                    </div>
                <?php endif; ?>
            </section>

            <section class="card card-reviews">
                <div class="card-header"><h2><i class="ri-chat-3-line"></i> Minhas avaliações</h2></div>
                <?php if (!$avaliacoesPerfil): ?>
                    <div class="profile-empty-state"><i class="ri-star-line"></i><p>Você ainda não avaliou nenhum serviço.</p></div>
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
<script src="../../Controller/calendario_horas.js"></script>
<script src="../../Controller/tema-idioma.js"></script>
</body>
</html>
