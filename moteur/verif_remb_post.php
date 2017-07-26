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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'v' . $_GET['numero']) !== false)) {
  try {
    include('../moteur/dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }
  // On recupère tout le contenu de la table point de collecte
  $reponse = $bdd->query('SELECT cr FROM `description_structure`');
  while ($donnees = $reponse->fetch()) {
    $code = $donnees['cr'];
  }
  $reponse->closeCursor();
  if ($_POST['passrmb'] === $code) {
    header('Location:../ifaces/remboursement.php?numero=' . $_GET['numero']);
  } else {
    header('Location:../ifaces/ventes.php?numero=' . $_GET['numero'] . '&err=mauvais mot de passe');
  }
} else {
  header('Location:../moteur/destroy.php');
}
