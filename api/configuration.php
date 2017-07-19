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


require_once('../core/session.php');
require_once('../core/validation.php');
require_once('../core/requetes.php');

global $bdd;

session_start();

header("content-type:application/json");

if (is_valid_session() && is_allowed_config()) {
  $json_raw = file_get_contents('php://input');
  $unsafe_json = json_decode($json_raw, true);

  require_once('../moteur/dbconfig.php');

  $structure = structure_validate($unsafe_json);
  structure_update($bdd, array_merge($structure, [
    'id' => 1,
    'siret' => $json['siret'],
    'mail' => $json['mail'],
    'adresse' => $json['adresse'],
    'telephone' => $json['telephone'],
    'id_localite' => $json['id_localite'],
    'taux_tva' => $json['taux_tva'],
    'nb_viz' => $json['nb_viz'],
  ]));

  header('Location:../ifaces/edition_description.php?msg=Configuration sauvegardée.');
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => "Session Invalide ou expiree."], JSON_FORCE_OBJECT));
}

