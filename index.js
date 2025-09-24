document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const menu = document.getElementById('menu');
            
            menuToggle.addEventListener('click', function() {
                this.classList.toggle('open');
                menu.classList.toggle('active');
                
                // Impede a rolagem da página quando o menu está aberto
                if (menu.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Fechar o menu ao clicar em um item (útil para mobile)
            document.querySelectorAll('#menu a').forEach(item => {
                item.addEventListener('click', () => {
                    menu.classList.remove('active');
                    menuToggle.classList.remove('open');
                    document.body.style.overflow = 'auto';
                });
            });
            
            // Fechar o menu ao clicar fora dele
            document.addEventListener('click', function(event) {
                if (!menu.contains(event.target) && !menuToggle.contains(event.target) && menu.classList.contains('active')) {
                    menu.classList.remove('active');
                    menuToggle.classList.remove('open');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Prevenir que cliques dentro do menu fechem ele
            menu.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
     
 // ======================// Carrossel da seção de Comentários// ======================
let currentIndex = 0;
const items = document.querySelectorAll(".carousel-item");
const dots = document.querySelectorAll(".dot");

function showSlide(index) {
  if (index >= items.length) currentIndex = 0;
  else if (index < 0) currentIndex = items.length - 1;
  else currentIndex = index;

  items.forEach((item, i) => {
    item.style.transform = `translateX(${-100 * currentIndex}%)`;
  });

  dots.forEach(dot => dot.classList.remove("active"));
  dots[currentIndex].classList.add("active");
}

document.querySelector(".next").addEventListener("click", () => showSlide(currentIndex + 1));
document.querySelector(".prev").addEventListener("click", () => showSlide(currentIndex - 1));
dots.forEach((dot, i) => dot.addEventListener("click", () => showSlide(i)));

// Automático a cada 5 segundos
setInterval(() => {
  showSlide(currentIndex + 1);
}, 5000);

showSlide(0);

// --------------------
// 🚀 Autoplay (a cada 5s)
// --------------------
let autoPlay = setInterval(nextSlide, 8000);

// Se o usuário clicar em algo, reseta o autoplay para não travar
function resetAutoPlay() {
  clearInterval(autoPlay);
  autoPlay = setInterval(nextSlide, 5000);
}

nextBtn.addEventListener('click', resetAutoPlay);
prevBtn.addEventListener('click', resetAutoPlay);
indicators.forEach(dot => dot.addEventListener('click', resetAutoPlay));
