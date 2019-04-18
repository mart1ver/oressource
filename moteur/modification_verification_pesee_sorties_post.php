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
  
  $sql = 'UPDATE 
    pesees_sorties SET
    id_type_dechet_evac = :evac,
    id_type_dechet = :dechet,
    id_type_poubelle = :poubelle,
    masse = :masse, 
    id_last_hero = :id_last_hero
    WHERE id = :id';
  $req = $bdd->prepare($sql);
  $req->bindValue(':id', $_POST['id'] ?? 0, PDO::PARAM_INT);
  $req->bindValue(':evac', $_POST['evac'] ?? 0, PDO::PARAM_INT);
  $req->bindValue(':dechet', $_POST['dechet'] ?? 0, PDO::PARAM_INT);
  $req->bindValue(':poubelle', $_POST['poubelle'] ?? 0, PDO::PARAM_INT);
  $req->bindParam(':masse', $_POST['masse'], PDO::PARAM_STR);
  $req->bindParam(':id_last_hero', $_SESSION['id'], PDO::PARAM_STR);
  $req->execute();
  $req->closeCursor();
  header('Location:../ifaces/modification_verif_sorties.php?id=' . (int) $_POST['id_sortie']);
} else {
  header('Location:../moteur/destroy.php');
}
