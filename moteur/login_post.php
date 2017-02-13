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

require_once('../core/requetes.php');
require_once('../core/session.php');
require_once('../core/validation.php');
include_once('dbconfig.php');

header("content-type:application/json");
$json_raw = file_get_contents('php://input');
$unsafe_json = json_decode($json_raw, true);
$json = validate_json_login($unsafe_json);

try {
  $email = $json['username'];
  $pass = $json['password']; // NE PAS FILTRER on utile le hash pas la valeur directe.
  $user = login_user($bdd, $email, $pass);
  session_start();
  $structure = structure($bdd);
  set_session($user, $structure);
  http_response_code(200); // OK
  echo(json_encode(['Accepted' => 'TODO: Envoyer utilisateur.'], JSON_FORCE_OBJECT));
  // echo(json_encode($user, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT));
} catch (Exception $e) {
  http_response_code(401); // Unauthorized
  var_dump($e);
  echo(json_encode(['error' => 'Mauvais identifiant ou mot de passe !'], JSON_FORCE_OBJECT));
}
