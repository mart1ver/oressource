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

require_once '../core/session.php';
require_once '../core/requetes.php';

function pesees_sorties_id(PDO $bdd, int $id): array {
  $sql = 'SELECT
    pesees_sorties.id,
    pesees_sorties.masse,
    pesees_sorties.id_sortie,
    pesees_sorties.id_type_poubelle poubelle,
    pesees_sorties.id_type_dechet dechet,
    pesees_sorties.id_type_dechet_evac evac
  FROM pesees_sorties
  WHERE pesees_sorties.id = :id';
  $r = fetch_id($bdd, $sql, $id);
  $r['id_type'] = $r['poubelle'] | $r['dechet'] | $r['evac'];
  return $r;
}

if (is_valid_session() && is_allowed_verifications()) {
  require_once('../moteur/dbconfig.php');

  $id = (int) ($_GET['id'] ?? 0) | ($_POST['id'] ?? 0);
  $pesee = pesees_sorties_id($bdd, $id);
  $meta = [];
  $type = '';
  $text = '';
  if ($pesee['dechet'] > 0) {
    $type = 'dechet';
    $text = "Type d'objet:";
    $meta = filter_visibles(types_dechets($bdd));
  } elseif ($pesee['evac'] > 0) {
    $type = 'evac';
    $text = 'Dechets et materiaux:';
    $meta = filter_visibles(types_dechets_evac($bdd));
  } elseif ($pesee['poubelle'] > 0) {
    $type = 'poubelle';
    $text = 'Type de poubelle:';
    $meta = filter_visibles(types_poubelles($bdd));
  }

  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Modifier la pesée n° <?= $id ?> appartenant à la sortie <?= $pesee['id_sortie'] ?> </h1>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/modification_verification_pesee_sorties_post.php" method="post">
          <input type="hidden" name="id_sortie" value="<?= $pesee['id_sortie'] ?>">
          <input type="hidden" name="id"      value="<?= $pesee['id'] ?>">
          <div class="col-md-3">
            <label for="id_type"><?= $text ?></label>
            <select id="id_type" name="<?= $type ?>" class="form-control" required>
              <?php foreach ($meta as $p) { ?>
                <option <?= $pesee['id_type'] === $p['id'] ? 'selected' : '' ?>
                  value="<?= $p['id']; ?>"><?= $p['nom'] ?></option>
                <?php } ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="masse">Masse:</label>
            <input class="form-control" id="masse" type="text"
                   value="<?= $pesee['masse'] ?>" name="masse" required>
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
