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

require_once '../core/session.php';

session_start();

if (is_valid_session() && is_allowed_gestion()) {
  require_once '../moteur/dbconfig.php';
  try {
    $req = $bdd->prepare('INSERT INTO grille_objets (nom,  prix, description, id_type_dechet, id_createur, id_last_hero) VALUES(?, ?, ?, ?, ?, ?)');
    $req->execute([$_POST['nom'], $_POST['prix'], $_POST['description'], $_POST['typo'], $_SESSION['id'], $_SESSION['id']]);
    $req->closeCursor();
    header('Location:../ifaces/grilles_prix.php?msg=Objet enregistré avec succes!' . '&typo=' . $_POST['typo']);
  } catch (PDOException $e) {
    if ($e->getCode() == '23000') {
      header('Location:../ifaces/grilles_prix.php?err=Un objet porte deja le meme nom!&nom=' . $_POST['nom'] . '&description=' . $_POST['description'] . '&typo=' . $_POST['typo'] . '&prix=' . $_POST['prix']);
      die();
    }
    throw $e;
  }
} else {
  header('Location:../moteur/destroy.php');
}
