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

function pesees_collectes_id(PDO $bdd, int $id) {
  $sql = 'SELECT * FROM pesees_collectes WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

if (is_valid_session() && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once '../moteur/dbconfig.php';
  $id = $_GET['id'];
  $pesee = pesees_collectes_id($bdd, $id);
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Modifier la pesée n°<?= $id ?> appartenant à la collecte <?= $pesee['id_collecte'] ?></h1>
    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_pesee_post.php" method="post">
          <input type="hidden" name="id" value="<?= $id ?>">
          <input type="hidden" name="id_collecte" value="<?= $pesee['id_collecte']; ?>">
          <?= selectConfig(['data' => filter_visibles(types_dechets($bdd)), 'key' => 'id_type_dechet', 'active' => $pesee['id_type_dechet'], 'text' => 'Type de dechet:']) ?>

          <div class="col-md-3">
            <label for="masse">Masse:</label>
            <br><input type="text" value="<?= $pesee['masse']; ?>" name="masse" id="masse" class="form-control" required>
          </div>
          <div class="col-md-3">
            <br>
            <button class="btn btn-warning">Modifier</button>
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
