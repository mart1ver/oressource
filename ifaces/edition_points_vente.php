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
require_once '../core/session.php';
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_config()) {
  require_once 'tete.php';

  $nom = $_GET['nom'] ?? '';
  $reponse = $bdd->prepare('SELECT id, timestamp, nom, adresse, couleur, commentaire, surface_vente, visible FROM points_vente');
  $reponse->execute();
  $points_ventes = $reponse->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <div class="container">
    <h1>Gestion des points de vente</h1>
    <div class="panel-heading">Gérez ici les différents points de vente.</div>
    <p>Attention les points de ventes doivent impérativement avoir des noms distincts!</p>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/points_vente_post.php" method="post">
          <div class="col-md-2">
            <label for="nom">Nom:</label>
            <br>
            <input type="text" value="" name="nom" id="nom" class="form-control " required autofocus>
          </div>
          <div class="col-md-3">
            <label for="adresse">Adresse:</label>
            <br>
            <input type="text" value ="" name="adresse" id="adresse" class="form-control " required>
          </div>
          <div class="col-md-2">
            <label for="commentaire">Commentaire:</label>
            <br>
            <input type="text" value ="" name="commentaire" id="commentaire" class="form-control " required>
          </div>
          <div class="col-md-2">
            <label for="surface">Surface de vente (m²):</label>
            <input type="text" value ="" name="surface" id="surface" class="form-control " required>
          </div>
          <div class="col-md-1">
            <label for="couleur">Couleur:</label><br>
            <input type="color" value="#11FFFF" name="couleur" id="couleur" class="form-control">
          </div>
          <div class="col-md-1"><br>
            <button name="creer" class="btn btn-default">Créer!</button>
          </div>
        </form>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Adresse:</th>
          <th>Couleur</th>
          <th>Commentaire:</th>
          <th>Surface de vente (m²):</th>
          <th>Visible</th>
          <th>Modifier</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($points_ventes as $point_vente) { ?>
          <tr>
            <td><?= $point_vente['id']; ?></td>
            <td><?= $point_vente['timestamp']; ?></td>
            <td><?= $point_vente['nom']; ?></td>
            <td><?= $point_vente['adresse']; ?></td>
            <td><span class="badge" style="background-color:<?= $point_vente['couleur']; ?>"><?= $point_vente['couleur']; ?></span></td>
            <td><?= $point_vente['commentaire']; ?></td>
            <td><?= $point_vente['surface_vente']; ?></td>
            <td><?= configBtnVisible(['url' => 'points_vente', 'id' => $point_vente['id'], 'visible' => $point_vente['visible']]) ?></td>
            <td>
              <form action="modification_points_vente.php" method="post">
                <input type="hidden" name="id" id="id" value="<?= $point_vente['id']; ?>">
                <button class="btn btn-warning btn-sm" type="submit">Modifier!</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div> <!-- /.container -->
  <?php
  include_once('pied.php');
} else {
  header('Location: ../moteur/destroy.php');
}
?>
