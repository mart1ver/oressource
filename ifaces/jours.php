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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'bi') !== false)) {
  require_once('../moteur/dbconfig.php');
  require_once 'tete.php';
  $reponse = $bdd->prepare('SELECT id, nom, couleur FROM type_dechets WHERE id = :type');
  $reponse->execute(['type' => $_GET['type']]);
  $donnees = $reponse->fetch(PDO::FETCH_ASSOC);
  $nom = $donnees['nom'];
  $couleur = $donnees['couleur'];
  $reponse->closeCursor();

  $reponse = $bdd->prepare('SELECT nom, id FROM type_dechets');
  $reponse->execute();
  $types_dechets = $reponse->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <script src="../js/raphael.js"></script>
  <script src="../js/morris/morris.min.js"></script>
  <div class="container">
    <div class="row">
      <br>
      <div class="col-md-4">
        <label for="reportrange">Choisissez la période à inspecter:</label>
        <br>
        <div id="reportrange" class="pull-left" 
             style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
          <i class="fa fa-calendar"></i>
          <span></span>
          <b class="caret"></b>
        </div>
      </div>

      <div class="col-md-4" >
        <label>Choisissez le type d'objet ou de dechet:</label><br>
        <select name="select" onchange="location = this.value;">
          <?php foreach ($types_dechets as $type) { ?>
            <option value="jours.php?date1=<?= $_GET['date1'] . '&date2=' . $_GET['date2'] . '&type=' . $type['id']; ?>"
            <?= ($type['id'] === $_GET['type']) ? 'selected' : ''; ?>><?= $type['nom']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
  </div>
  <hr/>
  <h2>
    <?php
    if ($_GET['date1'] === $_GET['date2']) {
      echo' Le ' . $_GET['date1'] . ' : </h2>';
    } else {
      echo' Du ' . $_GET['date1'] . ' au ' . $_GET['date2'] . ' : </h2>';
    }
    //on convertit les deux dates en un format compatible avec la bdd
    $txt1 = $_GET['date1'];
    $date1ft = DateTime::createFromFormat('d-m-Y', $txt1);
    $time_debut = $date1ft->format('Y-m-d');
    $time_debut = $time_debut . ' 00:00:00';
    $txt2 = $_GET['date2'];
    $date2ft = DateTime::createFromFormat('d-m-Y', $txt2);
    $time_fin = $date2ft->format('Y-m-d');
    $time_fin = $time_fin . ' 23:59:59';
    $interm = 0;
    $cmpt = 0;
    $reponse = $bdd->prepare('SELECT SUM(masse) AS nombre ,  DATE( timestamp ) AS time FROM pesees_collectes
WHERE DATE(pesees_collectes.timestamp) BETWEEN :du AND :au AND  pesees_collectes.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

    while ($donnees = $reponse->fetch()) {
      $interm = $interm + $donnees['nombre'];
      $cmpt = $cmpt + 1;
    }
    $reponse->closeCursor();
    if ($interm === 0) {
      $masse_moy_jour = 0;
    } else {
      $masse_moy_jour = round($interm / $cmpt, 2);
    }
    if ($masse_moy_jour === 0) {

    } else {
      echo '<h3>Évolution de la masse totale collectée au ' . $nom . '.</h3> Moyenne journalière: ' . $masse_moy_jour;
      ?> Kgs.
      <div id="collectes" style="height: 180px;"></div>
      <?php
    }
    $interm = 0;
    $cmpt = 0;
    $reponse = $bdd->prepare('SELECT SUM(masse) AS nombre ,  DATE( timestamp ) AS time FROM pesees_sorties
WHERE DATE(pesees_sorties.timestamp) BETWEEN :du AND :au AND  pesees_sorties.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

    while ($donnees = $reponse->fetch()) {
      $interm = $interm + $donnees['nombre'];
      $cmpt = $cmpt + 1;
    }
    $reponse->closeCursor();
    if ($interm === 0) {
      $masse_moy_jour = 0;
    } else {
      $masse_moy_jour = round($interm / $cmpt, 2);
    }
    if ($masse_moy_jour === 0) {

    } else {
      echo '<h3>Évolution des masses totales évacuées hors boutique en ' . $nom . '.</h3> Moyenne journalière: ' . $masse_moy_jour;
      ?> Kgs.
      <div id="sorties" style="height: 180px;"></div>
      <?php
    }
    $interm = 0;
    $cmpt = 0;
    $reponse = $bdd->prepare('SELECT SUM(quantite) AS nombre ,  DATE( timestamp ) AS time FROM vendus
WHERE DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

    while ($donnees = $reponse->fetch()) {
      $interm = $interm + $donnees['nombre'];
      $cmpt = $cmpt + 1;
    }
    $reponse->closeCursor();
    if ($interm === 0) {
      $masse_moy_jour = 0;
    } else {
      $masse_moy_jour = round($interm / $cmpt, 2);
      if ($masse_moy_jour === 0) {

      } else {
        echo '<h3>Évolution des quantités de ' . $nom . ' vendues.</h3> Moyenne journalière: ' . $masse_moy_jour;
      }
      ?> Pcs.
      <div id="qv" style="height: 180px;"></div>
      <?php
    }

    $interm = 0;
    $cmpt = 0;
    $reponse = $bdd->prepare('SELECT SUM(quantite*prix) AS nombre ,  DATE( timestamp ) AS time FROM vendus
WHERE DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

    while ($donnees = $reponse->fetch()) {
      $interm = $interm + $donnees['nombre'];
      $cmpt = $cmpt + 1;
    }
    $reponse->closeCursor();
    if ($interm === 0) {
      $masse_moy_jour = 0;
    } else {
      $masse_moy_jour = round($interm / $cmpt, 2);
    }
    if ($masse_moy_jour === 0) {

    } else {
      echo '<h3>Évolution du C.A quotidien, ' . $nom . '.</h3> Moyenne journalière: ' . $masse_moy_jour;
      ?> €.
      <div id="ca" style="height: 180px;"></div>
    <?php } ?>
    <script>
      new Morris.Bar({
        // ID of the element in which to draw the chart.
        element: 'collectes',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [

  <?php
  $reponse = $bdd->prepare('SELECT SUM( masse ) AS nombre, DATE( timestamp ) AS time
FROM pesees_collectes
WHERE  DATE(pesees_collectes.timestamp) BETWEEN :du AND :au AND  pesees_collectes.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

  while ($donnees = $reponse->fetch()) {
    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . '},';
  }
  $reponse->closeCursor();
  ?>
        ],
        xkey: 'y',
        ykeys: [ 'a' ],
        labels: [ 'Masse collectée' ],
        postUnits: "Kgs.",
        resize: true,
        barColors: [ '<?= $couleur; ?>' ],
  <?php
  $interm = 0;
  $cmpt = 0;

  $reponse = $bdd->prepare('SELECT SUM(masse) AS nombre ,  DATE( timestamp ) AS time FROM pesees_collectes
WHERE DATE(pesees_collectes.timestamp) BETWEEN :du AND :au AND  pesees_collectes.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
  }
  $reponse->closeCursor();
  echo 'goals: [' . $interm / $cmpt . '],';
  ?>
      });
    </script>
    <script>
      new Morris.Bar({
        // ID of the element in which to draw the chart.
        element: 'sorties',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
  <?php
  $reponse = $bdd->prepare('SELECT SUM( masse ) AS nombre, DATE( timestamp ) AS time
FROM pesees_sorties
WHERE  DATE(pesees_sorties.timestamp) BETWEEN :du AND :au AND  pesees_sorties.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

  while ($donnees = $reponse->fetch()) {
    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . '},';
  }
  $reponse->closeCursor();
  ?>
        ],
        xkey: 'y',
        ykeys: [ 'a' ],
        labels: [ 'Masse évacuée hors boutique' ],
        resize: true,
        postUnits: "Kgs.",
        barColors: [ '<?= $couleur; ?>' ],
  <?php
  $interm = 0;
  $cmpt = 0;

  $reponse = $bdd->prepare('SELECT SUM(masse) AS nombre ,  DATE( timestamp ) AS time FROM pesees_sorties
WHERE DATE(pesees_sorties.timestamp) BETWEEN :du AND :au AND  pesees_sorties.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
  }
  $reponse->closeCursor();
  echo 'goals: [' . $interm / $cmpt . '],';
  ?>
      });
    </script>
    <script>
      new Morris.Bar({
        // ID of the element in which to draw the chart.
        element: 'qv',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
  <?php
  $reponse = $bdd->prepare(
    'SELECT SUM( quantite ) AS nombre, DATE( timestamp ) AS time
FROM vendus
WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

  while ($donnees = $reponse->fetch()) {
    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . '},';
  }
  $reponse->closeCursor();
  ?>
        ],
        xkey: 'y',
        ykeys: [ 'a' ],
        labels: [ 'Q. vendue' ],
        resize: true,
        postUnits: "Pcs.",
        barColors: [ '<?= $couleur; ?>' ],
  <?php
  $interm = 0;
  $cmpt = 0;

  $reponse = $bdd->prepare('SELECT SUM(quantite) AS nombre ,  DATE( timestamp ) AS time FROM vendus
WHERE DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
  }
  $reponse->closeCursor();
  echo 'goals: [' . $interm / $cmpt . '],';
  ?>
      });
    </script>
    <script>
      new Morris.Bar({
        // ID of the element in which to draw the chart.
        element: 'ca',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
  <?php
  $reponse = $bdd->prepare('SELECT SUM( prix*quantite ) AS nombre, DATE( timestamp ) AS time
FROM vendus
WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);
  while ($donnees = $reponse->fetch()) {
    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . '},';
  }
  $reponse->closeCursor();
  ?>
        ],
        xkey: 'y',
        ykeys: [ 'a' ],
        labels: [ 'C.A.' ],
        resize: true,
        postUnits: "€",
        barColors: [ '<?= $couleur; ?>' ],
  <?php
  $interm = 0;
  $cmpt = 0;

  $reponse = $bdd->prepare('SELECT SUM(prix*quantite) AS nombre ,  DATE( timestamp ) AS time FROM vendus
WHERE DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" )
ORDER BY time');
  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']]);

  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
  }
  $reponse->closeCursor();
  echo 'goals: [' . $interm / $cmpt . '],';
  ?>
      });
    </script>
    <script type="text/javascript">
      'use strict';
      $(document).ready(() => {
        const get = process_get();
        const url = 'jours';
        const options = set_datepicker(get, url);
        bind_datepicker(options, get, url);
      });
    </script>
    <?php
    require_once 'pied.php';
  } else {
    header('Location: ../moteur/destroy.php');
  }
  ?>
