document.addEventListener('DOMContentLoaded', function () {
    const monthElement = document.getElementById('current-month');
    const daysContainer = document.getElementById('calendar-days');
    const previousButton = document.getElementById('prev-month');
    const nextButton = document.getElementById('next-month');
    const todayButton = document.getElementById('today-btn');

    // O script também é carregado em páginas sem calendário.
    if (!monthElement || !daysContainer || !previousButton || !nextButton || !todayButton) return;

    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    // Datas no formato YYYY-MM-DD são injetadas pelo PHP a partir de Agendamento.
    const eventDates = new Set(Array.isArray(window.LumisProfileEvents) ? window.LumisProfileEvents : []);

    function dateKey(year, month, day) {
        return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    function renderCalendar() {
        monthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        daysContainer.innerHTML = '';

        const firstWeekday = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        for (let i = 0; i < firstWeekday; i++) {
            const empty = document.createElement('div');
            empty.className = 'empty';
            empty.setAttribute('aria-hidden', 'true');
            daysContainer.appendChild(empty);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const cell = document.createElement('div');
            cell.textContent = day;
            cell.setAttribute('aria-label', `${day} de ${monthNames[currentMonth]} de ${currentYear}`);

            if (currentYear === today.getFullYear() && currentMonth === today.getMonth() && day === today.getDate()) {
                cell.classList.add('today');
            }
            if (eventDates.has(dateKey(currentYear, currentMonth, day))) {
                cell.classList.add('has-event');
                cell.setAttribute('title', 'Visita agendada');
            }
            daysContainer.appendChild(cell);
        }
    }

    previousButton.addEventListener('click', function () {
        currentMonth--;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar();
    });

    nextButton.addEventListener('click', function () {
        currentMonth++;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar();
    });

    todayButton.addEventListener('click', function () {
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        renderCalendar();
    });

    renderCalendar();
});
