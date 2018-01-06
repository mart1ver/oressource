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

require_once '../moteur/dbconfig.php';
require_once '../core/composants.php';
require_once '../core/requetes.php';
require_once '../core/session.php';

if (is_valid_session() && is_allowed_config()) {
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Gestion des points de collecte</h1>
    <div class="panel-heading">Gérez ici les différents points de collecte.</div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/points_collecte_post.php" method="post">
          <div class="col-md-2"><label for="nom">Nom:</label><br><br><input type="text" value ="<?= $_GET['nom'] ?? ''; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-3"><label for="adresse">Adresse:</label><br><br><input type="text" value ="<?= $_GET['adresse'] ?? ''; ?>" name="adresse" id="adresse" class="form-control" required></div>
          <div class="col-md-2"><label for="commentaire">Commentaire:</label><br><br> <input type="text" value ="<?= $_GET['commentaire'] ?? ''; ?>" name="commentaire" id="commentaire" class="form-control" required></div>
          <div class="col-md-2"><label for="pesee_max">Masse maxi. d'une pesée (Kg):</label> <input type="text" value ="<?= $_GET['pesee_max'] ?? ''; ?>" name="pesee_max" id="pesee_max" class="form-control" required></div>
          <div class="col-md-1"><label for="couleur">Couleur:</label><br><br><input type="color" value="#<?= $_GET['couleur'] ?? ''; ?>" name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><br><button class="btn btn-default">Creer!</button></div>
        </form>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Adresse</th>
          <th>Couleur</th>
          <th>Commentaire</th>
          <th>Pesée maxi.</th>
          <th>Visible</th>
          <th>Modifier</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach (points_collectes($bdd) as $donnees) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['adresse']; ?></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td><?= $donnees['commentaire']; ?></td>
            <td><?= $donnees['pesee_max']; ?></td>
            <td><?= configBtnVisible(['url' => 'points_collecte', 'id' => $donnees['id'], 'visible' => $donnees['visible']]) ?></td>
            <td><a style="appearance: button" class="btn btn-warning btn-sm" href="../ifaces/modification_points_collecte.php?id=<?= $donnees['id'] ?>">Modifier</a></td>
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
