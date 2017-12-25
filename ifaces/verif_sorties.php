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
            <th></th>
            <th>Modifié par</th>
            <th>Le</th>
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
              <td>
                <form action="modification_verification_sorties<?= $props['classe'] ?>.php?nsortie=<?= $d['id']; ?>" method="post">
                  <input type="hidden" name ="id" id="id" value="<?= $d['id']; ?>">
                  <input type="hidden" name ="nom" id="nom" value="<?= $d['nom']; ?>">
                  <input type="hidden" name ="date1" id="date1" value="<?= $props['start']; ?>">
                  <input type="hidden" name ="date2" id="date2" value="<?= $props['end']; ?>">
                  <input type="hidden" name ="npoint" id="npoint" value="<?= $props['numero']; ?>">
                  <button  class="btn btn-warning btn-sm" >Modifier</button>
                </form>
              </td>
              <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $users[$d['id_last_hero']]['mail'] : '' ?></td>
              <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $d['last_hero_timestamp'] : '' ?></td>
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
  require_once('../moteur/dbconfig.php');
  require_once '../core/requetes.php';
  require_once '../core/composants.php';

  $users = array_reduce(utilisateurs($bdd), function ($acc, $e) {
    $acc[$e['id']] = $e;
    return $acc;
  }, []);

  $time_debut = DateTime::createFromFormat('d-m-Y', $_GET['date1'])->format('Y-m-d') . ' 00:00:00';
  $time_fin = DateTime::createFromFormat('d-m-Y', $_GET['date2'])->format('Y-m-d') . ' 23:59:59';

  $base = [
    'numero' => $_GET['numero'],
    'start' => $_GET['date1'],
    'end' => $_GET['date2'],
    'endpoint' => 'verif_collecte',
    'users' => $users,
    'th1' => 'Type de collecte',
    'th3' => 'Localité',
    'th4' => 'Masse totale',
    'users' => $users,
    'start' => $_GET['date2'],
    'end' => $_GET['date1']
  ];
  
  $sortiesSQL = 'sorties.id,
        sorties.commentaire,
        sorties.timestamp,
        sorties.id_createur,
        sorties.id_last_hero,
        sorties.last_hero_timestamp';
  // FIXME: On à jamais utilisé les localités dans les sorties dons #Oups.
  $req = $bdd->prepare("SELECT
        $sortiesSQL,
        type_sortie.nom,
  --      localites.nom localisation,
  --      localites.id localite,
        SUM(pesees_sorties.masse) masse
      FROM sorties
      INNER JOIN pesees_sorties
      ON sorties.id = pesees_sorties.id_sortie
      INNER JOIN type_sortie
      ON type_sortie.id = sorties.id_type_sortie
  --    INNER JOIN localites
  --    ON localites.id = sorties.localisation
      WHERE sorties.id_point_sortie = :id_point_sortie
      AND DATE(sorties.timestamp) BETWEEN :du AND :au
      GROUP BY
      $sortiesSQL,
  --  localites.nom,
  --  localites.id
      type_sortie.nom");
  $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesDon = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

  $req = $bdd->prepare("SELECT
        $sortiesSQL,
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
      conventions_sorties.nom
      ORDER BY sorties.timestamp DESC");
  $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesConventions = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

  $req = $bdd->prepare("SELECT
        $sortiesSQL,
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
      filieres_sortie.nom
      ORDER BY sorties.timestamp DESC");
  $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesRecyclage = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

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
  $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesPoubelles = $req->fetchAll(PDO::FETCH_ASSOC);

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
  $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);
  $sortiesDechetterie = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();


  $base = [
    'h1' => 'Vérification des sorties hors-boutique',
    'points' => points_sorties($bdd),
    'numero' => $_GET['numero'],
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
      'classe' => ''
    ]))
    ?>
    <?=
    VerifSortiesTable(array_merge($base, [
      'h3' => 'Sorties conventionées :',
      'data' => $sortiesConventions,
      'classe' => 'c'
    ]))
    ?>

    <?=
    // Nom de l'entreprise
    VerifSortiesTable(array_merge($base, [
      'h3' => 'Sorties recyclage:',
      'data' => $sortiesRecyclage,
      'classe' => 'r'
    ]));
    ?>

    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Sorties poubelles :</h3>
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Date de création</th>
              <th>Masse totale</th>
              <th>Auteur de la ligne</th>
              <th></th>
              <th>Modifié par</th>
              <th>Le</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($sortiesPoubelles as $d) { ?>
              <tr>
                <td><?= $d['id']; ?></td>
                <td><?= $d['timestamp']; ?></td>
                <td><?= $d['masse']; ?></td>
                <td><?= $users[$d['id_createur']]['mail']; ?></td>
                <td>
                  <form action="modification_verification_sortiesp.php?nsortie=<?= $d['id']; ?>" method="post">
                    <input type="hidden" name="id" id="id" value="<?= $d['id']; ?>">
                    <input type="hidden" name="date1" id="date1" value="<?= $base['start']; ?>">
                    <input type="hidden" name="date2" id="date2" value="<?= $base['end']; ?>">
                    <input type="hidden" name="npoint" id="npoint" value="<?= $base['numero']; ?>">
                    <button  class="btn btn-warning btn-sm">Modifier</button>
                  </form>
                </td>
                <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $users[$d['id_last_hero']]['mail'] : '' ?></td>
                <td><?= $d['last_hero_timestamp'] !== $d['timestamp'] ? $d['last_hero_timestamp'] : '' ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Sorties déchetterie:</h3>
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Date de création</th>
              <th>Commentaire</th>
              <th>Masse totale</th>
              <th>Auteur de la ligne</th>
              <th></th>
              <th>Modifié par</th>
              <th>Le</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($sortiesPoubelles as $b) { ?>
              <tr>
                <td><?= $b['id']; ?></td>
                <td><?= $b['timestamp']; ?></td>
                <td><?= $b['timestamp']; ?></td>
                <td><?= $b['masse']; ?></td>
                <td><?= $users[$b['id_createur']]['mail']; ?></td>
                <td>
                  <form action="modification_verification_sortiesd.php?nsortie=<?= $b['id']; ?>" method="post">
                    <input type="hidden" name="id" id="id" value="<?= $b['id']; ?>">
                    <input type="hidden" name="date1" id="date1" value="<?= $base['start']; ?>">
                    <input type="hidden" name="date2" id="date2" value="<?= $base['end']; ?>">
                    <input type="hidden" name="npoint" id="npoint" value="<?= $base['numero']; ?>">
                    <button class="btn btn-warning btn-sm">Modifier</button>
                  </form>
                </td>
                <td><?= $b['last_hero_timestamp'] !== $b['timestamp'] ? $users[$b['id_last_hero']]['mail'] : '' ?></td>
                <td><?= $b['last_hero_timestamp'] !== $b['timestamp'] ? $b['last_hero_timestamp'] : '' ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- /.container -->
  <script type="text/javascript">
    'use strict';
    $(document).ready(() => {
      const query = process_get();
      const base = 'verif_sorties.php';
      const options = set_datepicker(query);
      bind_datepicker(options, {base, query});
    });
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
