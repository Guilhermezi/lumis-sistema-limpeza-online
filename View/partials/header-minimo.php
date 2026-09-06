<?php
$asset = $base === '' ? '../' : '../../';
?>
<header>
        <div id="logo-headers">
            <a href="<?= $base ?>index.php"><img src="<?= $asset ?>img/Logo_Sem_Nome.png" alt="Logo da Empresa" id="logo"></a>
        </div>

<?php if (!empty($tituloI18n)): ?>
        <h1 data-i18n="<?= $tituloI18n ?>"><?= $titulo ?></h1>
<?php endif; ?>

        <div class="nav-controls header-controls">
            <button class="Mode header-btn" id="themeToggle" aria-label="Alternar tema">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            <div class="LangSelector">
                <button class="LangBtn header-btn" id="langToggle" aria-label="Selecionar idioma">
                    &#127479;&#127479;
                </button>
                <div class="LangDropdown" id="langDropdown">
                    <a data-lang="pt" class="LangOption ativo">
                        <span class="flag">&#127463;&#127479;</span> <span data-i18n="lang-portugues">Português</span>
                    </a>
                    <a data-lang="en" class="LangOption">
                        <span class="flag">&#127482;&#127480;</span> <span data-i18n="lang-ingles">English</span>
                    </a>
                    <a data-lang="es" class="LangOption">
                        <span class="flag">&#127466;&#127480;</span> <span data-i18n="lang-espanhol">Español</span>
                    </a>
                </div>
            </div>
        </div>
    </header>