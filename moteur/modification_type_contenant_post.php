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

require '../core/session.php';

if (is_valid_session() && is_allowed_gestion()) {
  require_once 'dbconfig.php';

  $req = $bdd->prepare('UPDATE type_contenants SET nom = :nom, description = :description, masse = :masse, couleur = :couleur WHERE id = :id');
  $req->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
  $req->bindParam(':masse', (int)$_POST['masse'], PDO::PARAM_INT);
  $req->bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
  $req->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
  $req->bindParam(':couleur', $_POST['couleur'], PDO::PARAM_STR);
  $req->execute();
  $req->closeCursor();
  header('Location:../ifaces/edition_types_contenants.php');
} else {
  header('Location:../moteur/destroy.php');
}
