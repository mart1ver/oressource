--
-- Script de mise à jour
--
-- Si vous avez installé Oressource avant le 25 juin 2015
-- Vous devez exécuter ce script pour mettre à jour votre
-- base de données.
--

BEGIN;
ALTER TABLE  `description_structure` 
	ADD  `affsp` TEXT NOT NULL ,
	ADD  `affss` TEXT NOT NULL ,
	ADD  `affsr` TEXT NOT NULL ,
	ADD  `affsd` TEXT NOT NULL ; 

COMMIT;
