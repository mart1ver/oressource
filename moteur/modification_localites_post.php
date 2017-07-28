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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'k') !== false)) {
  require_once '../moteur/dbconfig.php';

  $req = $bdd->prepare('UPDATE localites SET nom = :nom, relation_openstreetmap = :relation_openstreetmap , commentaire = :commentaire, couleur = :couleur  WHERE id = :id');
  $req->execute(['nom' => $_POST['nom'], 'relation_openstreetmap' => $_POST['lien'], 'commentaire' => $_POST['commentaire'], 'couleur' => $_POST['couleur'], 'id' => $_POST['id']]);
  $req->closeCursor();

  header('Location:../ifaces/edition_localites.php');
} else {
  header('Location:../moteur/destroy.php');
}
