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
  require_once '../moteur/dbconfig.php';
  $ultime = isset($_POST['ultime']) ? 1 : 0;

  $req = $bdd->prepare('SELECT SUM(id) FROM types_poubelles WHERE nom = :nom ');
  $req->execute(['nom' => $_POST['nom']]);
  $donnees = $req->fetch();
  $req->closeCursor();

  if ($donnees['SUM(id)'] > 0) {
    header('Location:../ifaces/edition_types_poubelles.php?err=Un type de bac porte deja le meme nom!&nom=' . $_POST['nom'] . '&description=' . $_POST['description'] . '&masse_bac=' . $_POST['masse_bac'] . '&ultime=' . $ultime . '&couleur=' . substr($_POST['couleur'], 1));
  } else {
    $req = $bdd->prepare('INSERT INTO types_poubelles (nom, couleur, description, masse_bac, ultime, id_createur, id_last_hero) VALUES (?, ?, ?,  ?, ?, ?, ?)');
    $req->execute([$_POST['nom'], $_POST['couleur'], $_POST['description'], $_POST['masse_bac'], $ultime, $_SESSION['id'], $_SESSION['id']]);
    $req->closeCursor();
    header('Location:../ifaces/edition_types_poubelles.php?msg=Type de bac enregistré avec succes!');
  }
} else {
  header('Location:../moteur/destroy.php');
}
