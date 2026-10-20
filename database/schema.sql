-- ==============================================================================
-- Tech Hub - Schéma de Base de Données MySQL / MariaDB
-- Authentification, Sécurité et Profils Utilisateurs
-- ==============================================================================

-- 1. Création de la Base de Données
CREATE DATABASE IF NOT EXISTS `techhub_db`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `techhub_db`;

-- 2. Création de la Table des Utilisateurs
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'user',
    `avatar` VARCHAR(255) DEFAULT NULL,
    `remember_token` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `last_login_at` DATETIME DEFAULT NULL,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Utilisateur Administrateur initial (Optionnel)
-- Mot de passe par défaut : Admin1234! (haché en BCRYPT)
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`, `created_at`)
VALUES (
    'Alexandre',
    'analyste@techhub.io',
    '$2y$12$fTz3eT3K0pQ5vQhG9eXvye1y2i6a9T3x.Cj5hYy1Mv3eK7q1wXkCq',
    'admin',
    NOW()
) ON DUPLICATE KEY UPDATE `email`=`email`;
