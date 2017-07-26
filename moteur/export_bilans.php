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

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) and $_SESSION['systeme'] = "oressource" and (strpos($_SESSION['niveau'], 'bi') !== false)) {

  //Premiere ligne = nom des champs (
  $xls_output = "Numéro d'indicateur"."\t"."date"."\t"."nom"."\t"."description";
  $xls_output .= "\n\r";

  //on affiche un onglet par type d'objet
  try {
    // On se connecte à MySQL
    include('../moteur/dbconfig.php');
  } catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : '.$e->getMessage());
  }

  // Si tout va bien, on peut continuer

  // On recupère tout le contenu des visibles de la table type_dechets
  $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui" ');

  // On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
    $xls_output .= $donnees['id']."\t".$donnees['timestamp']."\t".$donnees['nom']."\t".$donnees['description']."\n";
  }
  $reponse->closeCursor(); // Termine le traitement de la requête

  header("Content-type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=type-dechets_" . date("Ymd").".xls");
  print $xls_output;
  exit;
} else {
  header('Location:../moteur/destroy.php');
}
