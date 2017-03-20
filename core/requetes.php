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

function grilles_objets_id($bdd, $id_dechet) {
  $req = $bdd->prepare("SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet");
  $req->bindValue(':id_type_dechet', $id_dechet, PDO::PARAM_INT);
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);
}

function convention_sortie(PDO $bdd) {
  $sql = 'SELECT id, nom FROM conventions_sorties WHERE visible = "oui"';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function convention_sortie_by_id(PDO $bdd, $id) {
  $sql = 'SELECT id, nom FROM conventions_sorties
          WHERE visible = "oui" AND id = :id LIMIT 1';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * $id est un entier.
 */
function point_collecte_id(PDO $bdd, $id) {
  $sql = 'SELECT pesee_max, nom
          FROM points_collecte
          WHERE id = :id
          LIMIT 1';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function point_sorties_id(PDO $bdd, $id) {
  $sql = 'SELECT pesee_max, nom FROM points_sortie WHERE id = :id LIMIT 1';
  $stmt = $bdd->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function points_collectes(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom, adresse FROM points_collecte WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function points_sorties(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom, adresse FROM points_sortie WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function points_ventes(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom, adresse FROM points_vente WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_contenants(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT masse, nom FROM type_contenants WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function localites(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom FROM localites WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_poubelles(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom, masse_bac, couleur FROM types_poubelles WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_conteneurs(PDO $bdd) {
  $sql = 'SELECT masse, nom FROM type_contenants WHERE visible = "oui"';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_dechets_evac(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom, couleur FROM type_dechets_evac WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_dechets(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom, couleur FROM type_dechets WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_sorties(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT id, nom FROM type_sortie WHERE visible = "oui"');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function types_collectes(PDO $bdd) {
  $sql = 'SELECT id, nom FROM type_collecte WHERE visible = "oui"';
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function filieres_sorties(PDO $bdd) {
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

function nb_categories_dechets_evac(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM type_dechets_evac LIMIT 1');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return $nombre_categories;
}

function nb_categories_dechets_item(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM type_dechets');
  $stmt->execute();
  $nombre_categories = (int) $stmt->fetch(PDO::FETCH_ASSOC)['nombrecat'];
  $stmt->closeCursor();
  return (int) $nombre_categories;
}

function nb_categories_poubelles(PDO $bdd) {
  $stmt = $bdd->prepare('SELECT MAX(id) AS nombrecat FROM types_poubelles LIMIT 1')['nombrecat'];
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
function insert_items_collecte(PDO $bdd, $id_collecte, $collecte, $items) {
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

// En attendant de faire des objets on fait un tableau associatif.
function insert_collecte(PDO $bdd, $collecte) {
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

function insert_items_sorties(PDO $bdd, $id_sorties, $sortie, $items) {
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

function insert_evac_sorties(PDO $bdd, $id_sorties, $sortie, $items) {
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

function insert_poubelle_sorties(PDO $bdd, $id_sorties, $sortie, $items) {
  $nombreCategories = nb_categories_dechets_poubelles($bdd);
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
  if  ($classe === 'sorties') {
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

function insert_sortie(PDO $bdd, $sortie) {
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

function structure(PDO $bdd) {
  $sql = 'SELECT tva_active, taux_tva, nom,
                siret, adresse, texte_adhesion, lot, viz,
                nb_viz, saisiec, affsp, affss, affsr,
                affsd, affsde, pes_vente, force_pes_vente
          FROM description_structure LIMIT 1';
  $req = $bdd->prepare($sql);
  $req->execute();
  return $req->fetch(PDO::FETCH_ASSOC);
}

// Return a valid user or an exception.
function login_user(PDO $bdd, $email, $password) {
  $passmd5 = md5($password);
  $req = $bdd->prepare('SELECT id, niveau, nom, prenom, mail
                        FROM utilisateurs
                        WHERE mail = :mail AND pass = :pass LIMIT 1');
  $req->bindValue(':mail', $email, PDO::PARAM_STR);
  $req->bindValue(':pass', $passmd5, PDO::PARAM_INT);
  $req->execute();
  $user = $req->fetch(PDO::FETCH_ASSOC);
  if ($user) {
    return $user;
  } else {
    throw new Exception('Mot de passe ou nom de compte invalide.');
  }
}

/**
 * Renvoie les donnees neccessaire a moris.js pour le tableau de bord.
 * @global type $bdd  Connection PDO valide.
 * @param string $sql Requete sql valide sql
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
