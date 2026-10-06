<?php
// Guarda do servidor + dados do usuário logado.
// TEM que vir antes de qualquer HTML: se o <!DOCTYPE html> for impresso antes,
// o session_start() falha com "headers already sent" e o exit corta a página
// no primeiro doctype, entregando uma página em branco.
require_once __DIR__ . '/../partials/usuario-dados.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script>
        try {
            var temaSalvo = localStorage.getItem('tema');
            document.documentElement.setAttribute('data-theme',
                temaSalvo || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        } catch (e) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
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
<body data-page="perfil" data-auth-requer="cliente"
      data-auth-login="auth/decisao.php" data-auth-home="index.php">

    <?php
$base = '';
$active = '';
include __DIR__ . '/../partials/header.php';
?>

    <main class="profile-page">

        <!-- ========== PROFILE HERO ========== -->
        <section class="profile-hero">
            <div class="hero-cover">
                <div class="hero-cover-overlay"></div>
            </div>
            <div class="hero-profile">
                <div class="hero-avatar-wrap">
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Foto do usuário" class="hero-avatar">
                    <button class="avatar-edit" aria-label="Editar foto">
                        <i class="ri-camera-line"></i>
                    </button>
                </div>
                <div class="hero-info">
                    <div class="hero-name-row">
                        <h1><?= htmlspecialchars($nome) ?></h1>
                        <span class="badge badge-client"><i class="ri-user-heart-line"></i> <span data-i18n="perfil-badge-cliente">Cliente</span></span>
                        <button class="btn-edit-profile">
                            <i class="ri-edit-line"></i> <span data-i18n="perfil-editar-perfil">Editar perfil</span>
                        </button>
                    </div>
                    <div class="hero-meta">
                        <?php if (!empty($nascimento)): ?>
                        <span class="meta-item"><i class="ri-calendar-fill"></i> <span data-i18n="perfil-nascimento">Nascido em</span> <?= htmlspecialchars(date('d/m/Y', strtotime($nascimento))) ?></span>
                        <span class="meta-divider"></span>
                        <?php endif; ?>
                        <span class="meta-item"><i class="ri-shield-check-line"></i> <span data-i18n="perfil-conta-verificada">Conta verificada</span></span>
                    </div>
                    <?php /* A avaliação ficava fixa em 4.5 com 128 avaliações. Não existe
                            tabela de avaliação no banco ainda, então_someu em vez de
                            mostrar número inventado. Quando existir, é aqui que entra. */ ?>
                </div>
            </div>
        </section>

        <!-- ========== STATS BAR ========== -->
        <section class="stats-bar">
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-service-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="47">0</span>
                    <span class="stat-label" data-i18n="perfil-stat-servicos">Serviços</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-star-smile-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="128">0</span>
                    <span class="stat-label" data-i18n="perfil-stat-avaliacoes">Avaliações</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-heart-3-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="5">0</span>
                    <span class="stat-label" data-i18n="perfil-stat-favoritos">Favoritos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-repeat-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="12">0</span>
                    <span class="stat-label" data-i18n="perfil-stat-recorrentes">Recorrentes</span>
                </div>
            </div>
        </section>

        <!-- ========== MAIN CONTENT ========== -->
        <div class="profile-layout">

            <!-- LEFT COLUMN -->
            <div class="profile-main">

                <!-- Personal Info -->
                <section class="card card-info">
                    <div class="card-header">
                        <h2><i class="ri-user-3-line"></i> <span data-i18n="perfil-info-titulo">Informações Pessoais</span></h2>
                        <button class="card-action"><i class="ri-more-2-fill"></i></button>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-info-label-nome">Nome completo</span>
                            <span class="info-value"><?= htmlspecialchars($nome) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-info-label-email">E-mail</span>
                            <span class="info-value"><?= htmlspecialchars($email) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-info-label-telefone">Telefone</span>
                            <span class="info-value"><?= htmlspecialchars($telefoneFormatado) ?></span>
                        </div>
                        <?php /* Endereço saiu daqui: a tabela Cliente não tem coluna de
                                endereço. Mostrar "Rua das Flores, 123" era dado falso.
                                Quando existir a coluna, este item volta com o valor real. */ ?>
                    </div>
                </section>

                <!-- Upcoming Visits -->
                <section class="card card-visits">
                    <div class="card-header">
                        <h2><i class="ri-calendar-event-line"></i> <span data-i18n="perfil-visitas-titulo">Próximas Visitas</span></h2>
                        <a href="#" class="card-link"><span data-i18n="perfil-visitas-ver-todas">Ver todas</span> <i class="ri-arrow-right-s-line"></i></a>
                    </div>
                    <div class="visits-list">
                        <div class="visit-item">
                            <div class="visit-date-badge">
                                <span class="visit-day">10</span>
                                <span class="visit-month">Jun</span>
                            </div>
                            <div class="visit-content">
                                <div class="visit-top">
                                    <h4 data-i18n="perfil-visita1-dia">Terça-feira</h4>
                                    <span class="visit-status status-upcoming" data-i18n="perfil-visita-status-agendada">Agendada</span>
                                </div>
                                <p data-i18n="perfil-visita1-servico">Limpeza rápida - 2 horas</p>
                                <div class="visit-time">
                                    <i class="ri-time-line"></i> 14:00 - 16:00
                                </div>
                            </div>
                            <div class="visit-actions">
                                <button class="btn-icon btn-confirm" title="Confirmar"><i class="ri-check-line"></i></button>
                                <button class="btn-icon btn-cancel" title="Cancelar"><i class="ri-close-line"></i></button>
                            </div>
                        </div>
                        <div class="visit-item">
                            <div class="visit-date-badge">
                                <span class="visit-day">20</span>
                                <span class="visit-month">Jun</span>
                            </div>
                            <div class="visit-content">
                                <div class="visit-top">
                                    <h4 data-i18n="perfil-visita2-dia">Sexta-feira</h4>
                                    <span class="visit-status status-upcoming" data-i18n="perfil-visita-status-agendada">Agendada</span>
                                </div>
                                <p data-i18n="perfil-visita2-servico">Limpeza localizada - 3 horas</p>
                                <div class="visit-time">
                                    <i class="ri-time-line"></i> 09:00 - 12:00
                                </div>
                            </div>
                            <div class="visit-actions">
                                <button class="btn-icon btn-confirm" title="Confirmar"><i class="ri-check-line"></i></button>
                                <button class="btn-icon btn-cancel" title="Cancelar"><i class="ri-close-line"></i></button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Activity Timeline -->
                <section class="card card-activity">
                    <div class="card-header">
                        <h2><i class="ri-time-line"></i> <span data-i18n="perfil-atividade-titulo">Atividade Recente</span></h2>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot dot-green"></div>
                            <div class="timeline-content">
                                <span class="timeline-date" data-i18n="perfil-timeline-data1">Hoje, 09:30</span>
                                <p data-i18n="perfil-timeline-servico-concluido">Serviço de limpeza concluído por <strong>Jorge Silva</strong></p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-blue"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">22 Mai, 14:00</span>
                                <p data-i18n="perfil-timeline-avaliacao">Avaliação enviada para <strong>Jorge Silva</strong> - 5 estrelas</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-green"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">20 Mai, 10:00</span>
                                <p data-i18n="perfil-timeline-pos-reforma">Serviço de limpeza pós-reforma concluído</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-orange"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">15 Mai, 08:45</span>
                                <p data-i18n="perfil-timeline-visita-agendada">Visita agendada para <strong>10 de Junho</strong></p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-blue"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">10 Mai, 16:20</span>
                                <p data-i18n="perfil-timeline-prof-favorito">Novo profissional favorito: <strong>Ana Ribeiro</strong></p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- RIGHT COLUMN (Sidebar) -->
            <div class="profile-sidebar">

                <!-- Agenda / Calendário -->
                <section class="card card-calendar">
                    <div class="card-header">
                        <h2><i class="ri-calendar-check-line"></i> <span data-i18n="perfil-agenda-titulo">Agenda</span></h2>
                    </div>
                    <div class="calendar-widget">
                        <div class="calendar-header">
                            <h3 id="current-month">Mês</h3>
                            <div class="nav-buttons">
                                <button id="prev-month" aria-label="Mês anterior"><i class="ri-arrow-left-s-line"></i></button>
                                <button id="today-btn" data-i18n="perfil-cal-hoje">Hoje</button>
                                <button id="next-month" aria-label="Próximo mês"><i class="ri-arrow-right-s-line"></i></button>
                            </div>
                        </div>
                        <div class="weekdays">
                            <div data-i18n="perfil-cal-dom">Dom</div>
                            <div data-i18n="perfil-cal-seg">Seg</div>
                            <div data-i18n="perfil-cal-ter">Ter</div>
                            <div data-i18n="perfil-cal-qua">Qua</div>
                            <div data-i18n="perfil-cal-qui">Qui</div>
                            <div data-i18n="perfil-cal-sex">Sex</div>
                            <div data-i18n="perfil-cal-sab">Sáb</div>
                        </div>
                        <div class="days" id="calendar-days"></div>
                        <div class="time-display">
                            <div class="selected-time" id="selected-time">12:00</div>
                            <div class="selected-date" id="selected-date">Nenhuma data selecionada</div>
                            <button class="select-button" id="open-modal" data-i18n="perfil-selecionar-horario">Selecionar Horário</button>
                        </div>
                        <div class="calendar-footer">
                            <div class="event-indicator">
                                <div class="event-dot"></div>
                                <span data-i18n="perfil-cal-dia-evento">Dia com evento</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Preferred Professional -->
                <section class="card card-professional">
                    <div class="card-header">
                        <h2><i class="ri-user-star-line"></i> <span data-i18n="perfil-pro-titulo">Profissional Preferido</span></h2>
                    </div>
                    <div class="pro-profile">
                        <div class="pro-avatar-wrap">
                            <img src="../img/foto_profissional_recorrente.png" alt="Jorge Silva" class="pro-avatar">
                            <span class="pro-status online"></span>
                        </div>
                        <div class="pro-identity">
                            <h3>Jorge Silva</h3>
                            <div class="pro-rating">
                                <div class="stars-inline sm">
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                </div>
                                <span>4.9</span>
                            </div>
                        </div>
                    </div>
                    <div class="pro-details">
                        <div class="pro-detail-row">
                            <i class="ri-brush-line"></i>
                            <div>
                                <span class="detail-label" data-i18n="perfil-pro-label-especialidades">Especialidades</span>
                                <span class="detail-value" data-i18n="perfil-pro-valor-especialidades">Residencial, Empresarial, Pós-reforma</span>
                            </div>
                        </div>
                        <div class="pro-detail-row">
                            <i class="ri-map-pin-line"></i>
                            <div>
                                <span class="detail-label" data-i18n="perfil-pro-label-regiao">Região</span>
                                <span class="detail-value">São Paulo e arredores</span>
                            </div>
                        </div>
                        <div class="pro-detail-row">
                            <i class="ri-money-dollar-circle-line"></i>
                            <div>
                                <span class="detail-label" data-i18n="perfil-pro-label-valor">Valor mínimo</span>
                                <span class="detail-value">R$ 150,00</span>
                            </div>
                        </div>
                        <div class="pro-detail-row">
                            <i class="ri-forbid-line"></i>
                            <div>
                                <span class="detail-label" data-i18n="perfil-pro-label-nao-realiza">Não realiza</span>
                                <span class="detail-value" data-i18n="perfil-pro-valor-nao-realiza">Lavar roupa, estofados e dentro de armários</span>
                            </div>
                        </div>
                    </div>
                    <div class="pro-actions">
                        <button class="btn-primary"><i class="ri-calendar-line"></i> <span data-i18n="perfil-pro-agendar">Agendar</span></button>
                        <button class="btn-secondary"><i class="ri-message-2-line"></i> <span data-i18n="perfil-pro-mensagem">Mensagem</span></button>
                    </div>
                </section>

                <!-- Reviews -->
                <section class="card card-reviews">
                    <div class="card-header">
                        <h2><i class="ri-chat-3-line"></i> <span data-i18n="perfil-avaliacoes-titulo">Minhas Avaliações</span></h2>
                    </div>
                    <div class="reviews-list">
                        <div class="review-item">
                            <div class="review-top">
                                <div class="review-stars">
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                </div>
                                <span class="review-date">15/05/2023</span>
                            </div>
                            <p class="review-text" data-i18n="perfil-review1-texto">Ótimo serviço! O Jorge foi extremamente profissional e cuidadoso com meus móveis. A limpeza ficou impecável e ele ainda deu dicas para manter a casa organizada por mais tempo.</p>
                            <button class="review-toggle"><span data-i18n="perfil-review-ler-mais">Ler mais</span> <i class="ri-arrow-down-s-line"></i></button>
                        </div>
                        <div class="review-item">
                            <div class="review-top">
                                <div class="review-stars">
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-line"></i>
                                </div>
                                <span class="review-date">02/04/2023</span>
                            </div>
                            <p class="review-text" data-i18n="perfil-review2-texto">Serviço muito bom, mas atrasou 20 minutos. A limpeza em si foi excelente, superou minhas expectativas. Recomendo, mas espero que sejam mais pontuais da próxima vez.</p>
                            <button class="review-toggle"><span data-i18n="perfil-review-ler-mais">Ler mais</span> <i class="ri-arrow-down-s-line"></i></button>
                        </div>
                        <div class="review-item">
                            <div class="review-top">
                                <div class="review-stars">
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-half-line"></i>
                                </div>
                                <span class="review-date">18/03/2023</span>
                            </div>
                            <p class="review-text" data-i18n="perfil-review3-texto">Contratei o serviço de limpeza pós-reforma e fiquei muito satisfeita. O profissional foi atencioso e meticuloso. Só não dou 5 estrelas porque algumas manchas mais difíceis não saíram completamente.</p>
                            <button class="review-toggle"><span data-i18n="perfil-review-ler-mais">Ler mais</span> <i class="ri-arrow-down-s-line"></i></button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <!-- Modal de seleção de horário -->
    <div class="modal" id="time-modal">
        <div class="modal-content">
            <button class="close-button" id="close-modal" aria-label="Fechar">&times;</button>
            <h2 class="modal-title" data-i18n="perfil-modal-titulo">Selecionar Horário</h2>
            <div class="time-inputs">
                <div class="time-input">
                    <label for="modal-hours" data-i18n="perfil-modal-horas">Horas</label>
                    <input type="number" id="modal-hours" min="0" max="23" value="12" placeholder="Horas" data-i18n-placeholder="perfil-modal-horas-placeholder">
                </div>
                <div class="time-input">
                    <label for="modal-minutes" data-i18n="perfil-modal-minutos">Minutos</label>
                    <input type="number" id="modal-minutes" min="0" max="59" value="0" placeholder="Minutos" data-i18n-placeholder="perfil-modal-minutos-placeholder">
                </div>
            </div>
            <div class="modal-buttons">
                <button class="modal-button cancel-button" id="cancel-modal" data-i18n="perfil-modal-cancelar">Cancelar</button>
                <button class="modal-button confirm-button" id="confirm-time" data-i18n="perfil-modal-confirmar">Confirmar</button>
            </div>
        </div>
    </div>

    <script src="../../Controller/calendario_horas.js"></script>
    <script src="../../Controller/tema-idioma.js"></script>
</body>
</html>