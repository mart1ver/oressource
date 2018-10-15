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
-- 	"Regulation_de_la_base_de_données",
-- 	"Ce type existe afin de réguler le saisies incohérentes des anciennes bases.",
-- 	"#000000",
-- 	0,
-- 	1,
-- 	1
-- );

-- UPDATE vendus set
--   `id_type_dechet` = (case
--     when `id_type_dechet` = 0
--     then (select id from type_dechets where type_dechets.nom = "Regulation_de_la_base_de_données")
--     else `id_type_dechet`
--   end)
-- where id_type_dechet = 0;

-- Récupération des utilisateurs effacés.
-- insert into utilisateurs (
--   nom,
--   prenom,
--   pass
-- ) values()
--   "utilisateur",
--   "supprimé",
--   "utilisateur_suprimée@localhost",

-- ) values (

-- );

update collectes
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = collectes.id_createur);
update collectes
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = collectes.id_last_hero);


update conventions_sorties
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = conventions_sorties.id_createur);
update conventions_sorties
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = conventions_sorties.id_last_hero);

update description_structure
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = description_structure.id_createur);
update description_structure
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = description_structure.id_last_hero);

update filieres_sortie
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = filieres_sortie.id_createur);
update filieres_sortie
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = filieres_sortie.id_last_hero);

update grille_objets
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = grille_objets.id_createur);
update grille_objets
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = grille_objets.id_last_hero);

update localites
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = localites.id_createur);
update localites
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = localites.id_last_hero);

update moyens_paiement
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = moyens_paiement.id_createur);
update moyens_paiement
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = moyens_paiement.id_last_hero);

update pesees_collectes
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = pesees_collectes.id_createur);
update pesees_collectes
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = pesees_collectes.id_last_hero);

update pesees_sorties
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = pesees_sorties.id_createur);
update pesees_sorties
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = pesees_sorties.id_last_hero);

update pesees_vendus
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = pesees_vendus.id_createur);
update pesees_vendus
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = pesees_vendus.id_last_hero);

update points_collecte
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = points_collecte.id_createur);
update points_collecte
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = points_collecte.id_last_hero);

update points_sortie
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = points_sortie.id_createur);
update points_sortie
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = points_sortie.id_last_hero);

update points_vente
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = points_vente.id_createur);
update points_vente
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = points_vente.id_last_hero);

update sorties
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = sorties.id_createur);
update sorties
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = sorties.id_last_hero);

update type_collecte
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_collecte.id_createur);
update type_collecte
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_collecte.id_last_hero);

update type_contenants
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_contenants.id_createur);
update type_contenants
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_contenants.id_last_hero);

update type_dechets
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_dechets.id_createur);
update type_dechets
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_dechets.id_last_hero);

update type_dechets_evac
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_dechets_evac.id_createur);
update type_dechets_evac
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_dechets_evac.id_last_hero);

update type_sortie
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_sortie.id_createur);
update type_sortie
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = type_sortie.id_last_hero);

update types_poubelles
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = types_poubelles.id_createur);
update types_poubelles
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = types_poubelles.id_last_hero);

update vendus
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = vendus.id_createur);
update vendus
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = vendus.id_last_hero);

update ventes
  set id_createur = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = ventes.id_createur);

update ventes
  set id_last_hero = 1
    where not exists (select 1
    from utilisateurs u
    where u.id = ventes.id_last_hero);

-- Fin de la resolution des utilisateurs supprimée

-- Fin moyen de paiement invalide.
update ventes
  set id_moyen_paiement = 1,
  commentaire = "[Recovery - id_moyen_paiement] Récupération car moyen de paiement invalide"
    where not exists (select 1
    from moyens_paiement m
    where m.id = ventes.id_moyen_paiement);

-- Resolution du cas ou types_dechets = 0 ou inconnu.
insert into type_dechets (
  nom,
  description,
  couleur,
  visible,
  id_createur,
  id_last_hero
  ) VALUES (
  "Regulation_de_la_base_de_données",
  "Ce type existe afin de réguler le saisies incohérentes des anciennes bases.",
  "#000000",
  0,
  1,
  1
);

UPDATE vendus set
  `id_type_dechet` = (case
    when `id_type_dechet` = 0
    then (select id from type_dechets where type_dechets.nom = "Regulation_de_la_base_de_données")
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
  "Regulation_de_la_base_de_données",
  "Ce type existe afin de réguler le saisies incohérentes des anciennes bases.",
  "#000000",
  0,
  1,
  1
);


alter table vendus add constraint FK_Vendus_Ventes
foreign key (id_vente) references ventes(id);

alter table vendus add constraint FK_Vendus_TypesDechets
foreign key (id_type_dechet) references type_dechets(id);

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