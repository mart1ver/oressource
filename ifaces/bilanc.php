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

function bilanCollecteMasse(PDO $bdd, int $id, $start, $fin): float {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT SUM(pesees_collectes.masse) total
FROM pesees_collectes, collectes
WHERE pesees_collectes.id_collecte = collectes.id
AND pesees_collectes.timestamp BETWEEN :du AND :au ' . $numero;
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  $masse = $stmt->fetch()['total'] ?? 0;
  $stmt->closeCursor();
  return $masse;
}

function bilansCollectes0(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
type_collecte.nom,
SUM(pesees_collectes.masse) somme,
pesees_collectes.timestamp,
type_collecte.id,
COUNT(distinct collectes.id) ncol
FROM pesees_collectes,collectes, type_collecte
WHERE pesees_collectes.timestamp BETWEEN :du AND :au
AND   type_collecte.id = collectes.id_type_collecte
AND   pesees_collectes.id_collecte = collectes.id ' . $numero . '
GROUP BY collectes.id_type_collecte';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function bilanCollecte2(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
  localites.nom,
  SUM(pesees_collectes.masse) somme,
  pesees_collectes.timestamp,
  localites.id localite,
  COUNT(distinct collectes.id) ncol
FROM  pesees_collectes, collectes, localites
WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au
  AND localites.id = collectes.localisation
  AND pesees_collectes.id_collecte = collectes.id' . $numero . '
GROUP BY localites.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function BilanCollectes1(PDO $bdd, int $id, int $typeCollecte, $start, $fin): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
  type_dechets.id,
  type_dechets.couleur,
  type_dechets.nom,
sum(pesees_collectes.masse) somme
FROM  type_dechets, pesees_collectes, type_collecte, collectes
WHERE pesees_collectes.timestamp BETWEEN :du AND :au
AND   type_dechets.id = pesees_collectes.id_type_dechet
AND   type_collecte.id = collectes.id_type_collecte
AND   pesees_collectes.id_collecte = collectes.id
AND   type_collecte.id = :id_type_collecte ' . $numero . '
GROUP BY type_dechets.id
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
  $sql = 'SELECT
  type_dechets.id id,
  type_dechets.couleur,
  type_dechets.nom,
  sum(pesees_collectes.masse) somme
 FROM type_dechets, pesees_collectes,localites, collectes
WHERE pesees_collectes.timestamp BETWEEN :du AND :au
AND   type_dechets.id = pesees_collectes.id_type_dechet
AND   localites.id = collectes.localisation
AND   pesees_collectes.id_collecte = collectes.id
AND   localites.id = :id_loc ' . $numero . '
GROUP BY type_dechets.id
ORDER BY somme DESC';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->bindParam(':id_loc', $localite, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function bilanCollecte4(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
  type_dechets.id id,
  type_dechets.nom nom,
  SUM(pesees_collectes.masse) somme,
  pesees_collectes.timestamp
FROM pesees_collectes, collectes, type_dechets
WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au
  AND type_dechets.id = pesees_collectes.id_type_dechet
  AND pesees_collectes.id_collecte = collectes.id' . $numero . '
 GROUP By type_dechets.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function MorrisCollecteMasse(PDO $bdd, int $id, $start, $end): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
        type_collecte.couleur,
        type_collecte.nom,
        sum(pesees_collectes.masse) somme
FROM type_collecte, pesees_collectes, collectes
WHERE type_collecte.id = collectes.id_type_collecte
AND   pesees_collectes.id_collecte = collectes.id
AND   DATE(collectes.timestamp) BETWEEN :du AND :au ' . $numero . '
GROUP BY type_collecte.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $end, PDO::PARAM_STR);
  return data_graphs($stmt);
}

function MorrisCollecteMasseTot(PDO $bdd, int $id, $start, $end): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
    type_dechets.couleur,
    type_dechets.nom,
    sum(pesees_collectes.masse) somme
    FROM type_dechets, pesees_collectes, collectes
    WHERE type_dechets.id = pesees_collectes.id_type_dechet
    AND   pesees_collectes.id_collecte = collectes.id
    AND   DATE(collectes.timestamp) BETWEEN :du AND :au ' . $numero . '
    GROUP BY type_dechets.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $end, PDO::PARAM_STR);
  return data_graphs($stmt);
}

function MorrisCollecteLoca(PDO $bdd, int $id, $start, $end): array {
  $numero = ($id > 0 ? " AND collectes.id_point_collecte = $id " : ' ');
  $sql = 'SELECT
        localites.couleur,
        localites.nom,
        SUM(DISTINCT pesees_collectes.masse) somme
FROM type_collecte, pesees_collectes, collectes, localites
WHERE localites.id = collectes.localisation
AND   type_collecte.id = collectes.id_type_collecte
AND   pesees_collectes.id_collecte = collectes.id
AND   DATE(collectes.timestamp) BETWEEN :du AND :au ' . $numero . '
GROUP BY localites.id';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $end, PDO::PARAM_STR);
  return data_graphs($stmt);
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
  $data = [
    'masse' => bilanCollecteMasse($bdd, $numero, $time_debut, $time_fin),
    ]
  ?>

  <div class="container">
    <div class="row">
      <div class="col-md-11">
        <h1>Bilan global</h1>
        <div class="col-md-4 col-md-offset-8" >
          <label for="reportrange">Choisissez la période à inspecter:</label>
          <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
            <i class="fa fa-calendar"></i>
            <span></span> <b class="caret"></b>
          </div>
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
        <li <?php if($numero == $p['id']){echo"class='active'";}  ?> >
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
        <?php if($data['masse'] > 0){ ?>
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
                  <?php foreach (bilansCollectes0($bdd, $numero, $time_debut, $time_fin) as $p) { ?>
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

              <!-- TODO: faire une tableau pour la répartion par type d'objets/collecté -->
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
                  <?php foreach (BilanCollecte2($bdd, $numero, $time_debut, $time_fin) as $a) { ?>
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
                  <?php foreach (BilanCollecte4($bdd, $numero, $time_debut, $time_fin) as $a) { ?>
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
      <?php }else{echo '<img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">';} ?>
      </div>

    </div>
  </div>

  <script>
    const graphmass = <?= json_encode(MorrisCollecteMasse($bdd, $numero, $time_debut, $time_fin)) ?>;
    Morris.Donut({
      element: 'graphmasse',
      data: graphmass.data,
      backgroundColor: '#ccc',
      labelColor: '#060',
      colors: graphmass.colors,
      formatter: (x) => `${x} Kg.`
    });
  </script>

  <script>
    const graph2masse = <?= json_encode(MorrisCollecteMasseTot($bdd, $numero, $time_debut, $time_fin)) ?>;
    Morris.Donut({
      element: 'graph2masse',
      data: graph2masse.data,
      backgroundColor: '#ccc',
      labelColor: '#060',
      colors: graph2masse.colors,
      formatter: (x) => `${x} Kg.`
    });
  </script>

  <script>
    const graphloca = <?= json_encode(MorrisCollecteLoca($bdd, $numero, $time_debut, $time_fin)) ?>;
    Morris.Donut({
      element: 'graphloca',
      data: graphloca.data,
      backgroundColor: '#ccc',
      labelColor: '#060',
      colors: graphloca.colors,
      formatter: (x) => `${x} Kg.`
    });
  </script>

  <script type="text/javascript">
    'use strict';
    $(document).ready(() => {
      const query = process_get();
      const base = 'bilanc.php';
      const options = set_datepicker(query);
      bind_datepicker(options, { base, query });
    });
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
