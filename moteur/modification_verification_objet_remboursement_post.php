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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once '../moteur/dbconfig.php';

  $req = $bdd->prepare('UPDATE vendus SET  remboursement = :remboursement, quantite = :quantite, id_last_hero = :id_last_hero, last_hero_timestamp = NOW()
    WHERE id = :id');
  $req->execute(['remboursement' => $_POST['remboursement'], 'quantite' => $_POST['quantite'], 'id' => $_POST['id'], 'id_last_hero' => $_SESSION['id']]);
  $req->closeCursor();
  $req = $bdd->prepare('UPDATE ventes SET  id_last_hero = :id_last_hero, last_hero_timestamp = NOW()
    WHERE id = :id');
  $req->execute(['id' => $_POST['nvente'], 'id_last_hero' => $_SESSION['id']]);
  $req->closeCursor();
  header('Location:../ifaces/verif_vente.php?numero=' . $_POST['npoint'] . '&date1=' . $_POST['date1'] . '&date2=' . $_POST['date2']);
} else {
  header('Location:../moteur/destroy.php');
}
