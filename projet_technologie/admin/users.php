<?php
// admin/users.php
require_once '../config/db.php';
require_once '../includes/header.php';

// Protection Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Astuce : Ajouter dynamiquement la colonne is_blocked si elle n'existe pas en BDD
$columns = $pdo->query("SHOW COLUMNS FROM utilisateurs LIKE 'is_blocked'")->fetchAll();
if (empty($columns)) {
    $pdo->exec("ALTER TABLE utilisateurs ADD COLUMN is_blocked BOOLEAN DEFAULT 0");
}

// Actions de modification
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ? AND role = 'user'");
    $stmt->execute([$_GET['delete']]);
    header("Location: users.php");
    exit();
}

if (isset($_GET['toggle_block'])) {
    // Inverse le statut de blocage
    $stmt = $pdo->prepare("UPDATE utilisateurs SET is_blocked = NOT is_blocked WHERE id = ? AND role = 'user'");
    $stmt->execute([$_GET['toggle_block']]);
    header("Location: users.php");
    exit();
}

// Récupération de tous les utilisateurs classiques
$users = $pdo->query("SELECT * FROM utilisateurs WHERE role='user' ORDER BY id DESC")->fetchAll();
?>

<div class="container">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem;">
        <h1 style="color: var(--primary);">Gestion des Utilisateurs</h1>
        <a href="admin/index.php" class="btn-submit" style="width: auto; margin:0; text-decoration:none; padding: 0.8rem 1.5rem; background: rgba(255,255,255,0.1);">Retour Dashboard</a>
    </div>

    <div class="glass-card" style="overflow-x: auto; padding: 1.5rem;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--surface-border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">ID</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Nom complet</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Email</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Statut</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr style="border-bottom: 1px solid var(--surface-border);">
                        <td style="padding: 1rem; font-weight: bold;"><?= $u['id'] ?></td>
                        <td style="padding: 1rem;"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
                        <td style="padding: 1rem;"><?= htmlspecialchars($u['email']) ?></td>
                        <td style="padding: 1rem;">
                            <?php if (isset($u['is_blocked']) && $u['is_blocked']): ?>
                                <span style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">Bloqué</span>
                            <?php else: ?>
                                <span style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">Actif</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem; display: flex; gap: 0.8rem; flex-wrap: wrap;">
                            <a href="admin/users.php?toggle_block=<?= $u['id'] ?>" class="btn-primary" style="text-decoration:none; padding: 0.5rem 1rem; background: var(--warning); font-size: 0.85rem;">
                                <?= (isset($u['is_blocked']) && $u['is_blocked']) ? 'Débloquer' : 'Bloquer' ?>
                            </a>
                            <a href="admin/users.php?delete=<?= $u['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur et tout son historique ?');" class="btn-primary" style="text-decoration:none; padding: 0.5rem 1rem; background: var(--danger); font-size: 0.85rem;">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(count($users) === 0): ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
