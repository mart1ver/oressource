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

if (is_valid_session() && is_allowed_verifications()) {
  require_once '../moteur/dbconfig.php';
  $users = map_by(utilisateurs($bdd), 'id');

  $vendus = vendu_by_id_vente($bdd, $_GET['nvente']);

  $reponse = $bdd->prepare('SELECT commentaire FROM ventes WHERE id = :id_vente');
  $reponse->execute(['id_vente' => $_GET['nvente']]);
  $commentaire = $reponse->fetch()['commentaire'];
  $reponse->closeCursor();

  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Modifier la vente n° <?= $_GET['nvente']; ?></h1>
    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_vente_post.php?nvente=<?= $_GET['nvente']; ?>" method="post">
          <input type="hidden" name="id" value="<?= $_GET['nvente']; ?>">
          <input type="hidden" name="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name="npoint" value="<?= $_POST['npoint']; ?>">
          <div class="col-md-3">
            <label for="commentaire">Commentaire:</label>
            <textarea name="commentaire" id="commentaire" class="form-control"><?= $commentaire ?></textarea>
          </div>

          <div class="col-md-3">
            <label for="moyen">Moyen de paiement:</label>
            <select name="moyen" id="moyen" class="form-control " required>
              <?php foreach (filter_visibles(moyens_paiements($bdd)) as $m) { ?>
                <option <?= $_POST['moyen'] === $m['nom'] ? 'selected' : '' ?>
                  value="<?= $m['id']; ?>"  ><?= $m['nom']; ?></option>
                <?php } ?>
            </select>
          </div>

          <div class="col-md-3">
            <br>
            <button name="creer" class="btn btn-warning">Modifier</button>
            <a href="verif_vente.php?date1=<?= $_POST['date1']; ?>&date2=<?= $_POST['date2']; ?>&numero=<?= $_POST['npoint']; ?>">
              <button name="creer" class="btn btn" style="float: right;">Annuler</button>
            </a>
          </div>
        </form>
      </div>
    </div>

    <h1>Objets inclus dans cette vente</h1>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Momment de la vente</th>
          <th>Type d'objet:</th>
          <th>Objet:</th>
          <th>Quantité</th>
          <th>Prix</th>
          <th>masse</th>
          <th>Auteur de la ligne</th>
          <th></th>
          <th>Modifié par</th>
          <th>Le:</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($vendus as $v) { ?>
          <tr>
            <td><?= $v['id']; ?></td>
            <td><?= $v['timestamp']; ?></td>
            <td><?= $v['type']; ?></td>
            <td><?= $v['objet']; ?></td>
            <td><?= $v['quantite']; ?></td>
            <td><?= $v['prix']; ?></td>
            <td><?= $v['masse'] ?? 0 ?></td>
            <td><?= $users[$v['id_createur']]['mail'] ?></td>
            <td><form action="modification_verification_objet.php" method="post">
                <input type="hidden" name="id" value="<?= $v['id']; ?>">
                <input type="hidden" name="nvente" value="<?= $v['id_vente']; ?>">
                <input type="hidden" name="quantite" value="<?= $v['quantite']; ?>">
                <input type="hidden" name="prix" value="<?= $v['prix']; ?>">
                <input type="hidden" name="masse" value="<?= $v['masse'] ?? 0 ?>">
                <input type="hidden" name="date1" value="<?= $_POST['date1']; ?>">
                <input type="hidden" name="date2" value="<?= $_POST['date2']; ?>">
                <input type="hidden" name="npoint" value="<?= $_POST['npoint']; ?>">
                <button class="btn btn-warning btn-sm">Modifier</button>
              </form>
            </td>
            <td><?= $v['last_hero_timestamp'] !== $v['timestamp'] ? $users[$v['id_last_hero']]['mail'] : '' ?></td>
            <td><?= $v['last_hero_timestamp'] !== $v['timestamp'] ? $v['last_hero_timestamp'] : '' ?></td>
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
