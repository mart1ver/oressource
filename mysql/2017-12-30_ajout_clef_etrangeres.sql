-- Script pour l'ajout des clefs etrangeres pour la base.

SET autocommit = 0;

START TRANSACTION;

/*
  Exemple de requête pour voir les données orphelines.
  select p.*
  from pesees_collectes as p
  left outer join collectes as c
  on c.id = p.id_collecte
  where c.id is null;
*/

-- Vendus

-- insert INTO type_dechets (
-- 	nom,
-- 	description,
-- 	couleur,
-- 	visible,
-- 	id_createur,
-- 	id_last_hero
-- 	) VALUES (
-- 	"[Maintenance] type_dechets inconnu",
-- 	"Ce type existe afin de réguler le saisies incohérentes des anciennes bases.",
-- 	"#000000",
-- 	0,
-- 	1,
-- 	1
-- );

-- UPDATE vendus set
--   `id_type_dechet` = (case
--     when `id_type_dechet` = 0
--     then (select id from type_dechets 
--       where type_dechets.nom = "[Maintenance] type_dechets inconnu")
--     else `id_type_dechet`
--   end)
-- where id_type_dechet = 0;

-- Récupération des utilisateurs effacés.

--=============================================================================
-- Moyen de paiement.
--=============================================================================
insert into moyens_paiement (
  nom,
  description,
  couleur,
  visible,
  id_createur,
  id_last_hero
  ) VALUES (
  "[Maintenance] moyens de paiement inconnu",
  "Ce type existe afin de réguler des saisies incorrectes.",
  "#000000",
  0,
  1,
  1
);

update ventes
  set `id_moyen_paiement` = (select id from moyens_paiement
        where id_moyen_paiement.nom = "[Maintenance] moyens de paiement inconnu"),
    commentaire = "[Maintenance] Récupération car moyen de paiement invalide"
    where not exists (select 1
    from moyens_paiement m
    where m.id = ventes.id_moyen_paiement);
--=============================================================================
-- Fin moyen de paiement invalide.
--=============================================================================


-- Resolution du cas ou types_dechets = 0 ou inconnu.
insert into type_dechets (
  nom,
  description,
  couleur,
  visible,
  id_createur,
  id_last_hero
  ) VALUES (
  "[Maintenance] type de dechets inconnu",
  "Ce type existe afin de réguler le saisies incohérentes des anciennes bases.",
  "#000000",
  0,
  1,
  1
);

UPDATE vendus set
  `id_type_dechet` = (case
    when `id_type_dechet` = 0
    then (select id from type_dechets 
      where type_dechets.nom = "[Maintenance] type de dechets inconnu")
    else `id_type_dechet`
  end)
 where id_type_dechet = 0;

 insert into type_dechets_evac (
  nom,
  description,
  couleur,
  visible,
  id_createur,
  id_last_hero
  ) VALUES (
  "[Maintenance] type_dechets orphelin",
  "Ce type existe afin de réguler le saisies incohérentes des anciennes bases.",
  "#000000",
  0,
  1,
  1
);

--=============================================================================
-- Ajouts de contraintes sur les clefs etrangeres
--=============================================================================
alter table vendus add constraint FK_Vendus_Ventes
foreign key (id_vente) references ventes(id);

alter table vendus add constraint FK_Vendus_TypesDechets
foreign key (id_type_dechet) references type_dechets(id);

-- TODO: Contrainte à ajouter dans une prochaine mise à jour de la base
-- neccessite un refactoring.
-- alter table vendus add constraint FK_Vendus_Objet
-- foreign key (id_objet) references grille_objets(id);

alter table vendus add CONSTRAINT FK_Vendus_Createur
foreign key (id_createur) references utilisateurs(id);

alter table vendus add CONSTRAINT FK_Vendus_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Ventes

alter table ventes add constraint FK_Ventes_MoyenPaiement
foreign key (id_moyen_paiement) references moyens_paiement(id);

alter table ventes add constraint FK_Ventes_PointVente
foreign key (id_point_vente) references points_vente(id);

alter table ventes add CONSTRAINT FK_Ventes_Createur
foreign key (id_createur) references utilisateurs(id);

alter table ventes add CONSTRAINT FK_Ventes_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Pesees Collectes

alter table pesees_collectes add CONSTRAINT FK_PeseesCollectes_Collectes
foreign key (id_collecte) references collectes(id);

alter table pesees_collectes add CONSTRAINT FK_PeseesCollectes_TypeDechet
foreign key (id_type_dechet) references type_dechets(id);

alter table pesees_collectes add CONSTRAINT FK_PeseesCollectes_Createur
foreign key (id_createur) references utilisateurs(id);

alter table pesees_collectes add CONSTRAINT FK_PeseesCollectes_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Pesees Sorties

alter table pesees_sorties add CONSTRAINT FK_PeseesSorties_Sorties
foreign key (id_sortie) references sorties(id);

-- alter table pesees_sorties add CONSTRAINT FK_PeseesSorties_TypeDechets
-- foreign key (id_type_dechet) references type_dechets(id);

-- alter table pesees_sorties add CONSTRAINT FK_PeseesSorties_TypePoubelles
-- foreign key (id_type_poubelle) references types_poubelles(id);

-- alter table pesees_sorties add CONSTRAINT FK_PeseesSorties_TypeDechetEvac
-- foreign key (id_type_dechet_evac) references type_dechets_evac(id);

alter table pesees_sorties add CONSTRAINT FK_PeseesSorties_Createur
foreign key (id_createur) references utilisateurs(id);

alter table pesees_sorties add CONSTRAINT FK_PeseesSorties_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Pesees Vendu

-- Ajoutée par https://github.com/mart1ver/oressource/pull/286
alter table pesees_vendus add CONSTRAINT FK_PeseesVendus_Vendus
foreign key (id) references vendus(id);

alter table pesees_vendus add CONSTRAINT FK_PeseesVendus_Createur
foreign key (id_createur) references utilisateurs(id);

alter table pesees_vendus add CONSTRAINT FK_PeseesVendus_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Points Collectes

alter table points_collecte add CONSTRAINT FK_PointCollecte_Createur
foreign key (id_createur) references utilisateurs(id);

alter table points_collecte add CONSTRAINT FK_PointCollecte_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Points Sorties

alter table points_sortie add CONSTRAINT FK_PointSortie_Createur
foreign key (id_createur) references utilisateurs(id);

alter table points_sortie add CONSTRAINT FK_PointSortie_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Points Ventes

alter table points_vente add CONSTRAINT FK_PointVente_Createur
foreign key (id_createur) references utilisateurs(id);

alter table points_vente add CONSTRAINT FK_PointVente_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Collectes

alter table collectes add CONSTRAINT FK_Collectes_TypeCollecte
foreign key (id_type_collecte) references type_collecte(id);

alter table collectes add CONSTRAINT FK_Collectes_PointCollectes
foreign key (id_point_collecte) references points_collecte(id);

alter table collectes add CONSTRAINT FK_Collectes_Localite
foreign key (localisation) references localites(id);

alter table collectes add CONSTRAINT FK_Collectes_Createur
foreign key (id_createur) references utilisateurs(id);

alter table collectes add CONSTRAINT FK_Collectes_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Sorties

-- alter table sorties add CONSTRAINT FK_Sorties_Filiere
-- foreign key (id_filiere) references filieres_sortie(id);

-- alter table sorties add CONSTRAINT FK_Sorties_Convention
-- foreign key (id_convention) references conventions_sorties(id);

-- alter table sorties add CONSTRAINT FK_Sorties_TypeSortie
-- foreign key (id_type_sortie) references type_sortie(id);

-- alter table sorties add CONSTRAINT FK_Sorties_Localites
-- foreign key (id_point_sortie) references localites(id);

alter table sorties add CONSTRAINT FK_Sorties_Createur
foreign key (id_createur) references utilisateurs(id);

alter table sorties add CONSTRAINT FK_Sorties_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Types Poubelles

alter table types_poubelles add CONSTRAINT FK_TypesPoubelle_Createur
foreign key (id_createur) references utilisateurs(id);

alter table types_poubelles add CONSTRAINT FK_TypesPoubelle_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Types Collectes

alter table type_collecte add CONSTRAINT FK_TypesCollecte_Createur
foreign key (id_createur) references utilisateurs(id);

alter table type_collecte add CONSTRAINT FK_TypesCollecte_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Types Contenants

alter table type_contenants add CONSTRAINT FK_TypesContenants_Createur
foreign key (id_createur) references utilisateurs(id);

alter table type_contenants add CONSTRAINT FK_TypesContenants_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Types Dechets

alter table type_dechets add CONSTRAINT FK_TypesDechets_Createur
foreign key (id_createur) references utilisateurs(id);

alter table type_dechets add CONSTRAINT FK_TypesDechets_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Type Dechets Evacuation

alter table type_dechets_evac add CONSTRAINT FK_TypesDechetsEvac_Createur
foreign key (id_createur) references utilisateurs(id);

alter table type_dechets_evac add CONSTRAINT FK_TypesDechetsEvac_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Type Sorties

alter table type_sortie add CONSTRAINT FK_TypeSortie_Createur
foreign key (id_createur) references utilisateurs(id);

alter table type_sortie add CONSTRAINT FK_TypesSortie_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- Utilisateurs

alter table utilisateurs add CONSTRAINT FK_Utilisateurs_Createur
foreign key (id_createur) references utilisateurs(id);

alter table utilisateurs add CONSTRAINT FK_Utilisateurs_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- conventions_sorties

alter table conventions_sorties add CONSTRAINT FK_ConvSortie_Createur
foreign key (id_createur) references utilisateurs(id);

alter table conventions_sorties add CONSTRAINT FK_ConvSortie_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- filieres_sortie

alter table filieres_sortie add CONSTRAINT FK_FiliereSortie_Createur
foreign key (id_createur) references utilisateurs(id);

alter table filieres_sortie add CONSTRAINT FK_FiliereSortie_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- grille_objets

alter table grille_objets add CONSTRAINT FK_GrilleObjet_TypeDechet
foreign key (id_type_dechet) references type_dechets(id);

alter table grille_objets add CONSTRAINT FK_GrilleObjet_Createur
foreign key (id_createur) references utilisateurs(id);

alter table grille_objets add CONSTRAINT FK_GrilleObjet_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- localites

alter table localites add CONSTRAINT FK_Localite_Createur
foreign key (id_createur) references utilisateurs(id);

alter table localites add CONSTRAINT FK_Localite_Editeur
foreign key (id_last_hero) references utilisateurs(id);

-- moyens_paiement

alter table moyens_paiement add CONSTRAINT FK_MoyensPaiment_Createur
foreign key (id_createur) references utilisateurs(id);

alter table moyens_paiement add CONSTRAINT FK_MoyensPaiment_Editeur
foreign key (id_last_hero) references utilisateurs(id);

Commit;
SET autocommit = 1;