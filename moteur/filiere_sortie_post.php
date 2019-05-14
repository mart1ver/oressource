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

if (is_valid_session() && is_allowed_partners()) {
  require_once '../moteur/dbconfig.php';
  try {
    $id_dechets = '';
    $types_dechets = $bdd->query('SELECT id FROM type_dechets_evac');
    foreach ($types_dechets as $type) {
      if (isset($_POST['tde' . $type['id']])) {
        $id_dechets = $id_dechets . 'a' . $type['id'];
      }
    }
    $types_dechets->closeCursor();
    if (count($id_dechets) == 0) {
      header('Location:../ifaces/edition_filieres_sortie.php?err=Veuillez renseigner les types de dechets gérer par la structure!&nom=' . $_POST['nom'] . '&description=' . $_POST['description'] . '&couleur=' . substr($_POST['couleur'], 1));
      die;
    }

    $req = $bdd->prepare('INSERT INTO filieres_sortie (
      nom,  couleur, description,
      id_type_dechet_evac, id_createur, id_last_hero
    ) VALUES (
      ?, ?, ?, ?, ?, ?
    )');

    $req->execute([$_POST['nom'], $_POST['couleur'], $_POST['description'], $id_dechets, $_SESSION['id'], $_SESSION['id']]);
    $req->closeCursor();
    header('Location:../ifaces/edition_filieres_sortie.php?msg=Filiere de sortie enregistrée avec succes!');
  } catch (PDOException $e) {
    if ($e->getCode() == '23000') {
      header('Location:../ifaces/edition_filieres_sortie.php?err=Une filiere porte deja le meme nom!&nom=' . $_POST['nom'] . '&description=' . $_POST['description'] . '&couleur=' . substr($_POST['couleur'], 1));
      die;
    }
    throw $e;
  }
} else {
  header('Location:../moteur/destroy.php');
}
