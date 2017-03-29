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

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND ( strpos($_SESSION['niveau'], 'bi') !== false)) {

  require_once('../moteur/dbconfig.php');
  require_once "tete.php";


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

  <script type="text/javascript" src="../js/moment.js"></script>
  <script type="text/javascript" src="../js/daterangepicker.js"></script>
  <script src="../js/raphael.js"></script>
  <script src="../js/morris/morris.min.js"></script>
  <div class="container">
      <div class="row">

              <br>
              <div class="col-md-4" >
                  <label for="reportrange">Choisissez la période à inspecter:</label><br>
                  <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                      <i class="fa fa-calendar"></i>
                      <span></span> <b class="caret"></b>
                  </div>

              </div>
              <div class="col-md-4" >
                  <label>Choisissez le type d'objet ou de dechet:</label><br>
                  <select name="select" onchange="location = this.value;">
                      <?php foreach ($types_dechets as $type) {?>
                        <option value="jours.php?date1='.<?= $_GET['date1'].'&date2='.$_GET['date2'].'&type='.$type['id'] ?>"
                         <?= ($type['id'] === $_GET['type']) ? 'selected' : '' ?>
                                ><?= $type['nom'] ?></option>
                      <?php } ?>
                  </select>
              </div>
          
      </div>    
  </div>
  <hr/>
  <h2>
      <?php
// on affiche la période visée
      if ($_GET['date1'] == $_GET['date2']) {
        echo' Le ' . $_GET['date1'] . " : </h2>";
      } else {
        echo' Du ' . $_GET['date1'] . " au " . $_GET['date2'] . " : </h2>";
      }
//on convertit les deux dates en un format compatible avec la bdd
      $txt1 = $_GET['date1'];
      $date1ft = DateTime::createFromFormat('d-m-Y', $txt1);
      $time_debut = $date1ft->format('Y-m-d');
      $time_debut = $time_debut . " 00:00:00";
      $txt2 = $_GET['date2'];
      $date2ft = DateTime::createFromFormat('d-m-Y', $txt2);
      $time_fin = $date2ft->format('Y-m-d');
      $time_fin = $time_fin . " 23:59:59";
      $interm = 0;
      $cmpt = 0;
      $reponse = $bdd->prepare('SELECT SUM(masse) AS nombre ,  DATE( timestamp ) AS time FROM pesees_collectes
WHERE DATE(pesees_collectes.timestamp) BETWEEN :du AND :au AND  pesees_collectes.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
      $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
      // On affiche chaque entree une à une
      while ($donnees = $reponse->fetch()) {
        $interm = $interm + $donnees['nombre'];
        $cmpt = $cmpt + 1;
      }
      $reponse->closeCursor(); // Termine le traitement de la requête
      if ($interm == 0) {
        $masse_moy_jour = 0;
      } else {
        $masse_moy_jour = round($interm / $cmpt, 2);
      }
      if ($masse_moy_jour == 0) {
        
      } else {
        echo "<h3>Évolution de la masse totale collectée au " . $nom . ".</h3> Moyenne journalière: " . $masse_moy_jour;
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
      $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
      // On affiche chaque entree une à une
      while ($donnees = $reponse->fetch()) {
        $interm = $interm + $donnees['nombre'];
        $cmpt = $cmpt + 1;
      }
      $reponse->closeCursor(); // Termine le traitement de la requête
      if ($interm == 0) {
        $masse_moy_jour = 0;
      } else {
        $masse_moy_jour = round($interm / $cmpt, 2);
      }
      if ($masse_moy_jour == 0) {
        
      } else {
        echo "<h3>Évolution des masses totales évacuées hors boutique en " . $nom . ".</h3> Moyenne journalière: " . $masse_moy_jour;
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
      $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
      // On affiche chaque entree une à une
      while ($donnees = $reponse->fetch()) {
        $interm = $interm + $donnees['nombre'];
        $cmpt = $cmpt + 1;
      }
      $reponse->closeCursor(); // Termine le traitement de la requête
      if ($interm == 0) {
        $masse_moy_jour = 0;
      } else {
        $masse_moy_jour = round($interm / $cmpt, 2);
        if ($masse_moy_jour == 0) {
          
        } else {
          echo "<h3>Évolution des quantités de " . $nom . " vendues.</h3> Moyenne journalière: " . $masse_moy_jour;
        }
        ?> Pcs.
        <div id="qv" style="height: 180px;"></div>
      <?php } ?>
      <?php
      $interm = 0;
      $cmpt = 0;
      $reponse = $bdd->prepare('SELECT SUM(quantite*prix) AS nombre ,  DATE( timestamp ) AS time FROM vendus
WHERE DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
      $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
      // On affiche chaque entree une à une
      while ($donnees = $reponse->fetch()) {
        $interm = $interm + $donnees['nombre'];
        $cmpt = $cmpt + 1;
      }
      $reponse->closeCursor(); // Termine le traitement de la requête
      if ($interm == 0) {
        $masse_moy_jour = 0;
      } else {
        $masse_moy_jour = round($interm / $cmpt, 2);
      }
      if ($masse_moy_jour == 0) {
        
      } else {
        echo "<h3>Évolution du C.A quotidien, " . $nom . ".</h3> Moyenne journalière: " . $masse_moy_jour;
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
// On recupère tout le contenu de la table affectations
  $reponse = $bdd->prepare('SELECT SUM( masse ) AS nombre, DATE( timestamp ) AS time
FROM pesees_collectes
WHERE  DATE(pesees_collectes.timestamp) BETWEEN :du AND :au AND  pesees_collectes.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
  $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
// On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . "},";
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Masse collectée'],
            postUnits: "Kgs.",
            resize: true,
            barColors: ['<?php echo $couleur ?>'],
            <?php 
 $interm = 0;
 $cmpt = 0;
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->prepare('SELECT SUM(masse) AS nombre ,  DATE( timestamp ) AS time FROM pesees_collectes
WHERE DATE(pesees_collectes.timestamp) BETWEEN :du AND :au AND  pesees_collectes.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
            $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'type' => $_GET['type'] ));
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
$interm = $interm + $donnees['nombre'];
$cmpt = $cmpt + 1;
             }
              $reponse->closeCursor(); // Termine le traitement de la requête
echo "goals: [".$interm/$cmpt."],";
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
// On recupère tout le contenu de la table affectations
  $reponse = $bdd->prepare('SELECT SUM( masse ) AS nombre, DATE( timestamp ) AS time
FROM pesees_sorties
WHERE  DATE(pesees_sorties.timestamp) BETWEEN :du AND :au AND  pesees_sorties.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
  $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));

// On affiche chaque entree une à une

  while ($donnees = $reponse->fetch()) {

    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . "},";
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Masse évacuée hors boutique'],
            resize: true,
            postUnits: "Kgs.",
            barColors: ['<?php echo $couleur ?>'],
  <?php
  $interm = 0;
  $cmpt = 0;
// On recupère tout le contenu de la table affectations
  $reponse = $bdd->prepare('SELECT SUM(masse) AS nombre ,  DATE( timestamp ) AS time FROM pesees_sorties
WHERE DATE(pesees_sorties.timestamp) BETWEEN :du AND :au AND  pesees_sorties.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
  $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
// On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
  echo "goals: [" . $interm / $cmpt . "],";
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
// On recupère tout le contenu de la table affectations
  $reponse = $bdd->prepare(
          'SELECT SUM( quantite ) AS nombre, DATE( timestamp ) AS time
        FROM vendus
        WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
        GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
        ORDER BY time');
  $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
// On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . "},";
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Q. vendue'],
            resize: true,
             postUnits: "Pcs.",
            barColors: ['<?php echo $couleur ?>'],
  <?php
  $interm = 0;
  $cmpt = 0;
// On recupère tout le contenu de la table affectations
  $reponse = $bdd->prepare('SELECT SUM(quantite) AS nombre ,  DATE( timestamp ) AS time FROM vendus
WHERE DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
  $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
// On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
  echo "goals: [" . $interm / $cmpt . "],";
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
// On recupère tout le contenu de la table affectations
  $reponse = $bdd->prepare('SELECT SUM( prix*quantite ) AS nombre, DATE( timestamp ) AS time
FROM vendus
WHERE  DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
  $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));

// On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {

    echo "{y:'" . $donnees['time'] . "', a:" . $donnees['nombre'] . "},";
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
  ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['C.A.'],
            resize: true,
            postUnits: "€",
            barColors: ['<?php echo $couleur ?>'],
  <?php
  $interm = 0;
  $cmpt = 0;
// On recupère tout le contenu de la table affectations
  $reponse = $bdd->prepare('SELECT SUM(prix*quantite) AS nombre ,  DATE( timestamp ) AS time FROM vendus
WHERE DATE(vendus.timestamp) BETWEEN :du AND :au AND  vendus.id_type_dechet = :type
GROUP BY DATE_FORMAT( time,  "%Y-%m-%d" ) 
ORDER BY time');
  $reponse->execute(array('du' => $time_debut, 'au' => $time_fin, 'type' => $_GET['type']));
// On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
    $interm = $interm + $donnees['nombre'];
    $cmpt = $cmpt + 1;
  }
  $reponse->closeCursor(); // Termine le traitement de la requête
  echo "goals: [" . $interm / $cmpt . "],";
  ?>
        });
      </script>
      <script type="text/javascript">
        "use strict";
        function $_GET(param) {
            var vars = {};
            window.location.href.replace(
                    /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
                    function (m, key, value) { // callback
                        vars[key] = value !== undefined ? value : '';
                    }
            );
            if (param) {
                return vars[param] ? vars[param] : null;
            }
            return vars;
        }
        $(document).ready(function () {
            var cb = function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('DD, MMMM, YYYY') + ' - ' + end.format('DD, MMMM, YYYY'));
                // alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
            }
            var dateuno = $_GET('date1');
            var moisuno = dateuno.substring(0, 2);
            var jouruno = dateuno.substring(3, 5);
            var anneeuno = dateuno.substring(6, 10);
            var dateunogf = moisuno + '/' + jouruno + "/" + anneeuno;
            var datedos = $_GET('date2');
            var moisdos = datedos.substring(0, 2);
            var jourdos = datedos.substring(3, 5);
            var anneedos = datedos.substring(6, 10);
            var datedosgf = moisdos + '/' + jourdos + "/" + anneedos;
            var optionSet1 = {
                startDate: dateunogf,
                endDate: datedosgf,
                minDate: '01/01/2010',
                maxDate: '12/31/2020',
                dateLimit: {days: 800},
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    "Aujoud'hui": [moment(), moment()],
                    'hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 derniers jours': [moment().subtract(6, 'days'), moment()],
                    '30 derniers jours': [moment().subtract(29, 'days'), moment()],
                    'Ce mois': [moment().startOf('month'), moment().endOf('month')],
                    'Le mois deriner': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'DD/MM/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Appliquer',
                    cancelLabel: 'Anuler',
                    fromLabel: 'Du',
                    toLabel: 'Au',
                    customRangeLabel: 'Période libre',
                    daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                    monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
                    firstDay: 1
                }
            };
            $('#reportrange').daterangepicker(optionSet1, cb);
            $('#reportrange span').html($_GET('date1') + ' - ' + $_GET('date2'));
            $('#reportrange').on('show.daterangepicker', function () {
                console.log("show event fired");
            });
            $('#reportrange').on('hide.daterangepicker', function () {
                console.log("hide event fired");
            });
            $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
                console.log("apply event fired, start/end dates are "
                        + picker.startDate.format('DD MM, YYYY')
                        + " to "
                        + picker.endDate.format('DD MM, YYYY')
                        );
                window.location.href = "jours.php?date1=" + picker.startDate.format('DD-MM-YYYY') + "&date2=" + picker.endDate.format('DD-MM-YYYY') + "&type=" +<?php echo $_GET['type'] ?>;
            });
            $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
                console.log("cancel event fired");
            });
            $('#options1').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
            });
            $('#options2').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
            });
            $('#destroy').click(function () {
                $('#reportrange').data('daterangepicker').remove();
            });
        });
      </script>
      <?php
      include "pied.php";
    } else {
      header('Location: ../moteur/destroy.php');
    }
    ?>
