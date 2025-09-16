const container = document.querySelector('.cadastro-container');
const btnLogin = document.getElementById('btnLogin');

btnLogin.addEventListener('click', (e) => {
  e.preventDefault();
  container.classList.toggle('login-ativo');
});
