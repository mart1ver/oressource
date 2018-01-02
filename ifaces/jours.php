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

function _generic_histo(PDO $bdd, string $sql, int $type, string $debut, string $fin): array {
  $stmt = $bdd->prepare($sql);
  $stmt->execute(['du' => $debut, 'au' => $fin, 'type' => $type]);
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $interm = 0;
  $cmpt = 0;

  foreach ($data as $donnees) {
    $interm += $donnees['nombre'];
    $cmpt += 1;
  }
  $stmt->closeCursor();

  $result = [
    'interm' => $interm,
    'cmpt' => $cmpt
  ];
  return $result;
}

function _pesees_colletes(PDO $bdd, int $type, string $debut, string $fin): array {
  $sql = 'SELECT SUM(masse) nombre, DATE_FORMAT(timestamp, "%Y-%m-%d") time
        FROM pesees_collectes
        WHERE DATE(pesees_collectes.timestamp) BETWEEN :du AND :au
        AND pesees_collectes.id_type_dechet = :type
        GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
        ORDER BY time';
  return _generic_histo($bdd, $sql, $type, $debut, $fin);
}

function _pesees_sorties(PDO $bdd, int $type, string $debut, string $fin): array {
  $sql = 'SELECT SUM(masse) nombre, DATE_FORMAT(timestamp, "%Y-%m-%d") time
        FROM pesees_sorties
        WHERE DATE(pesees_sorties.timestamp) BETWEEN :du AND :au
        AND pesees_sorties.id_type_dechet = :type
        GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
        ORDER BY time';
  return _generic_histo($bdd, $sql, $type, $debut, $fin);
}

function _quantit_vendue(PDO $bdd, int $type, string $debut, string $fin): array {
  $sql = 'SELECT SUM(quantite) nombre, DATE_FORMAT(timestamp, "%Y-%m-%d") time
          FROM vendus
          WHERE DATE(vendus.timestamp) BETWEEN :du AND :au
          AND vendus.id_type_dechet = :type
          GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
          ORDER BY time';
  return _generic_histo($bdd, $sql, $type, $debut, $fin);
}

function _CA(PDO $bdd, int $type, string $debut, string $fin): array {
  $sql = 'SELECT SUM(quantite*prix) nombre, DATE_FORMAT(timestamp, "%Y-%m-%d") time
          FROM vendus
          WHERE DATE(vendus.timestamp) BETWEEN :du AND :au
          AND vendus.id_type_dechet = :type
          GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
          ORDER BY time';
  return _generic_histo($bdd, $sql, $type, $debut, $fin);
}

if (is_valid_session() && is_allowed_bilan()) {
  require_once '../moteur/dbconfig.php';

  $types_dechets = types_dechets($bdd);
  $type_selected = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);
  $type = array_values(array_filter($types_dechets, function ($e) use ($type_selected) {
    return $type_selected === $e['id'];
  }))[0];

  $nom = $type['nom'];
  $couleur = $type['couleur'];

  $date1 = isset($_GET['date1']) ? DateTime::createFromFormat('d-m-Y', $_GET['date1']) : new DateTime();
  $time_debut = $date1->format('Y-m-d') . " 00:00:00";
  $start = $date1->format('d-m-Y');

  $date2 = isset($_GET['date2']) ? DateTime::createFromFormat('d-m-Y', $_GET['date2']) : new DateTime();
  $time_fin = $date2->format('Y-m-d') . " 00:00:00";
  $end = $date2->format('d-m-Y');
  require_once 'tete.php';
  ?>

  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <?= datePicker() ?>
      </div>

      <div class="col-md-4">
        <label>Choisissez le type d'objet:</label>
        <br>
        <select name="select" onchange="location = this.value; return false;">
          <?php foreach ($types_dechets as $type) { ?>
            <option value="jours.php?date1=<?= $start ?>&date2=<?= $end ?>&type=<?= $type['id'] ?>"
                    <?= ($type['id'] === $type_selected) ? 'selected' : ''; ?>><?= $type['nom'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <h2><?= $start === $end ? "Le $start " : "Du $start au $end" ?></h2>

    <?php
    $histoCol = _pesees_colletes($bdd, $type_selected, $time_debut, $time_fin);
    $masse_moy_jour = ($histoCol['interm'] === 0) ? 0 : round($histoCol['interm'] / $histoCol['cmpt'], 2);

    if ($masse_moy_jour !== 0) {
      echo '<h3>Évolution de la masse totale collectée au ' . $nom . '.</h3> Moyenne journalière: ' . $masse_moy_jour;
      ?> Kgs.
      <div id="collectes" style="height: 180px;"></div>
      <?php
    }

    $histoSor = _pesees_sorties($bdd, $type_selected, $time_debut, $time_fin);
    $masse_moy_jour = ($histoSor['interm'] === 0) ? 0 : round($histoSor['interm'] / $histoSor['cmpt'], 2);

    if ($masse_moy_jour !== 0) {
      echo '<h3>Évolution des masses totales évacuées hors boutique: ' . $nom . '.</h3> Moyenne journalière: ' . $masse_moy_jour;
      ?> Kgs.
      <div id="sorties" style="height: 180px;"></div>
      <?php
    }

    $histoQV = _quantit_vendue($bdd, $type_selected, $time_debut, $time_fin);
    $QV_moy_jour = ($histoQV['interm'] === 0) ? 0 : round($histoQV['interm'] / $histoQV['cmpt'], 2);

    if ($QV_moy_jour !== 0) {
      echo '<h3>Évolution des quantités: ' . $nom . '.</h3> Moyenne journalière: ' . $QV_moy_jour;
      ?> Kgs.
      <div id="qv" style="height: 180px;"></div>
      <?php
    }

    $histoCA = _CA($bdd, $type_selected, $time_debut, $time_fin);
    $CA_moy_jour = ($histoCA['interm'] === 0) ? 0 : round($histoCA['interm'] / $histoCA['cmpt'], 2);

    if ($QV_moy_jour !== 0) {
      echo '<h3>Évolution du chiffre de caisse quotidien: ' . $nom . '.</h3> Moyenne journalière: ' . $QV_moy_jour;
      ?> Kgs.
      <div id="ca" style="height: 180px;"></div>

    <?php } ?>
  </div> <!-- .container -->

  <script>
    try {
    new Morris.Bar({
    element: 'collectes',
            data: [
  <?php
  $reponse = $bdd->prepare('SELECT
    SUM(masse) nombre,
    DATE_FORMAT(timestamp, "%Y-%m-%d") time
  FROM pesees_collectes
  WHERE  DATE(pesees_collectes.timestamp) BETWEEN :du AND :au
  AND pesees_collectes.id_type_dechet = :type
  GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
  ORDER BY time');

  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $type_selected]);
  $interm = 0;
  $cmpt = 0;
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
    echo " { y: '{$donnees['time']}', a: '{$donnees['nombre']}' },";
  }
  $reponse->closeCursor();
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Masse collectée'],
            xLabelFormat: dateUStoFR,
            postUnits: "Kgs.",
            resize: true,
            barColors: ['<?= $couleur; ?>'],
  <?= $cmpt !== 0 ? 'goals: [ ' . $interm / $cmpt . '],' : '' ?>
    });
    } catch (e) {
    console.log(e);
    }
  </script>

  <script>
    try {
    new Morris.Bar({
    element: 'sorties',
            data: [
  <?php
  $reponse = $bdd->prepare('SELECT
    SUM(masse) nombre,
    DATE_FORMAT(timestamp, "%Y-%m-%d") time
  FROM pesees_sorties
  WHERE DATE(pesees_sorties.timestamp) BETWEEN :du AND :au
  AND pesees_sorties.id_type_dechet = :type
  GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
  ORDER BY time');

  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $type_selected]);
  $interm = 0;
  $cmpt = 0;
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
    echo " { y: '{$donnees['time']}', a: '{$donnees['nombre']}' },";
  }
  $reponse->closeCursor();
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Masse évacuée hors boutique'],
            xLabelFormat: dateUStoFR,
            resize: true,
            postUnits: "Kgs.",
            barColors: ['<?= $couleur; ?>'],
  <?= $cmpt !== 0 ? 'goals: [ ' . $interm / $cmpt . '],' : '' ?>
    });
    } catch (e) {
    console.log(e);
    }
  </script>

  <script>
    try {
    new Morris.Bar({
    element: 'qv',
            data: [
  <?php
  $reponse = $bdd->prepare('SELECT
    SUM(quantite) nombre,
    DATE_FORMAT(timestamp, "%Y-%m-%d") time
  FROM vendus
  WHERE DATE(vendus.timestamp) BETWEEN :du AND :au
  AND vendus.id_type_dechet = :type
  GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
  ORDER BY time');

  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $type_selected]);
  $interm = 0;
  $cmpt = 0;
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
    echo " { y: '{$donnees['time']}', a: '{$donnees['nombre']}' },";
  }
  $reponse->closeCursor();
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Q. vendue'],
            xLabelFormat: dateUStoFR,
            resize: true,
            postUnits: "Pcs.",
            barColors: ['<?= $couleur; ?>'],
  <?= $cmpt !== 0 ? 'goals: [ ' . $interm / $cmpt . '],' : '' ?>
    });
    } catch (e) {
    console.log(e);
    }
  </script>

  <script>
    try {
    new Morris.Bar({
    element: 'ca',
            data: [
  <?php
  $reponse = $bdd->prepare('SELECT
    SUM(prix * quantite) nombre,
    DATE_FORMAT(timestamp, "%Y-%m-%d") time
  FROM vendus
  WHERE DATE(vendus.timestamp) BETWEEN :du AND :au
  AND vendus.id_type_dechet = :type
  GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
  ORDER BY time');

  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $type_selected]);
  $interm = 0;
  $cmpt = 0;
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
    echo " { y: '{$donnees['time']}', a: '{$donnees['nombre']}' },";
  }
  $reponse->closeCursor();
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['C.A.'],
            xLabelFormat: dateUStoFR,
            resize: true,
            postUnits: "€",
            barColors: ['<?= $couleur; ?>'],
  <?= $cmpt !== 0 ? 'goals: [ ' . $interm / $cmpt . '],' : '' ?>
    });
    } catch (e) {
    console.log(e);
    }
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
