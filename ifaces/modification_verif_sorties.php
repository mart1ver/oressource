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
function fetch_all_id(PDO $bdd, string $sql, int $id) {
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $r;
}

// Pesée « Evac »
function pesees_sorties_items(PDO $bdd, int $id): array {
  $sql = 'SELECT
    pesees_sorties.id,
    pesees_sorties.id_createur,
    pesees_sorties.id_last_hero,
    pesees_sorties.masse,
    pesees_sorties.timestamp,
    pesees_sorties.last_hero_timestamp,
    pesees_sorties.id_type_dechet id_type,
    type_dechets.nom,
    type_dechets.couleur
  FROM pesees_sorties
  INNER JOIN type_dechets
  ON pesees_sorties.id_type_dechet = type_dechets.id
  WHERE pesees_sorties.id_sortie = :id';
  return fetch_all_id($bdd, $sql, $id);
}

// Pesée « Item »
function pesees_sorties_evac(PDO $bdd, int $id): array {
  $sql = 'SELECT
    pesees_sorties.id,
    pesees_sorties.id_createur,
    pesees_sorties.id_last_hero,
    pesees_sorties.masse,
    pesees_sorties.timestamp,
    pesees_sorties.last_hero_timestamp,
    pesees_sorties.id_type_dechet_evac id_type,
    type_dechets_evac.nom,
    type_dechets_evac.couleur
  FROM pesees_sorties
  INNER JOIN type_dechets_evac
  ON pesees_sorties.id_type_dechet_evac = type_dechets_evac.id
  WHERE pesees_sorties.id_sortie = :id';
  return fetch_all_id($bdd, $sql, $id);
}

function pesees_sortie_poubelle(PDO $bdd, int $id): array {
  $sql = 'SELECT
    pesees_sorties.id,
    pesees_sorties.id_last_hero,
    pesees_sorties.id_createur,
    pesees_sorties.masse,
    pesees_sorties.timestamp,
    pesees_sorties.last_hero_timestamp,
    pesees_sorties.id_type_poubelle id_type,
    types_poubelles.nom,
    types_poubelles.couleur
  FROM pesees_sorties
  INNER JOIN types_poubelles
  ON pesees_sorties.id_type_poubelle = types_poubelles.id
  WHERE pesees_sorties.id_sortie = :id';
  return fetch_all_id($bdd, $sql, $id);
}

function strategie_sortie(PDO $bdd, string $classe, int $id): array {
  if ($classe === 'p') {
    return [
      'pesees' => pesees_sortie_poubelle($bdd, $id),
      'h2' => 'poubelles',
    ];
  } elseif ($classe === 'c') {
    return [
      'meta' => array_reduce(filter_visibles(convention_sortie($bdd)), function ($acc, $e) {
          $acc[$e['id']] = $e;
          return $acc;
        }),
      'pesees' => array_merge(pesees_sorties_evac($bdd, $id), pesees_sorties_items($bdd, $id)),
      'h2' => 'conventionnés',
      'label' => 'Nom du partenaire:'
    ];
  } elseif ($classe === 'r') {
    return [
      'meta' => array_reduce(filter_visibles(filieres_sorties($bdd)), function ($acc, $e) {
          $acc[$e['id']] = $e;
          return $acc;
        }),
      'pesees' => pesees_sorties_evac($bdd, $id),
      'label' => "Nom de l'entreprise de recyclage:",
      'h2' => 'recyclage'
    ];
  } elseif ($classe === 'd') {
    return [
      'pesees' => pesees_sorties_evac($bdd, $id),
      'h2' => 'dechetterie'
    ];
  } else {
    return [
      'meta' => array_reduce(filter_visibles(types_sorties($bdd)), function ($acc, $e) {
          $acc[$e['id']] = $e;
          return $acc;
        }),
      'pesees' => array_merge(pesees_sorties_evac($bdd, $id), pesees_sorties_items($bdd, $id)),
      'h2' => 'dons',
      'label' => 'Type de sortie:'
    ];
  }
}

if (is_valid_session() && is_allowed_verifications()) {
  require_once '../moteur/dbconfig.php';
  $users = array_reduce(utilisateurs($bdd), function ($acc, $e) {
    $acc[$e['id']] = $e;
    return $acc;
  }, []);

  $filiere_sortie = filieres_sorties($bdd);

  $props = array_merge([
    'id' => (int) $_POST['id'],
    'id_type' => (int) ($_POST['id_type'] ?? 0),
    'classe' => $_POST['classe'],
    ], strategie_sortie($bdd, $_POST['classe'], $_POST['id']));

  //form action="../moteur/modification_verification_sorties_post.php" method="post"
  //form action="modification_verification_pesee_sorties.php" method="post"
  //form action="modification_verification_pesee_sorties.php" method="post"
  // var_dump($_SERVER['HTTP_REFERER']);
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Modifier la sortie n° <?= $_POST['id'] ?></h1>

    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_sorties<?= $props['classe'] ?>_post.php" method="post">
          <input type="hidden" name="id" value="<?= $props['id'] ?>">
          <?php if (isset($props['meta'])) { ?>
            <div class="col-md-3">
              <label for="id_meta"><?= $props['label'] ?></label>
              <select name="id_meta" class="form-control" required>
                <?php foreach ($props['meta'] as $p) { ?>
                  <option <?= $props['id_type'] === $p['id'] ? 'selected' : '' ?>
                    value="<?= $p['id']; ?>"><?= $p['nom']; ?></option>
                  <?php } ?>
              </select>
            </div>
          <?php } ?>

          <div class="col-md-3">
            <label for="commentaire">Commentaire</label>
            <textarea name="commentaire" class="form-control"><?= $_POST['commentaire'] ?></textarea>
          </div>

          <div class="col-md-3">
            <br>
            <button name="creer" class="btn btn-warning">Modifier</button>
          </div>
        </form>
      </div>
    </div>

    <h2>Pesées incluses dans cette sortie <?= $props['h2'] ?>:</h2>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Type de dechet:</th>
          <th>Masse</th>
          <th>Crée par</th>
          <th></th>
          <th>Modifié par</th>
          <th>Le:</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($props['pesees'] as $p) { ?>
          <tr>
            <td><?= $p['id']; ?></td>
            <td><?= $p['timestamp']; ?></td>
            <td><span class="badge" 
                      style="background-color:<?= $p['couleur'] ?>"><?= $p['nom']; ?></span></td>
            <td><?= $p['masse']; ?></td>
            <td><?= $users[$p['id_createur']]['mail']; ?></td>
            <td>
              <form action="modification_verification_pesee_sorties.php" method="post">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="hidden" name="date1" value="<?= $_POST['date1'] ?>">
                <input type="hidden" name="date2" value="<?= $_POST['date2'] ?>">
                <button class="btn btn-warning btn-sm">Modifier</button>
              </form>
            </td>
            <td><?= $p['last_hero_timestamp'] !== $p['timestamp'] ? $users[$p['id_last_hero']]['mail'] : '' ?></td>
            <td><?= $p['last_hero_timestamp'] !== $p['timestamp'] ? $p['last_hero_timestamp'] : '' ?></td>
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
