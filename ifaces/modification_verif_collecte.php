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
require_once '../core/composants.php';
function collectes_id(PDO $bdd, int $id) {
  $sql = 'SELECT * FROM collectes WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

if (is_valid_session() && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once '../moteur/dbconfig.php';
  require_once 'tete.php';

  $users = map_by(utilisateurs($bdd), 'id');

  $id = $_GET['id'];
  $collecte = collectes_id($bdd, $id);

  $req = $bdd->prepare('SELECT
      pesees_collectes.id,
      pesees_collectes.masse,
      pesees_collectes.timestamp,
      pesees_collectes.last_hero_timestamp,
      pesees_collectes.id_createur,
      pesees_collectes.id_last_hero,
      type_dechets.nom,
      type_dechets.couleur
    FROM collectes
    INNER JOIN pesees_collectes
    ON pesees_collectes.id_collecte = :id_collecte
    INNER JOIN type_dechets
    ON type_dechets.id = pesees_collectes.id_type_dechet
    GROUP BY pesees_collectes.id,
      pesees_collectes.masse,
      pesees_collectes.timestamp,
      pesees_collectes.last_hero_timestamp,
      pesees_collectes.id_createur,
      pesees_collectes.id_last_hero,
      type_dechets.nom,
      type_dechets.couleur');
  $req->execute(['id_collecte' => $id]);
  $pesees_collectes = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

  $props = [
    'endpoint' => 'verification_pesee',
    'data' => $pesees_collectes,
    'users' => $users
    ]
  ?>
  <div class="container">
    <h1>Modifier la collecte n° <?= $id ?></h1>
    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_collecte_post.php" method="post">
          <input type="hidden" name="id" id="id" value="<?= $id ?>">
          <?= selectConfig(['data' => filter_visibles(types_collectes($bdd)), 'key' => 'id_type_collecte', 'active' => $collecte['id_type_collecte'], 'text' => 'Type de collecte:']) ?>
          <?= selectConfig(['data' => filter_visibles(localites($bdd)), 'key' => 'localisation', 'active' => $collecte['localisation'], 'text' => 'Localisation:']) ?>
          <div class="col-md-3">
            <label for="commentaire">Commentaire</label>
            <textarea name="commentaire" id="commentaire" class="form-control"><?= $collecte['commentaire'] ?></textarea>
          </div>
          <div class="col-md-3">
            <br>
            <button class="btn btn-warning">Modifier</button>
          </div>
        </form>
      </div>
    </div>

    <h2>Pesées incluses dans cette collecte</h2>
    <?= listPesees($props) ?>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
