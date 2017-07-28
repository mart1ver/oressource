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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'j') !== false)) {
  require_once '../moteur/dbconfig.php';
  $req = $bdd->prepare('SELECT SUM(id) FROM filieres_sortie WHERE nom = :nom ');
  $req->execute(['nom' => $_POST['nom']]);
  $donnees = $req->fetch();
  $req->closeCursor();

  if ($donnees['SUM(id)'] > 0) { // SI le titre existe
    header('Location:../ifaces/edition_filieres_sortie.php?err=Une filiere porte deja le meme nom!&nom=' . $_POST['nom'] . '&description=' . $_POST['description'] . '&couleur=' . substr($_POST['couleur'], 1));
    $req->closeCursor();
  } else {
    $req->closeCursor();
    //on determine le nombre total de type dechets evac
    $id_dechets = '';
    $reponsesa = $bdd->query('SELECT id FROM type_dechets_evac');

    while ($donneessa = $reponsesa->fetch()) {
      if (isset($_POST['tde' . $donneessa['id']])) {
        $id_dechets = $id_dechets . 'a' . $donneessa['id'];
      }
    }
    $reponsesa->closeCursor();
    //on inssere la filiere de recyclage en base

    $req = $bdd->prepare('INSERT INTO filieres_sortie (nom,  couleur, description, id_type_dechet_evac, visible) VALUES(?, ?, ?,  ?, ?)');
    $req->execute([$_POST['nom'], $_POST['couleur'], $_POST['description'], $id_dechets, 'oui']);
    $req->closeCursor();
    header('Location:../ifaces/edition_filieres_sortie.php?msg=Filiere de sortie enregistrée avec succes!');
  }
} else {
  header('Location:../moteur/destroy.php');
}
