# QCM Premium - Projet Technologie Web

## 📖 À propos du projet
Ce projet est une application web complète de génération et passage de QCM, développée dans le cadre d'un module de développement web. Le cahier des charges a été rigoureusement respecté : **aucun framework**, PHP natif avec gestion des sessions, et base de données MySQL via PDO sécurisé.

## ✨ Fonctionnalités Principales
- **Utilisateurs** : Inscription sécurisée (hashage de mot de passe) et connexion par sessions.
- **Passage de QCM** : 10 questions tirées aléatoirement parmi une banque de données en base.
- **Anti-Triche Strict** : Chronomètre intégré (10 minutes max), obligation d'être en plein écran, annulation automatique en cas de changement d'onglet (blur/visibilitychange), désactivation du clic droit et du copier/coller.
- **Suivi et Résultats** : Affichage d'une note sur 20, correction détaillée et historique des tentatives avec calcul de la moyenne générale.
- **Espace Administrateur** : Interface CRUD pour gérer les utilisateurs (suppression, blocage) et les questions.

## 🛠️ Technologies Utilisées
- **Frontend** : HTML5, CSS3 (Design moderne Glassmorphism), JavaScript Vanilla (Logique anti-triche et timer).
- **Backend** : PHP 8 natif sans framework ni CMS.
- **Base de données** : MySQL / MariaDB avec requêtes préparées (PDO) contre les injections SQL.

## 🚀 Installation locale (MAMP / XAMPP)
1. Clonez ou déplacez le dossier du projet dans votre répertoire local de MAMP (`htdocs`) ou XAMPP (`htdocs`).
2. Ouvrez **PhpMyAdmin** et importez le fichier `database.sql` situé à la racine du projet.
3. Modifiez si besoin les identifiants de connexion dans le fichier `config/db.php` (par défaut configuré pour XAMPP sans mot de passe, modifiez par `root` si vous êtes sur un MAMP Mac).
4. Lancez l'application dans votre navigateur : `http://localhost/projet_technologie/`

## 🔑 Accès Administrateur par défaut
- **Email** : admin@qcm.local
- **Mot de passe** : password
