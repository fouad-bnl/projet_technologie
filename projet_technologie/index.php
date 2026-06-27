<?php
require_once 'config/db.php';
require_once 'includes/header.php';
?>

<div class="hero">
    <h1>Testez vos compétences techniques</h1>
    <p>Une plateforme moderne et sécurisée pour évaluer vos connaissances à travers des QCM de 10 questions aléatoires.</p>
    
    <?php if(!isset($_SESSION['user_id'])): ?>
        <div style="margin-top: 2rem;">
            <a href="register.php" class="btn-primary" style="font-size: 1.2rem; padding: 1rem 2rem; display: inline-block;">Commencer maintenant</a>
            <a href="login.php" style="margin-left: 1.5rem; color: var(--text-muted); font-weight: 600; text-decoration: none;">J'ai déjà un compte</a>
        </div>
    <?php else: ?>
        <div style="margin-top: 2rem;">
            <a href="qcm.php" class="btn-primary" style="font-size: 1.2rem; padding: 1rem 2rem; display: inline-block;">Lancer un nouveau QCM</a>
        </div>
    <?php endif; ?>
</div>

<div class="glass-card" style="margin-top: 4rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; text-align: left;">
    <div>
        <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.5rem;">⚡ Aléatoire & Rapide</h3>
        <p style="color: var(--text-muted);">10 questions tirées au sort parmi notre large base de données. Vous avez 10 minutes maximum.</p>
    </div>
    <div>
        <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.5rem;">🛡️ Anti-Triche</h3>
        <p style="color: var(--text-muted);">Environnement sécurisé : plein écran obligatoire, blocage du copier/coller et détection de changement d'onglet.</p>
    </div>
    <div>
        <h3 style="color: var(--primary); margin-bottom: 1rem; font-size: 1.5rem;">📊 Suivi Détaillé</h3>
        <p style="color: var(--text-muted);">Consultez votre historique, analysez vos erreurs et comparez votre moyenne générale.</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
