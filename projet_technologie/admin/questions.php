<?php
// admin/questions.php
require_once '../config/db.php';
require_once '../includes/header.php';

// Protection Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

// Action de Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: questions.php");
    exit();
}

// Action d'Ajout ou Modification via formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'] ?? '';
    $r1 = $_POST['reponse1'] ?? '';
    $r2 = $_POST['reponse2'] ?? '';
    $r3 = $_POST['reponse3'] ?? '';
    $r4 = $_POST['reponse4'] ?? '';
    $bonne = $_POST['bonne_reponse'] ?? 1;
    $categorie = $_POST['categorie'] ?? '';
    $id = $_POST['id'] ?? null;

    if (empty($question) || empty($r1) || empty($r2) || empty($r3) || empty($r4)) {
        $error = "Tous les champs texte (question et 4 réponses) sont obligatoires.";
    } else {
        if ($id) {
            // Modification
            $stmt = $pdo->prepare("UPDATE questions SET question=?, reponse1=?, reponse2=?, reponse3=?, reponse4=?, bonne_reponse=?, categorie=? WHERE id=?");
            $stmt->execute([$question, $r1, $r2, $r3, $r4, $bonne, $categorie, $id]);
            $success = "La question a été modifiée avec succès.";
        } else {
            // Création
            $stmt = $pdo->prepare("INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$question, $r1, $r2, $r3, $r4, $bonne, $categorie]);
            $success = "La question a été ajoutée à la base de données.";
        }
        $action = 'list'; // Retourner à la liste après l'action
    }
}

// Récupération des questions pour la vue liste
$questions = $pdo->query("SELECT * FROM questions ORDER BY id DESC")->fetchAll();
?>

<div class="container">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem;">
        <h1 style="color: var(--warning);">Gestion des Questions</h1>
        <div style="display: flex; gap: 1rem;">
            <?php if ($action === 'list'): ?>
                <a href="admin/questions.php?action=add" class="btn-submit" style="margin:0; width: auto; text-decoration:none; padding: 0.8rem 1.5rem;">+ Nouvelle Question</a>
            <?php endif; ?>
            <a href="admin/index.php" class="btn-submit" style="width: auto; margin:0; text-decoration:none; padding: 0.8rem 1.5rem; background: rgba(255,255,255,0.1);">Retour Dashboard</a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <!-- VUE : LISTE DES QUESTIONS -->
        <div class="glass-card" style="overflow-x: auto; padding: 1.5rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--surface-border);">
                        <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">ID</th>
                        <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">Intitulé</th>
                        <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">Catégorie</th>
                        <th style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $q): ?>
                        <tr style="border-bottom: 1px solid var(--surface-border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1rem; font-weight: bold;"><?= $q['id'] ?></td>
                            <td style="padding: 1rem; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($q['question']) ?>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="background: rgba(139, 92, 246, 0.2); color: var(--primary); padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">
                                    <?= htmlspecialchars($q['categorie'] ?: 'Non classé') ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; display: flex; gap: 0.8rem;">
                                <a href="admin/questions.php?action=edit&id=<?= $q['id'] ?>" class="btn-primary" style="text-decoration:none; padding: 0.5rem 1rem; font-size: 0.85rem;">Modifier</a>
                                <a href="admin/questions.php?delete=<?= $q['id'] ?>" onclick="return confirm('Supprimer définitivement cette question ?');" class="btn-primary" style="text-decoration:none; padding: 0.5rem 1rem; background: var(--danger); font-size: 0.85rem;">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    
    <?php elseif ($action === 'add' || $action === 'edit'): 
        // VUE : FORMULAIRE D'AJOUT OU DE MODIFICATION
        $edit_q = null;
        if ($action === 'edit' && isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $edit_q = $stmt->fetch();
        }
    ?>
        <div class="glass-card" style="max-width: 800px; margin: 0 auto;">
            <h2 style="margin-bottom: 2rem; color: var(--primary);"><?= $edit_q ? '✏️ Modifier la question' : '➕ Ajouter une question' ?></h2>
            
            <form method="POST" action="admin/questions.php">
                <?php if ($edit_q): ?>
                    <input type="hidden" name="id" value="<?= $edit_q['id'] ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Catégorie (Optionnel)</label>
                    <input type="text" name="categorie" class="form-control" placeholder="Ex: Programmation, Base de données..." value="<?= htmlspecialchars($edit_q['categorie'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Intitulé de la question <span style="color: var(--danger);">*</span></label>
                    <textarea name="question" class="form-control" rows="3" required><?= htmlspecialchars($edit_q['question'] ?? '') ?></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                    <div class="form-group">
                        <label>Option 1 <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="reponse1" class="form-control" required value="<?= htmlspecialchars($edit_q['reponse1'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Option 2 <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="reponse2" class="form-control" required value="<?= htmlspecialchars($edit_q['reponse2'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Option 3 <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="reponse3" class="form-control" required value="<?= htmlspecialchars($edit_q['reponse3'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Option 4 <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="reponse4" class="form-control" required value="<?= htmlspecialchars($edit_q['reponse4'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--surface-border);">
                    <label style="color: var(--warning); font-size: 1.1rem;">Laquelle est la bonne réponse ? <span style="color: var(--danger);">*</span></label>
                    <select name="bonne_reponse" class="form-control" required style="background: var(--bg-color); color: white; cursor: pointer; padding: 1rem;">
                        <option value="1" <?= ($edit_q['bonne_reponse']??1) == 1 ? 'selected' : '' ?>>Option 1</option>
                        <option value="2" <?= ($edit_q['bonne_reponse']??1) == 2 ? 'selected' : '' ?>>Option 2</option>
                        <option value="3" <?= ($edit_q['bonne_reponse']??1) == 3 ? 'selected' : '' ?>>Option 3</option>
                        <option value="4" <?= ($edit_q['bonne_reponse']??1) == 4 ? 'selected' : '' ?>>Option 4</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit" style="font-size: 1.2rem; padding: 1.2rem; margin-top: 2rem;">
                    <?= $edit_q ? 'Enregistrer les modifications' : 'Ajouter à la base' ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
