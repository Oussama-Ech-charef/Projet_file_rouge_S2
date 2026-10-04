-- Cabinet Medical / Teleconsultation
-- Database: cabinet_medical
-- Technologies: PHP + MySQL + PDO
-- File: databases/sql/database.sql
-- Contains: Patient, Consultation, Spécialité (with image), Ordonnance

CREATE DATABASE IF NOT EXISTS cabinet_medical CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cabinet_medical;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table: specialite (Spécialité)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ordonnance`;
DROP TABLE IF EXISTS `consultation`;
DROP TABLE IF EXISTS `specialite`;
DROP TABLE IF EXISTS `patient`;

CREATE TABLE `patient` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `telephone` VARCHAR(20) DEFAULT NULL,
    `date_naissance` DATE DEFAULT NULL,
    `adresse` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `specialite` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `consultation` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT NOT NULL,
    `specialite_id` INT NOT NULL,
    `date_consultation` DATETIME NOT NULL,
    `motif` VARCHAR(255) NOT NULL,
    `statut` VARCHAR(50) NOT NULL DEFAULT 'prevue',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_consultation_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_consultation_specialite` FOREIGN KEY (`specialite_id`) REFERENCES `specialite` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ordonnance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `consultation_id` INT NOT NULL,
    `patient_id` INT NOT NULL,
    `medicament` VARCHAR(255) NOT NULL,
    `posologie` VARCHAR(255) NOT NULL,
    `duree` VARCHAR(100) NOT NULL,
    `date_prescription` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_ordonnance_consultation` FOREIGN KEY (`consultation_id`) REFERENCES `consultation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_ordonnance_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Données initiales : Spécialités (6 + image)
-- L'image est stockée comme chemin vers assets/images/
-- --------------------------------------------------------
INSERT INTO `specialite` (`nom`, `description`, `image`) VALUES
('Cardiologie', 'Diagnostic et traitement des maladies du coeur et du systeme cardiovasculaire.', 'assets/images/Cardiologie.jpe'),
('Dermatologie', 'Prise en charge des maladies de la peau, des cheveux et des ongles.', 'assets/images/Dermatologie.jpe'),
('Pédiatrie', 'Soins medicaux dedies aux nourrissons, enfants et adolescents.', 'assets/images/Pédiatrie.jpe'),
('Gynécologie', 'Suivi de la sante de la femme et accompagnement de la grossesse.', 'assets/images/Gynécologie.jpe'),
('Ophtalmologie', 'Diagnostic et traitement des troubles de la vision et des yeux.', 'assets/images/Ophtalmologie.jpe'),
('Médecine générale', 'Consultations de premiere ligne et suivi global de la sante.', 'assets/images/Medecine_generale.jpe');

-- --------------------------------------------------------
-- Données d'exemple : Patients
-- --------------------------------------------------------
INSERT INTO `patient` (`nom`, `prenom`, `email`, `telephone`, `date_naissance`, `adresse`) VALUES
('Dubois', 'Marie', 'marie.dubois@example.com', '0612345678', '1990-04-12', '12 Rue de Paris, 75001 Paris'),
('Martin', 'Ahmed', 'ahmed.martin@example.com', '0623456789', '1985-08-23', '45 Avenue des Champs, 69002 Lyon'),
('Leroy', 'Sophie', 'sophie.leroy@example.com', '0634567890', '1998-11-05', '8 Rue Nationale, 13001 Marseille'),
('Benali', 'Karim', 'karim.benali@example.com', '0645678901', '1979-02-17', '22 Boulevard Victor Hugo, 31000 Toulouse');

-- --------------------------------------------------------
-- Données d'exemple : Consultations
-- --------------------------------------------------------
INSERT INTO `consultation` (`patient_id`, `specialite_id`, `date_consultation`, `motif`, `statut`) VALUES
(1, 1, '2026-09-10 09:30:00', 'Bilan cardiologique annuel', 'prevue'),
(2, 3, '2026-09-12 14:00:00', 'Controle pediatrique', 'terminee'),
(3, 2, '2026-09-15 10:00:00', 'Consultation dermatologique - eruption cutanee', 'prevue'),
(4, 6, '2026-09-18 11:30:00', 'Consultation generale - fievre persistante', 'prevue');

-- --------------------------------------------------------
-- Données d'exemple : Ordonnances
-- --------------------------------------------------------
INSERT INTO `ordonnance` (`consultation_id`, `patient_id`, `medicament`, `posologie`, `duree`, `date_prescription`) VALUES
(2, 2, 'Paracetamol 500mg', '1 comprime 3 fois par jour', '5 jours', '2026-09-12'),
(3, 3, 'Cetirizine 10mg', '1 comprime par jour', '7 jours', '2026-09-15');
