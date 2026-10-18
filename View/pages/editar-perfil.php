<?php require_once __DIR__ . '/../partials/usuario-edicao-dados.php'; ?>
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
    <link rel="stylesheet" href="../css/editar-perfil.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <title data-i18n="editar-perfil-titulo">Lumis - Editar perfil</title>
</head>
<body data-page="editar-perfil" data-auth-requer="<?= htmlspecialchars($tipo) ?>" data-auth-login="auth/decisao.php" data-auth-home="index.php">
<?php $base = ''; $active = ''; include __DIR__ . '/../partials/header.php'; ?>

<main class="profile-edit-page">
    <div class="profile-edit-heading">
        <div>
            <span class="profile-edit-eyebrow"><i class="ri-user-settings-line"></i> <span data-i18n="editar-perfil-conta">Minha conta</span></span>
            <h1 data-i18n="editar-perfil-titulo-form">Editar perfil</h1>
            <p data-i18n="editar-perfil-intro">Atualize os dados que aparecem no seu perfil.</p>
        </div>
        <a class="profile-edit-back" href="<?= htmlspecialchars($perfilDestino) ?>"><i class="ri-arrow-left-line"></i> <span data-i18n="editar-perfil-voltar">Voltar ao perfil</span></a>
    </div>

    <form id="profile-edit-form" class="profile-edit-card" action="../../Controller/api/atualizar-perfil.php" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <section class="profile-photo-section" aria-labelledby="foto-title">
            <div id="profile-photo-preview" class="profile-photo-preview">
                <?php if ($foto): ?>
                    <img src="<?= htmlspecialchars($foto) ?>" alt="Foto atual de <?= htmlspecialchars($linha['nome']) ?>">
                <?php else: ?>
                    <span><?= htmlspecialchars($iniciaisAvatar) ?></span>
                <?php endif; ?>
            </div>
            <div class="profile-photo-copy">
                <h2 id="foto-title" data-i18n="editar-perfil-foto">Foto do perfil</h2>
                <p data-i18n="editar-perfil-foto-ajuda">Envie uma imagem JPG, PNG ou WebP de até 5 MB.</p>
                <label class="profile-file-button" for="profile-photo-input"><i class="ri-upload-2-line"></i> <span data-i18n="editar-perfil-escolher-foto">Escolher foto</span></label>
                <input id="profile-photo-input" type="file" name="foto" accept="image/jpeg,image/png,image/webp">
                <?php if ($foto): ?>
                <label class="profile-remove-photo"><input type="checkbox" name="remover_foto" value="1"> <span data-i18n="editar-perfil-remover-foto">Remover foto atual</span></label>
                <?php endif; ?>
            </div>
        </section>

        <section class="profile-edit-fields" aria-labelledby="dados-title">
            <div class="profile-edit-section-title"><h2 id="dados-title" data-i18n="editar-perfil-dados">Dados pessoais</h2><p data-i18n="editar-perfil-obrigatorios">Campos marcados com * são obrigatórios.</p></div>

            <div class="profile-edit-grid">
                <div class="profile-edit-field">
                    <label for="profile-name" data-i18n="editar-perfil-nome">Nome completo *</label>
                    <input id="profile-name" type="text" name="nome" value="<?= htmlspecialchars($linha['nome']) ?>" maxlength="100" autocomplete="name" required>
                </div>
                <div class="profile-edit-field">
                    <label for="profile-email" data-i18n="editar-perfil-email">E-mail *</label>
                    <input id="profile-email" type="email" name="email" value="<?= htmlspecialchars($linha['email']) ?>" maxlength="100" autocomplete="email" required>
                </div>
                <div class="profile-edit-field">
                    <label for="profile-phone" data-i18n="editar-perfil-telefone">Telefone *</label>
                    <input id="profile-phone" type="tel" name="telefone" value="<?= htmlspecialchars(telefoneEdicao($linha['telefone'])) ?>" inputmode="numeric" autocomplete="tel" required>
                </div>
                <div class="profile-edit-field">
                    <label for="profile-birth" data-i18n="editar-perfil-nascimento">Data de nascimento</label>
                    <input id="profile-birth" type="date" name="data_nascimento" value="<?= htmlspecialchars($linha['data_nascimento'] ?? '') ?>" max="<?= date('Y-m-d') ?>" autocomplete="bday">
                </div>

                <?php if ($tipo === 'profissional'): ?>
                <div class="profile-edit-field profile-edit-field-wide">
                    <label for="profile-region" data-i18n="editar-perfil-regiao">Região de atuação *</label>
                    <input id="profile-region" type="text" name="regiao_atuacao" value="<?= htmlspecialchars($linha['regiao_atuacao'] ?? '') ?>" maxlength="100" required>
                </div>
                <div class="profile-edit-field">
                    <label for="profile-experience" data-i18n="editar-perfil-experiencia">Experiência</label>
                    <input id="profile-experience" type="text" name="experiencia" value="<?= htmlspecialchars($linha['experiencia'] ?? '') ?>" maxlength="50">
                </div>
                <div class="profile-edit-field">
                    <label for="profile-minimum" data-i18n="editar-perfil-valor">Valor mínimo (R$)</label>
                    <input id="profile-minimum" type="number" name="valor_minimo" value="<?= htmlspecialchars($linha['valor_minimo'] ?? '') ?>" min="0" max="999999.99" step="0.01" inputmode="decimal">
                </div>
                <?php endif; ?>
            </div>
        </section>

        <div id="profile-edit-message" class="profile-edit-message" role="status" aria-live="polite" hidden></div>
        <div class="profile-edit-actions">
            <a class="profile-cancel-button" href="<?= htmlspecialchars($perfilDestino) ?>"><span data-i18n="editar-perfil-cancelar">Cancelar</span></a>
            <button class="profile-save-button" type="submit"><i class="ri-save-line"></i> <span data-i18n="editar-perfil-salvar">Salvar alterações</span></button>
        </div>
    </form>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="../../Controller/editar-perfil.js"></script>
<script src="../../Controller/tema-idioma.js"></script>
</body>
</html>
