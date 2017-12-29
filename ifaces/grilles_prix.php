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

$type_obj = filter_input(INPUT_GET, 'id_type_dechet', FILTER_VALIDATE_INT) ?? 1;

if (is_valid_session() && is_allowed_gestion() && $type_obj !== false) {
  require_once '../moteur/dbconfig.php';

  $type_dechets = types_dechets($bdd);
  $grille = objet_id_dechet($bdd, $type_obj);
  require_once 'tete.php';
  ?>

  <div class="container">
    <h1>Grille des prix</h1>
    <ul class="nav nav-tabs">
      <?php foreach ($type_dechets as $type_dechet) { ?>
        <li class="<?= ($type_obj == $type_dechet['id'] ? 'active' : ''); ?>">
          <a href="grilles_prix.php?id_type_dechet=<?= $type_dechet['id']; ?>"><?= $type_dechet['nom']; ?></a>
        </li>
      <?php } ?>
    </ul>

    <div class="panel-body">
      <form action="../moteur/grilles_prix_post.php" method="post">
        <div class="row input-group">
          <div class="col-lg-3">
            <label for="nom">Nom:</label>
            <input id="nom" class="form-control" type="text" placeholder="nom" name="nom" required autofocus>
          </div>
          <div class="col-lg-3">
            <label for="description">Description:</label>
            <input id="description" class="form-control" type="text" placeholder="description" name="description" required>
          </div>
          <div class="col-lg-3">
            <label for="prix">Prix:</label>
            <input id="prix" class="form-control" type="text" placeholder="prix" name="prix" required>
            <input class="form-control" type="hidden" value="<?= $type_obj; ?>" name="typo">
          </div>
          <div class="col-lg-3">
            <br> <!-- TODO: trouver plus elegant en CSS que ce hack... -->
            <button name="creer" class="btn btn-default">Créer</button>
          </div>
        </div>
      </form>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>identifiant</th>
          <th>Nom</th>
          <th>Date de création</th>
          <th>Description</th>
          <th>Prix</th>
          <th>Supprimer</th>
          <th>Visible</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($grille as $item) { ?>
          <tr>
            <td><?= $item['id']; ?></td>
            <td><?= $item['nom']; ?></td>
            <td><?= $item['timestamp']; ?></td>
            <td><?= $item['description']; ?></td>
            <td><?= $item['prix']; ?></td>
            <td>
              <form action="../moteur/objet_sup.php" method="post">
                <input type="hidden" name="typo" value="<?= $type_obj; ?>">
                <input type="hidden" name="id" value="<?= $item['id']; ?>">
                <button class="btn btn-danger btn-sm ">Supprimer</button>
              </form>
            </td>
            <td><?= configBtnVisible(['url' => 'grille_objets', 'id' => $item['id'], 'visible' => $item['visible']]) ?></td>

            <td>
              <!-- TODO faire avec une infobulle JS -->
              <a class="btn btn-warning btn-sm"
                 href="modification_objet.php?id_obj=<?= $item['id']; ?>">Modifier</a>
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
