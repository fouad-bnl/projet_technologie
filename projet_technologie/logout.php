<?php
session_start(); // On récupère la session en cours

// On détruit toutes les variables de session
session_unset(); 

// On détruit la session
session_destroy(); 

// On redirige vers l'accueil
header("Location: index.php");
exit();
?>
