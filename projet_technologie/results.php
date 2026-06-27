<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Sécurité
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// On s'assure qu'on vient bien d'une soumission de QCM
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['current_qcm'])) {
    header("Location: index.php");
    exit();
}

$questions = $_SESSION['current_qcm'];
$user_id = $_SESSION['user_id'];
$cheat_detected = (isset($_POST['cheat_detected']) && $_POST['cheat_detected'] === '1');

$score = 0;
$max_score = 20;
$points_per_question = $max_score / max(1, count($questions));
$correction_details = [];

if ($cheat_detected) {
    $score = 0; // Sanction anti-triche
} else {
    // Calcul de la note normale
    $reponses_postees = $_POST['reponse'] ?? [];
    foreach ($questions as $q) {
        $q_id = $q['id'];
        $user_ans = $reponses_postees[$q_id] ?? null;
        $correct_ans = $q['bonne_reponse'] ?? 1; // Fallback sécurité ancienne session
        
        $is_correct = ($user_ans == $correct_ans);
        if ($is_correct) {
            $score += $points_per_question;
        }
        
        // Préparation des données pour l'affichage de la correction
        $correction_details[] = [
            'question' => $q['question'],
            'user_ans_text' => $user_ans ? ($q['reponse'.$user_ans] ?? 'Non défini') : 'Non répondu',
            'correct_ans_text' => $correct_ans ? ($q['reponse'.$correct_ans] ?? 'Non défini') : 'Non défini',
            'is_correct' => $is_correct
        ];
    }
}

// 1. Enregistrer la tentative globale
$stmt = $pdo->prepare("INSERT INTO tentatives (utilisateur_id, score, date) VALUES (?, ?, NOW())");
$stmt->execute([$user_id, $score]);
$tentative_id = $pdo->lastInsertId();

// 2. Enregistrer le détail des réponses
$reponses_postees = $_POST['reponse'] ?? [];
foreach ($questions as $q) {
    $q_id = $q['id'];
    $user_ans = $reponses_postees[$q_id] ?? 0; // 0 si non répondu
    $bonne_rep = $q['bonne_reponse'] ?? 1;
    $is_correct = ($user_ans == $bonne_rep) ? 1 : 0;
    
    if($cheat_detected) $is_correct = 0; // Forcer à faux si triche
    
    $stmt = $pdo->prepare("INSERT INTO reponses (tentative_id, question_id, reponse_utilisateur, correcte) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tentative_id, $q_id, $user_ans, $is_correct]);
}

// 3. Nettoyer la session pour éviter de soumettre deux fois ou de reprendre le test
unset($_SESSION['current_qcm']);
unset($_SESSION['qcm_start_time']);

?>

<div class="container">
    <div class="glass-card" style="text-align: center; margin-bottom: 3rem;">
        <h1 style="color: var(--primary); margin-bottom: 1rem;">Résultats de votre QCM</h1>
        
        <?php if ($cheat_detected): ?>
            <div class="alert alert-danger" style="font-size: 1.5rem; margin-bottom: 2rem;">
                ⚠️ TRICHE DÉTECTÉE : Les conditions de l'examen n'ont pas été respectées (changement d'onglet ou sortie de plein écran). 
                Votre tentative a été annulée. Note attribuée : 0/20.
            </div>
        <?php else: ?>
            <div style="font-size: 5rem; font-weight: 800; color: <?= $score >= 10 ? 'var(--success)' : 'var(--danger)' ?>;">
                <?= round($score, 2) ?> <span style="font-size: 2.5rem; color: var(--text-muted);">/ 20</span>
            </div>
            <p style="color: var(--text-muted); font-size: 1.2rem; margin-top: 1rem;">
                <?= $score >= 10 ? '🎉 Félicitations, vous avez la moyenne !' : 'Vous ferez mieux la prochaine fois.' ?>
            </p>
        <?php endif; ?>
        
        <div style="margin-top: 3rem;">
            <a href="history.php" class="btn-primary" style="text-decoration: none; padding: 1rem 2rem; font-size: 1.1rem;">Voir mon historique</a>
        </div>
    </div>

    <?php if (!$cheat_detected && !empty($correction_details)): ?>
        <h2 style="margin-bottom: 2rem; color: var(--primary);">Correction détaillée</h2>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach ($correction_details as $index => $detail): ?>
                <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid <?= $detail['is_correct'] ? 'var(--success)' : 'var(--danger)' ?>;">
                    <h4 style="margin-bottom: 1.5rem; font-size: 1.2rem;">Question <?= $index + 1 ?> : <?= htmlspecialchars($detail['question']) ?></h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <strong style="color: var(--text-muted);">Votre réponse :</strong><br>
                            <span style="color: <?= $detail['is_correct'] ? 'var(--success)' : 'var(--danger)' ?>; font-weight: bold; display: inline-block; margin-top: 0.5rem;">
                                <?= htmlspecialchars($detail['user_ans_text']) ?>
                            </span>
                        </div>
                        <?php if (!$detail['is_correct']): ?>
                            <div>
                                <strong style="color: var(--text-muted);">La bonne réponse était :</strong><br>
                                <span style="color: var(--success); font-weight: bold; display: inline-block; margin-top: 0.5rem;">
                                    <?= htmlspecialchars($detail['correct_ans_text']) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
