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
    <h1>Modifier l'objet vendu n° <?= $_POST['id']; ?> appartenant à la vente <?= $_POST['nvente']; ?> </h1>
    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_objet_post.php" method="post">
          <input type="hidden" name ="nvente" id="nvente" value="<?= $_POST['nvente']; ?>">
          <input type="hidden" name ="id" id="id" value="<?= $_POST['id']; ?>">
          <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">
          <div class="col-md-2">
            <label for="quantite">Quantité:</label>
            <br><input type="text" value="<?= $_POST['quantite']; ?>" name="quantite" id="quantite" class="form-control" required>
          </div>

          <div class="col-md-2">
            <label for="prix">Prix:</label>
            <br><input type="text" value="<?= $_POST['prix']; ?>" name="prix" id="prix" class="form-control" required>
          </div>

          <div class="col-md-2">
            <label for="prix">Masse:</label>
            <br><input <?= $_POST['masse'] == 0 ? 'disabled' : '' ?> type="text" value="<?= $_POST['masse']; ?>" name="masse" id="masse" class="form-control">
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
  </div>

  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
