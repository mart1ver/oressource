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
 * , 'localite': int // TODO: inutilise a ce jour: Correspond a la localite du donateur en cas de sortie don.
 * , 'id_point_sortie': int // point de sortie
 * , 'id_user: int // Utilisateur valide de Oressource.
 * }
 * SI un des champs est invalide le serveur reponds 400 Bad request avec un objet json
 * detaillant l'erreur.
 */

session_start();
header("content-type:application/json");

if (is_valid_session() && is_allowed_sortie()) {

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

    require_once('../moteur/dbconfig.php');

    $bdd->beginTransaction();
    $id_sortie = insert_sortie($bdd, $sortie);
    $requete_OK = false;

    if ($sortie['classe'] === 'sorties'
            || $sortie['classe'] === 'sortiesc') {
      if (count($json['items']) > 0) {
        insert_items_sorties($bdd, $id_sortie, $sortie, $json['items']);
        $requete_OK = true;
      }
      if (count($json['evacs']) > 0) {
        insert_evac_sorties($bdd, $id_sortie, $sortie, $json['evacs']);
        $requete_OK = true;
      }
    } elseif ($sortie['classe'] === 'sortiesd'
              || $sortie['classe'] === 'sortiesr') {
      if (count($json['evacs']) > 0) {
        insert_evac_sorties($bdd, $id_sortie, $sortie, $json['evacs']);
        $requete_OK = true;
      }
    } elseif ($sortie['classe'] === 'sortiesp') {
      if (count($json['evacs']) > 0) {
        $sortie['commentaire'] = '';
        insert_poubelle_sorties($bdd, $id_sortie, $sortie, $json['evacs']);
        $requete_OK = true;
      }
    } else {
      throw new UnexpectedValueException("Classe de sortie inconnue");
    }

    if ($requete_OK) {
      $bdd->commit();
      http_response_code(200); // Created
      // Note: Renvoyer l'url d'acces a la ressource
      echo(json_encode(['id_sortie' => $id_sortie], JSON_NUMERIC_CHECK));
    } else {
      throw new UnexpectedValueException("Insertion sans objet ni evac abbandon.");
    }
  } catch (UnexpectedValueException $e) {
    $bdd->rollback();
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->getMessage()], JSON_FORCE_OBJECT));
  }
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => "Session Invalide ou expiree."], JSON_FORCE_OBJECT));
}
