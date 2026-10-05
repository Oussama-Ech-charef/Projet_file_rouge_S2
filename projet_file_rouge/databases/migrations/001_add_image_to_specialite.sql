-- Migration: 001_add_image_to_specialite.sql
-- Objectif: Ajouter le champ `image` à la table `specialite` (Spécialité)
-- Ce champ stocke le chemin vers le fichier image dans assets/images/
-- Exemple: assets/images/Cardiologie.jpe
-- N'exécuter qu'une seule fois. Compatible avec un état initial sans colonne image.

-- Ajout de la colonne image
ALTER TABLE `specialite`
ADD COLUMN `image` VARCHAR(255) NOT NULL DEFAULT '' AFTER `description`;

-- Mise à jour des 6 spécialités avec les chemins réels vers les 7 images fournies (1 hero + 6 spécialités)
-- L'image Hero (hero.jpe) n'est pas liée à une spécialité, elle est utilisée directement dans la Hero Section.

UPDATE `specialite` SET `image` = 'assets/images/Cardiologie.jpe' WHERE `nom` = 'Cardiologie';
UPDATE `specialite` SET `image` = 'assets/images/Dermatologie.jpe' WHERE `nom` = 'Dermatologie';
UPDATE `specialite` SET `image` = 'assets/images/Pédiatrie.jpe' WHERE `nom` = 'Pédiatrie';
UPDATE `specialite` SET `image` = 'assets/images/Gynécologie.jpe' WHERE `nom` = 'Gynécologie';
UPDATE `specialite` SET `image` = 'assets/images/Ophtalmologie.jpe' WHERE `nom` = 'Ophtalmologie';
UPDATE `specialite` SET `image` = 'assets/images/Medecine_generale.jpe' WHERE `nom` = 'Médecine générale';
