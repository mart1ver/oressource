--
-- Script de mise à jour
--
-- Si vous avez installé Oressource avant le 11 octobre 2016
-- Vous devez exécuter ce script pour mettre à jour votre
-- base de données.
--

BEGIN;

ALTER TABLE `description_structure`
ADD COLUMN `session_timeout` int(11) NOT NULL;

ALTER TABLE `description_structure`
ADD COLUMN `pes_vente` text NOT NULL;

ALTER TABLE `description_structure`
ADD COLUMN `force_pes_vente` text NOT NULL;

CREATE TABLE IF NOT EXISTS `pesees_vendus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(11,3) NOT NULL,
  `quantite` int(11) NOT NULL,
  `id_vendu` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

COMMIT;
