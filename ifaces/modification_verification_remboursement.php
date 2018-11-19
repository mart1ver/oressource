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

  $id = $_GET['nvente'];
  $rembs = vendu_by_id_vente($bdd, $id);

  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Modifier le remboursement n° <?= $_GET['nvente']; ?></h1>
    <div class="panel-body">
      <br>
    </div>

    <h1>Objets inclus dans ce remboursement</h1>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Type d'objet:</th>
          <th>Objet:</th>
          <th>Quantité</th>
          <th>Prix</th>
          <th>Auteur de la ligne</th>
          <th></th>
          <th>Modifié par</th>
          <th>Le:</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($rembs as $r) { ?>
          <tr>
            <td><?= $r['id']; ?></td>
            <td><?= $r['timestamp']; ?></td>
            <td><?= $r['type']; ?></td>
            <td><?= $r['objet']; ?></td>
            <td><?= $r['quantite']; ?></td>
            <td><?= $r['remboursement']; ?></td>
            <td><?= $users[$r['id_createur']]['mail'] ?></td>
            <td><form action="modification_verification_objet_remboursement.php" method="post">
                <input type="hidden" name="id" value="<?= $r['id']; ?>">
                <input type="hidden" name="nvente" value="<?= $id ?>">
                <input type="hidden" name="quantite" value="<?= $r['quantite']; ?>">
                <input type="hidden" name="remboursement"value="<?= $r['remboursement']; ?>">
                <input type="hidden" name="date1" value="<?= $_POST['date1']; ?>">
                <input type="hidden" name="date2" value="<?= $_POST['date2']; ?>">
                <input type="hidden" name="npoint" value="<?= $_POST['npoint']; ?>">
                <button class="btn btn-warning btn-sm">Modifier</button>
              </form>
            <td><?= $r['last_hero_timestamp'] !== $r['timestamp'] ? $users[$r['id_last_hero']]['mail'] : '' ?></td>
            <td><?= $r['last_hero_timestamp'] !== $r['timestamp'] ? $r['last_hero_timestamp'] : '' ?></td>
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
