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

// Bilan des collectes
session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

function BilanCollectes1(PDO $bdd, int $id, int $typeCollecte, $start, $fin): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
  type_dechets.id,
  type_dechets.nom,
  sum(pesees_collectes.masse) somme
FROM  type_dechets, pesees_collectes, type_collecte, collectes
WHERE pesees_collectes.timestamp BETWEEN :du AND :au
AND   type_dechets.id = pesees_collectes.id_type_dechet
AND   type_collecte.id = collectes.id_type_collecte
AND   pesees_collectes.id_collecte = collectes.id
AND   type_collecte.id = :id_type_collecte ' . $numero . '
GROUP BY type_dechets.id, type_dechets.couleur, type_dechets.nom
ORDER BY somme DESC';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->bindParam(':id_type_collecte', $typeCollecte, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function BilanCollecte3(PDO $bdd, int $id, int $localite, $start, $fin): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = "SELECT
    type_dechets.id,
    type_dechets.couleur,
    type_dechets.nom,
    sum(pesees_collectes.masse) somme
  FROM type_dechets
  INNER JOIN pesees_collectes
  ON type_dechets.id = pesees_collectes.id_type_dechet
  INNER JOIN collectes
  ON pesees_collectes.id_collecte = collectes.id
  AND collectes.localisation = :id_loc
  AND collectes.timestamp BETWEEN :du AND :au $numero
  GROUP BY type_dechets.id, type_dechets.couleur, type_dechets.nom
  ORDER BY somme DESC";
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->bindParam(':id_loc', $localite, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function MorrisCollecteMasse(PDO $bdd, int $id, $start, $end): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = "SELECT
    type_collecte.id,
    type_collecte.couleur,
    type_collecte.nom,
    SUM(pesees_collectes.masse) somme,
    COUNT(DISTINCT collectes.id) ncol
  FROM type_collecte
  INNER JOIN collectes
  ON type_collecte.id = collectes.id_type_collecte
  AND collectes.timestamp BETWEEN :du AND :au $numero
  INNER JOIN pesees_collectes
  ON   pesees_collectes.id_collecte = collectes.id
  GROUP BY type_collecte.id, type_collecte.couleur, type_collecte.nom
  ORDER BY somme DESC";
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $end, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function MorrisCollecteMasseTot(PDO $bdd, int $id, $start, $end): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = "SELECT
    type_dechets.id,
    type_dechets.couleur,
    type_dechets.nom,
    sum(pesees_collectes.masse) somme
  FROM type_dechets
  INNER JOIN pesees_collectes
  ON type_dechets.id = pesees_collectes.id_type_dechet
  INNER JOIN collectes
  ON pesees_collectes.id_collecte = collectes.id
  AND collectes.timestamp BETWEEN :du AND :au $numero
  GROUP BY type_dechets.id, type_dechets.couleur, type_dechets.nom
  ORDER BY somme DESC";
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $end, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

function MorrisCollecteLoca(PDO $bdd, int $id, $start, $end): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = "SELECT
    localites.id localite,
    localites.couleur,
    localites.nom,
    SUM(pesees_collectes.masse) somme,
    COUNT(distinct collectes.id) ncol
  FROM localites
  INNER JOIN collectes
  ON localites.id = collectes.localisation
  INNER JOIN pesees_collectes
  ON pesees_collectes.id_collecte = collectes.id
  AND collectes.timestamp BETWEEN :du AND :au $numero
  GROUP BY localites.id, localites.couleur, localites.nom
  ORDER BY somme DESC";
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $end, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();
  return $result;
}

if (is_valid_session() && is_allowed_bilan()) {
  require_once './tete.php';
  require_once '../moteur/dbconfig.php';

  $date1 = $_GET['date1'];
  $date2 = $_GET['date2'];
  $time_debut = DateTime::createFromFormat('d-m-Y', $date1)->format('Y-m-d') . ' 00:00:00';
  $time_fin = DateTime::createFromFormat('d-m-Y', $date2)->format('Y-m-d') . ' 23:59:59';

  $numero = (int) filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);
  $points_collectes = filter_visibles(points_collectes($bdd));

  $collectes_TypesCollectes = MorrisCollecteMasse($bdd, $numero, $time_debut, $time_fin);
  $collectes_loca = MorrisCollecteLoca($bdd, $numero, $time_debut, $time_fin);
  $collectes_MasseTot = MorrisCollecteMasseTot($bdd, $numero, $time_debut, $time_fin);

  $data = [
    'masse' => array_reduce($collectes_TypesCollectes, function($acc, $e) {
        return $acc + $e['somme'];
      }, 0),
  ];
  ?>

  <div class="container">
    <div class="row">
      <div class="col-md-11">
        <h1>Bilan global</h1>
        <div class="col-md-4 col-md-offset-8" >
          <?= datePicker() ?>
        </div>

        <ul class="nav nav-tabs">
          <li class="active"><a>Collectes</a></li>
          <li><a href="bilanhb.php?numero=0&date1=<?= $date1 ?>&date2=<?= $date2 ?>">Sorties hors-boutique</a></li>
          <li><a href="bilanv.php?numero=0&date1=<?= $date1 ?>&date2=<?= $date2 ?>">Ventes</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 col-md-offset-1" >
      <h2> Bilan des collectes de la structure</h2>
      <ul class="nav nav-tabs">
        <?php foreach ($points_collectes as $p) { ?>
          <li class="<?= $numero == $p['id'] ? 'active' : '' ?>">
            <a href="bilanc.php?numero=<?= $p['id'] ?>&date1=<?= $date1 ?>&date2=<?= $date2; ?>"><?= $p['nom']; ?></a>
          </li>
        <?php } ?>
        <li class="<?= $numero === 0 ? 'active' : '' ?>">
          <a href="bilanc.php?numero=0&date1=<?= $date1 ?>&date2=<?= $date2; ?>">Tous les points</a>
        </li>
      </ul>
      <br>

      <div class="row">
        <h2><?= $date1 === $date2 ? "Le {$date1}," : " Du {$date1} au {$date2}," ?>
          Masse collectée: <?= $data['masse'] ?> kg<?= $numero === 0 ? ' , sur ' . count($points_collectes) . ' Point(s) de collecte' : '' ?>.</h2>
        <?php if ($data['masse'] > 0) { ?>
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Répartition par types d'objets</h3>
            </div>

            <div class="panel-body">
              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                  <tr>
                    <th style="width:300px">type d'objet</th>
                    <th>Masse collectée</th>
                    <th>%</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($collectes_MasseTot as $a) { ?>
                    <tr data-toggle="collapse" data-target=".partyp<?= $a['nom']; ?>">
                      <td><a href="jours.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&type=<?= $a['id'] ?>"><?= $a['nom'] ?></a></td>
                      <td><?= $a['somme']; ?></td>
                      <td><?= round($a['somme'] * 100 / $data['masse'], 2); ?></td>
                    </tr>

                  <?php } ?>
                </tbody>
              </table>

              <div id="graph2masse" style="height: 180px;"></div> <br><br>

              <!-- TODO: refaire cette fonctionnalité
              <a href="../moteur/export_bilanc_parloca.php?numero=<?= $numero ?>&date1=<?= $date1 ?>&date2=<?= $date2; ?>">
                <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
              </a>
              -->
            </div>
          </div>
      </div>
         <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Répartition par types de collectes</h3>
              </div>

              <div class="panel-body">
                <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                  <thead>
                    <tr>
                      <th style="width:300px">Type de collecte</th>
                      <th>Nbr.de collectes</th>
                      <th>Masse collectée</th>
                      <th>%</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($collectes_TypesCollectes as $p) { ?>
                      <tr data-toggle="collapse" data-target=".parmasse<?= $p['id']; ?>">
                        <td><?= $p['nom']; ?></td>
                        <td><?= $p['ncol']; ?></td>
                        <td><?= $p['somme']; ?></td>
                        <td><?= round($p['somme'] * 100 / $data['masse'], 2); ?></td>
                      </tr>
                      <?php foreach (BilanCollectes1($bdd, $numero, $p['id'], $time_debut, $time_fin) as $d) { ?>
                        <tr class="collapse parmasse<?= $p['id']; ?>">
                          <td>
                            <a href="jours.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&type=<?= $d['id'] ?>"><?= $d['nom']; ?></a>
                          </td>
                          <td></td>
                          <td><?= $d['somme'] ?> kg</td>
                          <td><?= round($d['somme'] * 100 / $data['masse'], 2) ?> %</td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                </table>

                <div id="graphmasse" style="height: 180px;"></div>

                <!-- TODO: refaire cette fonctionnalité
                <a href="../moteur/export_bilanc_partype.php?numero=<?= $numero ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>">
                  <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv)</button>
                </a>
                -->
              </div>
            </div>
          </div>
       <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Répartition par localité</h3>
              </div>

              <div class="panel-body">
                <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                  <thead>
                    <tr>
                      <th style="width:300px">Localité</th>
                      <th>Nbr.de collectes</th>
                      <th>Masse collectée</th>
                      <th>%</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($collectes_loca as $a) { ?>
                      <tr data-toggle="collapse" data-target=".parloc<?= $a['localite']; ?>">
                        <td><?= $a['nom']; ?></td>
                        <td><?= $a['ncol']; ?></td>
                        <td><?= $a['somme']; ?></td>
                        <td><?= round($a['somme'] * 100 / $data['masse'], 2); ?></td>
                      </tr>
                      <?php foreach (BilanCollecte3($bdd, $numero, $a['localite'], $time_debut, $time_fin) as $b) { ?>
                        <tr class="collapse parloc<?= $a['localite'] ?>">
                          <td class="hiddenRow">
                            <a href="jours.php?date1=<?= $date1 ?>&date2=<?= $date2 ?>&type=<?= $b['id'] ?>"><?= $b['nom'] ?></a>
                          </td>
                          <td></td>
                          <td class="hiddenRow"><?= $b['somme'] ?> kg</td>
                          <td class="hiddenRow"><?= round($b['somme'] * 100 / $data['masse'], 2) ?> %</td>
                        </tr>
                      <?php } ?>
                    <?php } ?>
                  </tbody>
                </table>

                <div id="graphloca" style="height: 180px;"></div>

                <!-- TODO: refaire cette fonctionnalité
                <a href="../moteur/export_bilanc_parloca.php?numero=<?= $numero ?>&date1=<?= $date1 ?>&date2=<?= $date2; ?>">
                  <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
                </a>
                -->
              </div>
            </div>
          </div>
    </div>
    <?php } else { ?>
      <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
    <?php } ?>
  </div>
</div>
  <script type="text/javascript">
    'use strict';

    $(document).ready(() => {
      graphMorris(<?= json_encode(data_graphs($collectes_TypesCollectes)) ?>, 'graphmasse');
      graphMorris(<?= json_encode(data_graphs($collectes_MasseTot)) ?>, 'graph2masse');
      graphMorris(<?= json_encode(data_graphs($collectes_loca)) ?>, 'graphloca');
    });
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
