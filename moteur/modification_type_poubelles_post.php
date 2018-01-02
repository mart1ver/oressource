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
  require_once '../moteur/dbconfig.php';
  $ultime = isset($_POST['ultime']) ? 1 : 0;
  $req = $bdd->prepare('UPDATE types_poubelles SET nom = :nom,  description = :description,  masse_bac = :masse_bac,  ultime = :ultime , couleur = :couleur  WHERE id = :id');
  $req->execute(['nom' => $_POST['nom'], 'description' => $_POST['description'], 'masse_bac' => $_POST['masse_bac'], 'ultime' => $ultime, 'couleur' => $_POST['couleur'], 'id' => $_POST['id']]);
  $req->closeCursor();
  header('Location:../ifaces/edition_types_poubelles.php');
} else {
  header('Location:../moteur/destroy.php');
}
