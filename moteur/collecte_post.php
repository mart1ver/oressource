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

namespace collecte_post;

use DateTime;

global $bdd;

require_once('../core/session.php');
require_once('../core/validation.php');
require_once('../core/requetes.php');

session_start();

header("content-type:application/json");
$json_raw = file_get_contents('php://input');
$unsafe_json = json_decode($json_raw, true);

// TODO: Revenir sur la validation plus tard c'est pas parfait maintenant.
// Parsing et filtrage des entrees pour eviter les failles et injections.
$json = validate_json_collecte($unsafe_json);

if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource") {

  if (count($json['items']) < 0) {
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => 'masse <= 0.0 ou type item inconnu.'], JSON_FORCE_OBJECT));
    die();
  }

  if (is_allowed_collecte_id($json['id_point'])
    && is_allowed_saisie_collecte()) {

    // Si l'utilisateur peux editer la date on Essaye de l'extraire
    // Sinon on prends la date cote serveur.
    $timestamp = (is_allowed_edit_date() ? parseDate($json['antidate']) : new DateTime('now'));
    $collecte = [
        'timestamp' => $timestamp,
        'id_type_action' => $json['id_type_action'],
        'localite' => $json['localite'],
        'id_point' => $json['id_point'],
        'commentaire' => $json['commentaire'],
        'id_user' => $json['id_user'],
    ];

    require_once('dbconfig.php');
    $bdd->beginTransaction();
    $id_collecte = insert_collecte($bdd, $collecte);

    try {
      if (count($json['items'])) {
      insert_items_collecte($bdd, $id_collecte, $collecte, $json['items']);
      $bdd->commit();
      } else {
        throw new InvalidArgumentException("Collecte sans pesees abbandon!");
      }
      http_response_code(200); // Created
      // Note: Renvoyer l'url d'acces a la ressource
      echo(json_encode(['id_collecte' => $id_collecte], JSON_NUMERIC_CHECK));
    } catch (InvalidArgumentException $e) {
      $bdd->rollback();
      http_response_code(400); // Bad Request
      echo(json_encode(['error' => $e->msg]));
    }
  } else {
    http_response_code(403); // Forbidden.
    echo(json_encode(['error' => 'Action interdite pour cet utilisateur.']));
  }
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => 'Session Timed out or invalid.']));
}
