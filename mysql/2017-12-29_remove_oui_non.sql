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

-- Allez zou fini les oui/non.
UPDATE moyens_paiement set visible = (case when visible = 'oui' then 1 else 0 end);
alter table moyens_paiement MODIFY column visible boolean not null default true;

UPDATE filieres_sortie set visible = (case when visible = 'oui' then 1 else 0 end);
alter table filieres_sortie MODIFY column visible boolean not null default true;

UPDATE localites set visible = (case when visible = 'oui' then 1 else 0 end);
alter table localites MODIFY column visible boolean not null default true;

UPDATE grille_objets set visible = (case when visible = 'oui' then 1 else 0 end);
alter table grille_objets MODIFY column visible boolean not null default true;

UPDATE type_dechets_evac set visible = (case when visible = 'oui' then 1 else 0 end);
alter table type_dechets_evac MODIFY column visible boolean not null default true;

UPDATE types_poubelles set visible = (case when visible = 'oui' then 1 else 0 end);
alter table types_poubelles MODIFY column visible boolean not null default true;

UPDATE types_poubelles set ultime = (case when ultime = 'oui' then 1 else 0 end);
alter table types_poubelles MODIFY column ultime boolean not null default true;

UPDATE type_sortie set visible = (case when visible = 'oui' then 1 else 0 end);
alter table type_sortie MODIFY column visible boolean not null default true;

UPDATE type_collecte set visible = (case when visible = 'oui' then 1 else 0 end);
alter table type_collecte MODIFY column visible boolean not null default true;

UPDATE type_contenants set visible = (case when visible = 'oui' then 1 else 0 end);
alter table type_contenants MODIFY column visible boolean not null default true;

UPDATE type_dechets set visible = (case when visible = 'oui' then 1 else 0 end);
alter table type_dechets MODIFY column visible boolean not null default true;

UPDATE points_sortie set visible = (case when visible = 'oui' then 1 else 0 end);
alter table points_sortie MODIFY column visible boolean not null default true;

UPDATE points_collecte set visible = (case when visible = 'oui' then 1 else 0 end);
alter table points_collecte MODIFY column visible boolean not null default true;

UPDATE points_vente set visible = (case when visible = 'oui' then 1 else 0 end);
alter table points_vente MODIFY column visible boolean not null default true;

UPDATE conventions_sorties set visible = (case when visible = 'oui' then 1 else 0 end);
alter table conventions_sorties MODIFY column visible boolean not null default true;

UPDATE description_structure set
  saisiec         = (case when saisiec = 'oui' then 1 else 0 end),
  viz             = (case when viz = 'oui' then 1 else 0 end),
  lot             = (case when lot = 'oui' then 1 else 0 end),
  affsp           = (case when affsp = 'oui' then 1 else 0 end),
  affss           = (case when affss = 'oui' then 1 else 0 end),
  affsr           = (case when affsr = 'oui' then 1 else 0 end),
  affsd           = (case when affsd = 'oui' then 1 else 0 end),
  affsde          = (case when affsde = 'oui' then 1 else 0 end),
  pes_vente       = (case when pes_vente = 'oui' then 1 else 0 end),
  tva_active      = (case when tva_active = 'oui' then 1 else 0 end),
  force_pes_vente = (case when force_pes_vente = 'oui' then 1 else 0 end);

alter table description_structure MODIFY column saisiec         boolean not null default true;
alter table description_structure MODIFY column lot             boolean not null default true;
alter table description_structure MODIFY column viz             boolean not null default true;
alter table description_structure MODIFY column tva_active      boolean not null default true;
alter table description_structure MODIFY column affsp           boolean not null default true;
alter table description_structure MODIFY column affss           boolean not null default true;
alter table description_structure MODIFY column affsr           boolean not null default true;
alter table description_structure MODIFY column affsd           boolean not null default true;
alter table description_structure MODIFY column affsde          boolean not null default true;
alter table description_structure MODIFY column pes_vente       boolean not null default true;
alter table description_structure MODIFY column force_pes_vente boolean not null default true;
