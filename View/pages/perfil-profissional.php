<?php
// Guarda do servidor: sem sessão de profissional, não abre o perfil.
// Sem isso a página respondia 200 para qualquer visitante, e a proteção do
// JavaScript (data-auth-requer) é contornável — basta abrir a URL direto.
//
// Precisa vir antes de qualquer HTML, senão o session_start() falha com
// "headers already sent" e a página sai em branco.
require_once __DIR__ . '/../partials/usuario-profissional-dados.php';
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
    <title>Lumis - Perfil Profissional</title>
</head>
<body data-page="perfil-pro" data-auth-requer="profissional"
      data-auth-login="auth/decisao.php" data-auth-home="index.php">

    <?php
$base = '';
$active = '';
include __DIR__ . '/../partials/header.php';
?>

    <main class="profile-page">

        <!-- ========== PROFILE HERO ========== -->
        <section class="profile-hero">
            <div class="hero-cover hero-cover--pro">
                <div class="hero-cover-overlay"></div>
            </div>
            <div class="hero-profile">
                <div class="hero-avatar-wrap">
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Foto do profissional" class="hero-avatar">
                    <button class="avatar-edit" aria-label="Editar foto">
                        <i class="ri-camera-line"></i>
                    </button>
                </div>
                <div class="hero-info">
                    <div class="hero-name-row">
                        <h1><?= htmlspecialchars($nome) ?></h1>
                        <span class="badge badge-pro"><i class="ri-shield-star-line"></i> <span data-i18n="perfil-pro-badge-profissional">Profissional</span></span>
                        <button class="btn-edit-profile">
                            <i class="ri-edit-line"></i> <span data-i18n="perfil-pro-editar-perfil">Editar perfil</span>
                        </button>
                    </div>
                    <div class="hero-meta">
                        <span class="meta-item"><i class="ri-map-pin-2-fill"></i> <?= htmlspecialchars($regiao) ?></span>
                        <span class="meta-divider"></span>
                        <?php if ($verificado): ?>
                        <span class="meta-item"><i class="ri-shield-check-line"></i> <span data-i18n="perfil-conta-verificada">Conta verificada</span></span>
                        <?php endif; ?>
                    </div>
                    <div class="hero-rating">
                        <div class="stars-inline">
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-fill"></i>
                            <i class="ri-star-half-line"></i>
                        </div>
                        <span class="rating-score">4.9</span>
                        <span class="rating-count" data-i18n="perfil-pro-rating-count">(86 avaliações)</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== STATS BAR ========== -->
        <section class="stats-bar">
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-service-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="312">0</span>
                    <span class="stat-label" data-i18n="perfil-pro-stat-servicos">Serviços</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-star-smile-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="86">0</span>
                    <span class="stat-label" data-i18n="perfil-pro-stat-avaliacoes">Avaliações</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-thumb-up-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="98">0</span>
                    <span class="stat-label" data-i18n="perfil-pro-stat-aprovacao">% Aprovação</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ri-repeat-line"></i></div>
                <div class="stat-info">
                    <span class="stat-number" data-target="67">0</span>
                    <span class="stat-label" data-i18n="perfil-pro-stat-recorrentes">Recorrentes</span>
                </div>
            </div>
        </section>

        <!-- ========== MAIN CONTENT ========== -->
        <div class="profile-layout">

            <!-- LEFT COLUMN -->
            <div class="profile-main">

                <!-- Professional Info -->
                <section class="card card-info">
                    <div class="card-header">
                        <h2><i class="ri-briefcase-line"></i> <span data-i18n="perfil-pro-info-titulo">Informações Profissionais</span></h2>
                        <button class="card-action"><i class="ri-more-2-fill"></i></button>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-pro-info-label-especialidades">Especialidades</span>
                            <span class="info-value" data-i18n="perfil-pro-info-valor-especialidades">Residencial, Empresarial, Pós-reforma</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-pro-info-label-regiao">Região de atuação</span>
                            <span class="info-value"><?= htmlspecialchars($regiao) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-pro-info-label-valor">Valor mínimo</span>
                            <span class="info-value"><?= htmlspecialchars($valorMinimo) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-pro-info-label-contato">Contato</span>
                            <span class="info-value"><?= htmlspecialchars($email) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-pro-info-label-telefone">Telefone</span>
                            <span class="info-value"><?= htmlspecialchars($telefoneFormatado) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label" data-i18n="perfil-pro-info-label-nao-realiza">Não realiza</span>
                            <span class="info-value" data-i18n="perfil-pro-info-valor-nao-realiza">Lavar roupa, estofados e dentro de armários</span>
                        </div>
                    </div>
                </section>

                <!-- Upcoming Visits -->
                <section class="card card-visits">
                    <div class="card-header">
                        <h2><i class="ri-calendar-event-line"></i> <span data-i18n="perfil-pro-visitas-titulo">Próximas Visitas</span></h2>
                        <a href="#" class="card-link"><span data-i18n="perfil-pro-visitas-ver-todas">Ver todas</span> <i class="ri-arrow-right-s-line"></i></a>
                    </div>
                    <div class="visits-list">
                        <div class="visit-item">
                            <div class="visit-date-badge">
                                <span class="visit-day">10</span>
                                <span class="visit-month">Jun</span>
                            </div>
                            <div class="visit-content">
                                <div class="visit-top">
                                    <h4 data-i18n="perfil-pro-visita1-dia">Terça-feira</h4>
                                    <span class="visit-status status-upcoming" data-i18n="perfil-pro-visita-status-agendada">Agendada</span>
                                </div>
                                <p data-i18n="perfil-pro-visita1-servico">Limpeza rápida - 2 horas</p>
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
                                    <h4 data-i18n="perfil-pro-visita2-dia">Sexta-feira</h4>
                                    <span class="visit-status status-upcoming" data-i18n="perfil-pro-visita-status-agendada">Agendada</span>
                                </div>
                                <p data-i18n="perfil-pro-visita2-servico">Limpeza localizada - 3 horas</p>
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
                        <h2><i class="ri-time-line"></i> <span data-i18n="perfil-pro-atividade-titulo">Atividade Recente</span></h2>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot dot-green"></div>
                            <div class="timeline-content">
                                <span class="timeline-date" data-i18n="perfil-pro-timeline-data1">Hoje, 09:30</span>
                                <p data-i18n="perfil-pro-timeline-servico-concluido">Serviço concluído para <strong>Maria de Souza</strong> - Limpeza Residencial</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-blue"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">22 Mai, 14:00</span>
                                <p data-i18n="perfil-pro-timeline-avaliacao">Avaliação recebida de <strong>Ana Ribeiro</strong> - 5 estrelas</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-green"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">20 Mai, 10:00</span>
                                <p data-i18n="perfil-pro-timeline-pos-reforma">Serviço pós-reforma concluído para <strong>Pedro Santos</strong></p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-orange"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">15 Mai, 08:45</span>
                                <p data-i18n="perfil-pro-timeline-agendamento">Novo agendamento confirmado para <strong>10 de Junho</strong></p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot dot-blue"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">10 Mai, 16:20</span>
                                <p data-i18n="perfil-pro-timeline-cliente-recorrente">Novo cliente recorrente: <strong>Lucas Martins</strong></p>
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
                        <h2><i class="ri-calendar-check-line"></i> <span data-i18n="perfil-pro-agenda-titulo">Agenda</span></h2>
                    </div>
                    <div class="calendar-widget">
                        <div class="calendar-header">
                            <h3 id="current-month">Mês</h3>
                            <div class="nav-buttons">
                                <button id="prev-month" aria-label="Mês anterior"><i class="ri-arrow-left-s-line"></i></button>
                                <button id="today-btn" data-i18n="perfil-pro-cal-hoje">Hoje</button>
                                <button id="next-month" aria-label="Próximo mês"><i class="ri-arrow-right-s-line"></i></button>
                            </div>
                        </div>
                        <div class="weekdays">
                            <div data-i18n="perfil-pro-cal-dom">Dom</div>
                            <div data-i18n="perfil-pro-cal-seg">Seg</div>
                            <div data-i18n="perfil-pro-cal-ter">Ter</div>
                            <div data-i18n="perfil-pro-cal-qua">Qua</div>
                            <div data-i18n="perfil-pro-cal-qui">Qui</div>
                            <div data-i18n="perfil-pro-cal-sex">Sex</div>
                            <div data-i18n="perfil-pro-cal-sab">Sáb</div>
                        </div>
                        <div class="days" id="calendar-days"></div>
                        <div class="time-display">
                            <div class="selected-time" id="selected-time">12:00</div>
                            <div class="selected-date" id="selected-date">Nenhuma data selecionada</div>
                            <button class="select-button" id="open-modal" data-i18n="perfil-pro-selecionar-horario">Selecionar Horário</button>
                        </div>
                        <div class="calendar-footer">
                            <div class="event-indicator">
                                <div class="event-dot"></div>
                                <span data-i18n="perfil-pro-cal-dia-evento">Dia com evento</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Client Reviews -->
                <section class="card card-reviews">
                    <div class="card-header">
                        <h2><i class="ri-chat-3-line"></i> <span data-i18n="perfil-pro-avaliacoes-clientes-titulo">Avaliações de Clientes</span></h2>
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
                            <p class="review-text" data-i18n="perfil-pro-review1-texto">Ótimo serviço! O Jorge foi extremamente profissional e cuidadoso com meus móveis. A limpeza ficou impecável e ele ainda deu dicas para manter a casa organizada por mais tempo.</p>
                            <button class="review-toggle"><span data-i18n="perfil-pro-review-ler-mais">Ler mais</span> <i class="ri-arrow-down-s-line"></i></button>
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
                            <p class="review-text" data-i18n="perfil-pro-review2-texto">Serviço muito bom, mas atrasou 20 minutos. A limpeza em si foi excelente, superou minhas expectativas. Recomendo, mas espero que sejam mais pontuais da próxima vez.</p>
                            <button class="review-toggle"><span data-i18n="perfil-pro-review-ler-mais">Ler mais</span> <i class="ri-arrow-down-s-line"></i></button>
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
                            <p class="review-text" data-i18n="perfil-pro-review3-texto">Contratei o serviço de limpeza pós-reforma e fiquei muito satisfeita. O profissional foi atencioso e meticuloso. Só não dou 5 estrelas porque algumas manchas mais difíceis não saíram completamente.</p>
                            <button class="review-toggle"><span data-i18n="perfil-pro-review-ler-mais">Ler mais</span> <i class="ri-arrow-down-s-line"></i></button>
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
            <h2 class="modal-title" data-i18n="perfil-pro-modal-titulo">Selecionar Horário</h2>
            <div class="time-inputs">
                <div class="time-input">
                    <label for="modal-hours" data-i18n="perfil-pro-modal-horas">Horas</label>
                    <input type="number" id="modal-hours" min="0" max="23" value="12" placeholder="Horas" data-i18n-placeholder="perfil-pro-modal-horas-placeholder">
                </div>
                <div class="time-input">
                    <label for="modal-minutes" data-i18n="perfil-pro-modal-minutos">Minutos</label>
                    <input type="number" id="modal-minutes" min="0" max="59" value="0" placeholder="Minutos" data-i18n-placeholder="perfil-pro-modal-minutos-placeholder">
                </div>
            </div>
            <div class="modal-buttons">
                <button class="modal-button cancel-button" id="cancel-modal" data-i18n="perfil-pro-modal-cancelar">Cancelar</button>
                <button class="modal-button confirm-button" id="confirm-time" data-i18n="perfil-pro-modal-confirmar">Confirmar</button>
            </div>
        </div>
    </div>

    <script src="../../Controller/calendario_horas.js"></script>
    <script src="../../Controller/tema-idioma.js"></script>
</body>
</html>