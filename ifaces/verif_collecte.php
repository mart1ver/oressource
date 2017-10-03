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
  require_once('../moteur/dbconfig.php');
  require_once 'tete.php';
  ?>


  <div class="container" style="width:1300px">
    <h1>Vérification des collectes</h1>
    <div class="panel-body">
      <ul class="nav nav-tabs">
        <?php
        $reponse = $bdd->query('SELECT * FROM points_collecte');
        while ($donnees = $reponse->fetch()) { ?>
          <li<?php
          if ($_GET['numero'] === $donnees['id']) {
            echo ' class="active"';
          }
          ?>><a href="<?= 'verif_collecte.php?numero=' . $donnees['id'] . '&date1=' . $_GET['date1'] . '&date2=' . $_GET['date2']; ?>"><?= $donnees['nom']; ?></a></li>
            <?php
          }
          $reponse->closeCursor();
          ?>
      </ul>

      <br>
      <div class="row">
        <div class="col-md-3 col-md-offset-9" >
          <label for="reportrange">Choisissez la période à inspecter:</label><br>
          <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
            <i class="fa fa-calendar"></i>
            <span></span> <b class="caret"></b>
          </div>
        </div>
      </div>
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

    <?php
    // On recupère toute la liste des filieres de sortie
    //   $reponse = $bdd->query('SELECT * FROM grille_objets');

    $req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `collectes`
                       WHERE collectes.id_point_collecte = :id_point_collecte AND DATE(collectes.timestamp) BETWEEN :du AND :au   ');
    $req->execute(['id_point_collecte' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $req->fetch()) {
      if ($donnees['nid'] > 0) {
        $req->closeCursor();
        ?>

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Moment de la collecte</th>
              <th>Type de collecte</th>
              <th>Commentaire</th>
              <th>Localité</th>
              <th>Masse totale</th>
              <th>Créée par</th>
              <th></th>
              <th>Modifié par</th>
              <th>Le:</th>
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

            $req = $bdd->prepare('SELECT collectes.id,collectes.timestamp ,type_collecte.nom, collectes.commentaire, localites.nom localisation, utilisateurs.mail mail , collectes.last_hero_timestamp lht
                       FROM collectes ,type_collecte, localites,utilisateurs
                       WHERE type_collecte.id = collectes.id_type_collecte

                        AND utilisateurs.id = collectes.id_createur
                        AND localites.id = collectes.localisation
                        AND collectes.id_point_collecte = :id_point_collecte
                        AND DATE(collectes.timestamp) BETWEEN :du AND :au  ');
            $req->execute(['id_point_collecte' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

            while ($donnees = $req->fetch()) { ?>
              <tr>
                <td style="height:20px"><?= $donnees['id']; ?></td>
                <td style="height:20px"><?= $donnees['timestamp']; ?></td>
                <td style="height:20px"><?= $donnees['nom']; ?></td>
                <td width="20%" style="height:20px"><?= $donnees['commentaire']; ?></td>
                <td style="height:20px"><?= $donnees['localisation']; ?></td>
                <td style="height:20px">
                  <?php
                  $req2 = $bdd->prepare('SELECT SUM(pesees_collectes.masse) masse
                       FROM pesees_collectes
                       WHERE  pesees_collectes.id_collecte = :id_collecte ');
                  $req2->execute(['id_collecte' => $donnees['id']]);

                  while ($donnees2 = $req2->fetch()) { ?>

                    <?= $donnees2['masse']; ?>
                  <?php } ?>
                </td>

                <td><?= $donnees['mail']; ?></td>

                <td>

                  <form action="modification_verification_collecte.php?ncollecte=<?= $donnees['id']; ?>" method="post">

                    <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                    <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                    <input type="hidden" name ="localisation" id="localisation" value="<?= $donnees['localisation']; ?>">
                    <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                    <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                    <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                    <button  class="btn btn-warning btn-sm" >Modifier</button>
                  </form>

                </td>

                <td>

                  <?php
                  $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, collectes
                       WHERE  collectes.id = :id_collecte
                       AND utilisateurs.id = collectes.id_last_hero');
                  $req3->execute(['id_collecte' => $donnees['id']]);

                  while ($donnees3 = $req3->fetch()) { ?>

                    <?= $donnees3['mail']; ?>
                    <?php
                  }
                  $req3->closeCursor();
                  3;
                  ?>

                </td>
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
            2;
            ?>
          </tbody>
        </table>

        <?php
      } else {
        echo 'Pas de correspondance trouvée pour cette période<br><br>';
        $req->closeCursor();
      }
    }
    ?>
  </div><!-- /.container -->
  <script type="text/javascript">
    'use strict';
    $(document).ready(() => {
      const query = process_get();
      const base = 'verif_collecte.php';
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
