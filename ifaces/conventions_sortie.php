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

// Oressource 2014, formulaire de référencement des conventions
// avec les partenaires de la structure: liste des conventions déjà référencées et
// possibilité de les cacher à l'utilisateur ou de modifier les données

session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_partners()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  $conventions = convention_sortie($bdd);
  ?>

  <div class="container">
    <div class='panel'>
      <div class='panel-title'>
           <h1>Gestion des conventions avec les partenaires</h1>
      </div>
      <div class="panel-heading">
        <p>Afin de différencier les partenaires de réemploi au moment de la mise en bilan.</p>
      </div>
      <div class="panel-body">
        <div class="row">
          <form action="../moteur/convention_sortie_post.php" method="post">
            <div class="col-md-3">
              <?= textInput(['text' => 'Nom:', 'name' => 'nom'], $_GET['nom'] ?? '') ?>
            </div>
            <div class="col-md-2">
              <?= textInput(['text' => 'Description:', 'name' => 'description'], $_GET['description'] ?? '') ?>
            </div>
            <div class="col-md-1">
              <label>Couleur:
                <input type="color" value="<?= '#' . ($_GET['couleur'] ?? 'FFFF') ?>" name="couleur" id="couleur" class="form-control" required>
              </label>
            </div>
            <div class="col-md-1">
              <br>
              <button name="creer" class="btn btn-default">Créer!</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?= configModif(['data' => convention_sortie($bdd), 'url' => 'convention_sortie']) ?>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
