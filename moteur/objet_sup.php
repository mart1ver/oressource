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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'g') !== false)) {
  try {
    include('dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }
  $req = $bdd->prepare('DELETE FROM grille_objets WHERE id = :id');
  $req->execute(['id' => $_POST['id']]);

  $req->closeCursor();
  header('Location:../ifaces/grilles_prix.php?msg=Objet definitivement supprimé des grilles de prix.' . '&typo=' . $_POST['typo']);
} else {
  header('Location:../moteur/destroy.php');
}
