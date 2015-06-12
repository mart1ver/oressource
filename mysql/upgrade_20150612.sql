--
-- Script de mise à jour
--
-- Si vous avez installé Oressource avant le 12 juin 2015
-- Vous devez exécuter ce script pour mettre à jour votre
-- base de données.
--

BEGIN;

ALTER TABLE `description_structure` 
ADD COLUMN `lot` text NOT NULL;

ALTER TABLE `description_structure`  
ADD COLUMN `viz` text NOT NULL;

ALTER TABLE `description_structure`
ADD COLUMN `nb_viz` int(11) NOT NULL;

ALTER TABLE `description_structure`
ADD COLUMN `saisiec` text NOT NULL;

COMMIT;
