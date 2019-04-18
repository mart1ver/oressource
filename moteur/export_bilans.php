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

session_start();

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'bi') !== false)) {
  require_once '../moteur/dbconfig.php';
  //Premiere ligne = nom des champs (
  $xls_output = "Numéro d'indicateur" . "\t" . 'date' . "\t" . 'nom' . "\t" . 'description';
  $xls_output .= "\n\r";

  $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = 1');
  while ($donnees = $reponse->fetch()) {
    $xls_output .= $donnees['id'] . "\t" . $donnees['timestamp'] . "\t" . $donnees['nom'] . "\t" . $donnees['description'] . "\n";
  }
  $reponse->closeCursor();

  header('Content-type: application/vnd.ms-excel');
  header('Content-disposition: attachment; filename=type-dechets_' . date('Ymd') . '.xls');
  echo $xls_output;
  exit;
}
header('Location:../moteur/destroy.php');
