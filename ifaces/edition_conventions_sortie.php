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

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Couleur</th>
          <th>Visible</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($conventions as $cs) { ?>
          <tr>
            <td><?= $cs['id']; ?></td>
            <td><?= $cs['timestamp']; ?></td>
            <td><?= $cs['nom']; ?></td>
            <td><?= $cs['description']; ?></td>
            <td>
              <span class="badge" style="background-color:<?= $cs['couleur']; ?>"><?= $cs['couleur']; ?></span>
            </td>
            <td>
              <form action="../moteur/convention_sortie_visible.php" method="post">
                <input type="hidden" name ="id" id="id" value="<?= $cs['id']; ?>">
                <input type="hidden" name="visible" id="visible"
                       value="<?= $cs['visible'] === 'oui' ? 'non' : 'oui' ?>">
                <button class="btn btn-sm <?= $cs['visible'] === 'oui' ? 'btn-info' : 'btn-danger' ?>">
                  <?= $cs['visible'] === 'oui' ? 'oui' : 'non' ?>
                </button>
              </form>
            </td>

            <td>
              <form action="modification_convention_sortie.php" method="post">
                <input type="hidden" name="id" id="id" value="<?= $cs['id']; ?>">
                <input type="hidden" name="nom" id="nom" value="<?= $cs['nom']; ?>">
                <input type="hidden" name="description" id="description" value="<?= $cs['description']; ?>">
                <input type="hidden" name="couleur" id="couleur" value="<?= substr($cs['couleur'], 1); ?>">
                <button class="btn btn-warning btn-sm" >Modifier!</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
