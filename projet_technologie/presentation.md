# 🎤 Présentation Orale du Projet QCM (Script)

**Bonjour à tous, je vous présente mon projet de fin de module : une application web de QCM sécurisée et anti-triche.**

### 1. L'objectif et l'approche technique
Pour ce projet, le défi principal était d'utiliser uniquement les technologies web fondamentales : HTML, CSS, JavaScript natif, et PHP natif sans utiliser la facilité offerte par les frameworks (comme Laravel ou Symfony). L'objectif était de bâtir une architecture solide avec une arborescence claire (séparation de la logique dans `includes/` et `config/`) et d'assurer une sécurité sans faille côté serveur en utilisant PDO pour bloquer les injections SQL et la fonction native `password_hash` pour protéger les mots de passe.

### 2. Le Design et l'Expérience Utilisateur
J'ai choisi de m'éloigner des designs très basiques souvent vus dans les projets étudiants pour offrir une vraie expérience premium. J'ai utilisé l'esthétique du "Glassmorphism" avec un mode sombre très moderne, des dégradés subtils et des micro-animations en CSS pur. L'interface est intuitive et 100% responsive.

### 3. Le Cœur du système : L'Anti-Triche
La partie dont je suis le plus fier est la mécanique Anti-Triche en JavaScript couplée à la validation PHP. Dès qu'un étudiant démarre un test :
- Le navigateur passe automatiquement en plein écran obligatoire.
- Un chronomètre visuel s'active avec une limite stricte de 10 minutes.
- Le clic droit, le copier-coller et la sélection de texte sont bridés.
- Si le candidat quitte le mode plein écran, ouvre un autre onglet, ou réduit son navigateur, le moteur JS intercepte l'action grâce aux Events `visibilitychange` et `fullscreenchange`. Il lève alors un flag de triche, soumet le QCM de force, et le backend PHP sanctionne instantanément la tentative par un 0/20.

### 4. Suivi et Administration
Du côté des utilisateurs, l'étudiant peut voir la correction de ses questions à la fin de l'examen et consulter un tableau de bord calculant sa moyenne générale sur toutes ses tentatives. Du côté de l'administration, j'ai développé un espace protégé (vérifié par les variables de `$_SESSION`) qui permet au corps enseignant de gérer le pool de questions de la BDD et de bloquer l'accès ou supprimer des étudiants malveillants.

**En conclusion**, ce projet m'a permis de consolider mes acquis en développement "from scratch", et de comprendre les enjeux réels de la gestion d'une session, du routage en PHP et de la sécurité. 

Je vous invite à présent à faire un test et essayer de tricher avec le système !
