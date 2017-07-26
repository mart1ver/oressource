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
  try {
    include('../moteur/dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }


  $req = $bdd->prepare('SELECT SUM(id) FROM type_sortie WHERE nom = :nom ');
  $req->execute(['nom' => $_POST['nom']]);
  $donnees = $req->fetch();
  $req->closeCursor();

  if ($donnees['SUM(id)'] > 0) { // SI le titre existe
    header('Location:../ifaces/types_collecte.php?err=Un type de sortie porte deja le meme nom!&nom=' . $_POST['nom'] . '&description=' . $_POST['description'] . '&couleur=' . substr($_POST['couleur'], 1));
    $req->closeCursor();
  } else {
    $req->closeCursor();
    try {
      include('dbconfig.php');
    } catch (Exception $e) {
      die('Erreur : ' . $e->getMessage());
    }

    $req = $bdd->prepare('INSERT INTO type_sortie (nom,  couleur, description, visible) VALUES(?, ?,  ?, ?)');
    $req->execute([$_POST['nom'], $_POST['couleur'], $_POST['description'], 'oui']);
    $req->closeCursor();
    header('Location:../ifaces/edition_types_sortie.php?msg=Type de sortie enregistré avec succes!');
  }
} else {
  header('Location:../moteur/destroy.php');
}
