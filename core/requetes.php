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

function nb_categories_dechets_evac(PDO $bdd) {
  return (int) $bdd->query('SELECT MAX(id) AS nombrecat FROM type_dechets_evac LIMIT 1')['nombrecat'];
}

function nb_categories_dechets_item(PDO $bdd) {
  return (int) $bdd->query('SELECT MAX(id) AS nombrecat FROM type_dechets LIMIT 1')['nombrecat'];
}

function nb_categories_poubelles(PDO $bdd) {
  return (int) $bdd->query('SELECT MAX(id) AS nombrecat FROM types_poubelles LIMIT 1')['nombrecat'];
}

// Insere une collecte dans la base.
// On ecrit le nombre de categories maxi dans la varialble $nombreCategories
// HACK C'est pas parfait il faudrait comparer aux differents ID de la base.
// Si des utilisateurs suppriment des objet sa la main c'est la galere...
// Mais d'un cote niveau tracabilite ils devraient pas supprimer de categories...
// genere une exception si les masses sont inferieurs a 0.
function insert_items_collecte(PDO $bdd, $id_collecte, $collecte, $items) {
  $reponse = $bdd->query('SELECT MAX(id) AS nombrecat FROM type_dechets LIMIT 1;');
  $nombreCategories = (int) $reponse->fetch()['nombrecat'];
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
                            (timestamp, id_type_collecte,  adherent,
                            localisation, id_point_collecte,
                            commentaire, id_createur)
                          VALUES (:timestamp, :id_type_action, :adherent,
                                  :localite, :id_point,
                                  :commentaire, :id_createur)');
  $req->bindValue(':timestamp', $collecte['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_type_action', $collecte['id_type_action'], PDO::PARAM_INT);
  // HACK: virer les adherants de la base ou juste changer le type en booleen serieusement...
  $req->bindValue(':adherent', 'non', PDO::PARAM_STR);
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

function insert_sortie(PDO $bdd, $sortie) {
  $req = $bdd->prepare('INSERT INTO sorties (timestamp, id_type_sortie, adherent,
                                             classe, id_point_sortie, commentaire , id_createur)
                        VALUES(:timestamp, :type_sortie, :adherent,
                               :classe, :id_point_sortie, :commentaire,
                               :id_createur)');
  $req->bindvalue(':timestamp', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindvalue(':type_sortie', $sortie['type_sortie'], PDO::PARAM_INT);
  $req->bindvalue(':adherent', 'non', PDO::PARAM_STR);
  $req->bindvalue(':classe', $sortie['classe'], PDO::PARAM_STR);
  $req->bindvalue(':id_point_sortie', $sortie['id_point_sortie'], PDO::PARAM_INT);
  $req->bindvalue(':commentaire', $bdd->quote($sortie['commentaire']), PDO::PARAM_STR);
  $req->bindvalue(':id_createur', $sortie['id_user'], PDO::PARAM_INT);
  $req->execute();
  return (int) $bdd->lastInsertId();
}

function structure(PDO $bdd) {
  $req = $bdd->prepare('SELECT tva_active, taux_tva, nom,
                             siret, adresse, texte_adhesion, lot, viz,
                             nb_viz, saisiec, affsp, affss, affsr,
                             affsd, affsde, pes_vente, force_pes_vente
                             FROM description_structure LIMIT 1');
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