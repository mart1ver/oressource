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
  if ($classe === 'sortiesp') {
    return [
      'data' => pesees_sortie_poubelle($bdd, $id),
      'h2' => 'poubelles',
    ];
  } elseif ($classe === 'sortiesc') {
    return [
      'meta' => map_by(filter_visibles(convention_sortie($bdd)), 'id'),
      'data' => array_merge(pesees_sorties_evac($bdd, $id), pesees_sorties_items($bdd, $id)),
      'h2' => 'conventionnés',
      'label' => 'Nom du partenaire:'
    ];
  } elseif ($classe === 'sortiesr') {
    return [
      'meta' => map_by(filter_visibles(filieres_sorties($bdd)), 'id'),
      'data' => pesees_sorties_evac($bdd, $id),
      'label' => "Nom de l'entreprise de recyclage:",
      'h2' => 'recyclage'
    ];
  } elseif ($classe === 'sortiesd') {
    return [
      'data' => pesees_sorties_evac($bdd, $id),
      'h2' => 'dechetterie'
    ];
  } else {
    return [
      'meta' => map_by(filter_visibles(types_sorties($bdd)), 'id'),
      'data' => array_merge(pesees_sorties_evac($bdd, $id), pesees_sorties_items($bdd, $id)),
      'h2' => 'dons',
      'label' => 'Type de sortie:'
    ];
  }
}

if (is_valid_session() && is_allowed_verifications()) {
  require_once '../moteur/dbconfig.php';
  $users = map_by(utilisateurs($bdd), 'id');

  $filiere_sortie = filieres_sorties($bdd);
  $id = (int) $_GET['id'];
  $sortie = sortie_id($bdd, $id);
  $classe = $sortie['classe'];
  $props = array_merge([
    'id' => $id,
    'id_type' => (int) ($sortie['id_convention'] | $sortie['id_type_sortie'] | $sortie['id_filiere']),
    'classe' => $classe,
    'users' => $users,
    'endpoint' => 'verification_pesee_sorties'
    ], strategie_sortie($bdd, $classe, $id));
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Modifier la sortie n° <?= $id ?></h1>

    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_sorties_post.php" method="post">
          <input type="hidden" name="id" value="<?= $props['id'] ?>">
          <input type="hidden" name="classe" value="<?= $props['classe'] ?>">
          <?php if (isset($props['meta'])) { ?>
            <?= selectConfig(['data' => $props['meta'], 'key' => 'id_meta', 'active' => $props['id_type'], 'text' => $props['label']]) ?>
          <?php } ?>

          <div class="col-md-3">
            <label for="commentaire">Commentaire</label>
            <textarea name="commentaire" class="form-control"><?= $sortie['commentaire'] ?></textarea>
          </div>

          <div class="col-md-3">
            <br>
            <button class="btn btn-warning">Modifier</button>
          </div>
        </form>
      </div>
    </div>

    <h2>Pesées incluses dans cette sortie <?= $props['h2'] ?>:</h2>
    <?= listPesees($props) ?>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
