<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Sécurité : Accessible uniquement aux utilisateurs connectés
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si un QCM est déjà en cours dans la session pour éviter de changer les questions en rechargeant la page (triche)
if (!isset($_SESSION['current_qcm'])) {
    // Tirer 10 questions aléatoires depuis la BDD
    $stmt = $pdo->query("SELECT id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse FROM questions ORDER BY RAND() LIMIT 10");
    $_SESSION['current_qcm'] = $stmt->fetchAll();
    $_SESSION['qcm_start_time'] = time(); // Heure de début pour vérification côté serveur
}

$questions = $_SESSION['current_qcm'];

// S'il n'y a pas de questions en BDD (ex: installation fraîche)
if (count($questions) === 0) {
    echo "<div class='glass-card'><div class='alert alert-danger'>Aucune question disponible. L'administrateur doit en ajouter.</div></div>";
    require_once 'includes/footer.php';
    exit();
}
?>

<div id="start-screen" class="glass-card" style="max-width: 600px; margin: 4rem auto; text-align: center;">
    <h2 style="color: var(--danger); margin-bottom: 1rem;">⚠️ Attention - Règles du QCM</h2>
    <ul style="text-align: left; margin-bottom: 2rem; color: var(--text-muted); line-height: 1.6; list-style-position: inside;">
        <li>Le QCM se déroulera en <strong>plein écran obligatoire</strong>.</li>
        <li>Vous avez <strong>10 minutes</strong> au total (chronomètre intégré).</li>
        <li>Toute tentative de quitter le plein écran, changer d'onglet, ou minimiser la fenêtre <strong>annulera votre tentative</strong> (Note de 0/20).</li>
        <li>Le clic droit, la sélection de texte et le copier-coller sont désactivés.</li>
    </ul>
    <button id="btn-start-qcm" class="btn-submit" style="font-size: 1.2rem; background: var(--danger);">J'ai compris, démarrer le QCM</button>
</div>

<div id="qcm-container" style="display: none; max-width: 800px; margin: 0 auto; position: relative;">
    
    <!-- Chronomètre -->
    <div id="timer-display" style="position: sticky; top: 100px; background: var(--danger); color: white; padding: 1rem; border-radius: 8px; text-align: center; font-size: 1.5rem; font-weight: bold; margin-bottom: 2rem; z-index: 10; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);">
        Temps restant : <span id="time">10:00</span>
    </div>

    <!-- Formulaire du QCM -->
    <form id="qcm-form" action="results.php" method="POST">
        <?php foreach ($questions as $index => $q): ?>
            <div class="glass-card" style="margin-bottom: 2rem; user-select: none;">
                <h3 style="margin-bottom: 1.5rem; color: var(--primary);">
                    Question <?= $index + 1 ?> : <?= htmlspecialchars($q['question']) ?>
                </h3>
                
                <div class="options-group" style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <label style="display: flex; align-items: center; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; cursor: pointer; transition: background 0.2s; border: 1px solid var(--surface-border);">
                            <input type="radio" name="reponse[<?= $q['id'] ?>]" value="<?= $i ?>" required style="margin-right: 1rem; transform: scale(1.2);">
                            <?= htmlspecialchars($q['reponse'.$i]) ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Champ caché pour indiquer à results.php si l'annulation est due à la triche -->
        <input type="hidden" name="cheat_detected" id="cheat_detected" value="0">
        
        <button type="submit" id="btn-submit-qcm" class="btn-submit" style="font-size: 1.2rem; padding: 1.5rem; margin-bottom: 3rem;">Valider mes réponses</button>
    </form>
</div>

<!-- Inclusion des scripts -->
<script src="public/js/anti-cheat.js"></script>
<script src="public/js/timer.js"></script>

<?php require_once 'includes/footer.php'; ?>
