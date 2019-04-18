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

require_once '../core/session.php';

if (is_valid_session() && is_allowed_verifications()) {
  require_once '../moteur/dbconfig.php';
  $classe = $_POST['classe'];

  $req = null;
  $baseSql = 'id_last_hero = :id_last_hero, commentaire = :commentaire';
  if ($classe === 'sortiesd') {
    $req = $bdd->prepare("UPDATE sorties SET
     $baseSql
    WHERE id = :id");
  } elseif ($classe === 'sortiesr') {
    $req = $bdd->prepare("UPDATE sorties SET
        id_filiere = :id_filiere,
        $baseSql
    WHERE id = :id");
    $req->bindParam(':id_filiere', $_POST['id_meta'], PDO::PARAM_INT);
  } elseif ($classe === 'sortiesp') {
    $req = $bdd->prepare("UPDATE sorties SET $baseSql
    WHERE id = :id");
  } elseif ($classe === 'sorties') {
    $req = $bdd->prepare("UPDATE sorties SET
      id_type_sortie = :id_type_sortie, $baseSql
    WHERE id = :id");
    $req->bindParam(':id_type_sortie', $_POST['id_meta'], PDO::PARAM_INT);
  } elseif ($classe === 'sortiesc') {
    $req = $bdd->prepare("UPDATE sorties SET
      id_convention = :id_convention,
      $baseSql
    WHERE id = :id");
    $req->bindParam(':id_convention', $_POST['id_meta'], PDO::PARAM_INT);
  }

  $_POST['commentaire'] = $_POST['commentaire'] ?? '';
  $req->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
  $req->bindParam(':commentaire', $_POST['commentaire'] , PDO::PARAM_STR);
  $req->bindParam(':id_last_hero', $_SESSION['id'], PDO::PARAM_INT);
  $req->execute();
  $req->closeCursor();
  header('Location:../ifaces/modification_verif_sorties.php?id=' . (int) $_POST['id']);
} else {
  header('Location:../moteur/destroy.php');
}
