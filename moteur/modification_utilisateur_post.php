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
require_once '../core/requetes.php';

if (is_valid_session() && is_allowed_users()) {
  require_once '../moteur/dbconfig.php';

  $same_mail = array_filter(utilisateurs($bdd), function ($u) {
    return $u['mail'] === $_POST['mail'] && $_POST['nom'] !== $u['nom'] && $_POST['prenom'] !== $u['prenom'];
  });

  if (count($same_mail) > 0) {
    header('Location:../ifaces/utilisateurs.php?err=Cette addresse email est deja utilisée par un autre utilisateur');
    die();
  }
  utilisateur_update($bdd, new_utilisateur($_POST['nom'], $_POST['prenom'], $_POST['mail'], new_droits($bdd, $_POST), $_POST['id']));
  header('Location: ../ifaces/edition_utilisateurs.php?msg=Utilisateur modifié avec succes!');
} else {
  header('Location:../moteur/destroy.php');
}
