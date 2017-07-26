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
    include('../moteur/dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }


  $req = $bdd->prepare('SELECT SUM(id) FROM grille_objets WHERE nom = :nom ');
  $req->execute(['nom' => $_POST['nom']]);
  $donnees = $req->fetch();
  $req->closeCursor();

  if ($donnees['SUM(id)'] > 0) { // SI le titre existe
    header('Location:../ifaces/grilles_prix.php?err=Un objet porte deja le meme nom!&nom=' . $_POST['nom'] . '&description=' . $_POST['description'] . '&typo=' . $_POST['typo'] . '&prix=' . $_POST['prix']);
    $req->closeCursor();
  } else {
    $req->closeCursor();
    try {
      include('dbconfig.php');
    } catch (Exception $e) {
      die('Erreur : ' . $e->getMessage());
    }

    $req = $bdd->prepare('INSERT INTO grille_objets (nom,  prix, description, id_type_dechet, visible) VALUES(?, ?, ?, ?,? )');
    $req->execute([$_POST['nom'], $_POST['prix'], $_POST['description'], $_POST['typo'], 'oui']);
    $req->closeCursor();
    header('Location:../ifaces/grilles_prix.php?msg=Objet enregistré avec succes!' . '&typo=' . $_POST['typo']);
  }
} else {
  header('Location:../moteur/destroy.php');
}
