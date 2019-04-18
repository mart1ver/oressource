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

require_once '../core/composants.php';
require_once '../core/session.php';
require_once '../core/requetes.php';

if (is_valid_session() && (strpos($_SESSION['niveau'], 'k') !== false)) {
  require_once '../moteur/dbconfig.php';
  require_once 'tete.php';
  $props = array_merge(['h1' => 'Gestion des partenaires de réemploi.',
    'type' => 'partenaire',
    'endpoint' => 'conventions_sorties'
  ], convention_sortie_by_id($bdd, $_POST['id']));
  ?>
  <?= config_types3_page_modif($props) ?>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
