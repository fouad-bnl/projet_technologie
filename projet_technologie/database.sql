CREATE DATABASE IF NOT EXISTS qcm_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qcm_app;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    reponse1 VARCHAR(255) NOT NULL,
    reponse2 VARCHAR(255) NOT NULL,
    reponse3 VARCHAR(255) NOT NULL,
    reponse4 VARCHAR(255) NOT NULL,
    bonne_reponse INT NOT NULL CHECK (bonne_reponse BETWEEN 1 AND 4),
    categorie VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS tentatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    score FLOAT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tentative_id INT NOT NULL,
    question_id INT NOT NULL,
    reponse_utilisateur INT NOT NULL,
    correcte BOOLEAN NOT NULL,
    FOREIGN KEY (tentative_id) REFERENCES tentatives(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Insertion de l'administrateur par défaut
-- Mot de passe : "password" (hash bcrypt)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role)
VALUES ('Admin', 'Super', 'admin@qcm.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertion de 3 questions de test
INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie)
VALUES 
('Que signifie PHP ?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Hosting Platform', 'Programmer HTML Pages', 2, 'Programmation Web'),
('Quel attribut HTML est utilisé pour définir un style en ligne ?', 'class', 'styles', 'font', 'style', 4, 'HTML/CSS'),
('Quelle fonction PHP est utilisée pour vérifier si une variable est définie et non nulle ?', 'isset()', 'empty()', 'is_null()', 'defined()', 1, 'PHP');
