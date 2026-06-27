<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Sécurité
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupération de l'historique de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT id, score, date FROM tentatives WHERE utilisateur_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$tentatives = $stmt->fetchAll();

// Calcul de la moyenne
$moyenne = 0;
if (count($tentatives) > 0) {
    $total_score = array_sum(array_column($tentatives, 'score'));
    $moyenne = $total_score / count($tentatives);
}
?>

<div class="container">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 3rem; gap: 1rem;">
        <h1 style="color: var(--primary);">Mon Historique</h1>
        <div class="glass-card" style="padding: 1.5rem 2.5rem; border-color: <?= $moyenne >= 10 ? 'var(--success)' : 'var(--danger)' ?>;">
            <span style="color: var(--text-muted); font-size: 1.1rem;">Moyenne générale :</span>
            <span style="font-size: 2rem; font-weight: 800; margin-left: 1rem; color: <?= $moyenne >= 10 ? 'var(--success)' : 'var(--danger)' ?>;">
                <?= count($tentatives) > 0 ? round($moyenne, 2) . ' / 20' : 'N/A' ?>
            </span>
        </div>
    </div>

    <?php if (count($tentatives) === 0): ?>
        <div class="glass-card" style="text-align: center; padding: 5rem 2rem;">
            <h3 style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1.5rem;">Vous n'avez pas encore passé de QCM.</h3>
            <a href="qcm.php" class="btn-primary" style="text-decoration: none; font-size: 1.2rem; padding: 1rem 2rem;">Passer mon premier test</a>
        </div>
    <?php else: ?>
        <div class="glass-card" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--surface-border);">
                        <th style="padding: 1.5rem 1rem; color: var(--primary); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Tentative #</th>
                        <th style="padding: 1.5rem 1rem; color: var(--primary); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Date et Heure</th>
                        <th style="padding: 1.5rem 1rem; color: var(--primary); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Score Obtenu</th>
                        <th style="padding: 1.5rem 1rem; color: var(--primary); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tentatives as $index => $t): ?>
                        <tr style="border-bottom: 1px solid var(--surface-border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                            <!-- Numérotation inversée (la plus récente en haut) -->
                            <td style="padding: 1.5rem 1rem; font-weight: bold;"><?= count($tentatives) - $index ?></td>
                            
                            <!-- Formatage de la date (ex: 27/06/2026 à 15:30) -->
                            <td style="padding: 1.5rem 1rem; color: var(--text-muted);">
                                <?= date('d/m/Y \à H:i', strtotime($t['date'])) ?>
                            </td>
                            
                            <!-- Score -->
                            <td style="padding: 1.5rem 1rem; font-weight: bold; font-size: 1.1rem; color: <?= $t['score'] >= 10 ? 'var(--success)' : 'var(--danger)' ?>;">
                                <?= round($t['score'], 2) ?> / 20
                            </td>
                            
                            <!-- Statut avec Badges visuels -->
                            <td style="padding: 1.5rem 1rem;">
                                <?php if ($t['score'] == 0): ?>
                                    <span style="background: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 0.4rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem;">Échec / Tricherie</span>
                                <?php elseif ($t['score'] >= 10): ?>
                                    <span style="background: rgba(16, 185, 129, 0.2); color: var(--success); padding: 0.4rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem;">Validé</span>
                                <?php else: ?>
                                    <span style="background: rgba(245, 158, 11, 0.2); color: var(--warning); padding: 0.4rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem;">Non acquis</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
