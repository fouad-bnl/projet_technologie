// public/js/anti-cheat.js

document.addEventListener('DOMContentLoaded', () => {
    const startScreen = document.getElementById('start-screen');
    const qcmContainer = document.getElementById('qcm-container');
    const startBtn = document.getElementById('btn-start-qcm');
    const cheatInput = document.getElementById('cheat_detected');
    const qcmForm = document.getElementById('qcm-form');

    let qcmStarted = false;
    let isSubmitting = false; // Drapeau pour désactiver l'anti-triche lors de la soumission

    if (!startBtn) return; // Sécurité si on n'est pas sur la bonne page

    // Désactiver l'anti-triche de manière préventive quand l'utilisateur valide de lui-même
    qcmForm.addEventListener('submit', () => {
        isSubmitting = true;
    });

    // 1. Démarrer en plein écran au clic
    startBtn.addEventListener('click', async () => {
        try {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                await elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                await elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                await elem.msRequestFullscreen();
            }
            
            // Cacher l'écran d'accueil et afficher le QCM
            startScreen.style.display = 'none';
            qcmContainer.style.display = 'block';
            qcmStarted = true;
            
            // Démarrer le chronomètre depuis timer.js (10 minutes = 600 secondes)
            if(typeof startTimer === 'function') {
                startTimer(600); 
            }

        } catch (err) {
            alert("Impossible de passer en plein écran. Vous devez autoriser le plein écran dans votre navigateur pour passer ce test.");
        }
    });

    // 2. Détection de la sortie du plein écran
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('mozfullscreenchange', handleFullscreenChange);
    document.addEventListener('MSFullscreenChange', handleFullscreenChange);

    function handleFullscreenChange() {
        if (qcmStarted && !isSubmitting && !document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
            triggerCheat("Vous avez quitté le plein écran. Votre tentative est annulée.");
        }
    }

    // 3. Détection de changement d'onglet ou minimisation de la fenêtre
    // Lors d'une redirection, le navigateur peut déclencher 'visibilitychange'.
    // Grâce à 'isSubmitting', l'alerte ne se déclenchera plus quand on clique sur Valider.
    document.addEventListener('visibilitychange', () => {
        if (qcmStarted && !isSubmitting && document.hidden) {
            triggerCheat("Changement d'onglet ou minimisation détecté. Votre tentative est annulée.");
        }
    });

    // 4. Désactiver le clic droit
    document.addEventListener('contextmenu', (e) => {
        if (qcmStarted && !isSubmitting) {
            e.preventDefault();
            alert("⚠️ Action non autorisée : Le clic droit est désactivé.");
        }
    });

    // 5. Désactiver le copier/coller et la sélection
    document.addEventListener('copy', (e) => { if(qcmStarted && !isSubmitting) e.preventDefault(); });
    document.addEventListener('cut', (e) => { if(qcmStarted && !isSubmitting) e.preventDefault(); });
    document.addEventListener('paste', (e) => { if(qcmStarted && !isSubmitting) e.preventDefault(); });

    // Fonction commune en cas de triche détectée
    function triggerCheat(message) {
        if (isSubmitting) return; // Sécurité supplémentaire
        isSubmitting = true; // Empêcher la fonction d'être appelée en boucle
        alert("⚠️ TRICHE DÉTECTÉE : " + message);
        cheatInput.value = "1"; // On signale la triche au backend
        qcmForm.submit(); // On soumet automatiquement le formulaire pour invalider la tentative
    }
});
