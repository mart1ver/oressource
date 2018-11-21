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

require_once '../core/requetes.php';

function change_pass(PDO $bdd, int $id, $oldpass='', $pass1='', $pass2=''): string {
  $user = utilisateurs_id($bdd, $id);
  // On récupére et hash l'ancien mot de passe donné par l'utilisateur et celui en base.
  $bddpass = $user['pass'];
  $oldpass = md5($_POST['passold']);
  $name = $user['nom'];

  if ($bddpass !== $oldpass) {
    return 'err=Vous avez fait une erreur dans votre mot de passe actuel';
  }

  // EsterEgg de https://en.wikipedia.org/wiki/Serial_Experiments_Lain
  // Un utilisateur ne peut avoir un mot de passe pass null.
  if ($_POST['pass1'] === '' || $_POST['pass2'] === '') {
    return "err=Vous avez tentée surement par mégarde de vous attribuer un mot de passe vacant. Moi Lain l'intelligence artificielle de Oressource ne peut effectuer cette opération popentiellement dangeureuse.";
  }

  if ($_POST['pass1'] !== $_POST['pass2']) {
    return "err=Le mot de passe et sa confirmation divergent.";
  }

  // A partir de maintenant on sait que pass1 === pass2.
  // On hash le nouveau mot de passe.
  $newpass = md5($_POST['pass1']);

  if ($newpass === $oldpass) {
    return "err=Vous venez de tenter de modifier votre mot de passe par le même mot de passe, à quoi bon? Par faute de sens dans cette operation administrative, oressource ne procedera à aucun changement.";
  }

  if ($_POST['pass1'] === $_POST['pass2']) {
    $req = $bdd->prepare('UPDATE utilisateurs SET pass = :pass WHERE id = :id');
    $req->execute(['pass' => $newpass, 'id' => $id]);
    $req->closeCursor();
    return "msg=Mot de passe modifié avec succes, utilisateur: $name mail: {$user['mail']}";
  } else {
    return "err=Veuillez inscrire deux mots de passe semblables";
  }
}

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource') {
  require_once '../moteur/dbconfig.php';
  $id = $_SESSION['id'];
  $str = change_pass($bdd, $id, $_POST['passold'], $_POST['pass1'], $_POST['pass2']);
  header("Location: ../ifaces/edition_mdp_utilisateur.php?$str");
} else {
  header('Location:../moteur/destroy.php');
}
