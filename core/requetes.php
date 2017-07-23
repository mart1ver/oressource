<?php

/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function objet_id_dechet(PDO $bdd, $id_dechet) {
  $stmt = $bdd->prepare("SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet");
  $stmt->bindValue(':id_type_dechet', $id_dechet, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function objet_id(PDO $bdd, $id_obj) {
  $req = $bdd->prepare("SELECT * FROM grille_objets WHERE id = :id_obj");
  $req->bindValue(':id_obj', $id_obj, PDO::PARAM_INT);
  $req->execute();
  $result = $req->fetch(PDO::FETCH_ASSOC);
  $req->closeCursor();
  return $result;
}

function objet_update_visible(PDO $bdd, $id, $visible) {
  $req = $bdd->prepare('update grille_objets set visible = :visible where id = :id');
  $req->bindValue(':id', $id, PDO::PARAM_INT);
  $req->bindValue(':visible', $visible, PDO::PARAM_STR);
  $req->execute();
  $req->closeCursor();
}

function objet_update_nom(PDO $bdd, $id, $nom) {
  $req = $bdd->prepare('update grille_objets set nom = :nom where id = :id');
  $req->bindValue(':id', $id, PDO::PARAM_INT);
  $req->bindValue(':nom', $nom, PDO::PARAM_STR);
  $req->execute();
  $req->closeCursor();
}

function objet_update(PDO $bdd, int $id, $prix, $nom, $description) {
  $req = $bdd->prepare('
      update grille_objets
      set nom = :nom1,
          description = :description,
          prix = :prix
      where BINARY nom <> :nom2
      and id = :id');
  $req->bindValue(':id', $id, PDO::PARAM_INT);
  $req->bindValue(':prix', $prix);
  $req->bindParam(':nom1', $nom, PDO::PARAM_STR);
  $req->bindParam(':nom2', $nom, PDO::PARAM_STR);
  $req->bindParam(':description', $description, PDO::PARAM_STR);
  $req->execute();
  if ($req->rowCount() === 0) {
    $req->closeCursor();
    throw new UnexpectedValueException('Un objet avec le meme nom existe deja.');
  }
  $req->closeCursor();
}

function utilisateurs_id(PDO $bdd, int $id) {
  $sql = 'SELECT
    utilisateurs.mail mail
    FROM utilisateurs
    WHERE utilisateurs.id = :id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function utilisateurs(PDO $bdd): array {
  $sql = 'SELECT
    utilisateurs.mail mail
    FROM utilisateurs';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function convention_sortie(PDO $bdd): array {
  $sql = 'SELECT id, nom FROM conventions_sorties WHERE visible = "oui"';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function convention_sortie_by_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom FROM conventions_sorties
          WHERE visible = "oui" AND id = :id LIMIT 1';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function point_collecte_id(PDO $bdd, int $id): array {
  $sql = 'SELECT pesee_max, nom
          FROM points_collecte
          WHERE id = :id
          LIMIT 1';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $point_collecte = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $point_collecte;
}

function point_sorties_id(PDO $bdd, int $id): array {
  $sql = 'SELECT pesee_max, nom FROM points_sortie WHERE id = :id LIMIT 1';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $point_sortie = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $point_sortie;
}

function points_collectes(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom, adresse FROM points_collecte WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function points_sorties(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom, adresse FROM points_sortie WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function points_sorties_id(PDO $bdd, int $id): array {
  $stmt = $bdd->prepare('SELECT id, nom, adresse, description, visible FROM points_sortie WHERE id = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function points_ventes(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom, adresse FROM points_vente WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function points_ventes_id(PDO $bdd, int $id_point_vente): array {
  $stmt = $bdd->prepare('SELECT id, nom, adresse FROM points_vente WHERE id = :id');
  $stmt->bindValue(':id', $id_point_vente, PDO::PARAM_INT);
  $stmt->execute();
  $point_sortie = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $point_sortie;
}

function types_contenants(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT masse, nom FROM type_contenants WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_contenants_id(PDO $bdd, int $id): array {
  $stmt = $bdd->prepare('SELECT masse, nom FROM type_contenants WHERE id = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function localites(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom FROM localites WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_poubelles(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom, masse_bac, couleur FROM types_poubelles WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_poubelles_id(PDO $bdd, int $id): array {
  $stmt = $bdd->prepare('SELECT id, nom, masse_bac, couleur, ultime FROM types_poubelles WHERE id = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function types_conteneurs(PDO $bdd): array {
  $sql = 'SELECT masse, nom FROM type_contenants WHERE visible = "oui"';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_dechets_evac(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom, couleur FROM type_dechets_evac WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_dechets_evac_id(PDO $bdd, int $id): array {
  $stmt = $bdd->prepare('SELECT id, nom, couleur, description FROM type_dechets_evac WHERE :id = id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function types_dechets(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom, couleur, description FROM type_dechets WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_dechets_id(PDO $bdd, int $id): array {
  $stmt = $bdd->prepare('SELECT id, nom, couleur, description FROM type_dechets WHERE :id = id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function types_sorties(PDO $bdd): array {
  $stmt = $bdd->prepare('SELECT id, nom FROM type_sortie WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_collectes(PDO $bdd): array {
  $sql = 'SELECT id, nom FROM type_collecte WHERE visible = "oui"';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_collectes_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, description, couleur FROM type_collecte WHERE :id = id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function filieres_sorties(PDO $bdd): array {
  $sql = 'SELECT filieres_sortie.id, filieres_sortie.nom, filieres_sortie.id_type_dechet_evac AS type_dechet
          FROM filieres_sortie
          WHERE filieres_sortie.visible = "oui"';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  $filieres_sorties = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return array_map(function($filiere) {
    $filiere['accepte_type_dechet'] = explode('a', $filiere['type_dechet']);
    return $filiere;
  }, $filieres_sorties);
}

function nb_categories_dechets_evac(PDO $bdd): int {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM type_dechets_evac LIMIT 1');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return $nombre_categories;
}

function nb_categories_dechets_item(PDO $bdd): int {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM type_dechets');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return (int) $nombre_categories;
}

function nb_categories_poubelles(PDO $bdd): int {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM types_poubelles LIMIT 1');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return (int) $nombre_categories;
}

// Insere une collecte dans la base.
// On ecrit le nombre de categories maxi dans la varialble $nombreCategories
// HACK C'est pas parfait il faudrait comparer aux differents ID de la base.
// Si des utilisateurs suppriment des objet sa la main c'est la galere...
// Mais d'un cote niveau tracabilite ils devraient pas supprimer de categories...
// genere une exception si les masses sont inferieurs a 0.
function insert_items_collecte(PDO $bdd, int $id_collecte, $collecte, $items) {
  $nombreCategories = nb_categories_dechets_item($bdd);
  $req = $bdd->prepare('INSERT INTO pesees_collectes
                            (timestamp, masse, id_collecte, id_type_dechet, id_createur)
                        VALUES (:timestamp, :masse, :id_collecte, :id_type_dechet, :id_createur)');
  $req->bindValue(':timestamp', $collecte['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_collecte', $id_collecte, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $collecte['id_user'], PDO::PARAM_INT);
  foreach ($items as $item) {
    $masse = (double) parseFloat($item['masse']);
    $type_dechet = (int) parseInt($item['type']);
    if ($masse > 0.00 && $type_dechet <= $nombreCategories) {
      $req->bindValue(':masse', $masse);
      $req->bindValue(':id_type_dechet', $type_dechet, PDO::PARAM_INT);
      $req->execute();
    } else {
      $req->closeCursor();
      throw new UnexpectedValueException('masse <= 0.0 ou type item inconnu');
    }
  }
}

// TODO: Refactorer ça trop d'info
function pesee_collectes_id(PDO $bdd, int $id_collecte): int {
  $sql = 'SELECT
    pesees_collectes.id, pesees_collectes.timestamp,
    type_dechets.nom, pesees_collectes.masse, type_dechets.couleur,
    utilisateurs.mail mail, pesees_collectes.last_hero_timestamp edit_date,
    pesees_collectes.id_last_hero id_editeur
    FROM pesees_collectes, type_dechets, utilisateurs, collectes
    WHERE type_dechets.id = pesees_collectes.id_type_dechet
    AND utilisateurs.id = pesees_collectes.id_createur
    AND pesees_collectes.id_collecte = :id_collecte
    GROUP BY id';
  $stmt = $bdd->prepare($sql);
  $stmt->prepare(['id_collecte' => $id_collecte]);
  return $stmt - fetchAll(PDO::FETCH_ASSOC);
}

function collecte_id(PDO $bdd, int $id): array {
  $sql = 'Select id,
    timestamp, id_type_collecte,
    localisation, id_point_collecte,
    commentaire, id_createur,
    id_editeur, edit_date
    From collecte Where id = :id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $collecte = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $collecte;
}

// En attendant de faire des objets on fait un tableau associatif.
function insert_collecte(PDO $bdd, array $collecte): int {
  $req = $bdd->prepare('INSERT INTO collectes
                            (timestamp, id_type_collecte,
                            localisation, id_point_collecte,
                            commentaire, id_createur)
                          VALUES (:timestamp, :id_type_action,
                                  :localite, :id_point,
                                  :commentaire, :id_createur)');
  $req->bindValue(':timestamp', $collecte['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_type_action', $collecte['id_type_action'], PDO::PARAM_INT);
// HACK: virer les adherants de la base ou juste changer le type en booleen serieusement...
  $req->bindValue(':localite', $collecte['localite'], PDO::PARAM_INT);
  $req->bindValue(':id_point', $collecte['id_point'], PDO::PARAM_INT);
  $req->bindValue(':commentaire', $bdd->quote($collecte['commentaire']), PDO::PARAM_STR);
  $req->bindValue(':id_createur', $collecte['id_user'], PDO::PARAM_INT);
  $req->execute();
  return (int) $bdd->lastInsertId();
}

function insert_items_sorties(PDO $bdd, int $id_sorties, $sortie, $items) {
  $nombreCategories = nb_categories_dechets_item($bdd);
  $req = $bdd->prepare('INSERT INTO pesees_sorties (timestamp, masse,  id_sortie, id_type_dechet, id_createur)
                            VALUES(:timestamp, :masse, :id_sortie, :id_type_dechet, :id_createur)');
  $req->bindValue(':timestamp', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_sortie', $id_sorties, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $sortie['id_user'], PDO::PARAM_INT);
  foreach ($items as $item) {
    $masse = (double) parseFloat($item['masse']);
    $type_dechet = (int) parseInt($item['type']);
    if ($masse > 0.00 && $type_dechet <= $nombreCategories) {
      $req->bindValue(':masse', $masse);
      $req->bindValue(':id_type_dechet', $type_dechet, PDO::PARAM_INT);
      $req->execute();
    } else {
      $req->closeCursor();
      throw new UnexpectedValueException('masse <= 0.0 ou type item inconnu');
    }
  }
}

function insert_evac_sorties(PDO $bdd, int $id_sorties, $sortie, $items) {
  $nombreCategories = nb_categories_dechets_evac($bdd);
  $req = $bdd->prepare('INSERT INTO pesees_sorties (timestamp, masse,  id_sortie, id_type_dechet_evac, id_createur)
                            VALUES(:timestamp, :masse, :id_sortie, :id_type_dechet_evac, :id_createur)');
  $req->bindValue(':timestamp', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_sortie', $id_sorties, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $sortie['id_user'], PDO::PARAM_INT);
  foreach ($items as $item) {
    $masse = (double) parseFloat($item['masse']);
    $type_dechet = (int) parseInt($item['type']);
    if ($masse > 0.00 && $type_dechet <= $nombreCategories) {
      $req->bindValue(':masse', $masse);
      $req->bindValue(':id_type_dechet_evac', $type_dechet, PDO::PARAM_INT);
      $req->execute();
    } else {
      $req->closeCursor();
      throw new UnexpectedValueException('masse <= 0.0 ou type item inconnu');
    }
  }
}

function insert_poubelle_sorties(PDO $bdd, int $id_sorties, $sortie, $items) {
  $nombreCategories = nb_categories_poubelles($bdd);
  $req = $bdd->prepare('INSERT INTO pesees_sorties (timestamp, masse, id_sortie, id_type_poubelle, id_createur)
                            VALUES(:timestamp, :masse, :id_sortie, :id_type_poubelle, :id_createur)');
  $req->bindValue(':timestamp', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_sortie', $id_sorties, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $sortie['id_user'], PDO::PARAM_INT);
  foreach ($items as $item) {
    $masse = (double) parseFloat($item['masse']);
    $type_dechet = (int) parseInt($item['type']);
    if ($masse > 0.00 && $type_dechet <= $nombreCategories) {
      $req->bindValue(':masse', $masse);
      $req->bindValue(':id_type_poubelle', $type_dechet, PDO::PARAM_INT);
      $req->execute();
    } else {
      $req->closeCursor();
      throw new UnexpectedValueException('masse <= 0.0 ou type item inconnu');
    }
  }
}

function specialise_sortie(PDOStatement $stmt, $sortie) {
  $classe = $sortie['classe'];
// Sorties Dons
  if ($classe === 'sorties') {
    $stmt->bindvalue(':type_sortie', $sortie['type_sortie'], PDO::PARAM_INT);
    $stmt->bindvalue(':id_filiere', 0, PDO::PARAM_INT);
    $stmt->bindvalue(':id_convention', 0, PDO::PARAM_INT);
// Sorties recycleur
  } elseif ($classe === 'sortiesr') {
    $stmt->bindvalue(':type_sortie', 0, PDO::PARAM_INT);
    $stmt->bindvalue(':id_filiere', $sortie['type_sortie'], PDO::PARAM_INT);
    $stmt->bindvalue(':id_convention', 0, PDO::PARAM_INT);
// Sorties conventions
  } elseif ($classe === 'sortiesc') {
    $stmt->bindvalue(':type_sortie', 0, PDO::PARAM_INT);
    $stmt->bindvalue(':id_filiere', 0, PDO::PARAM_INT);
    $stmt->bindvalue(':id_convention', $sortie['type_sortie'], PDO::PARAM_INT);
  } elseif ($classe === 'sortiesp' || $classe === 'sortiesd') {
    $stmt->bindvalue(':type_sortie', 0, PDO::PARAM_INT);
    $stmt->bindvalue(':id_filiere', 0, PDO::PARAM_INT);
    $stmt->bindvalue(':id_convention', 0, PDO::PARAM_INT);
  } else {
    throw new UnexpectedValueException("class de sortie inconnue");
  }
  return $stmt;
}

function insert_sortie(PDO $bdd, array $sortie): array {
  $sql = 'INSERT INTO sorties (timestamp, id_filiere, id_convention,
                                id_type_sortie, classe,
                                id_point_sortie, commentaire, id_createur)
          VALUES(:timestamp, :id_filiere, :id_convention,
                 :type_sortie, :classe,
                 :id_point_sortie, :commentaire, :id_createur)  ';
  $req = $bdd->prepare($sql);
  $req->bindvalue(':timestamp', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindvalue(':classe', $sortie['classe'], PDO::PARAM_STR);

  $req = specialise_sortie($req, $sortie);

  $req->bindvalue(':id_point_sortie', $sortie['id_point_sortie'], PDO::PARAM_INT);
  $req->bindvalue(':commentaire', $bdd->quote($sortie['commentaire']), PDO::PARAM_STR);
  $req->bindvalue(':id_createur', $sortie['id_user'], PDO::PARAM_INT);
  $req->execute();
  return (int) $bdd->lastInsertId();
}

function structure(PDO $bdd): array {
  $sql = 'SELECT id_localite, tva_active, taux_tva, nom,
                siret, telephone, mail, description, cr, adresse, texte_adhesion, lot, viz,
                nb_viz, saisiec, affsp, affss, affsr,
                affsd, affsde, pes_vente, force_pes_vente
          FROM description_structure LIMIT 1';
  $req = $bdd->prepare($sql);
  $req->execute();
  $result = $req->fetch(PDO::FETCH_ASSOC);

  /* a activé une fois les changements prets.
  $result['tva_active'] = oui_non_to_bool($result['tva_active']);
  $result['lot'] = oui_non_to_bool($result['lot']);
  $result['viz'] = oui_non_to_bool($result['viz']);
  $result['saisiec'] = oui_non_to_bool($result['saisiec']);
  $result['affsp'] = oui_non_to_bool($result['affsp']);
  $result['affss'] = oui_non_to_bool($result['affss']);
  $result['affsr'] = oui_non_to_bool($result['affsr']);
  $result['affsd'] = oui_non_to_bool($result['affsd']);
  $result['affsde'] = oui_non_to_bool($result['affsde']);
  $result['pes_vente'] = oui_non_to_bool($result['pes_vente']);
  $result['force_pes_vente'] = oui_non_to_bool($result['force_pes_vente']);
  */
  $req->closeCursor();
  return $result;
}

function structure_update(PDO $bdd, array $structure): void {
  $sql = 'UPDATE description_structure
    SET nom = :nom,
    adresse = :adresse,
    description = :description,
    siret = :siret,
    telephone = :telephone,
    mail =:mail,
    taux_tva = :taux_tva,
    tva_active= :tva_active,
    cr = :cr,
    lot = :lot,
    viz = :viz,
    nb_viz = :nb_viz,
    saisiec =: saisiec,
    affsp = :affsp,
    affss = :affss,
    affsr = :affsr,
    affsd = :affsd,
    affsde = :affsde,
    pes_vente = :pes_vente,
    force_pes_vente = :force_pes_vente
    WHERE id = :id';
  $stmt = $bdd->prepare($sql);
  $stmt->execute($structure);
  $stmt->closeCursor();
}

// Return a valid user or an exception.
function login_user(PDO $bdd, string $email, string $password): array {
  $passmd5 = md5($password);
  $req = $bdd->prepare('SELECT id, niveau, nom, prenom, mail
                        FROM utilisateurs
                        WHERE mail = :mail AND pass = :pass LIMIT 1');
  $req->bindValue(':mail', $email, PDO::PARAM_STR);
  $req->bindValue(':pass', $passmd5, PDO::PARAM_INT);
  $req->execute();
  $user = $req->fetch(PDO::FETCH_ASSOC);
  $req->closeCursor();
  if ($user) {
    return $user;
  } else {
    throw new Exception('Mot de passe ou nom de compte invalide.');
  }
}

/**
 * Renvoie les donnees neccessaire a moris.js pour le tableau de bord.
 * @global type $bdd  Connection PDO valide.
 * @param string $sql Requete sql valide
 * @return array Correspondant aux donnees pour morris.js.
 */
function data_graphs(PDOStatement $stmt) {
  $stmt->execute();
  $data = [];
  $colors = [];
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $iter) {
    array_push($data, ['value' => $iter['somme'], 'label' => $iter['nom']]);
    array_push($colors, $iter['couleur']);
  }
  return ['data' => $data, 'colors' => $colors];
}

function data_graphs_from_bilan($bilan, $key) {
  $data = [];
  $colors = [];
  foreach ($bilan as $_ => $iter) {
    array_push($data, ['value' => $iter[$key], 'label' => $iter['nom']]);
    array_push($colors, $iter['couleur']);
  }
  return ['data' => $data, 'colors' => $colors];
}

// Tableau de recap du Chiffre d'Affaire par mode de paiement
// Utile pour vérifier le fond de caisse en fin de vente
// Equivalent de la touche 'Z' sur une caisse enregistreuse
// Affichage du tableau
function chiffre_affaire_par_mode_paiement(PDO $bdd, $start, $stop) {
  $sql = 'SELECT
    ventes.id_moyen_paiement AS id_moyen,
    moyens_paiement.nom AS moyen,
    COUNT(DISTINCT(ventes.id)) AS quantite_vendue,
    SUM(vendus.prix * vendus.quantite) AS total,
    SUM(vendus.remboursement) AS remboursement
  FROM
    ventes,
    vendus,
    moyens_paiement
  WHERE
    vendus.id_vente = ventes.id
     AND moyens_paiement.id = ventes.id_moyen_paiement
     AND DATE(vendus.timestamp)
     BETWEEN :du AND :au
    GROUP BY ventes.id_moyen_paiement';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function chiffre_affaire_mode_paiement_point_vente(PDO $bdd, $start, $stop, $id_point_vente) {
  $sql = 'SELECT
    ventes.id_moyen_paiement AS id_moyen,
    moyens_paiement.nom AS moyen,
    COUNT(DISTINCT(ventes.id)) AS quantite_vendue,
    SUM(vendus.prix * vendus.quantite) AS total,
    SUM(vendus.remboursement) AS remboursement
  FROM
    ventes,
    vendus,
    moyens_paiement
  WHERE
    vendus.id_vente = ventes.id
     AND moyens_paiement.id = ventes.id_moyen_paiement
     AND DATE(vendus.timestamp) BETWEEN :du AND :au
     AND ventes.id_point_vente = :id_point_vente
  GROUP BY ventes.id_moyen_paiement';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->bindValue(':id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function nb_points_ventes($bdd) {
  $sql = 'SELECT COUNT(id) as nb_points_ventes
                          FROM points_vente LIMIT 1';
  $stmt = $bdd->query($sql);
  $nb_point_ventes = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nb_points_ventes'];
  $stmt->closeCursor();
  return $nb_point_ventes;
}

function nb_ventes(PDO $bdd, $start, $stop) {
  $sql = 'SELECT
    COUNT(DISTINCT(ventes.id)) as nb_ventes
    FROM ventes, vendus
    WHERE vendus.id_vente = ventes.id
    AND DATE(vendus.timestamp)
    BETWEEN :du AND :au
    AND vendus.prix > 0';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $nb_ventes = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nb_ventes'];
  $stmt->closeCursor();
  return $nb_ventes;
}

function nb_ventes_point_vente(PDO $bdd, $start, $stop, $id_point_vente) {
  $sql = 'SELECT
    COUNT(DISTINCT(ventes.id)) as nb_ventes
    FROM ventes
    INNER JOIN vendus
    ON ventes.id_point_vente = :id_point_vente
      AND vendus.id_vente = ventes.id
    AND DATE(vendus.timestamp)
    BETWEEN :du AND :au
    AND vendus.prix > 0';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->bindValue(':id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $stmt->execute();
  $nb_ventes = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nb_ventes'];
  $stmt->closeCursor();
  return $nb_ventes;
}

function nb_remboursements_point_vente(PDO $bdd, $start, $stop, $id_point_vente) {
  $sql = 'SELECT
    COUNT(DISTINCT(ventes.id)) as nb_remb
    FROM ventes
    INNER JOIN vendus
    ON ventes.id_point_vente = :id_point_vente
      AND vendus.id_vente = ventes.id
    AND DATE(vendus.timestamp)
    BETWEEN :du AND :au
    AND vendus.remboursement > 0';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->bindValue(':id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $stmt->execute();
  $result = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nb_remb'];
  $stmt->closeCursor();
  return $result;
}

function nb_remboursements(PDO $bdd, $start, $stop) {
  $sql = 'SELECT
    COUNT(DISTINCT(ventes.id)) as nb_remb
    FROM ventes, vendus
    WHERE vendus.id_vente = ventes.id
    AND DATE(vendus.timestamp)
    BETWEEN :du AND :au
    AND vendus.remboursement > 0';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $result = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nb_remb'];
  $stmt->closeCursor();
  return $result;
}

function viz_caisse(PDO $bdd, int $id_point_vente, int $offset): array {
  $reqVentes = $bdd->prepare('
    select
      ventes.id as id,
      ventes.timestamp as date_creation,
      moyens_paiement.nom as moyen,
      moyens_paiement.couleur as coul,
      ventes.commentaire as commentaire,
      ventes.last_hero_timestamp as lht,
      utilisateurs.mail as mail,
      SUM(vendus.prix * vendus.quantite) as credit,
      SUM(vendus.remboursement * vendus.quantite) as debit,
      SUM(vendus.quantite) as quantite
    from ventes
    inner join vendus
      on vendus.id_vente = ventes.id
    inner join moyens_paiement
      on ventes.id_moyen_paiement = moyens_paiement.id
    inner join utilisateurs
      on utilisateurs.id = ventes.id_createur
    and ventes.id_point_vente = :id_point_vente
    and date(ventes.timestamp) = date(current_timestamp())
    group by ventes.id
    order by ventes.timestamp desc
    limit 0, :offset');
  $reqVentes->bindValue('id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $reqVentes->bindValue('offset', $offset, PDO::PARAM_INT);
  $reqVentes->execute();
  return $reqVentes->fetchAll(PDO::FETCH_ASSOC);
}

function bilan_ventes_par_type(PDO $bdd, $start, $stop) {
  $sql = '
    SELECT
      type_dechets.id as id,
      type_dechets.couleur as couleur,
      type_dechets.nom as nom,
      SUM(vendus.prix * vendus.quantite) as chiffre_degage,
      SUM(vendus.quantite) as vendu_quantite,
      SUM(vendus.remboursement) as remb_somme,
      SUM(case when vendus.remboursement > 0 then 1 else 0 end) as remb_quantite
    FROM vendus
    INNER JOIN type_dechets
    ON vendus.id_type_dechet = type_dechets.id
    WHERE DATE(vendus.timestamp)
    BETWEEN :du AND :au
    GROUP BY type_dechets.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();

  $result = [];
  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $data['id'];
    unset($data['id']);
    $result[$id] = $data;
  }
  $stmt->closeCursor();
  return $result;
}

function bilan_ventes_par_type_point_vente(PDO $bdd, $start, $stop, $id_point_vente) {
  $sql = '
    SELECT
      type_dechets.id as id,
      type_dechets.couleur as couleur,
      type_dechets.nom as nom,
      SUM(vendus.prix * vendus.quantite) as chiffre_degage,
      SUM(vendus.quantite) as vendu_quantite,
      SUM(vendus.remboursement) as remb_somme,
      SUM(case when vendus.remboursement > 0 then 1 else 0 end) as remb_quantite
    FROM vendus
    INNER JOIN type_dechets
    ON vendus.id_type_dechet = type_dechets.id
    INNER JOIN ventes
    ON vendus.id_vente = ventes.id
      AND ventes.id_point_vente = :id_point_vente
    WHERE DATE(vendus.timestamp)
    BETWEEN :du AND :au
    GROUP BY type_dechets.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->bindValue(':id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $stmt->execute();

  $result = [];
  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $data['id'];
    unset($data['id']);
    $result[$id] = $data;
  }
  $stmt->closeCursor();
  return $result;
}

function bilan_ventes_pesees(PDO $bdd, $start, $stop) {
  $sql = 'SELECT
      type_dechets.id as id,
      type_dechets.nom as nom,
      type_dechets.couleur as couleur,
      COUNT(DISTINCT(pesees_vendus.id)) as nb_pesees_ventes,
      COALESCE(SUM(pesees_vendus.quantite), 0) as quantite_pesee_vendu,
      COALESCE(SUM(pesees_vendus.masse), 0) as vendu_masse,
      COALESCE(AVG(pesees_vendus.masse), 0) as moy_masse_vente
    FROM pesees_vendus
    INNER JOIN vendus
    ON vendus.id = pesees_vendus.id_vendu
    INNER JOIN type_dechets
    ON vendus.id_type_dechet = type_dechets.id
    WHERE DATE(pesees_vendus.timestamp)
    BETWEEN :du AND :au
    GROUP BY type_dechets.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $result = [];
  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $data['id'];
    unset($data['id']);
    $result[$id] = $data;
  }
  $stmt->closeCursor();
  return $result;
}

function bilan_ventes_pesees_point_vente(PDO $bdd, $start, $stop, $id_point_vente) {
  $sql = 'SELECT
      type_dechets.id as id,
      type_dechets.nom as nom,
      type_dechets.couleur as couleur,
      COUNT(DISTINCT(pesees_vendus.id)) as nb_pesees_ventes,
      COALESCE(SUM(pesees_vendus.quantite), 0) as quantite_pesee_vendu,
      COALESCE(SUM(pesees_vendus.masse), 0) as vendu_masse,
      COALESCE(AVG(pesees_vendus.masse), 0) as moy_masse_vente
    FROM pesees_vendus
    INNER JOIN vendus
    ON vendus.id = pesees_vendus.id_vendu
    INNER JOIN type_dechets
    ON vendus.id_type_dechet = type_dechets.id
    INNER JOIN ventes
    ON vendus.id_vente = ventes.id
      AND ventes.id_point_vente = :id_point_vente
    WHERE DATE(pesees_vendus.timestamp)
    BETWEEN :du AND :au
    GROUP BY type_dechets.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->bindValue(':id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $stmt->execute();
  $result = [];
  while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $data['id'];
    unset($data['id']);
    $result[$id] = $data;
  }
  $stmt->closeCursor();
  return $result;
}

function bilan_ventes(PDO $bdd, $start, $stop) {
  $sql = '
    SELECT
      (select count(*) from ventes) as nb_ventes,
      SUM(vendus.prix * vendus.quantite) as chiffre_degage,
      SUM(vendus.quantite) as vendu_quantite,
      SUM(vendus.remboursement) as remb_somme,
      SUM(case when vendus.remboursement > 0 then 1 else 0 end) as remb_quantite,
      COALESCE(SUM(pesees_vendus.masse), 0) as vendu_masse
    FROM vendus
    LEFT JOIN pesees_vendus
    ON vendus.id = pesees_vendus.id_vendu
    WHERE DATE(vendus.timestamp)
    BETWEEN :du AND :au';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->execute();
  $bilan = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $bilan;
}

function bilan_ventes_point_vente(PDO $bdd, $start, $stop, $id_point_vente) {
  $sql = '
    SELECT
      (select count(*) from ventes) as nb_ventes,
      SUM(vendus.prix * vendus.quantite) as chiffre_degage,
      SUM(vendus.quantite) as vendu_quantite,
      SUM(vendus.remboursement) as remb_somme,
      SUM(case when vendus.remboursement > 0 then 1 else 0 end) as remb_quantite,
      COALESCE(SUM(pesees_vendus.masse), 0) as vendu_masse
    FROM vendus
    LEFT JOIN pesees_vendus
    ON vendus.id = pesees_vendus.id_vendu
    WHERE DATE(vendus.timestamp)
    BETWEEN :du AND :au
    AND ventes.id_point_vente = :id_point_vente';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':du', $start, PDO::PARAM_STR);
  $stmt->bindValue(':au', $stop, PDO::PARAM_STR);
  $stmt->bindValue(':id_point_vente', $id_point_vente, PDO::PARAM_INT);
  $stmt->execute();
  $bilan = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $bilan;
}
