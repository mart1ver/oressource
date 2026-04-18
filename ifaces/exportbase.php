<?php

/*
  Oressource
  Copyright (C) 2014-2018  Martin Vert and Oressource devellopers

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
require_once('../core/requetes.php');

/**
 * Fonction qui permet de telecharger un fichier via son nom,
 *
 * Documentation des types mimes sur MDN:
 * https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_type
 * @param string $pathToFile
 * @param string $type type MIME complet
 * @param string $attachementName Nom du fichier à télécharger par defaut vide.
 */

if (is_valid_session() && is_allowed_config()) {
  require_once('../moteur/dbconfig.php');

  # Get the ressourcerie name provided in database
  $struct = structure($bdd)['nom'];

  $exportPath = '/tmp/';
  $exportFileName = 'export_oressource_';
  $exportPathServer = tempnam($exportPath, $exportFileName);

  $worked = exec("mysqldump --opt --host=$host --user=$user --password=$pass $base > \"$exportPathServer\"");

  $struct = strtolower(str_replace(" ", "_", $struct));

  header("Content-Type: application/sql");
  header("Content-disposition: attachment; filename=".$exportFileName.$struct);
  echo file_get_contents($exportPathServer);
  unlink($exportPathServer);
} else {
  header('Location:../moteur/destroy.php');
}
