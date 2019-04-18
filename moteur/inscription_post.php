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
    return $u['mail'] === $_POST['mail'];
  });
  $mail = $_POST['mail'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $pass1 = $_POST['pass1'];
  $pass2 = $_POST['pass2'];

  if (count($same_mail) > 0) {
    header('Location:../ifaces/utilisateurs.php?err=Cette addresse email est deja utilisée par un autre utilisateur!&nom=' . $nom . '&prenom=' . $prenom . '&mail=' . $mail);
    die();
  }

  if ($pass1 === NULL || $pass2 === NULL) {
    header('Location: ../ifaces/utilisateurs.php?err=Le Mot de passe ne dois pas être vacant.&nom=' . $nom . '&prenom=' . $prenom . '&mail=' . $mail);
    die();
  }

  if ($pass1 === $pass2) {
    $droits = new_droits($bdd, $_POST);
    $user = new_utilisateur($nom, $prenom, $mail, $droits, $pass1);
    utilisateur_insert($bdd, $user);
    header('Location: ../ifaces/utilisateurs.php?msg=Utilisateur ajouté avec succes!');
  } else {
    header('Location: ../ifaces/utilisateurs.php?err=Veuillez confirmer votre mot de passe&nom=' . $nom . '&prenom=' . $prenom . '&mail=' . $mail);
  }
} else {
  header('Location:../moteur/destroy.php');
}
