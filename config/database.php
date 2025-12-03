-- ───────────────────────────────
-- TABLE ROLES
-- ───────────────────────────────
CREATE TABLE roles (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (name) VALUES
('administrateur'),
('ecole'),
('entreprise'),
('utilisateur');

-- ───────────────────────────────
-- TABLE USERS
-- ───────────────────────────────
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
role_id INT NOT NULL,
firstname VARCHAR(100),
lastname VARCHAR(100),
email VARCHAR(255) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
active BOOLEAN DEFAULT TRUE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- ───────────────────────────────
-- TABLE QUIZ
-- ───────────────────────────────
CREATE TABLE quiz (
id INT AUTO_INCREMENT PRIMARY KEY,
owner_id INT NOT NULL, -- école ou entreprise
title VARCHAR(255) NOT NULL,
description TEXT,
status ENUM('en_ecriture', 'lance', 'termine') DEFAULT 'en_ecriture',
active BOOLEAN DEFAULT TRUE,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (owner_id) REFERENCES users(id)
);

-- ───────────────────────────────
-- TABLE QUESTIONS
-- type = 'qcm' ou 'libre'
-- ───────────────────────────────
CREATE TABLE questions (
id INT AUTO_INCREMENT PRIMARY KEY,
quiz_id INT NOT NULL,
question_text TEXT NOT NULL,
type ENUM('qcm', 'libre') NOT NULL,
points INT DEFAULT 1,

FOREIGN KEY (quiz_id) REFERENCES quiz(id)
);

-- ───────────────────────────────
-- TABLE REPONSES POSSIBLES (pour QCM)
-- ───────────────────────────────
CREATE TABLE question_choices (
id INT AUTO_INCREMENT PRIMARY KEY,
question_id INT NOT NULL,
choice_text TEXT NOT NULL,
is_correct BOOLEAN DEFAULT FALSE,

FOREIGN KEY (question_id) REFERENCES questions(id)
);

-- ───────────────────────────────
-- TABLE REPONSES DES UTILISATEURS
-- (QCM : choice_id rempli / Libre : answer_text rempli)
-- ───────────────────────────────
CREATE TABLE user_responses (
id INT AUTO_INCREMENT PRIMARY KEY,
quiz_id INT NOT NULL,
question_id INT NOT NULL,
user_id INT NOT NULL,

choice_id INT NULL, -- si QCM
answer_text TEXT NULL, -- si réponse libre

submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (quiz_id) REFERENCES quiz(id),
FOREIGN KEY (question_id) REFERENCES questions(id),
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (choice_id) REFERENCES question_choices(id)
);

-- ───────────────────────────────
-- TABLE LOG DES CONNEXIONS (optionnel)
-- ───────────────────────────────
CREATE TABLE login_logs (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (user_id) REFERENCES users(id)
);
