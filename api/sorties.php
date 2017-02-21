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

// TODO: Verifier que les objets co rrespondent bien possibilites du recycleur.

require_once('../core/session.php');
require_once('../core/validation.php');
require_once('../core/requetes.php');

global $bdd;

session_start();

header("content-type:application/json");
$json_raw = file_get_contents('php://input');
$unsafe_json = json_decode($json_raw, true);
$json = validate_json_sorties($unsafe_json);

// Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette fonction:
if (isset($_SESSION['id'])
  && $_SESSION['systeme'] = "oressource") {
  if (!is_allowed_sortie()) {
    http_response_code(403); // Forbiden.
    echo(json_encode(['error' => 'Action interdite.'], JSON_FORCE_OBJECT));
    die();
  }

  require_once('../moteur/dbconfig.php');

  $timestamp = (is_allowed_edit_date() ? parseDate($json['antidate']) : new DateTime('now'));
  $sortie = [
      'timestamp' => $timestamp,
      'type_sortie' => $json['id_type_action'],
      'localite' => $json['localite'],
      'classe' => $json['classe'],
      'id_point_sortie' => $json['id_point'],
      'commentaire' => $json['commentaire'],
      'id_user' => $json['id_user'],
  ];

  $bdd->beginTransaction();
  $id_sortie = insert_sortie($bdd, $sortie);
  try {
    // TODO: Refactorer ca avec une variable de type fonction.
    if ($sortie['classe'] === 'sortie') {
      $requete_OK = false;
      if (count($json['items'])) {
        insert_items_sorties($bdd, $id_sortie, $sortie, $json['items']);
        $requete_OK = true;
      }
      if (count($json['evacs'])) {
        insert_evac_sorties($bdd, $id_sortie, $sortie, $json['evacs']);
        $requete_OK = true;
      }
    } elseif ($sortie['classe'] === 'sortier') {
      if (count($json['evacs'])) {
        insert_evac_sorties($bdd, $id_sortie, $sortie, $json['evacs']);
        $requete_OK = true;
      }
    }

    if ($requete_OK) {
      $bdd->commit();
      http_response_code(200); // Created
      // Note: Renvoyer l'url d'acces a la ressource
      echo(json_encode(['id_sortie' => $id_sortie], JSON_NUMERIC_CHECK));
    } else {
      throw new UnexpectedValueException("insertion sans objet ni evac abbandon.");
    }
  } catch (UnexpectedValueException $e) {
    $bdd->rollback();
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->msg], JSON_FORCE_OBJECT));
  }
} else {
  http_response_code(401); // Unauthorized.
}
