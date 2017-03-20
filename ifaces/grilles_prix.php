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

require_once('../moteur/dbconfig.php');
require_once('../core/requetes.php');
require_once('../core/session.php');


$type_obj = filter_input(INPUT_GET, 'typo', FILTER_VALIDATE_INT);

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_gestion()
  && $type_obj !== false) {
  require_once("tete.php");


  $type_dechets = types_dechets($bdd);
  $grille = grilles_objets_id($bdd, $type_obj);
  ?>

  <div class="container">
    <h1>Grille des prix</h1>
    <ul class="nav nav-tabs">
      <?php foreach ($type_dechets as $type_dechet) { ?>
        <li class="<?= ($type_obj === $type_dechet['id'] ? 'active' : '') ?>">
          <a href="les_prix.php?typo=<?= $type_dechet['id'] ?>"><?= $type_dechet['nom'] ?></a>
        </li>
      <?php } ?>
    </ul>

    <div class="panel-body">
      <form action="../moteur/grilles_prix_post.php" method="post">
        <div class="row input-group">
          <div class="col-lg-3">
            <label for="nom">Nom:</label>
            <input id="nom" class="form-control" type="text" placeholder="nom" name="nom"  required autofocus>
          </div>
          <div class="col-lg-3">
            <label for="description">Description:</label>
            <input id="description" class="form-control" type="text" placeholder="description" name="description" required>
          </div>
          <div class="col-lg-3">
            <label for="prix">Prix:</label>
            <input id="prix" class="form-control" type="text" placeholder="prix" name="prix" required >
            <input class="form-control" type="hidden" value="<?= $type_obj ?>" name="typo">
          </div>
          <div class="col-lg-3">
            <br> <!-- TODO: trouver plus elegant en CSS que ce hack... -->
            <button name="creer" class="btn btn-default">créer</button>
          </div>
        </div>
      </form>
    </div>

    <table class="table">
      <thead>
        <tr>
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
            <td><?= $item['nom'] ?></td>
            <td><?= $item['timestamp'] ?></td>
            <td><?= $item['description'] ?></td>
            <td><?= $item['prix'] ?></td>
            <td>
              <form action="../moteur/objet_sup.php" method="post">
                <input type="hidden" name="typo" value="<?= $type_obj ?>">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button class="btn btn-danger btn-sm ">Supprimer</button>
              </form>
            </td>

            <td>
              <form action="../moteur/objet_visible.php" method="post">
                <input type="hidden" name="typo" value="<?= $type_obj ?>">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <input type="hidden" name="visible" value="<?=
          ($item['visible'] === 'oui' ? 'non' : 'oui')
          ?>">
                       <?php if ($item['visible'] === 'oui') { ?>
                  <button class="btn btn-info btn-sm"><?= $item['visible'] ?></button>
                <?php } else { ?>
                  <button class="btn btn-danger btn-sm"><?= $item['visible'] ?></button>
                <?php } ?>
              </form>
            </td>

            <td>
              <form action="modification_objet.php" method="post">
                <input type="hidden" name="typo" value="<?= $type_obj ?>">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <input type="hidden" name="nom" value="<?= $item['nom'] ?>">
                <input type="hidden" name="description" value="<?= $item['description'] ?>">
                <input type="hidden" name="prix" value="<?= $item['prix'] ?>">
                <button class="btn btn-warning btn-sm">Modifier</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div><!-- /.container -->

  <?php
  include "pied.php";
} else {
  header('Location: ../moteur/destroy.php');
}
