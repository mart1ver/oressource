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

global $bdd;

require_once '../core/session.php';
require_once '../core/validation.php';
require_once '../core/requetes.php';

function insert_collecte(PDO $bdd, array $collecte): int {
  $req = $bdd->prepare('INSERT INTO collectes
                            (timestamp, id_type_collecte,
                            localisation, id_point_collecte,
                            commentaire, id_createur, id_last_hero)
                          VALUES (:timestamp, :id_type_action,
                                  :localite, :id_point,
                                  :commentaire, :id_createur, :id_createur1)');
  $req->bindValue(':timestamp', $collecte['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_type_action', $collecte['id_type_action'], PDO::PARAM_INT);
  $req->bindValue(':localite', $collecte['localite'], PDO::PARAM_INT);
  $req->bindValue(':id_point', $collecte['id_point'], PDO::PARAM_INT);
  $req->bindValue(':commentaire', $collecte['commentaire'], PDO::PARAM_STR);
  $req->bindValue(':id_createur', $collecte['id_user'], PDO::PARAM_INT);
  $req->bindValue(':id_createur1', $collecte['id_user'], PDO::PARAM_INT);
  $req->execute();
  return (int) $bdd->lastInsertId();
}

// Insere une collecte dans la base.
// On ecrit le nombre de categories maxi dans la varialble $nombreCategories
// HACK C'est pas parfait il faudrait comparer aux differents ID de la base.
// Si des utilisateurs suppriment des objet sa la main c'est la galere...
// Mais d'un cote niveau tracabilite ils devraient pas supprimer de categories...
// genere une exception si les masses sont inferieurs a 0.
function insert_items_collecte(PDO $bdd, int $id_collecte, array $collecte, array $items) {
  $nombreCategories = nb_categories_dechets_item($bdd);
  $req = $bdd->prepare('INSERT INTO pesees_collectes
                            (timestamp, masse, id_collecte, id_type_dechet, id_createur, id_last_hero)
                        VALUES (:timestamp, :masse, :id_collecte, :id_type_dechet, :id_createur, :id_createur1)');
  $req->bindValue(':timestamp', $collecte['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_collecte', $id_collecte, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $collecte['id_user'], PDO::PARAM_INT);
  $req->bindValue(':id_createur1', $collecte['id_user'], PDO::PARAM_INT);
  foreach ($items as $item) {
    $masse = (float) parseFloat($item['masse']);
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

session_start();

header("content-type:application/json");

if (is_valid_session()) {

  $json_raw = file_get_contents('php://input');
  $unsafe_json = json_decode($json_raw, true);

  // TODO: Revenir sur la validation plus tard c'est pas parfait maintenant.
  // Parsing et filtrage des entrees pour eviter les failles et injections.
  try {
    $json = validate_json_collecte($unsafe_json);
    if (count($json['items']) <= 0) {
      throw new UnexpectedValueException('Collecte sans pesées.');
    }
  } catch (UnexpectedValueException $e) {
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->getMessage()]));
    die();
  }

  if (is_allowed_collecte_id($json['id_point'])) {
    try {
      $timestamp = allowDate($json) ? parseDate($json['date']) : new DateTime('now');
      $collecte = [
        'timestamp' => $timestamp,
        'id_type_action' => $json['id_type_action'],
        'localite' => $json['localite'],
        'id_point' => $json['id_point'],
        'commentaire' => $json['commentaire'],
        'id_user' => $json['id_user'],
      ];

      require_once('../moteur/dbconfig.php');
      $bdd->beginTransaction();
      $id_collecte = insert_collecte($bdd, $collecte);
      insert_items_collecte($bdd, $id_collecte, $collecte, $json['items']);
      $bdd->commit();

      http_response_code(200); // Created
      // Note: Renvoyer l'url d'acces a la ressource
      echo(json_encode(['id' => $id_collecte], JSON_NUMERIC_CHECK));
    } catch (InvalidArgumentException $e) {
      $bdd->rollBack();
      http_response_code(400); // Bad Request
      echo(json_encode(['error' => $e->getMessage()]));
    } catch (UnexpectedValueException $e) {
      http_response_code(400); // Bad Request
      echo(json_encode(['error' => $e->getMessage()]));
    } catch (PDOException $e) {
      $bdd->rollBack();
      http_response_code(500); // Internal Server Error
      echo(json_encode(['error' => 'Une erreur est survenue dans Oressource vente annulée.']));
      throw $e;
    }
  } else {
    http_response_code(403); // Forbidden.
    echo(json_encode(['error' => 'Action interdite pour cet utilisateur.']));
  }
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => 'Session Timed out or invalid.']));
}
