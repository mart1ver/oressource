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


if (is_valid_session() && is_allowed_bilan()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';
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
          <li><a href="<?= 'bilanc.php?date1=' . $_GET['date1'] . '&date2=' . $_GET['date2'] . '&numero=0'; ?>">Collectes</a></li>
          <li class="active"><a>Sorties hors-boutique</a></li>
          <li><a href="<?= 'bilanv.php?date1=' . $_GET['date1'] . '&date2=' . $_GET['date2'] . '&numero=0'; ?>">Ventes</a></li>
        </ul>
      </div>
    </div>

  </div>
  <div class="row">
    <div class="col-md-8 col-md-offset-1" >
      <h2> Bilan des sorties hors-boutique de la structure
      </h2>
      <ul class="nav nav-tabs">
        <?php
        $reponse = $bdd->query('SELECT * FROM points_sortie');
        while ($donnees = $reponse->fetch()) { ?>
          <li<?php
          if ($_GET['numero'] === $donnees['id']) {
            echo ' class="active"';
          }
          ?>><a href="<?= 'bilanhb.php?numero=' . $donnees['id'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>"> <?= $donnees['nom']; ?> </a></li>
            <?php
          }
          $reponse->closeCursor();
          ?>
        <li<?php
        if ($_GET['numero'] === 0) {
          echo ' class="active"';
        }
        ?>><a href="<?= 'bilanhb.php?numero=0' . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>">Tous les points</a></li>
      </ul>

      <br>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8 col-md-offset-1">
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
        masse totale évacuée: <?php
        if ($_GET['numero'] === 0) {
          $req = $bdd->prepare('SELECT SUM(pesees_sorties.masse)AS total   FROM pesees_sorties  WHERE  pesees_sorties.timestamp BETWEEN :du AND :au ');
          $req->execute(['du' => $time_debut, 'au' => $time_fin]);
          $donnees = $req->fetch();
          $mtotcolo = $donnees['total'];
          echo $donnees['total'] . ' Kgs.';

          $req->closeCursor();
        } else {
          //si on observe un point en particulier

          $req = $bdd->prepare('SELECT SUM(pesees_sorties.masse)AS total
FROM pesees_sorties ,sorties
WHERE pesees_sorties.id_sortie = sorties.id
AND pesees_sorties.timestamp BETWEEN :du AND :au  AND sorties.id_point_sortie = :numero ');
          $req->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);
          $donnees = $req->fetch();
          $mtotcolo = $donnees['total'];
          echo $donnees['total'] . ' Kgs.';

          $req->closeCursor();
        }
        if ($_GET['numero'] === 0) { ?>
          , sur <?php
// on determine le nombre de points de collecte
          /*
           */
          $req = $bdd->prepare('SELECT COUNT(id) FROM points_sortie');
          $req->execute(['au' => $time_fin]);
          $donnees = $req->fetch();

          echo $donnees['COUNT(id)'];
          $req->closeCursor();
          ?> Point(s) de sorties.

        <?php } ?></h2>
    </div>
  </div>
  <div class="row">
    <div class="col-md-5 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Répartition par classe de sorties
          </h3>
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

              <?php
              if ($_GET['numero'] === 0) {
                $reponse = $bdd->prepare('SELECT
SUM(pesees_sorties.masse) somme,pesees_sorties.timestamp,sorties.classe,COUNT(distinct sorties.id) ncol
FROM
pesees_sorties,sorties
WHERE
  pesees_sorties.timestamp BETWEEN :du AND :au  AND pesees_sorties.id_sortie = sorties.id
GROUP BY classe');
                $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                while ($donnees = $reponse->fetch()) { ?>
                  <tr data-toggle="collapse" data-target=".parmasse<?= $donnees['classe']; ?>">

                    <?php
                    switch ($donnees['classe']) {
                      case 'sortiesc';
                        ?>
                        <td>don aux partenaires</td>
                        <?php
                        break;
                      case 'sorties';
                        ?>
                        <td>don</td>
                        <?php
                        break;
                      case 'sortiesd';
                        ?>
                        <td>dechetterie</td>
                        <?php
                        break;
                      case 'sortiesp';
                        ?>
                        <td>poubelles</td>
                        <?php
                        break;
                      case 'sortiesr';
                        ?>
                        <td>recycleurs</td>
                        <?php
                        break;
                      default;
                        ?>
                        <td>base érronée</td>
                    <?php } ?>

                    <td><?= $donnees['ncol']; ?></td>
                    <td><?= $donnees['somme']; ?></td>
                    <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                  </tr>
                  <?php
                }
                $reponse->closeCursor();
              } else {
                $reponse = $bdd->prepare('SELECT
SUM(pesees_sorties.masse) somme,pesees_sorties.timestamp,sorties.classe,COUNT(distinct sorties.id) ncol
FROM
pesees_sorties,sorties
WHERE
  pesees_sorties.timestamp BETWEEN :du AND :au  AND pesees_sorties.id_sortie = sorties.id AND sorties.id_point_sortie = :numero
GROUP BY classe');
                $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                while ($donnees = $reponse->fetch()) { ?>
                  <tr data-toggle="collapse" data-target=".parmasse<?= $donnees['classe']; ?>">
                    <?php
                    switch ($donnees['classe']) {
                      case 'sortiesc';
                        ?>
                        <td>don aux partenaires</td>
                        <?php
                        break;
                      case 'sorties';
                        ?>
                        <td>don</td>
                        <?php
                        break;
                      case 'sortiesd';
                        ?>
                        <td>dechetterie</td>
                        <?php
                        break;
                      case 'sortiesp';
                        ?>
                        <td>poubelles</td>
                        <?php
                        break;
                      case 'sortiesr';
                        ?>
                        <td>recycleurs</td>
                        <?php
                        break;
                      default;
                        ?>
                        <td>base érronée</td>
                    <?php } ?>
                    <td><?= $donnees['ncol']; ?></td>
                    <td><?= $donnees['somme']; ?></td>
                    <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                  </tr>
                  <?php
                }
                $reponse->closeCursor();
              }
              ?>
            </tbody>
          </table>
          <br>
          <a href="<?= '../moteur/export_bilanc_partype.php?numero=' . $_GET['numero'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>">

            <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
          </a>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">détail par type d'objets
          </h3>
        </div>
        <div class="panel-body">

          <?php if ($_GET['numero'] === 0) { ?>
            <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse0" aria-expanded="false" aria-controls="collapse0">
                Dons simples
              </a></div>
            <div class="collapse" id="collapse0">
              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                  <tr>
                    <th style="width:300px">typo</th>
                    <th>masse</th>
                    <th>%</th>
                  </tr>
                </thead>

                <?php
                $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sorties"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sorties"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom2');
                $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                while ($donnees = $reponse->fetch()) { ?>
                  <tr>
                    <td><?= $donnees['nom']; ?></td>
                    <td><?= $donnees['somme']; ?></td>
                    <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                  </tr>
                  <?php
                }
                $reponse->closeCursor();
                ?>

                </tbody>
              </table>
            </div>
            <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse0">
                Dons aux partenaires
              </a></div>
            <div class="collapse" id="collapse1">
              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                  <tr>
                    <th style="width:300px">typo</th>
                    <th>masse</th>
                    <th>%</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesc"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesc"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom2');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                  while ($donnees = $reponse->fetch()) { ?>

                  <td><?= $donnees['nom']; ?></td>
                  <td><?= $donnees['somme']; ?></td>
                  <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                  </tr>
                  <?php
                }
                $reponse->closeCursor();
                ?>

                </tbody>
              </table>

            </div>
            <div class="list-group">
              <a class="list-group-item" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse0">
                dechetterie
              </a>
            </div>
            <div class="collapse" id="collapse2">
              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                  <tr>
                    <th style="width:300px">typo</th>
                    <th>masse</th>
                    <th>%</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesd"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesd"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom2');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                  while ($donnees = $reponse->fetch()) { ?>

                  <td><?= $donnees['nom']; ?></td>
                  <td><?= $donnees['somme']; ?></td>
                  <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                  </tr>
                  <?php
                }
                $reponse->closeCursor();
                ?>

                </tbody>
              </table>
            </div>
            <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse0">
                poubelles
              </a></div>
            <div class="collapse" id="collapse3">
              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                  <tr>
                    <th style="width:300px">type de bac</th>
                    <th>masse</th>
                    <th>%</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT types_poubelles.nom, sum(pesees_sorties.masse) somme
FROM types_poubelles, pesees_sorties, sorties
WHERE
pesees_sorties.id_sortie = sorties.id
AND
types_poubelles.id = pesees_sorties.id_type_poubelle
AND sorties.classe = "sortiesp"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom
');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                  while ($donnees = $reponse->fetch()) { ?>

                  <td><?= $donnees['nom']; ?></td>
                  <td><?= $donnees['somme']; ?></td>
                  <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                  </tr>
                  <?php
                }
                $reponse->closeCursor();
                ?>

                </tbody>
              </table>

            </div>
            <div class="list-group">
              <a class="list-group-item" data-toggle="collapse" href="#collapse4" aria-expanded="false" aria-controls="collapse0">
                recycleurs
              </a>
            </div>
            <div class="collapse" id="collapse4">

              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                  <tr>
                    <th style="width:300px">typo</th>
                    <th>masse</th>
                    <th>%</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesr"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesr"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom2');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                  while ($donnees = $reponse->fetch()) { ?>

                  <td><?= $donnees['nom']; ?></td>
                  <td><?= $donnees['somme']; ?></td>
                  <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                  </tr>
                  <?php
                }
                $reponse->closeCursor();
              } else { ?>

                <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse0" aria-expanded="false" aria-controls="collapse0">
                    Dons simples
                  </a></div>
                <div class="collapse" id="collapse0">
                  <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                    <thead>

                      <tr>
                        <th style="width:300px">typo</th>
                        <th>masse</th>
                        <th>%</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sorties"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sorties"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom2');
                      $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                      while ($donnees = $reponse->fetch()) { ?>
                        <tr>
                          <td><?= $donnees['nom']; ?></td>
                          <td><?= $donnees['somme']; ?></td>
                          <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                        </tr>
                        <?php
                      }
                      $reponse->closeCursor();
                      ?>

                    </tbody>
                  </table>

                </div>
                <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse0">
                    Dons aux partenaires
                  </a></div>
                <div class="collapse" id="collapse1">

                  <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                    <thead>

                      <tr>
                        <th style="width:300px">typo</th>
                        <th>masse</th>
                        <th>%</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesc"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesc"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom2');
                      $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                      while ($donnees = $reponse->fetch()) { ?>

                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                      </tr>
                      <?php
                    }
                    $reponse->closeCursor();
                    ?>

                    </tbody>
                  </table>

                </div>
                <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse0">
                    dechetterie
                  </a></div>
                <div class="collapse" id="collapse2">
                  <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">

                    <tr>
                      <th style="width:300px">typo</th>
                      <th>masse</th>
                      <th>%</th>
                    </tr>
                    </thead>
                    <tbody>

                      <?php
                      $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesd"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesd"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom2');
                      $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                      while ($donnees = $reponse->fetch()) { ?>

                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                      </tr>
                      <?php
                    }
                    $reponse->closeCursor();
                    ?>

                    </tbody>
                  </table>

                </div>
                <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse0">
                    poubelles
                  </a></div>
                <div class="collapse" id="collapse3">

                  <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                    <thead>

                      <tr>
                        <th style="width:300px">type de bac</th>
                        <th>masse</th>
                        <th>%</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $reponse = $bdd->prepare('SELECT types_poubelles.nom, sum(pesees_sorties.masse) somme
FROM types_poubelles, pesees_sorties, sorties
WHERE
pesees_sorties.id_sortie = sorties.id
AND
types_poubelles.id = pesees_sorties.id_type_poubelle
AND sorties.classe = "sortiesp"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom
');
                      $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                      while ($donnees = $reponse->fetch()) { ?>

                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                      </tr>
                      <?php
                    }
                    $reponse->closeCursor();
                    ?>

                    </tbody>
                  </table>

                </div>
                <div class="list-group"><a class="list-group-item" data-toggle="collapse" href="#collapse4" aria-expanded="false" aria-controls="collapse0">
                    recycleurs
                  </a></div>
                <div class="collapse" id="collapse4">

                  <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                    <thead>
                      <tr>
                        <th style="width:300px">typo</th>
                        <th>masse</th>
                        <th>%</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $reponse = $bdd->prepare('SELECT type_dechets.nom, sum(pesees_sorties.masse) somme
FROM type_dechets, pesees_sorties, sorties
WHERE
type_dechets.id=pesees_sorties.id_type_dechet
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesr"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom
UNION
SELECT type_dechets_evac.nom nom2, sum(pesees_sorties.masse) somme
FROM type_dechets_evac, pesees_sorties, sorties
WHERE
type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesr"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom2');
                      $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                      while ($donnees = $reponse->fetch()) { ?>

                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                      </tr>
                      <?php
                    }
                    $reponse->closeCursor();
                  }
                  ?>

                  </tbody>
                </table>

              </div>
              <br>
              <a href="<?= '../moteur/export_bilanc_parloca.php?numero=' . $_GET['numero'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>">
                <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
              </a>
          </div>
        </div>
      </div>

      <div class="col-md-5">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Partenaires, recycleurs et dons
            </h3>
          </div>
          <div class="panel-body">

            <?php if ($_GET['numero'] === 0) { ?>

              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                <th style="width:300px">Dons simples</th>
                <tr>
                  <th style="width:300px">type de sortie</th>
                  <th>masse</th>
                  <th>%</th>
                </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT type_sortie.nom, sum(pesees_sorties.masse) somme
FROM type_sortie, pesees_sorties, sorties
WHERE
type_sortie.id=sorties.id_type_sortie
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sorties"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                  while ($donnees = $reponse->fetch()) { ?>
                    <tr>
                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                    </tr>
                    <?php
                  }
                  $reponse->closeCursor();
                  ?>

                </tbody>
              </table>
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

                  <?php
                  $reponse = $bdd->prepare('SELECT conventions_sorties.nom, sum(pesees_sorties.masse) somme, COUNT(sorties.id) nombre
FROM conventions_sorties, pesees_sorties, sorties
WHERE
conventions_sorties.id=sorties.id_convention
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesc"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                  while ($donnees = $reponse->fetch()) { ?>
                    <tr>
                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['nombre']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                    </tr>
                    <?php
                  }
                  $reponse->closeCursor();
                  ?>

                </tbody>
              </table>
              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                <th style="width:300px">Recycleurs</th>
                <tr>
                  <th style="width:300px">Nom du recycleur</th>
                  <th>masse</th>
                  <th>%</th>
                </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT filieres_sortie.nom, sum(pesees_sorties.masse) somme
FROM filieres_sortie, pesees_sorties, sorties
WHERE
filieres_sortie.id=sorties.id_filiere
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesr"
AND pesees_sorties.timestamp BETWEEN :du AND :au
GROUP BY nom');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin]);

                  while ($donnees = $reponse->fetch()) { ?>
                    <tr>
                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                    </tr>
                    <?php
                  }
                  $reponse->closeCursor();
                  ?>

                </tbody>
              </table>
              <?php
            } else { ?>

              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                <th style="width:300px">Dons simples</th>
                <tr>
                  <th style="width:300px">type de sortie</th>
                  <th>masse</th>
                  <th>%</th>
                </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT type_sortie.nom, sum(pesees_sorties.masse) somme
FROM type_sortie, pesees_sorties, sorties
WHERE
type_sortie.id=sorties.id_type_sortie
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sorties"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                  while ($donnees = $reponse->fetch()) { ?>
                    <tr>
                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                    </tr>
                    <?php
                  }
                  $reponse->closeCursor();
                  ?>

                </tbody>
              </table>
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

                  <?php
                  $reponse = $bdd->prepare('SELECT conventions_sorties.nom, sum(pesees_sorties.masse) somme, COUNT(sorties.id) nombre
FROM conventions_sorties, pesees_sorties, sorties
WHERE
conventions_sorties.id=sorties.id_convention
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesc"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                  while ($donnees = $reponse->fetch()) { ?>
                    <tr>
                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['nombre']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                    </tr>
                    <?php
                  }
                  $reponse->closeCursor();
                  ?>

                </tbody>
              </table>
              <table class="table table-condensed table-striped table table-bordered table-hover" style="border-collapse:collapse;">
                <thead>
                <th style="width:300px">Recycleurs</th>
                <tr>
                  <th style="width:300px">Nom du recycleur</th>
                  <th>masse</th>
                  <th>%</th>
                </tr>
                </thead>
                <tbody>

                  <?php
                  $reponse = $bdd->prepare('SELECT filieres_sortie.nom, sum(pesees_sorties.masse) somme
FROM filieres_sortie, pesees_sorties, sorties
WHERE
filieres_sortie.id=sorties.id_filiere
AND
pesees_sorties.id_sortie = sorties.id
AND sorties.classe = "sortiesr"
AND pesees_sorties.timestamp BETWEEN :du AND :au
AND sorties.id_point_sortie = :numero
GROUP BY nom');
                  $reponse->execute(['du' => $time_debut, 'au' => $time_fin, 'numero' => $_GET['numero']]);

                  while ($donnees = $reponse->fetch()) { ?>
                    <tr>
                      <td><?= $donnees['nom']; ?></td>
                      <td><?= $donnees['somme']; ?></td>
                      <td><?= round($donnees['somme'] * 100 / $mtotcolo, 2); ?></td>
                    </tr>
                    <?php
                  }
                  $reponse->closeCursor();
                  ?>

                </tbody>
              </table>
            <?php } ?>
            <br>
            <a href="<?= '../moteur/export_bilanc_partype.php?numero=' . $_GET['numero'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>">

              <button type="button" class="btn btn-default btn-xs" disabled>exporter ces données (.csv) </button>
            </a>
          </div>

        </div>

      </div>
    </div>
    <script type="text/javascript">
      'use strict';
      $(document).ready(() => {
        const get = process_get();
        const url = 'bilanhb';
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
