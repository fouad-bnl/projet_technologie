<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCM Pro - Testez vos connaissances</title>
    <!-- Base URL pour s'assurer que les chemins relatifs fonctionnent toujours peu importe le dossier courant -->
    <base href="/projet_technologie/">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php" style="text-decoration:none; color:inherit;">QCM<span>Pro</span></a>
        </div>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="qcm.php">Passer un QCM</a>
                <a href="history.php">Historique</a>
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="admin/index.php" style="color: var(--warning);">Admin</a>
                <?php endif; ?>
                <a href="logout.php" class="btn-logout">Déconnexion</a>
            <?php else: ?>
                <a href="login.php">Connexion</a>
                <a href="register.php" class="btn-primary">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>
    <main class="container">
