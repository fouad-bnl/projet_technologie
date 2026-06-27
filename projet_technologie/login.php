<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Rediriger si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Rechercher l'utilisateur par son email (Anti-injection SQL)
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Vérifier si l'utilisateur existe et si le mot de passe correspond au hash en BDD
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            
            // Vérifier si le compte est bloqué (Sécurité ajoutée depuis l'interface admin)
            if (isset($user['is_blocked']) && $user['is_blocked']) {
                $error = "Accès refusé : Votre compte a été bloqué par un administrateur.";
            } else {
                // Création de la session sécurisée
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role']; // 'user' ou 'admin'
                
                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: qcm.php");
                }
                exit();
            }
        } else {
            $error = "Adresse email ou mot de passe incorrect.";
        }
    }
}
?>

<div class="glass-card" style="max-width: 500px; margin: 2rem auto;">
    <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary);">Connexion</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">Adresse Email</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
        </div>
        <button type="submit" class="btn-submit">Se connecter</button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <p style="color: var(--text-muted);">Pas encore de compte ? <a href="register.php" style="color: var(--primary);">S'inscrire</a></p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
