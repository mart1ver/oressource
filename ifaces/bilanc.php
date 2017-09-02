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

require_once '../core/session.php';
require_once '../core/requetes.php';

// Bilan des collectes

if (is_valid_session() && is_allowed_bilan()) {
  require_once './tete.php';
  require_once '../moteur/dbconfig.php';

  $points_collectes = points_collectes($bdd);
  ?>

  <div class="container">
    <div class="row">
      <div class="col-md-11 " >
        <h1>Bilan global</h1>
        <div class="col-md-4 col-md-offset-8" >
          <label for="reportrange">Choisissez la période à inspecter:</label><br>
          <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
            <i class="fa fa-calendar"></i>
            <span></span> <b class="caret"></b>
          </div>
        </div>
        <ul class="nav nav-tabs">
          <li class="active"><a>Collectes</a></li>
          <li><a href="<?= 'bilanhb.php?date1=' . $_GET['date1'] . '&date2=' . $_GET['date2'] . '&numero=0'; ?>">Sorties hors-boutique</a></li>
          <li><a href="<?= 'bilanv.php?date1=' . $_GET['date1'] . '&date2=' . $_GET['date2'] . '&numero=0'; ?>">Ventes</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 col-md-offset-1" >
      <h2> Bilan des collectes de la structure
      </h2>
      <ul class="nav nav-tabs">
        <?php
        $reponse = $bdd->query('SELECT * FROM points_collecte');
        while ($donnees = $reponse->fetch()) { ?>
          <li<?php
          if ($_GET['numero'] === $donnees['id']) {
            echo ' class="active"';
          }
          ?>><a href="<?= 'bilanc.php?numero=' . $donnees['id'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>"><?= $donnees['nom']; ?></a></li>
            <?php
          }
          $reponse->closeCursor();
          ?>

        <li<?php
        if ($_GET['numero'] === 0) {
          echo ' class="active"';
        }
        ?>><a href="<?= 'bilanc.php?numero=0' . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>">Tous les points</a></li>
      </ul>
      <br>

      <div class="row">
        <h2>
          <?php
          if ($_GET['date1'] === $_GET['date2']) {
            echo' Le ' . $_GET['date1'] . ' ,';
          } else {
            echo' Du ' . $_GET['date1'] . ' au ' . $_GET['date2'] . ' ,';
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
          Masse collectée: <?php
          if ($_GET['numero'] === 0) {
            $req = $bdd->prepare('SELECT SUM(pesees_collectes.masse)AS total   FROM pesees_collectes  WHERE  DATE(pesees_collectes.timestamp) BETWEEN :du AND :au  ');
            $req->execute(['du' => $time_debut, 'au' => $time_fin]);
            $donnees = $req->fetch();
            $mtotcolo = $donnees['total'];
            echo $donnees['total'] . ' Kgs.';

            $req->closeCursor();
          } else {
            //si on observe un point en particulier

            $req = $bdd->prepare('SELECT SUM(pesees_collectes.masse)AS total
FROM pesees_collectes ,collectes
WHERE pesees_collectes.id_collecte = collectes.id
AND pesees_collectes.timestamp BETWEEN :du AND :au  AND collectes.id_point_collecte = :numero ');

            $req->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);
            $donnees = $req->fetch();
            $mtotcolo = $donnees['total'];
            echo $donnees['total'] . ' Kgs.';

            $req->closeCursor();
          }
          if ($_GET['numero'] === 0) { ?>
            , sur <?php
// on determine le nombre de points de collecte
            $req = $bdd->prepare('SELECT COUNT(id) FROM points_collecte');
            $req->execute(['au' => $time_fin]);
            $donnees = $req->fetch();

            echo $donnees['COUNT(id)'];

            $req->closeCursor();
            ?> Point(s) de collecte.
          <?php } ?></h2>
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Répartition par type de collecte
              </h3>
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
                  <?php
                  if ($_GET['numero'] === 0) {
                    $reponse = $bdd->prepare('SELECT
type_collecte.nom,SUM(`pesees_collectes`.`masse`) somme,pesees_collectes.timestamp,type_collecte.id,COUNT(distinct collectes.id) ncol
FROM
pesees_collectes,collectes,type_collecte

WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
GROUP BY id_type_collecte');
                    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                    while ($donnees = $reponse->fetch()) { ?>
                      <tr data-toggle="collapse" data-target=".parmasse<?= $donnees['id']; ?>">
                        <td><?= $donnees['nom']; ?></td>
                        <td><?= $donnees['ncol']; ?></td>
                        <td><?= $donnees['somme']; ?></td>
                        <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                      </tr>
                      <?php
                      $reponse2 = $bdd->prepare('SELECT type_dechets.id,type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,type_collecte , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au
AND type_dechets.id = pesees_collectes.id_type_dechet
AND type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
AND type_collecte.id = :id_type_collecte
GROUP BY nom
ORDER BY somme DESC');
                      $reponse2->execute(['du' => $time_debut, 'au' => $time_fin, 'id_type_collecte' => $donnees['id']]);

                      while ($donnees2 = $reponse2->fetch()) { ?>

                        <tr class="collapse parmasse<?= $donnees['id']; ?>">
                          <td>
                            <a href=" jours.php?date1=<?= $_GET['date1']; ?>&date2=<?= $_GET['date2']; ?>&type=<?= $donnees2['id']; ?>"> <?= $donnees2['nom']; ?> </a>
                          </td>
                          <td>
                            <?= $donnees2['somme'] . ' Kgs.'; ?>
                          </td>
                          <td>
                            <?= round($donnees2['somme'] * 100 / $donnees['somme'], 2) . ' %'; ?>
                          </td>
                        </tr>
                        <?php
                      }
                      $reponse2->closeCursor();
                    }
                    $reponse->closeCursor();
                  } else {
// on determine les masses totales collèctés sur cete période(pour un point donné)
                    $reponse = $bdd->prepare('SELECT
type_collecte.nom,SUM(`pesees_collectes`.`masse`) somme,pesees_collectes.timestamp,type_collecte.id,COUNT(distinct collectes.id) ncol
FROM
pesees_collectes,collectes,type_collecte

WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
AND collectes.id_point_collecte = :numero
GROUP BY id_type_collecte');
                    $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                    while ($donnees = $reponse->fetch()) { ?>
                      <tr data-toggle="collapse" data-target=".parmasse<?= $donnees['id']; ?>">
                        <td><?= $donnees['nom']; ?></td>
                        <td><?= $donnees['ncol']; ?></td>
                        <td><?= $donnees['somme']; ?></td>
                        <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                      </tr>
                      <?php
                      $reponse2 = $bdd->prepare('SELECT type_dechets.id,type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,type_collecte , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au
AND type_dechets.id = pesees_collectes.id_type_dechet
AND type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
AND type_collecte.id = :id_type_collecte AND collectes.id_point_collecte = :numero
GROUP BY nom
ORDER BY somme DESC');
                      $reponse2->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero'], 'id_type_collecte' => $donnees['id']]);

                      while ($donnees2 = $reponse2->fetch()) { ?>
                        <tr class="collapse parmasse<?= $donnees['id']; ?> active">

                          <td class="hiddenRow">
                            <a href=" jours.php?date1=<?= $_GET['date1']; ?>&date2=<?= $_GET['date2']; ?>&type=<?= $donnees2['id']; ?>"> <?= $donnees2['nom']; ?> </a>
                          </td>
                          <td class="hiddenRow">
                            <?= $donnees2['somme'] . ' Kgs.'; ?>
                          </td>
                          <td class="hiddenRow">
                            <?= round($donnees2['somme'] * 100 / $donnees['somme'], 2) . ' %'; ?>
                          </td>
                        </tr>
                        <?php
                      }
                      $reponse2->closeCursor();
                    }
                    $reponse->closeCursor();
                  }
                  ?>
                </tbody>
              </table>
              <br>
              <div  id="graphmasse" style="height: 180px;"></div>
              <br>
              <div  id="graph2masse" style="height: 180px;"></div>
              <br>
              <a href="<?= '../moteur/export_bilanc_partype.php?numero=' . $_GET['numero'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>">
                <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Répartition par localité
              </h3>
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
                  <?php
                  if ($_GET['numero'] === 0) {

// on determine les masses totales collèctés sur cete période(pour Tous les points)
                    $reponse = $bdd->prepare('SELECT
localites.nom,SUM(pesees_collectes.masse) somme,pesees_collectes.timestamp,localites.id id,COUNT(distinct collectes.id) ncol
FROM
pesees_collectes,collectes,localites

WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
GROUP BY id');
                    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                    while ($donnees = $reponse->fetch()) { ?>
                      <tr data-toggle="collapse" data-target=".parloc<?= $donnees['id']; ?>">
                        <td><?= $donnees['nom']; ?></td>
                        <td><?= $donnees['ncol']; ?></td>
                        <td><?= $donnees['somme']; ?></td>
                        <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                      </tr>
                      <?php
                      $reponse2 = $bdd->prepare('SELECT type_dechets.id AS idd,localites.id,type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,localites , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au
AND type_dechets.id = pesees_collectes.id_type_dechet
AND localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
AND localites.id = :id_loc
GROUP BY nom
ORDER BY somme DESC');
                      $reponse2->execute(['du' => $time_debut, 'au' => $time_fin, 'id_loc' => $donnees['id']]);

                      while ($donnees2 = $reponse2->fetch()) {
                        ?>
                        <tr class="collapse parloc<?= $donnees['id']; ?>">
                          </td>
                      <a href=" jours.php?date1=<?= $_GET['date1']; ?>&date2=<?= $_GET['date2']; ?>&type=<?= $donnees2['idd']; ?>"> <?= $donnees2['nom']; ?> </a>
                      </td>
                      <td>
                        <?= $donnees2['somme'] . ' Kgs.'; ?>
                      </td>
                      <td>
                        <?= round($donnees2['somme'] * 100 / $donnees['somme'], 2) . ' %'; ?>
                      </td>
                      </tr>
                      <?php
                    }
                    $reponse2->closeCursor();
                  }
                  $reponse->closeCursor();
                } else {

// on determine les masses totales collèctés sur cete période(pour un point donné)
                  $reponse = $bdd->prepare('SELECT
localites.nom,SUM(pesees_collectes.masse) somme,pesees_collectes.timestamp,localites.id,COUNT(distinct collectes.id) ncol
FROM
pesees_collectes,collectes,localites

WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
AND collectes.id_point_collecte = :numero
GROUP BY id
');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                  while ($donnees = $reponse->fetch()) { ?>
                    <tr data-toggle="collapse" data-target=".parloc<?= $donnees['id']; ?>">
                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['ncol']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                    </tr>
                    <?php
                    $reponse2 = $bdd->prepare('SELECT type_dechets.id,type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,type_collecte , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au
AND type_dechets.id = pesees_collectes.id_type_dechet
AND type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
AND type_collecte.id = :id_type_collecte AND collectes.id_point_collecte = :numero
GROUP BY nom
ORDER BY somme DESC');
                    $reponse2->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero'], 'id_type_collecte' => $donnees['id']]);

                    while ($donnees2 = $reponse2->fetch()) { ?>
                      <tr class="collapse parloc<?= $donnees['id']; ?> active">

                        <td class="hiddenRow">
                          <a href=" jours.php?date1=<?= $_GET['date1']; ?>&date2=<?= $_GET['date2']; ?>&type=<?= $donnees2['id']; ?>"> <?= $donnees2['nom']; ?> </a>
                        </td>
                        <td class="hiddenRow">
                          <?= $donnees2['somme'] . ' Kgs.'; ?>
                        </td>
                        <td class="hiddenRow">
                          <?= round($donnees2['somme'] * 100 / $donnees['somme'], 2) . ' %'; ?>
                        </td>
                      </tr>
                      <?php
                    }
                    $reponse2->closeCursor();
                  }
                  $reponse->closeCursor();
                }
                ?>
                </tbody>
              </table>
              <br>

              <div id="graphloca" style="height: 180px;"></div>
              <br>
              <div id="graph2loca" style="height: 180px;"></div>

              <br>
              <a href="<?= '../moteur/export_bilanc_parloca.php?numero=' . $_GET['numero'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>">
                <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
              </a>
            </div>
          </div>

        </div>
        <script>       Morris.Donut({
              element: 'graphmasse',

              data: [
  <?php
  if ($_GET['numero'] === 0) {
    // On recupère tout les masses collectés pour chaque type
    $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme
     FROM type_collecte,pesees_collectes,collectes WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id AND DATE(collectes.timestamp) BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
    }
    $reponse->closeCursor();
    ?>
                ],
                backgroundColor: '#ccc',
                labelColor: '#060',
                colors: [
    <?php
    // On recupère les couleurs de chaque type
    $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme
     FROM type_collecte,pesees_collectes,collectes WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id AND DATE(collectes.timestamp) BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo '"' . $donnees['couleur'] . '"' . ',';
    }
    $reponse->closeCursor();
    ?>
                ],
                formatter: function(x) {
                  return x + " Kg."
                }
              });
          </script>
          <?php
        } else {
          $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme
              FROM type_collecte,pesees_collectes,collectes
              WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
          }
          $reponse->closeCursor();
          ?>
          ],
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: [
          <?php
          $reponse = $bdd->prepare('SELECT type_collecte.couleur,type_collecte.nom, sum(pesees_collectes.masse) somme
              FROM type_collecte,pesees_collectes,collectes
              WHERE type_collecte.id = collectes.id_type_collecte AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo '"' . $donnees['couleur'] . '"' . ',';
          }
          $reponse->closeCursor();
          ?>
          ],
          formatter: function (x) { return x + " Kg."}
          });
          </script>
        <?php } ?>
        <script>       Morris.Donut({
            element: 'graph2masse',

            data: [
  <?php
  if ($_GET['numero'] === 0) {
    // On recupère tout les masses collectés pour chaque type
    $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
     FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
    }
    $reponse->closeCursor();
    ?>
              ],
              backgroundColor: '#ccc',
              labelColor: '#060',
              colors: [
    <?php
    // On recupère les couleurs de chaque type
    $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
     FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo "'" . $donnees['couleur'] . "'" . ',';
    }
    $reponse->closeCursor();
    ?>
              ],
              formatter: function(x) {
                return x + " Kg."
              }
            });
          </script>
          <?php
        } else {
          $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
              FROM type_dechets,pesees_collectes,collectes
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
          }
          $reponse->closeCursor();
          ?>
          ],
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: [
          <?php
          $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
              FROM type_dechets,pesees_collectes,collectes
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo "'" . $donnees['couleur'] . "'" . ',';
          }
          $reponse->closeCursor();
          ?>
          ],
          formatter: function (x) { return x + " Kg."}
          });
          </script>
        <?php } ?>
        <script>       Morris.Donut({
            element: 'graphloca',

            data: [
  <?php
  if ($_GET['numero'] === 0) {
    // On recupère tout les masses collectés pour chaque type
    $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(distinct pesees_collectes.masse) somme
     FROM type_dechets,pesees_collectes,collectes,localites WHERE localites.id = collectes.localisation AND pesees_collectes.id_collecte = collectes.id AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
    }
    $reponse->closeCursor();
    ?>
              ],
              backgroundColor: '#ccc',
              labelColor: '#060',
              colors: [
    <?php
    // On recupère les couleurs de chaque type
    $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(distinct pesees_collectes.masse) somme
     FROM type_dechets,pesees_collectes,collectes,localites WHERE localites.id = collectes.localisation AND pesees_collectes.id_collecte = collectes.id AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo "'" . $donnees['couleur'] . "'" . ',';
    }
    $reponse->closeCursor();
    ?>
              ],
              formatter: function(x) {
                return x + " Kg."
              }
            });
          </script>
          <?php
        } else {
          $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(pesees_collectes.masse) somme
              FROM localites,pesees_collectes,collectes
              WHERE localites.id = collectes.localisation AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
          }
          $reponse->closeCursor();
          ?>
          ],
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: [
          <?php
          $reponse = $bdd->prepare('SELECT localites.couleur,localites.nom, sum(pesees_collectes.masse) somme
              FROM localites,pesees_collectes,collectes
              WHERE localites.id = collectes.localisation AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo "'" . $donnees['couleur'] . "'" . ',';
          }
          $reponse->closeCursor();
          ?>
          ],
          formatter: function (x) { return x + " Kg."}
          });
          </script>

        <?php } ?>

        <script>       Morris.Donut({
            element: 'graph2loca',

            data: [
  <?php
  if ($_GET['numero'] === 0) {
    // On recupère tout les masses collectés pour chaque type
    $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
      FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
    }
    $reponse->closeCursor();
    ?>
              ],
              backgroundColor: '#ccc',
              labelColor: '#060',
              colors: [
    <?php
    // On recupère les couleurs de chaque type
    $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
      FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
GROUP BY nom');
    $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $reponse->fetch()) {
      echo "'" . $donnees['couleur'] . "'" . ',';
    }
    $reponse->closeCursor();
    ?>
              ],
              formatter: function(x) {
                return x + " Kg."
              }
            });
          </script>
          <?php
        } else {
          $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
              FROM type_dechets,pesees_collectes,collectes
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo '{value:' . $donnees['somme'] . ', label:"' . $donnees['nom'] . '"},';
          }
          $reponse->closeCursor();
          ?>
          ],
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: [
          <?php
          $reponse = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
              FROM type_dechets,pesees_collectes,collectes
              WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.timestamp BETWEEN :du AND :au
              AND pesees_collectes.id_collecte = collectes.id AND collectes.id_point_collecte = :numero
GROUP BY nom');
          $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

          while ($donnees = $reponse->fetch()) {
            echo "'" . $donnees['couleur'] . "'" . ',';
          }
          $reponse->closeCursor();
          ?>
          ],
          formatter: function (x) { return x + " Kg."}
          });
          </script>

        <?php } ?>
      </div>

      <br>

    </div>
  </div>
  <script type="text/javascript">
    'use strict';
    $(document).ready(() => {
      const get = process_get();
      const url = 'bilanc';
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
