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

session_start();

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
function download(
  string $pathTofile,
  string $type,
  string $attachementName = ""
) {
  $size = filesize($pathTofile);
  $name = $attachementName === "" ? $pathTofile : $attachementName;
  // Write in buffer HTTP header
  ob_start();
  header('Content-Description: File Transfer');
  header("Content-Type: $type;  charset=utf-8");
  header("Content-disposition: attachment; filename=\"$name\"");
  header("Content-Transfer-Encoding: binary");
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private", false);
  header("Content-Length: $size");
  ob_end_flush();
  // Release and write buffer
  // Write the body of the request
  readfile($pathTofile);
}

if (is_valid_session() && is_allowed_config()) {
  require_once('../moteur/dbconfig.php');

  # Get the ressourcerie name provided in database
  $struct = structure($bdd)['nom'];

  # Go into the ./mysql folder
  $exportPath = '../mysql/';
  chdir($exportPath);

  # Name the sql dump file
  $exportFileName = 'sauvegarde_oressource';
  $fileExtention = '.sql';
  $exportPathServer = $exportFileName . $fileExtention;

  # Dump the database via mysqldump (provided by mysql-client)
  $worked = exec("mysqldump --opt --host=$host --user=$user --password=$pass $base > \"$exportPathServer\"");

  // Remove spaces from name and name the zip file
  $struct = str_replace(" ", "_", $struct);
  $fileZip = $exportFileName . '_' . $struct . '.zip';

  # Zip the sql file
  $worked |= exec("zip \"$fileZip\" \"$exportPathServer\"");

  // Delete sql file
  unlink($exportPathServer);

  switch ($worked) {
    case 0:
      $AttachName = $exportFileName . '_' . $struct . '_' . date("d-m-Y_H-i-s") . '.zip';
      download($fileZip, 'application/octet-stream', $AttachName);
      break;
    case 1:
      header("Location:structures.php?err=Probleme pendant l'export du fichier");
      break;
    case 2:
      header("Location:structures.php?err=Probleme pendant l'export de la base");
      break;
  }
} else {
  header('Location:../moteur/destroy.php');
}
