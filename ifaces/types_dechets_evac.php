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

require_once '../core/requetes.php';
require_once '../core/composants.php';

session_start();

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'k') !== false)) {
  require_once '../moteur/dbconfig.php';
  require_once 'tete.php';
  ?>
  <div class="container">
    <?= config_types3([
      'h1' => 'Gestion de la typologie des déchets évacués',
      'heading' => "Gérez ici les différents types de déchets évacués par la structure.",
      'text' => "Permet de gérer par classes les déchets et matériaux sortant de la structure",
      'url' => '../moteur/types_dechets_evac_post.php']) ?>

    <?= configModif(['data' => types_dechets_evac($bdd), 'url' => 'types_dechets_evac']) ?>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
