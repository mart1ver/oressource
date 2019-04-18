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

-- Relaxe de la contrainte des mots de passe non null.
-- Si null utilisateur non loggable
ALTER TABLE utilisateurs MODIFY column pass text null;

INSERT INTO `utilisateurs` (
    `timestamp`, `niveau`, `nom`,
    `prenom`, `mail`, `pass`,
    `id_createur`, `id_last_hero`, `last_hero_timestamp`
  ) VALUES (
    CURRENT_TIMESTAMP, '', '[maintenance]',
    '[maintenance]', 'inconnu@localhost', '',
    '1', '1', CURRENT_TIMESTAMP
);

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

ALTER TABLE sorties        MODIFY COLUMN id_filiere          int DEFAULT 0;
ALTER TABLE sorties        MODIFY COLUMN id_convention       int DEFAULT 0;
ALTER TABLE sorties        MODIFY COLUMN id_type_sortie      int DEFAULT 0;
ALTER TABLE sorties        MODIFY COLUMN id_point_sortie     int NOT NULL;

ALTER TABLE pesees_sorties  MODIFY COLUMN id_type_dechet      int DEFAULT 0;
ALTER TABLE pesees_sorties  MODIFY COLUMN id_type_poubelle    int DEFAULT 0;
ALTER TABLE pesees_sorties  MODIFY COLUMN id_type_dechet_evac int DEFAULT 0;
ALTER TABLE grille_objets   MODIFY COLUMN prix                DECIMAL(10,2) DEFAULT 0.0 NOT NULL;
ALTER TABLE types_poubelles MODIFY COLUMN masse_bac           DECIMAL(10,2) DEFAULT 0.0 NOT NULL;
ALTER TABLE type_contenants MODIFY COLUMN masse               DECIMAL(10,2) DEFAULT 0.0 NOT NULL;
ALTER TABLE points_sortie   MODIFY COLUMN pesee_max           DECIMAL(10,2) DEFAULT 0.0 NOT NULL;
ALTER TABLE points_collecte MODIFY COLUMN pesee_max           DECIMAL(10,2) DEFAULT 0.0 NOT NULL;
ALTER TABLE points_vente    MODIFY COLUMN surface_vente       DECIMAL(10,2) DEFAULT 0.0 NOT NULL;
ALTER TABLE description_structure MODIFY COLUMN taux_tva      DECIMAL(10,3) DEFAULT 0.0 NOT NULL;
ALTER TABLE description_structure MODIFY COLUMN id_localite   int DEFAULT 1 NOT NULL;
ALTER TABLE description_structure MODIFY COLUMN cr            CHAR(32);

ALTER TABLE collectes           MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE conventions_sorties MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE filieres_sortie     MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE grille_objets       MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE localites           MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE moyens_paiement     MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE pesees_collectes    MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE pesees_sorties      MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE pesees_vendus       MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE points_collecte     MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE points_sortie       MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE points_vente        MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE sorties             MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE type_collecte       MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE type_contenants     MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE type_dechets        MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE type_dechets_evac   MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE types_poubelles     MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE utilisateurs        MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE vendus              MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE ventes              MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();
ALTER TABLE pesees_vendus       MODIFY COLUMN last_hero_timestamp timestamp NOT NULL default now() ON UPDATE now();

-- Un vendus peut ne pas avoir id_objet.
ALTER TABLE `vendus` MODIFY COLUMN `id_objet` int(11) NULL;

-- Un utilisateur dois avoir un email/pseudo unique.
ALTER TABLE utilisateurs        ADD CONSTRAINT UN_Utilisateur_mail    UNIQUE KEY(mail(255));

-- Contraintes pour avoir des « noms » uniques coté base.
ALTER TABLE conventions_sorties ADD CONSTRAINT UN_ConvSortie_nom      UNIQUE KEY(nom(255));
ALTER TABLE points_vente        ADD CONSTRAINT UN_PointsVente_nom     UNIQUE KEY(nom(255));
ALTER TABLE points_collecte     ADD CONSTRAINT UN_PointsSortie_nom    UNIQUE KEY(nom(255));
ALTER TABLE points_sortie       ADD CONSTRAINT UN_PointsCollecte_nom  UNIQUE KEY(nom(255));
ALTER TABLE type_dechets        ADD CONSTRAINT UN_TypeDechets_nom     UNIQUE KEY(nom(255));
ALTER TABLE type_contenants     ADD CONSTRAINT UN_TypeContenants_nom  UNIQUE KEY(nom(255));
ALTER TABLE type_collecte       ADD CONSTRAINT UN_TypeCollecte_nom    UNIQUE KEY(nom(255));
ALTER TABLE type_sortie    	    ADD CONSTRAINT UN_TypeSortie_nom      UNIQUE KEY(nom(255));
ALTER TABLE types_poubelles     ADD CONSTRAINT UN_TypesPoubelles_nom  UNIQUE KEY(nom(255));
ALTER TABLE type_dechets_evac   ADD CONSTRAINT UN_TypeDechetsEvac_nom UNIQUE KEY(nom(255));
ALTER TABLE grille_objets       ADD CONSTRAINT UN_GrilleObjets_nom    UNIQUE KEY(nom(255));
ALTER TABLE localites           ADD CONSTRAINT UN_localites_nom       UNIQUE KEY(nom(255));
ALTER TABLE moyens_paiement     ADD CONSTRAINT UN_MoyensPaiement_nom  UNIQUE KEY(nom(255));
ALTER TABLE filieres_sortie     ADD CONSTRAINT UN_FilieresSortie_nom  UNIQUE KEY(nom(255));

Commit;
SET autocommit = 1;
