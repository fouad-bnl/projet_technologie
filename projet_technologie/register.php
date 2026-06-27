<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Rediriger si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['mot_de_passe'] ?? '';

    // Validation basique
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Le format de l'email est invalide.";
    } else {
        // Vérifier si l'email existe déjà (Éviter les doublons)
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé par un autre compte.";
        } else {
            // Hashage du mot de passe (Sécurité)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertion sécurisée avec requête préparée (Anti-injection SQL)
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, 'user')");
            if ($stmt->execute([$nom, $prenom, $email, $hashedPassword])) {
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<div class="glass-card" style="max-width: 500px; margin: 2rem auto;">
    <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary);">Créer un compte</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success" style="text-align: center;">
            <?= htmlspecialchars($success) ?><br><br>
            <a href="login.php" class="btn-primary" style="display: inline-block; margin-top: 1rem; text-decoration: none;">Aller à la connexion</a>
        </div>
    <?php else: ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" class="form-control" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
            </div>
            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>
        <div style="text-align: center; margin-top: 1.5rem;">
            <p style="color: var(--text-muted);">Déjà un compte ? <a href="login.php" style="color: var(--primary);">Se connecter</a></p>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
