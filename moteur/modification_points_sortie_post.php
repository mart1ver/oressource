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
  $req = $bdd->prepare('SELECT SUM(id) FROM points_sortie WHERE nom = :nom AND id <> :id ');
  $req->execute(['nom' => $_POST['nom'], 'id' => $_POST['id']]);
  $donnees = $req->fetch();
  $req->closeCursor();

  if ($donnees['SUM(id)'] > 0) { // SI le titre existe
    header('Location:../ifaces/modification_points_sortie.php?err=Un point de sortie porte deja le meme nom!&nom=' . $_POST['nom'] . '&adresse=' . $_POST['adresse'] . '&pesee_max=' . $_POST['pesee_max'] . '&commentaire=' . $_POST['commentaire'] . '&couleur=' . substr($_POST['couleur'], 1));
  } else {
    $req = $bdd->prepare('UPDATE points_sortie SET nom = :nom, adresse = :adresse , commentaire = :commentaire, pesee_max = :pesee_max, couleur = :couleur  WHERE id = :id');
    $req->execute(['nom' => $_POST['nom'], 'adresse' => $_POST['adresse'], 'commentaire' => $_POST['commentaire'], 'pesee_max' => $_POST['pesee_max'], 'couleur' => $_POST['couleur'], 'id' => $_POST['id']]);
    $req->closeCursor();
    header('Location:../ifaces/edition_points_sorties.php');
  }
} else {
  header('Location:../moteur/destroy.php');
}
