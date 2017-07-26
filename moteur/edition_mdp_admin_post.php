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

require_once('../moteur/dbconfig.php');
require_once('../core/session.php');
require_once('../core/requetes.php');

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_users()) {
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

  if ($_POST['pass1'] === $_POST['pass2']) {
    require_once('dbconfig.php');

    $req = $bdd->prepare('UPDATE utilisateurs SET pass = :pass WHERE id = :id');
    $req->execute(array('pass' => md5($_POST['pass1']), 'id' => $id));
    $req->closeCursor();

    header("Location: ../ifaces/edition_utilisateurs.php?msg=Mot de passe modifié avec succes, utilisateur:{$_SESSION['nom']}, mail: {$_SESSION['mail']}");
  } else {
    header("Location: ../ifaces/edition_mdp_admin.php?err=Veuillez inscrire deux mots de passe semblables");
  }
} else {
  header('Location:../moteur/destroy.php');
}
