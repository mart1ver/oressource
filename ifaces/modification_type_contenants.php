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
require_once '../core/session.php';
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_gestion()) {
  require_once '../moteur/dbconfig.php';
  $contenants = types_contenants_id($bdd, $_POST['id']);
  $props = [
    'url' => 'modification_type_contenant',
    'id' => $contenants['id'],
    'nom' => $contenants['nom'],
    'masse' => $contenants['masse'],
    'couleur' => substr($contenants['couleur'], 1),
    'description' => $contenants['description'],
    'textBtn' => 'Modifier'
  ];
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Gestion des types de bacs et de moyens de manutention.</h1>
    <div class="panel-heading">Modifier les données concernant le moyen de manutention n° <?= $_POST['id'] ?>, <?= $contenants['nom'] ?>.</div>
    <div class="panel-body">
      <div class="row">
        <?= config_types4_form($props) ?>
        <br>
        <a href="edition_types_contenants.php">
          <button class="btn btn">Annuler</button>
        </a>
      </div>
    </div>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
