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
require_once('dbconfig.php');

/*
 * On reponds en JSON a la requete POST qui nous est envoyer.
 * Si le login est valide on renvoie un code 200 et un json qui a terme sera
 * classe qui represente un utilisateur.
 *
 * Sinon on renvoie une 401 Unauthorized et un petit document JSON qui explique l'erreur.
 *
 * On attends un JSON du schema suivant:
 * login.json
 * {
 *   'username': FILTER_VALIDATE_EMAIL, // A terme on pourrais etre moins restrictif.
 *   'password': octets bruts va etre hasher aucune validation/sanitizitation.
 * }
 * Reponse:
 * HTTPS Status code: 200 - OK
 * { 'status': 'Accepted' }
 * Ou en cas d'echec.
 * HTTP Status code: 401 - Unauthorized
 * { 'error': 'Mauvais identifiant ou mot de passe' }
 */

header('content-type:application/json');
$json_raw = file_get_contents('php://input');
$unsafe_json = json_decode($json_raw, true);
$json = validate_json_login($unsafe_json);

try {
  $email = $json['username'];
  if ($json['password'] === NULL) {
    throw new Exception('Mot de passe ou nom de compte invalide.');
  }
  $pass = $json['password']; // NE PAS FILTRER on utile le hash pas la valeur directe.
  $user = login_user($bdd, $email, $pass);
  session_start();
  $structure = structure($bdd);
  set_session($user, $structure);
  http_response_code(200); // OK
  echo json_encode(['status' => 'OK'], JSON_FORCE_OBJECT);
  // A terme on revera un document json decrivant l'utilisateur connecter.
  // echo(json_encode($user, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT));
} catch (Exception $e) {
  http_response_code(401); // Unauthorized
  echo json_encode(['error' => 'Mauvais identifiant ou mot de passe !'], JSON_FORCE_OBJECT);
}
