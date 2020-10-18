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
function VerifSortiesTable(array $props) {
  $users = $props['users'];
  ob_start();
  ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title"><?= $props['h3'] ?></h3>
    </div>
    <div class="panel-body">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>Type</th>
            <th>Commentaire</th>
            <th>Masse totale</th>
            <th>Auteur de la ligne</th>
            <th>Modifié par</th>
            <th>Le</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($props['data'] as $d) { ?>
            <tr>
              <td><?= $d['id']; ?></td>
              <td><?= $d['timestamp']; ?></td>
              <td><?= $d['nom']; ?></td>
              <td><?= $d['commentaire']; ?></td>
              <td><?= $d['masse']; ?></td>
              <td><?= $users[$d['id_createur']]['mail']; ?></td>
              <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $users[$d['id_last_hero']]['mail'] : '' ?></td>
              <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $d['last_hero_timestamp'] : '' ?></td>
              <td><a style="appearance: button" class="btn btn-warning btn-sm" href="../ifaces/modification_<?= $props['endpoint'] ?>.php?id=<?= $d['id'] ?>">Modifier</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

require_once '../core/session.php';

session_start();

if (is_valid_session() && is_allowed_verifications()) {
  require_once '../moteur/dbconfig.php';
  require_once '../core/requetes.php';
  require_once '../core/composants.php';

  $users = map_by(utilisateurs($bdd), 'id');
  $numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

  $time_debut = DateTime::createFromFormat('d-m-Y', $_GET['date1'])->format('Y-m-d') . ' 00:00:00';
  $time_fin = DateTime::createFromFormat('d-m-Y', $_GET['date2'])->format('Y-m-d') . ' 23:59:59';

  $sortiesSQL = 'sorties.id,
        sorties.commentaire,
        sorties.timestamp,
        sorties.id_createur,
        sorties.id_last_hero,
        sorties.last_hero_timestamp';
  // Sorties Dons
  $req = $bdd->prepare("SELECT
        $sortiesSQL,
        sorties.id_type_sortie id_type,
        type_sortie.nom,
        SUM(pesees_sorties.masse) masse
      FROM sorties
      INNER JOIN pesees_sorties
      ON sorties.id = pesees_sorties.id_sortie
      INNER JOIN type_sortie
      ON type_sortie.id = sorties.id_type_sortie
      WHERE sorties.id_point_sortie = :id_point_sortie
      AND DATE(sorties.timestamp) BETWEEN :du AND :au
      AND sorties.classe = 'sorties'
      GROUP BY
      $sortiesSQL,
      sorties.id_type_sortie,
      type_sortie.nom");
  $req->execute(['id_point_sortie' => $numero, 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesDon = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

  // Sorties Conventions
  $req = $bdd->prepare("SELECT
        $sortiesSQL,
        sorties.id_convention id_type,
        conventions_sorties.nom,
        SUM(pesees_sorties.masse) masse
      FROM sorties
      INNER JOIN pesees_sorties
      ON sorties.id = pesees_sorties.id_sortie
      INNER JOIN conventions_sorties
      ON conventions_sorties.id = sorties.id_convention
      WHERE sorties.id_point_sortie = :id_point_sortie
      AND DATE(sorties.timestamp) BETWEEN :du AND :au
      AND sorties.classe = 'sortiesc'
      GROUP BY
      $sortiesSQL,
      sorties.id_convention,
      conventions_sorties.nom
      ORDER BY sorties.timestamp DESC");
  $req->execute(['id_point_sortie' => $numero, 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesConventions = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

  // Sorties Recyclages
  $req = $bdd->prepare("SELECT
        $sortiesSQL,
        sorties.id_filiere id_type,
        filieres_sortie.nom,
        SUM(pesees_sorties.masse) masse
      FROM sorties
      INNER JOIN pesees_sorties
      ON sorties.id = pesees_sorties.id_sortie
      INNER JOIN filieres_sortie
      ON filieres_sortie.id = sorties.id_filiere
      WHERE sorties.id_point_sortie = :id_point_sortie
      AND DATE(sorties.timestamp) BETWEEN :du AND :au
      AND sorties.classe = 'sortiesr'
      GROUP BY
      $sortiesSQL,
      sorties.id_last_hero,
      sorties.id_filiere,
      sorties.id_filiere,
      filieres_sortie.nom
      ORDER BY sorties.timestamp DESC");
  $req->execute(['id_point_sortie' => $numero, 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesRecyclage = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

  // Sorties Poubelles
  $req = $bdd->prepare("SELECT
        $sortiesSQL,
        SUM(pesees_sorties.masse) masse
      FROM sorties
      INNER JOIN pesees_sorties
      ON sorties.id = pesees_sorties.id_sortie
      WHERE sorties.id_point_sortie = :id_point_sortie
      AND DATE(sorties.timestamp) BETWEEN :du AND :au
      AND sorties.classe = 'sortiesp'
      GROUP BY
      $sortiesSQL
      ORDER BY sorties.timestamp DESC");
  $req->execute(['id_point_sortie' => $numero, 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesPoubelles = $req->fetchAll(PDO::FETCH_ASSOC);

  // Sorties dechetteries
  $req->closeCursor();
  $req = $bdd->prepare("SELECT
        $sortiesSQL,
        SUM(pesees_sorties.masse) masse
      FROM sorties
      INNER JOIN pesees_sorties
      ON sorties.id = pesees_sorties.id_sortie
      WHERE sorties.id_point_sortie = :id_point_sortie
      AND DATE(sorties.timestamp) BETWEEN :du AND :au
      AND sorties.classe = 'sortiesd'
      GROUP BY
      $sortiesSQL
      ORDER BY sorties.timestamp DESC");
  $req->execute(['id_point_sortie' => $numero, 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesDechetterie = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();


  $base = [
    'h1' => 'Vérification des sorties hors-boutique',
    'points' => points_sorties($bdd),
    'numero' => $numero,
    'start' => $_GET['date1'],
    'end' => $_GET['date2'],
    'endpoint' => 'verif_sorties',
    'users' => $users
  ];
  require_once 'tete.php';
  ?>
  <div class="container">
    <?= headerVerif($base) ?>
    <?=
    VerifSortiesTable(array_merge($base, [
      'h3' => 'Dons simples :',
      'data' => $sortiesDon,
    ]))
    ?>
    <?=
    VerifSortiesTable(array_merge($base, [
      'h3' => 'Sorties conventionées :',
      'data' => $sortiesConventions,
    ]))
    ?>

    <?=
    // Nom de l'entreprise
    VerifSortiesTable(array_merge($base, [
      'h3' => 'Sorties recyclage:',
      'data' => $sortiesRecyclage,
    ]));
    ?>

    <?=
    VerifSortiesTable(array_merge($base, [
      'h3' => 'Sorties poubelles :',
      'data' => $sortiesPoubelles,
    ]));
    ?>

    <?=
    VerifSortiesTable(array_merge($base, [
      'h3' => 'Sorties déchetterie :',
      'data' => $sortiesDechetterie,
    ]));
    ?>

  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
