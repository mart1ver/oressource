-- Oressource
-- Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU Affero General Public License as
-- published by the Free Software Foundation, either version 3 of the
-- License, or (at your option) any later version.

-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU Affero General Public License for more details.

-- You should have received a copy of the GNU Affero General Public License
-- along with this program.  If not, see <http://www.gnu.org/licenses/>.

-- Mise en confirmité avec les 'NOT NULL' et 'NO-ZERO-IN-DATE'

SET autocommit = 0;

START TRANSACTION;

UPDATE `utilisateurs` set
    `id_createur` = (case
    when `id_createur` = 0 then 1 else `id_createur` end),
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when (`last_hero_timestamp` IS NULL) OR (`last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s'))
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE pesees_sorties set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE pesees_collectes set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE moyens_paiement set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE localites set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE grille_objets set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE filieres_sortie set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE conventions_sorties set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE collectes set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

  UPDATE collectes set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE points_collecte set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE points_sortie set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE points_vente set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE sorties set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE type_collecte set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE type_contenants set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE type_dechets set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE type_dechets_evac set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE type_sortie set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE types_poubelles set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE vendus set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE ventes set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when `last_hero_timestamp` is null OR `last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s')
    then `timestamp` else `last_hero_timestamp`
  end);

UPDATE `pesees_vendus` set
  `id_last_hero` = (case
    when `id_last_hero` = 0
    then `id_createur` else `id_last_hero`
  end),
  `last_hero_timestamp` = (case
    when (`last_hero_timestamp` IS NULL) OR (`last_hero_timestamp` = STR_TO_DATE('0000-00-00 00:00:00', '%Y-%m-%d %H:%i:%s'))
    then `timestamp` else `last_hero_timestamp`
  end);

Commit;
SET autocommit = 1;
