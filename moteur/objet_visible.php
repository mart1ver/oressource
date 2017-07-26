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

require_once('../core/validation.php');
require_once('../core/session.php');
require_once('../core/requetes.php');

session_start();

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_gestion()) {
  require_once('dbconfig.php');

  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $visible = bool_to_oui_non(filter_input(INPUT_GET, 'visible', FILTER_VALIDATE_BOOLEAN));
  if ($id && $visible) {
    grille_objects_update_visible($bdd, $id, $visible);
  } else {
    header('Location:../moteur/destroy.php');
  }

  header('Location:../ifaces/grilles_prix.php?typo=' . $id);
} else {
  header('Location:../moteur/destroy.php');
}
