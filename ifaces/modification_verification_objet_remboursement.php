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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Modifier l'objet remboursé n° <?= $_POST['id']; ?> appartenant au remboursement n° <?= $_POST['nvente']; ?> </h1>
    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_objet_remboursement_post.php" method="post">
          <input type="hidden" name="nvente" value="<?= $_POST['nvente']; ?>">
          <input type="hidden" name="id" value="<?= $_POST['id']; ?>">
          <input type="hidden" name="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name="npoint"  value="<?= $_POST['npoint']; ?>">

          <div class="col-md-3">
            <label for="quantite">Quantité:</label>
            <br><input type="text" value="<?= $_POST['quantite']; ?>" name="quantite" id="quantite" class="form-control" required>
          </div>

          <div class="col-md-3">
            <label for="remboursement">Prix:</label>
            <br><input type="text" value="<?= $_POST['remboursement']; ?>" name="remboursement" id="remboursement" class="form-control" required>
          </div>

          <div class="col-md-3">
            <br>
            <button name="creer" class="btn btn-warning">Modifier</button>
          </div>
        </form>
      </div>

    </div>

  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
