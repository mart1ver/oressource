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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && (strpos($_SESSION['niveau'], 'j') !== false)) {
  //martin vert
  // Connexion à la base de données
  include('dbconfig.php');
  // Insertion du post à l'aide d'une requête préparée
  $req = $bdd->prepare('UPDATE filieres_sortie SET visible = :visible WHERE id = :id');
  $req->execute(array('visible' => $_POST['visible'], 'id' => $_POST['id']));
  // Redirection du visiteur vers la page de gestion des affectation
  header('Location:../ifaces/edition_filieres_sortie.php');
} else {
  header('Location:../moteur/destroy.php');
}
