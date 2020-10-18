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

// TODO: Verifier que les objets correspondent bien possibilites du recycleur.

global $bdd;

require_once('../core/session.php');
require_once('../core/validation.php');
require_once('../core/requetes.php');

/*
 * Gestion des sorties de Oressource:
 *
 * Verification de la session:
 * Si identifiant incorrecte -> 401 Unauthorized
 * Si identifiant correct mais droits insuffisants -> 403 Forbiden
 *
 * Un objet json de sortie est compose de la sorte:
 * { 'antidate': NULL | Date
 * , 'classe':   'sortiec' | 'sortie' | 'sortier
 * , 'type_sortie': int // Correspond soit a l'id de convention soit type de sortie soit id filiere.
 * , 'id_point_sortie': int // point de sortie
 * , 'id_user: int // Utilisateur valide de Oressource.
 * }
 * SI un des champs est invalide le serveur reponds 400 Bad request avec un objet json
 * detaillant l'erreur.
 */
function insert_pesee_sortie(PDO $bdd, int $id_sortie, array $sortie, array $items, string $id_type_field) {
  $sql = "INSERT INTO pesees_sorties (
    timestamp,
    last_hero_timestamp,
    masse,
    $id_type_field,
    id_sortie,
    id_createur,
    id_last_hero
   ) VALUES(
    :timestamp,
    :timestamp1,
    :masse,
    :$id_type_field,
    :id_sortie,
    :id_createur,
    :id_createur1)";
  $req = $bdd->prepare($sql);

  $req->bindValue(':timestamp', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':timestamp1', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_sortie', $id_sortie, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $sortie['id_user'], PDO::PARAM_INT);
  $req->bindValue(':id_createur1', $sortie['id_user'], PDO::PARAM_INT);
  foreach ($items as $item) {
    $masse = (float) parseFloat($item['masse']);
    $type_dechet = (int) parseInt($item['type']);
    if ($masse > 0.00 && $type_dechet > 0) {
      $req->bindValue(':masse', $masse);
      $req->bindValue(":$id_type_field", $type_dechet, PDO::PARAM_INT);
      $req->execute();
    } else {
      $req->closeCursor();
      throw new UnexpectedValueException('masse <= 0.0 ou type item inconnu');
    }
  }
}

function specialise_sortie(PDOStatement $stmt, array $sortie): PDOStatement {
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
    throw new UnexpectedValueException('class de sortie inconnue');
  }
  return $stmt;
}

function insert_sortie(PDO $bdd, array $sortie): int {
  $sql = 'INSERT INTO sorties (
      timestamp,
      last_hero_timestamp,
      id_filiere,
      id_convention,
      id_type_sortie,
      classe,
      id_point_sortie,
      commentaire,
      id_createur,
      id_last_hero
    ) VALUES (
      :timestamp,
      :timestamp1,
      :id_filiere,
      :id_convention,
      :type_sortie,
      :classe,
      :id_point_sortie,
      :commentaire,
      :id_createur,
      :id_createur1)';
  $req = $bdd->prepare($sql);
  $req->bindvalue(':timestamp', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindvalue(':timestamp1', $sortie['timestamp']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindvalue(':classe', $sortie['classe'], PDO::PARAM_STR);
  $req->bindvalue(':id_point_sortie', $sortie['id_point_sortie'], PDO::PARAM_INT);
  $req->bindvalue(':commentaire', $sortie['commentaire'], PDO::PARAM_STR);
  $req->bindvalue(':id_createur', $sortie['id_user'], PDO::PARAM_INT);
  $req->bindvalue(':id_createur1', $sortie['id_user'], PDO::PARAM_INT);

  $req = specialise_sortie($req, $sortie);
  $req->execute();
  return (int) $bdd->lastInsertId();
}

session_start();
header("content-type:application/json");

if (is_valid_session()) {
  try {
    $json_raw = file_get_contents('php://input');
    $unsafe_json = json_decode($json_raw, true);

    try {
      $json = validate_json_sorties($unsafe_json);
    } catch (UnexpectedValueException $e) {
      http_response_code(400); // Bad Request
      echo(json_encode(['error' => $e->getMessage()]));
      die();
    }

    if (!is_allowed_sortie_id($json['id_point'])) {
      http_response_code(403); // Forbiden.
      echo(json_encode(['error' => 'Action interdite.'], JSON_FORCE_OBJECT));
      die();
    }
    $timestamp = allowDate($json) ? parseDate($json['date']) : new DateTime('now');
    $sortie = [
      'timestamp' => $timestamp,
      'type_sortie' => $json['id_type_action'],
      'localite' => $json['localite'],
      'classe' => $json['classe'],
      'id_point_sortie' => $json['id_point'],
      'commentaire' => $json['commentaire'],
      'id_user' => $json['id_user'],
    ];

    require_once('../moteur/dbconfig.php');

    $bdd->beginTransaction();
    $id_sortie = (int) insert_sortie($bdd, $sortie);
    $requete_OK = false;
    if (count($json['items'] ?? []) > 0) {
      if ($sortie['classe'] === 'sorties' || $sortie['classe'] === 'sortiesc') {
        insert_pesee_sortie($bdd, $id_sortie, $sortie, $json['items'], 'id_type_dechet');
        $requete_OK = true;
      }
    }

    if (count($json['evacs'] ?? []) > 0) {
      if ($sortie['classe'] === 'sortiesd'
        || $sortie['classe'] === 'sortiesc'
        || $sortie['classe'] === 'sortiesr'
        || $sortie['classe'] === 'sorties') {
        insert_pesee_sortie($bdd, $id_sortie, $sortie, $json['evacs'], 'id_type_dechet_evac');
        $requete_OK = true;
      } elseif ($sortie['classe'] === 'sortiesp') {
        $sortie['commentaire'] = '';
        insert_pesee_sortie($bdd, $id_sortie, $sortie, $json['evacs'], 'id_type_poubelle');
        $requete_OK = true;
      }
    }

    if ($requete_OK) {
      $bdd->commit();
      http_response_code(200); // OK
      echo(json_encode(['id' => $id_sortie], JSON_NUMERIC_CHECK));
    } else {
      throw new UnexpectedValueException("Sortie invalide.");
    }
  } catch (UnexpectedValueException $e) {
    $bdd->rollBack();
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->getMessage()], JSON_FORCE_OBJECT));
  } catch (PDOException $e) {
    $bdd->rollBack();
    http_response_code(500); // Internal Server Error
    echo(json_encode(['error' => 'Une erreur est survenue dans Oressource sortie annulée.']));
    throw $e;
  }
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => "Session Invalide ou expiree."], JSON_FORCE_OBJECT));
}
