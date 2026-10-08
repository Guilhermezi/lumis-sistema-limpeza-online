<?php
$asset = $base === '' ? '../' : '../../';
$controller = $asset . '../Controller/';
?>
<header>
        <div id="logo-headers">
            <a href="<?= $base ?>index.php"><img src="<?= $asset ?>img/Logo_Sem_Nome.png" alt="Logo da Empresa" id="logo"></a>
        </div>
        <nav>
            <!-- Botão hamburguer -->
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul id="menu">
                <li><a href="<?= $active === 'inicio' ? '#' : $base . 'index.php' ?>"><i class="ri-home-2-line"></i> <span data-i18n="nav-inicio">Início</span></a></li>
                <li class="has-submenu">
                    <a href="<?= $active === 'sobre' ? '#' : $base . 'empresa/sobre.php' ?>">
                        <i class="ri-information-line"></i> <span data-i18n="nav-sobre">Sobre</span> <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?= $active === 'cidade' ? '#' : $base . 'empresa/cidade-consciente.php' ?>"><i class="ri-earth-line"></i> <span data-i18n="nav-cidade">Cidade consciente</span></a></li>
                    </ul>
                </li>
                <!-- Item adicional para mobile -->
                <li class="mobile-only-item"><a href="<?= $active === 'cidade' ? '#' : $base . 'empresa/cidade-consciente.php' ?>"><i class="ri-earth-line"></i> <span data-i18n="nav-cidade">Cidade consciente</span></a></li>
                <li><a href="<?= $active === 'servicos' ? '#' : $base . 'empresa/servicos.php' ?>"><i class="ri-tools-line"></i> <span data-i18n="nav-servicos">Serviços</span></a></li>
                <li><a href="<?= $active === 'planos' ? '#' : $base . 'empresa/planos.php' ?>"><i class="ri-wallet-3-line"></i> <span data-i18n="nav-planos">Planos</span></a></li>
                <li><a href="<?= $active === 'contato' ? '#' : $base . 'empresa/contato.php' ?>"><i class="ri-chat-2-line"></i> <span data-i18n="nav-contato">Contato</span></a></li>
                <li><a href="<?= $active === 'ajuda' ? '#' : $base . 'empresa/ajuda.php' ?>"><i class="ri-question-line"></i> <span data-i18n="nav-ajuda">Ajuda</span></a></li>
                <li class="nav-auth">
                    <a class="bntCadastro" id="authLoginLink" href="<?= $base ?>auth/decisao.php"><i class="ri-login-box-line"></i> <span data-i18n="nav-login">Login</span></a>
                    <div class="auth-user" id="authUserBox" hidden>
                        <span class="auth-user-hello"><span data-i18n="nav-hello">Olá,</span> <strong id="authUserName"></strong></span>
                        <a class="auth-profile-link" id="authProfileLink"
                           data-cliente-url="<?= $base ?>perfil.php"
                           data-profissional-url="<?= $base ?>perfil-profissional.php"
                           href="<?= $base ?>perfil.php"><i class="ri-user-line"></i> <span data-i18n="nav-perfil">Meu perfil</span></a>
                        <button type="button" class="auth-logout-btn" id="authLogoutBtn"><i class="ri-logout-box-r-line"></i> <span data-i18n="nav-logout">Sair</span></button>
                    </div>
                </li>
            </ul>

            <div class="nav-controls">
                <button class="Mode" id="themeToggle" aria-label="Alternar tema">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>

                <div class="LangSelector">
                    <button class="LangBtn" id="langToggle" aria-label="Selecionar idioma">
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
        </nav>
    </header>
    <script src="<?= $controller ?>Auth.js"></script>
    <!-- O menu hambúrguer mora aqui porque TODO página que inclui este header
         tem o #menuToggle/#menu acima. Carregar junto evita ter que lembrar
         de colocar a tag em cada uma das 22 páginas. -->
    <script src="<?= $controller ?>menu.js"></script>
