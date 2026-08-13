document.querySelectorAll('.counter').forEach(counter => {
  const minusBtn = counter.querySelector('.minus');
  const plusBtn = counter.querySelector('.plus');
  const valueEl = counter.querySelector('.value');
  const labelEl = counter.querySelector('.label');
  const singular = counter.dataset.label; // ex: "banheiro", "quarto", "hora"
  const plural = singular + "s";          // ex: "banheiros", "quartos", "horas"

  minusBtn.addEventListener('click', e => {
    e.preventDefault();
    let current = parseInt(valueEl.textContent);
    if (current > 0) {
      current--;
      valueEl.textContent = current;
      labelEl.textContent = current === 1 ? singular : plural;
    }
  });

  plusBtn.addEventListener('click', e => {
    e.preventDefault();
    let current = parseInt(valueEl.textContent);
    if (current < 20) {  // Máximo de 20
      current++;
      valueEl.textContent = current;
      labelEl.textContent = current === 1 ? singular : plural;
    }
  });
});