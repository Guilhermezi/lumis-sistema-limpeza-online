<?php
$asset = $base === '' ? '../' : '../../';
?>
<footer id="Footer">
        <div class="footer-content">
            <div class="footer-section">
                <img src="<?= $asset ?>img/Logo_com_nome.png" alt="Logo da Lumis" class="logo-footer">
                <p><span class="P-Color" data-i18n="footer-tagline">Especialistas em limpeza sustentável</span></p>
                <div class="socials">
                    <a href="https://www.facebook.com/profile.php?id=61580024704625" target="_blank"><i class="ri-facebook-circle-fill"></i></a>
                    <a href="https://www.instagram.com/lumisstartup/?next=%2F" target="_blank"><i class="ri-instagram-fill"></i></a>
                    <a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a>
                    <a href="https://x.com/LumisStartup" target="_blank"><i class="ri-twitter-x-fill"></i></a>
                </div>
            </div>
            
               
          <div class="footer-section links">
                <h2 class="Footer-Titulo" data-i18n="footer-links">Links Rápidos</h2>
                <ul>
                    <li><a href="<?= $base ?>index.php" data-i18n="footer-inicio">Início</a></li>
                    <li><a href="<?= $base ?>empresa/sobre.php" data-i18n="footer-sobre">Sobre</a></li>
                    <li><a href="<?= $base ?>empresa/servicos.php" data-i18n="footer-servicos">Serviços</a></li>
                    <li><a href="<?= $base ?>empresa/planos.php" data-i18n="footer-planos">Planos</a></li>
                    <li><a href="<?= $base ?>empresa/contato.php" data-i18n="footer-contato">Contato</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h2 class="Footer-Titulo" data-i18n="footer-tipos">Tipos de Serviços</h2>
                <ul>
                    <li><a href="<?= $base ?>servicos/estofados.php" data-i18n="footer-estofados">Estofados</a></li>
                    <li><a href="<?= $base ?>servicos/vidros.php" data-i18n="footer-vidros">Vidros</a></li>
                    <li><a href="<?= $base ?>servicos/carros.php" data-i18n="footer-carros">Carros</a></li>
                    <li><a href="<?= $base ?>servicos/escritorios.php" data-i18n="footer-escritorios">Escritórios</a></li>
                    <li><a href="<?= $base ?>servicos/empresarial.php" data-i18n="footer-empresarial">Empresarial</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h2 class="Footer-Titulo" data-i18n="footer-localizacao">Localização</h2>
                <p class="P-Color" data-i18n="footer-endereco-rua">Av. Amador Bueno da Veiga;</p>
                <p class="P-Color" data-i18n="footer-endereco">Endereço: 4430;</p>
                <p class="P-Color" data-i18n="footer-cidade">Cidade: São Paulo - SP;</p>
                <p class="P-Color" data-i18n="footer-cep">CEP: 03890-000</p>
            </div>
            
            <div class="footer-section">
                <h2 class="Footer-Titulo" data-i18n="footer-contato-t">Contato</h2>
                <p class="P-Color" data-i18n="footer-telefone">Telefone: (11) 98121-4352</p>
                <p class="P-Color"><span data-i18n="footer-email">Email:</span> <a href="mailto:lumisstartup@gmail.com">lumisstartup@gmail.com</a></p>
            </div>
        </div>
        
        <div class="subfooter">
            <p class="P-Color" data-i18n="footer-direitos">© Lumis. Todos os direitos reservados.</p>
            <div class="subfooter-links">
                <a href="<?= $base ?>empresa/politica-de-privacidade.php" data-i18n="footer-politica">Política de Privacidade</a>
                <span>|</span>
                <a href="<?= $base ?>empresa/termos-de-uso.php" data-i18n="footer-termos">Termos de Uso</a>
            </div>
        </div>
    </footer>