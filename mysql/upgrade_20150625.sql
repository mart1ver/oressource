--
-- Script de mise à jour
--
-- Si vous avez installé Oressource avant le 25 juin 2015
-- Vous devez exécuter ce script pour mettre à jour votre
-- base de données.
--

BEGIN;

ALTER TABLE `description_structure` 
ADD COLUMN `affsp` text NOT NULL;

ALTER TABLE `description_structure`  
ADD COLUMN `affss` text NOT NULL;

ALTER TABLE `description_structure`
ADD COLUMN `affsr` text NOT NULL;

ALTER TABLE `description_structure`
ADD COLUMN `affsd` text NOT NULL;

ALTER TABLE `description_structure`
ADD COLUMN `affsde` text NOT NULL;

COMMIT;