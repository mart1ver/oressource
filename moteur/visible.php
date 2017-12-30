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

function table_visible(PDO $bdd, string $table, int $id, bool $visible) {
  $req = $bdd->prepare("UPDATE $table SET visible = :visible WHERE id = :id");
  $req->bindValue(':visible', $visible === true, PDO::PARAM_INT);
  $req->bindValue(':id', $id, PDO::PARAM_INT);
  $req->execute();
  $req->closeCursor();
}

if (is_valid_session() && (is_allowed_config() || is_allowed_gestion())) {
  require_once '../moteur/dbconfig.php';
  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
  $visible = json_decode($_POST['visible']) ? true : false;
  table_visible($bdd, $_POST['table'], $id, $visible);
  header("Location:{$_SERVER['HTTP_REFERER']}");
} else {
  header('Location:../moteur/destroy.php');
}
