<?php
// admin/index.php
require_once '../config/db.php';
require_once '../includes/header.php';

// Protection de la route Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Récupération des statistiques
$nb_users = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='user'")->fetchColumn();
$nb_questions = $pdo->query("SELECT COUNT(*) FROM questions")->fetchColumn();
$nb_tentatives = $pdo->query("SELECT COUNT(*) FROM tentatives")->fetchColumn();
?>

<div class="container">
    <h1 style="color: var(--warning); margin-bottom: 2rem;">Tableau de bord Administrateur</h1>
    
    <!-- Statistiques rapides -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        <div class="glass-card" style="text-align: center; border-bottom: 4px solid var(--primary);">
            <h3 style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">Utilisateurs inscrits</h3>
            <div style="font-size: 3.5rem; font-weight: 800; color: var(--text-main);"><?= $nb_users ?></div>
        </div>
        <div class="glass-card" style="text-align: center; border-bottom: 4px solid var(--success);">
            <h3 style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">Questions en BDD</h3>
            <div style="font-size: 3.5rem; font-weight: 800; color: var(--text-main);"><?= $nb_questions ?></div>
        </div>
        <div class="glass-card" style="text-align: center; border-bottom: 4px solid var(--warning);">
            <h3 style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem;">Total des tentatives</h3>
            <div style="font-size: 3.5rem; font-weight: 800; color: var(--text-main);"><?= $nb_tentatives ?></div>
        </div>
    </div>

    <!-- Navigation Admin -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <a href="admin/users.php" class="glass-card" style="text-align: center; text-decoration: none; display: block; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <h2 style="color: var(--primary); margin-bottom: 1rem;">👥 Gestion des Utilisateurs</h2>
            <p style="color: var(--text-muted);">Lister, supprimer ou bloquer les comptes des étudiants.</p>
        </a>
        <a href="admin/questions.php" class="glass-card" style="text-align: center; text-decoration: none; display: block; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <h2 style="color: var(--warning); margin-bottom: 1rem;">📝 Gestion des Questions</h2>
            <p style="color: var(--text-muted);">Ajouter, modifier ou supprimer le pool de questions du QCM.</p>
        </a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
