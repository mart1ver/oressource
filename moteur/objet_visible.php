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

require_once '../core/validation.php';
require_once '../core/session.php';
require_once '../core/requetes.php';

function objet_update_visible(PDO $bdd, int $id, bool $visible) {
  $req = $bdd->prepare('update grille_objets set visible = :visible where id = :id');
  $req->bindValue(':id', $id, PDO::PARAM_INT);
  $req->bindValue(':visible', bool_to_oui_non($visible), PDO::PARAM_STR);
  $req->execute();
  $req->closeCursor();
}

session_start();

if (is_valid_session() && is_allowed_gestion()) {
  require_once 'dbconfig.php';
  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
  $visible = ($_POST['visible'] ?? 'false') === 'true';
  if (!is_null($id)) {
    objet_update_visible($bdd, $id, $visible);
    header("Location:../ifaces/grilles_prix.php?typo=$id");
    die;
  }
} else {
  header('Location:../moteur/destroy.php');
  die;
}
