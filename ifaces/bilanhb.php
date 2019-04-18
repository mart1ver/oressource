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

// Bilan des sorties hors boutique
session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

function sortie_type(string $type): string {
  switch ($type) {
    case 'sortiesc';
      return 'don aux partenaires';
    case 'sorties';
      return 'don';
    case 'sortiesd';
      return 'dechetterie';
    case 'sortiesp';
      return 'poubelles';
    case 'sortiesr';
      return 'recycleurs';
    default;
      return 'base érronée';
  }
}

function bilansSortiesRepartition(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND sorties.id_point_sortie = $id " : ' ');
  $sql = 'SELECT
    SUM(pesees_sorties.masse) somme,
    sorties.classe,
    COUNT(distinct sorties.id) ncol
  FROM
    pesees_sorties, sorties
  WHERE
    pesees_sorties.timestamp BETWEEN :du AND :au
  AND pesees_sorties.id_sortie = sorties.id ' . $numero . '
  GROUP BY sorties.classe';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function _bilansSortiesPrepare(PDO $bdd, int $id, $start, $fin, string $type): PDOStatement {
  $numero = ($id > 0 ? " AND sorties.id_point_sortie = $id " : ' ');
  $sql = 'SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE type_dechets.id = pesees_sorties.id_type_dechet
AND   pesees_sorties.id_sortie = sorties.id
AND   sorties.classe = :type0
AND   pesees_sorties.timestamp BETWEEN :du0 AND :au0 ' . $numero . '
GROUP BY type_dechets.id, type_dechets.nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE type_dechets_evac.id = pesees_sorties.id_type_dechet_evac
AND   pesees_sorties.id_sortie = sorties.id
AND   sorties.classe = :type1
AND   pesees_sorties.timestamp BETWEEN :du1 AND :au1 ' . $numero . '
GROUP BY type_dechets_evac.nom';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':type0', $type, PDO::PARAM_STR);
  $stmt->bindParam(':type1', $type, PDO::PARAM_STR);
  $stmt->bindParam(':du0', $start, PDO::PARAM_STR);
  $stmt->bindParam(':du1', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au0', $fin, PDO::PARAM_STR);
  $stmt->bindParam(':au1', $fin, PDO::PARAM_STR);
  return $stmt;
}

function poubelles(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND sorties.id_point_sortie = $id " : ' ');
  $sql = '
  SELECT types_poubelles.nom, sum(pesees_sorties.masse) somme
FROM types_poubelles, pesees_sorties, sorties
WHERE
pesees_sorties.id_sortie = sorties.id
AND
types_poubelles.id = pesees_sorties.id_type_poubelle
AND sorties.classe = "sortiesp"
AND pesees_sorties.timestamp BETWEEN :du AND :au ' . $numero . '
GROUP BY types_poubelles.nom';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function donsSimples(PDO $bdd, int $id, $start, $fin): array {
  $stmt = _bilansSortiesPrepare($bdd, $id, $start, $fin, 'sorties');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function recycleurs(PDO $bdd, int $id, $start, $fin): array {
  $stmt = _bilansSortiesPrepare($bdd, $id, $start, $fin, 'sortiesr');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function partenaires(PDO $bdd, int $id, $start, $fin): array {
  $stmt = _bilansSortiesPrepare($bdd, $id, $start, $fin, 'sortiesc');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function dechetteries(PDO $bdd, int $id, $start, $fin): array {
  $stmt = _bilansSortiesPrepare($bdd, $id, $start, $fin, 'sortiesd');
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function masse_evacue(PDO $bdd, int $id, $start, $fin): float {
  $numero = ($id > 0 ? " AND sorties.id_point_sortie = $id " : ' ');
  $sql = 'SELECT
    COALESCE(SUM(pesees_sorties.masse), 0) total
    FROM pesees_sorties, sorties
    WHERE pesees_sorties.id_sortie = sorties.id
    AND pesees_sorties.timestamp BETWEEN :du AND :au ' . $numero;
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  $donnees = $stmt->fetch();
  $masse = $donnees['total'];
  $stmt->closeCursor();
  return $masse;
}

function bilanSortiesDon(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND sorties.id_point_sortie = $id " : ' ');
  $sql = 'SELECT type_sortie.nom, sum(pesees_sorties.masse) somme
FROM type_sortie, pesees_sorties, sorties
WHERE type_sortie.id = sorties.id_type_sortie
AND pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sorties"
AND pesees_sorties.timestamp BETWEEN :du AND :au ' . $numero . '
GROUP BY type_sortie.nom';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function bilanSortiesConvention(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND sorties.id_point_sortie = $id " : ' ');
  $sql = 'SELECT conventions_sorties.nom, sum(pesees_sorties.masse) somme, COUNT(sorties.id) nombre
FROM conventions_sorties, pesees_sorties, sorties
WHERE conventions_sorties.id=sorties.id_convention
AND pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesc"
AND pesees_sorties.timestamp BETWEEN :du AND :au ' . $numero . '
GROUP BY conventions_sorties.nom';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function bilanSortiesRecycleur(PDO $bdd, int $id, $start, $fin): array {
  $numero = ($id > 0 ? " AND sorties.id_point_sortie = $id " : ' ');
  $sql = 'SELECT filieres_sortie.nom, sum(pesees_sorties.masse) somme
FROM filieres_sortie, pesees_sorties, sorties
WHERE
filieres_sortie.id=sorties.id_filiere
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesr"
AND pesees_sorties.timestamp BETWEEN :du AND :au ' . $numero . '
GROUP BY filieres_sortie.nom';
  $stmt = $bdd->prepare($sql);
  $stmt->bindParam(':du', $start, PDO::PARAM_STR);
  $stmt->bindParam(':au', $fin, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function strategie(PDO $bdd, int $id, $start, $fin) {
  return [
    'donsSimples' => donsSimples($bdd, $id, $start, $fin),
    'recycleurs' => recycleurs($bdd, $id, $start, $fin),
    'partenaires' => partenaires($bdd, $id, $start, $fin),
    'dechetteries' => dechetteries($bdd, $id, $start, $fin),
    'poubelles' => poubelles($bdd, $id, $start, $fin),
    'masse' => masse_evacue($bdd, $id, $start, $fin),
    'repartitions' => bilansSortiesRepartition($bdd, $id, $start, $fin),
  ];
}


if (is_valid_session() && is_allowed_bilan()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  $numero = (int) filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);
  $points_sortie = filter_visibles(points_sorties($bdd));


  $date1 = $_GET['date1'];
  $date2 = $_GET['date2'];
  $time_debut = DateTime::createFromFormat('d-m-Y', $date1)->format('Y-m-d') . ' 00:00:00';
  $time_fin = DateTime::createFromFormat('d-m-Y', $date2)->format('Y-m-d') . ' 23:59:59';
  $data = strategie($bdd, $numero, $time_debut, $time_fin);
  ?>

  <div class="container">
    <div class="row">
      <div class="col-md-11 " >
        <h1>Bilan global</h1>
        <div class="col-md-4 col-md-offset-8" >
          <?= datePicker() ?>
        </div>
        <ul class="nav nav-tabs">
          <li><a href="bilanc.php?numero=0&date1=<?= $date1 ?>&date2=<?= $date2 ?>">Collectes</a></li>
          <li class="active"><a>Sorties hors-boutique</a></li>
          <li><a href="bilanv.php?numero=0&date1=<?= $date1 ?>&date2=<?= $date2 ?>">Ventes</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 col-md-offset-1">
      <h2> Bilan des sorties hors-boutique de la structure</h2>
      <ul class="nav nav-tabs">
        <?php foreach ($points_sortie as $p) { ?>
          <li class="<?= $numero == $p['id'] ? 'active' : '' ?>">
            <a href="bilanhb.php?numero=<?= $p['id'] ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>"> <?= $p['nom']; ?></a>
          </li>
        <?php } ?>
        <li class="<?= $numero === 0 ? 'active' : '' ?>">
          <a href="bilanhb.php?numero=0&date1=<?= $date1 ?>&date2=<?= $date2 ?>">Tous les points</a>
        </li>
      </ul>
      <br>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 col-md-offset-1">
      <h2><?= $date1 === $date2 ? " Le {$date1}," : " Du {$date1} au {$date2}," ?>
        masse totale évacuée: <?= $data['masse'] ?>kg<?= $numero === 0 ? ' sur ' . count($points_sortie) . ' Point(s) de sorties.' : '.' ?></h2>
          <?php if($data['masse'] > 0){ ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-5 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Répartition par classe de sorties</h3>
        </div>

        <div class="panel-body">
          <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
            <thead>
              <tr>
                <th style="width:300px">Classe:</th>
                <th>Nbr.de bons de sortie</th>
                <th>Masse évacuée</th>
                <th>%</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data['repartitions'] as $rep) { ?>
                <tr data-toggle="collapse" data-target=".parmasse<?= $rep['classe'] ?>">
                  <td><?= sortie_type($rep['classe']) ?></td>
                  <td><?= $rep['ncol'] ?></td>
                  <td><?= $rep['somme'] ?></td>
                  <td><?= round($rep['somme'] * 100 / $data['masse'], 2); ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <br>
          <!--
          <a href="../moteur/export_bilanc_partype.php?numero=<?= $numero ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>">
            <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
          </a>
          -->
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Détail par type d'objets</h3>
        </div>
        <div class="panel-body">
          <?= count($data['donsSimples']) ? bilanTable3(['id' => 0, 'text' => 'Dons simples', 'td0' => 'type objet', 'td1' => 'somme', 'td2' => '%', 'masse' => $data['masse'], 'data' => $data['donsSimples']]) : '' ?>
          <?= count($data['partenaires']) ? bilanTable3(['id' => 1, 'text' => 'Dons aux partenaires', 'td0' => 'type objet', 'td1' => 'somme', 'td2' => '%', 'masse' => $data['masse'], 'data' => $data['partenaires']]) : '' ?>
          <?= count($data['dechetteries']) ? bilanTable3(['id' => 2, 'text' => 'Dechetterie', 'td0' => 'type objet', 'td1' => 'somme', 'td2' => '%', 'masse' => $data['masse'], 'data' => $data['dechetteries']]) : '' ?>
          <?= count($data['poubelles']) ? bilanTable3(['id' => 3, 'text' => 'Poubelles', 'td0' => 'type objet', 'td1' => 'somme', 'td2' => '%', 'masse' => $data['masse'], 'data' => $data['poubelles']]) : '' ?>
          <?= count($data['recycleurs']) ? bilanTable3(['id' => 4, 'text' => 'Recycleurs', 'td0' => 'type objet', 'td1' => 'somme', 'td2' => '%', 'masse' => $data['masse'], 'data' => $data['recycleurs']]) : '' ?>
          <!--
          <a href="../moteur/export_bilanc_parloca.php?numero=<?= $numero ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>">
            <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv)</button>
          </a>
          -->
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Partenaires, recycleurs et dons</h3>
        </div>

        <div class="panel-body">
          <?= bilanTable3Hover(['text' => 'Dons simples', 'td0' => 'Type de sortie', 'td1' => 'masse', 'td2' => '%', 'masse' => $data['masse'], 'data' => bilanSortiesDon($bdd, $numero, $time_debut, $time_fin)]) ?>
          <?= bilanTable3Hover(['text' => 'Recycleurs', 'td0' => 'Nom du recycleur', 'td1' => 'masse', 'td2' => '%', 'masse' => $data['masse'], 'data' => bilanSortiesRecycleur($bdd, $numero, $time_debut, $time_fin)]) ?>

          <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
            <thead>
            <th style="width:300px">Dons aux partenaires</th>
            <tr>
              <th style="width:300px">Nom du partenaire</th>
              <th>Nbr. de sorties</th>
              <th>masse</th>
              <th>%</th>
            </tr>
            </thead>
            <tbody>
              <?php foreach (bilanSortiesConvention($bdd, $numero, $time_debut, $time_fin) as $p) { ?>
                <tr>
                  <td><?= $p['nom']; ?></td>
                  <td><?= $p['nombre']; ?></td>
                  <td><?= $p['somme']; ?></td>
                  <td><?= round($p['somme'] * 100 / $data['masse'], 2); ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

          <br>
          <!--
          <a href="../moteur/export_bilanc_partype.php?numero=<?= $numero ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>">
            <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
          </a>
          -->
        </div>
      </div>
          <?php }else{echo '<img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">';} ?>
    </div>
  </div>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
