
document.addEventListener('DOMContentLoaded', function() {
    // VARIÁVEIS COMPARTILHADAS E ELEMENTOS DO DOM
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let selectedDate = new Date();
    let selectedTime = new Date();

    const monthNames = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];
    
    const daysOfWeek = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
    const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    // Dias com eventos (apenas para demonstração)
    const eventDays = [5, 12, 19, 25];

    // Elementos do Calendário
    const monthElement = document.getElementById('current-month');
    const daysContainer = document.getElementById('calendar-days');
    const prevMonthButton = document.getElementById('prev-month');
    const nextMonthButton = document.getElementById('next-month');
    const todayButton = document.getElementById('today-btn');

    // Elementos do Seletor de Hora
    const selectedDateElement = document.getElementById('selected-date');
    const selectedTimeElement = document.getElementById('selected-time');
    const openModalButton = document.getElementById('open-modal');
    const closeModalButton = document.getElementById('close-modal');
    const cancelModalButton = document.getElementById('cancel-modal');
    const confirmTimeButton = document.getElementById('confirm-time');
    const timeModal = document.getElementById('time-modal');
    const modalHoursInput = document.getElementById('modal-hours');
    const modalMinutesInput = document.getElementById('modal-minutes');

    // FUNÇÕES DO CALENDÁRIO
    function renderCalendar() {
        monthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        daysContainer.innerHTML = '';

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.classList.add('empty');
            daysContainer.appendChild(emptyDay);
        }

        const today = new Date();
        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            dayElement.textContent = i;

            if (currentYear === today.getFullYear() &&
                currentMonth === today.getMonth() &&
                i === today.getDate()) {
                dayElement.classList.add('today');
            }

            if (eventDays.includes(i)) {
                dayElement.classList.add('has-event');
            }
            
            // Verifica se este dia é o dia selecionado atualmente e adiciona a classe
            if (currentYear === selectedDate.getFullYear() &&
                currentMonth === selectedDate.getMonth() &&
                i === selectedDate.getDate()) {
                dayElement.classList.add('selected');
            }

            dayElement.addEventListener('click', function() {
                selectDate(i);
            });

            daysContainer.appendChild(dayElement);
        }
    }

    function selectDate(day) {
        const previouslySelected = document.querySelector('.selected');
        if (previouslySelected) {
            previouslySelected.classList.remove('selected');
        }

        const days = daysContainer.childNodes;
        for (let i = 0; i < days.length; i++) {
            if (days[i].textContent == day && !days[i].classList.contains('empty')) {
                days[i].classList.add('selected');
                selectedDate = new Date(currentYear, currentMonth, day);
                break;
            }
        }
        updateDisplay();
    }

    function goToPreviousMonth() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    }

    function goToNextMonth() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    }

    function goToToday() {
        currentDate = new Date();
        currentMonth = currentDate.getMonth();
        currentYear = currentDate.getFullYear();
        renderCalendar();
        selectDate(currentDate.getDate());
    }

    // FUNÇÕES DO SELETOR DE HORA
    function closeModal() {
        timeModal.style.display = 'none';
    }

    function updateDisplay() {
        // Atualiza a hora
        const hours = String(selectedTime.getHours()).padStart(2, '0');
        const minutes = String(selectedTime.getMinutes()).padStart(2, '0');
        selectedTimeElement.textContent = `${hours}:${minutes}`;

        // Atualiza a data
        const day = daysOfWeek[selectedDate.getDay()];
        const date = selectedDate.getDate();
        const month = months[selectedDate.getMonth()];
        const year = selectedDate.getFullYear();
        selectedDateElement.textContent = `${day}, ${date} ${month} ${year}`;
    }

    // EVENT LISTENERS
    prevMonthButton.addEventListener('click', goToPreviousMonth);
    nextMonthButton.addEventListener('click', goToNextMonth);
    todayButton.addEventListener('click', goToToday);

    openModalButton.addEventListener('click', function() {
        modalHoursInput.value = selectedTime.getHours();
        modalMinutesInput.value = selectedTime.getMinutes();
        timeModal.style.display = 'flex';
    });
    
    closeModalButton.addEventListener('click', closeModal);
    cancelModalButton.addEventListener('click', closeModal);

    confirmTimeButton.addEventListener('click', function() {
        let hours = parseInt(modalHoursInput.value) || 0;
        let minutes = parseInt(modalMinutesInput.value) || 0;
        
        hours = Math.min(23, Math.max(0, hours));
        minutes = Math.min(59, Math.max(0, minutes));
        
        selectedTime.setHours(hours);
        selectedTime.setMinutes(minutes);
        
        updateDisplay();
        closeModal();
    });

    window.addEventListener('click', function(event) {
        if (event.target === timeModal) {
            closeModal();
        }
    });

    // RENDERIZAÇÃO INICIAL
    renderCalendar();
    selectDate(currentDate.getDate());
});
