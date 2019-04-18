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

require_once('../moteur/dbconfig.php');
require_once('../core/session.php');
require_once('../core/requetes.php');

session_start();

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && is_allowed_bilan()) {
  require_once('../moteur/dbconfig.php');

  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
  $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
  $id_dechet = filter_input(INPUT_POST, 'id_dechet', FILTER_VALIDATE_INT);
  $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

  try {
    $req = $bdd->prepare('UPDATE grille_objets
    SET nom = :nom,
        description = :description,
        prix = :prix
    WHERE id = :id');
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':prix', $prix);
    $req->bindParam(':nom', $nom, PDO::PARAM_STR);
    $req->bindParam(':description', $description, PDO::PARAM_STR);
    $req->execute();
    $req->closeCursor();
    header('Location:../ifaces/grilles_prix.php');
  } catch (PDOException $e) {
    if ($e->getCode() == '23000') {
      header("Location:../ifaces/grilles_prix.php?err={$e->getMessage()}&typo={$id}");
    }
    throw $e;
  }
} else {
  header('Location:../moteur/destroy.php');
}
