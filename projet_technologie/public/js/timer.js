// public/js/timer.js

function startTimer(durationInSeconds) {
    let timer = durationInSeconds;
    const display = document.getElementById('time');
    const form = document.getElementById('qcm-form');
    const timerDisplayContainer = document.getElementById('timer-display');

    const interval = setInterval(() => {
        let minutes = parseInt(timer / 60, 10);
        let seconds = parseInt(timer % 60, 10);

        // Formatage 00:00
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        // Effet visuel quand il reste moins de 60 secondes
        if (timer <= 60) {
            timerDisplayContainer.style.animation = "pulse 1s infinite alternate";
            timerDisplayContainer.style.background = "#991b1b"; // dark red
        }

        // Fin du temps imparti
        if (--timer < 0) {
            clearInterval(interval);
            alert("⏳ Le temps imparti (10 minutes) est écoulé ! Vos réponses vont être soumises automatiquement.");
            form.submit();
        }
    }, 1000);
}

// Ajouter l'animation "pulse" dynamiquement pour la fin du chrono
const style = document.createElement('style');
style.innerHTML = `
@keyframes pulse {
    0% { transform: scale(1); }
    100% { transform: scale(1.05); }
}
`;
document.head.appendChild(style);
