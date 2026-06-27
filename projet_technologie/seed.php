<?php
require_once 'config/db.php';

$questions = [
    [
        'question' => 'Que signifie l\'acronyme CSS ?',
        'r1' => 'Computer Style Sheets',
        'r2' => 'Cascading Style Sheets',
        'r3' => 'Creative Style Sheets',
        'r4' => 'Colorful Style Sheets',
        'bonne' => 2,
        'cat' => 'HTML/CSS'
    ],
    [
        'question' => 'Quel est le rôle principal de JavaScript dans une page web ?',
        'r1' => 'Stocker les données côté serveur',
        'r2' => 'Mettre en forme le texte',
        'r3' => 'Rendre la page interactive',
        'r4' => 'Gérer la base de données',
        'bonne' => 3,
        'cat' => 'JavaScript'
    ],
    [
        'question' => 'Comment déclare-t-on une variable en PHP ?',
        'r1' => 'var nom = "Valeur";',
        'r2' => '$nom = "Valeur";',
        'r3' => 'let nom = "Valeur";',
        'r4' => 'variable nom = "Valeur";',
        'bonne' => 2,
        'cat' => 'PHP'
    ],
    [
        'question' => 'En SQL, quelle instruction permet d\'extraire des données d\'une base ?',
        'r1' => 'EXTRACT',
        'r2' => 'GET',
        'r3' => 'PULL',
        'r4' => 'SELECT',
        'bonne' => 4,
        'cat' => 'Base de données'
    ],
    [
        'question' => 'Quel port réseau est utilisé par défaut pour le protocole HTTP ?',
        'r1' => '21',
        'r2' => '443',
        'r3' => '80',
        'r4' => '8080',
        'bonne' => 3,
        'cat' => 'Réseau'
    ],
    [
        'question' => 'Quelle balise HTML est utilisée pour inclure un fichier JavaScript externe ?',
        'r1' => '<javascript>',
        'r2' => '<script>',
        'r3' => '<js>',
        'r4' => '<link>',
        'bonne' => 2,
        'cat' => 'HTML/CSS'
    ],
    [
        'question' => 'Dans l\'architecture MVC, que signifie la lettre "M" ?',
        'r1' => 'Module',
        'r2' => 'Macro',
        'r3' => 'Modèle',
        'r4' => 'Main',
        'bonne' => 3,
        'cat' => 'Architecture Web'
    ]
];

$compteur = 0;
foreach ($questions as $q) {
    // Vérifier si la question existe déjà pour éviter les doublons
    $check = $pdo->prepare("SELECT id FROM questions WHERE question = ?");
    $check->execute([$q['question']]);
    
    if (!$check->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$q['question'], $q['r1'], $q['r2'], $q['r3'], $q['r4'], $q['bonne'], $q['cat']]);
        $compteur++;
    }
}

echo "<h2 style='color: green;'>Succès ! $compteur questions ont été ajoutées à la base de données.</h2>";
echo "<p>Vous avez maintenant au moins 10 questions pour votre QCM.</p>";
echo "<a href='index.php'>Retour à l'accueil</a>";
?>
