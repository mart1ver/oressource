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
require_once('../moteur/dbconfig.php');
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once 'tete.php';
  ?>

  <script type="text/javascript" src="../js/moment.js"></script>
  <script type="text/javascript" src="../js/daterangepicker.js"></script>
  <div class="container">
    <h1>verification des ventes</h1>
    <div class="panel-body">
      <ul class="nav nav-tabs">
        <?php
        $reponse = $bdd->query('SELECT * FROM points_vente');
        while ($donnees = $reponse->fetch()) { ?>
          <li<?php
          if ($_GET['numero'] === $donnees['id']) {
            echo ' class="active"';
          }
          ?>><a href="<?= 'verif_vente.php?numero=' . $donnees['id'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>"><?= $donnees['nom']; ?></a></li>
            <?php
          }
          $reponse->closeCursor();
          ?>
      </ul>

      <br>
      <div class="row">

        <div class="col-md-3 col-md-offset-9" >
          <label for="reportrange">Choisissez la période à inspecter::</label><br>
          <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
            <i class="fa fa-calendar"></i>
            <span></span> <b class="caret"></b>
          </div>
        </div>
        <script type="text/javascript">
          "use strict";
          function $_GET(param) {
            var vars = { };
            window.location.href.replace(
                    /[?&]+([^=&]+)=?([^&]*)?/gi,
                    function(m, key, value) {
                      vars[key] = value !== undefined ? value : '';
                    }
            );

            if (param) {
              return vars[param] ? vars[param] : null;
            }
            return vars;
          }
          $(document).ready(function() {

            var cb = function(start, end, label) {
              console.log(start.toISOString(), end.toISOString(), label);
              $('#reportrange span').html(start.format('DD, MMMM, YYYY') + ' - ' + end.format('DD, MMMM, YYYY'));

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
              maxDate: '12/31/2030',
              dateLimit: { days: 800 },
              showDropdowns: true,
              showWeekNumbers: true,
              timePicker: false,
              timePickerIncrement: 1,
              timePicker12Hour: true,
              ranges: {
                "Aujoud'hui": [ moment(), moment() ],
                'hier': [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
                '7 derniers jours': [ moment().subtract(6, 'days'), moment() ],
                '30 derniers jours': [ moment().subtract(29, 'days'), moment() ],
                'Ce mois': [ moment().startOf('month'), moment().endOf('month') ],
                'Le mois deriner': [ moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month') ]
              },
              opens: 'left',
              buttonClasses: [ 'btn btn-default' ],
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
                daysOfWeek: [ 'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa' ],
                monthNames: [ 'Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre' ],
                firstDay: 1
              }
            };

            $('#reportrange span').html($_GET('date1') + ' - ' + $_GET('date2'));

            $('#reportrange').daterangepicker(optionSet1, cb);

            $('#reportrange').on('show.daterangepicker', function() {
              console.log("show event fired");
            });
            $('#reportrange').on('hide.daterangepicker', function() {
              console.log("hide event fired");
            });
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
              console.log("apply event fired, start/end dates are "
                      + picker.startDate.format('DD MM, YYYY')
                      + " to "
                      + picker.endDate.format('DD MM, YYYY')
                      );
              window.location.href = "verif_vente.php?date1=" + picker.startDate.format('DD-MM-YYYY') + "&date2=" + picker.endDate.format('DD-MM-YYYY') + "&numero=" + "<?= $_GET['numero']; ?>";
            });
            $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
              console.log("cancel event fired");
            });

            $('#options1').click(function() {
              $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
            });

            $('#options2').click(function() {
              $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
            });

            $('#destroy').click(function() {
              $('#reportrange').data('daterangepicker').remove();
            });

          });
        </script>
        <?php
        if ($_GET['date1'] === $_GET['date2']) {
          echo' le ' . $_GET['date1'];
        } else {
          echo' du ' . $_GET['date1'] . ' au ' . $_GET['date2'] . ' :';
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
        ?>
      </div>

    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Momment de la vente</th>
          <th>Crédit</th>
          <th>Débit</th>
          <th>Nombre d'objets</th>
          <th>Moyen de paiement</th>
          <th>Masse pesée</th>
          <th>Commentaire</th>
          <th>Auteur de la ligne</th>
          <th></th>
          <th>Modifié par</th>
          <th style="width:100px">Le</th>

        </tr>
      </thead>
      <tbody>
        <?php
        /*
          'SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
          FROM type_dechets,pesees_collectes
          WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
          GROUP BY nom'
         */

        // On recupère toute la liste des filieres de sortie
        //   $reponse = $bdd->query('SELECT * FROM grille_objets');

        $req = $bdd->prepare('SELECT ventes.id,ventes.timestamp ,moyens_paiement.nom moyen, moyens_paiement.couleur coul, ventes.commentaire ,ventes.last_hero_timestamp lht
                       FROM ventes ,moyens_paiement
                       WHERE ventes.id_point_vente = :id_point_vente
                       AND ventes.id_moyen_paiement = moyens_paiement.id
                       AND DATE(ventes.timestamp) BETWEEN :du AND :au ');
        $req->execute(['id_point_vente' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

        while ($donnees = $req->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td> <?php
              $req2 = $bdd->prepare('SELECT SUM(vendus.prix*vendus.quantite) pto
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente
                       ');
              $req2->execute(['id_vente' => $donnees['id']]);

              while ($donnees2 = $req2->fetch()) {
                if ($donnees2['pto'] > 0) {
                  echo $donnees2['pto'];
                  $rembo = 'non';
                }
              }
              ?></td>
            <td><?php
              $req3 = $bdd->prepare('SELECT SUM(vendus.remboursement*vendus.quantite) pto
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente
                       ');
              $req3->execute(['id_vente' => $donnees['id']]);

              while ($donnees3 = $req3->fetch()) {
                if ($donnees3['pto'] > 0) {
                  echo $donnees3['pto'];
                  $rembo = 'oui';
                }
              }
              ?></td>
            <td><?php
              $req4 = $bdd->prepare('SELECT SUM(vendus.quantite) pto
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente
                       ');
              $req4->execute(['id_vente' => $donnees['id']]);

              while ($donnees4 = $req4->fetch()) {
                if ($donnees4['pto'] > 0) {
                  echo $donnees4['pto'];
                }
              }
              ?></td>

            <td> <span class="badge" style="background-color:<?= $donnees['coul']; ?>"><?= $donnees['moyen']; ?></span></td>
            <td><?php
              $req5 = $bdd->prepare('SELECT SUM(pesees_vendus.masse) mto
                       FROM vendus,pesees_vendus
                       WHERE   vendus.id_vente = :id_vente AND pesees_vendus.id_vendu =  vendus.id
                       ');
              $req5->execute(['id_vente' => $donnees['id']]);

              while ($donnees5 = $req5->fetch()) {
                if ($donnees5['mto'] > 0) {
                  echo $donnees5['mto'];
                }
              }
              ?></td>
            <td style="width:100px"><?= $donnees['commentaire']; ?></td>
            <td><?php
              $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, ventes
                       WHERE  ventes.id = :id_vente
                       AND utilisateurs.id = ventes.id_createur');
              $req5->execute(['id_vente' => $donnees['id']]);

              while ($donnees5 = $req5->fetch()) { ?>

                <?= $donnees5['mail']; ?>
              <?php } ?></td>
            <td>
              <?=
              $donnees3['pto'];
              echo $donnees4['pto'];

              if ($rembo === 'non') { ?>

                <form action="modification_verification_vente.php?nvente=<?= $donnees['id']; ?>" method="post">
                  <input type="hidden" name ="moyen" id="moyen" value="<?= $donnees['moyen']; ?>">
                  <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                  <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                  <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                  <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                  <button  class="btn btn-warning btn-sm" >Modifier</button>
                </form>
                <?php
              }
              if ($rembo === 'oui') { ?>

                <form action="modification_verification_remboursement.php?nvente=<?= $donnees['id']; ?>" method="post">
                  <input type="hidden" name ="moyen" id="moyen" value="<?= $donnees['moyen']; ?>">
                  <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                  <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                  <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                  <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                  <button  class="btn btn-warning btn-sm" >Modifier</button>
                </form>
              <?php } ?>

            </td>
            <td><?php
              $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, ventes
                       WHERE  ventes.id = :id_vente
                       AND utilisateurs.id = ventes.id_last_hero');
              $req5->execute(['id_vente' => $donnees['id']]);

              while ($donnees5 = $req5->fetch()) { ?>

                <?= $donnees5['mail']; ?>
              <?php } ?></td>

            <td><?php
              if ($donnees['lht'] !== '0000-00-00 00:00:00') {
                echo $donnees['lht'];
              }
              ?></td>
          </tr>
          <?php
        }
        $req->closeCursor();
        $req2->closeCursor();
        $req3->closeCursor();
        $req4->closeCursor();
        $req5->closeCursor();
        ?>
      </tbody>
    </table>

  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
