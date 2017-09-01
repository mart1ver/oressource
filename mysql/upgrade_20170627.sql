--
-- Script de mise à jour
--
-- Si vous avez installé Oressource avant le 21 juin 2017
-- Vous devez exécuter ce script pour mettre à jour votre
-- base de données.
--

BEGIN;

ALTER TABLE `collectes`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `conventions_sorties`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `description_structure`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `filieres_sortie`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `grille_objets`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `localites`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `moyens_paiement`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `pesees_collectes`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `pesees_sorties`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `pesees_vendus`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `points_collecte`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `points_sortie`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `points_vente`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `sorties`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `types_poubelles`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `type_collecte`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `type_contenants`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `type_dechets`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `type_dechets_evac`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `type_sortie`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `utilisateurs`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `vendus`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `ventes`
MODIFY `id_createur` int(11) NOT NULL DEFAULT 0,
MODIFY `id_last_hero` int(11) NOT NULL DEFAULT 0,
MODIFY `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP;

COMMIT;
