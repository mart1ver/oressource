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

if (is_valid_session()) {
  if (!is_allowed_config()) {
    http_response_code(403); // Forbiden.
    echo(json_encode(['error' => 'Action interdite.'], JSON_FORCE_OBJECT));
    die();
  }

  $json_raw = file_get_contents('php://input');
  $unsafe_json = json_decode($json_raw, true);

  require_once('../moteur/dbconfig.php');
  try {
    $structure = structure_validate($unsafe_json);
    structure_update($bdd, array_merge($structure, [
      'id' => 1,
    ]));
  } catch (PDOException $e) {
    http_response_code(500); // Internal Server Error.
    echo(json_encode(['error' => "Une erreur est survenue dans Oressource Oups."], JSON_FORCE_OBJECT));
    throw $e;
  }

  http_response_code(200); // Sucess.
  echo(json_encode(['success' => 'Configuration saved']));
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => "Session Invalide ou expiree."], JSON_FORCE_OBJECT));
}
