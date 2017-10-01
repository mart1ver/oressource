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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once 'tete.php';
  require_once('../moteur/dbconfig.php');

  //formulaire permettant la correction de sorties
  ?>

  <div class="container">
    <h1>Vérification des sorties hors-boutique</h1>
    <div class="panel-body">
      <ul class="nav nav-tabs">
        <?php
        // On recupère tout le contenu des visibles de la table points_sortie
        $reponse = $bdd->query('SELECT * FROM points_sortie');
        while ($donnees = $reponse->fetch()) { ?>
          <li<?php
          if ($_GET['numero'] === $donnees['id']) {
            echo ' class="active"';
          }
          ?>><a href="<?= 'verif_sorties.php?numero=' . $donnees['id'] . '&date=' . $_GET['date']; ?>"><?= $donnees['nom']; ?></a></li>
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
            <i class="glyphicon glyphicon-calendar"> </i>
            <span></span> <b class="caret"></b>
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
    </div>
    <?php
    // On recupère toute la liste des filieres de sortie
    //   $reponse = $bdd->query('SELECT * FROM grille_objets');

    $req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties`
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au  AND classe = "sorties" ');
    $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $req->fetch()) {
      if ($donnees['nid'] > 0) {
        $req->closeCursor();
        ?>
        <div class="panel panel-info">
          <div class="panel-heading"><h3 class="panel-title">Dons simples:</h3> </div>
          <div class="panel-body">

            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date de création</th>
                  <th>Type de collecte</th>
                  <th>Commentaire</th>
                  <th>Masse totale</th>
                  <th>Auteur de la ligne</th>
                  <th></th>
                  <th>Modifié par</th>
                  <th>Le</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,type_sortie.nom,sorties.commentaire ,sorties.classe classe
                       FROM sorties ,type_sortie
                       WHERE type_sortie.id = sorties.id_type_sortie  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au
                       ORDER BY sorties.timestamp DESC');
                $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

                while ($donnees = $req->fetch()) { ?>
                  <tr>
                    <td><?= $donnees['id']; ?></td>
                    <td><?= $donnees['timestamp']; ?></td>
                    <td><?= $donnees['nom']; ?></td>
                    <td><?= $donnees['commentaire']; ?></td>

                    <td>

                      <?php
                      $req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
                      $req2->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees2 = $req2->fetch()) { ?>

                        <?= $donnees2['masse']; ?>
                      <?php } ?>
                    </td>
                    <td>
                      <?php
                      $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs ,sorties
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_createur');
                      $req3->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees3 = $req3->fetch()) { ?>

                        <?= $donnees3['mail']; ?>
                      <?php } ?>
                    </td>
                    <td>

                      <form action="modification_verification_sorties.php?nsortie=<?= $donnees['id']; ?>" method="post">

                        <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                        <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                        <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                        <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                        <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                        <button  class="btn btn-warning btn-sm" >Modifier</button>
                      </form>

                    </td>

                    <td><?php
                      $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM sorties, utilisateurs
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_last_hero
                       ');
                      $req5->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees5 = $req5->fetch()) { ?>

                        <?= $donnees5['mail']; ?>

                      <?php } ?></td>
                    <td><?php
                      $req4 = $bdd->prepare('SELECT sorties.last_hero_timestamp lht
                       FROM sorties
                       WHERE  sorties.id = :id_sortie
                       ');
                      $req4->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees4 = $req4->fetch()) {
                        if ($donnees4['lht'] !== '0000-00-00 00:00:00') {
                          echo $donnees4['lht'];
                        }
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
          </div>
        </div>
        <?php
      } else {
        echo 'Pas de dons sur cette période<br><br>';
        $req->closeCursor();
      }
    }

    // On recupère toute la liste des filieres de sortie
    //   $reponse = $bdd->query('SELECT * FROM grille_objets');

    $req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties`
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesc" ');
    $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $req->fetch()) {
      if ($donnees['nid'] > 0) {
        $req->closeCursor();
        ?>
        <div class="panel panel-info">
          <div class="panel-heading"><h3 class="panel-title">Sorties conventionées:</h3> </div>
          <div class="panel-body">            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date de création</th>
                  <th>Nom du partenaire</th>
                  <th>Commentaire</th>
                  <th>Masse totale</th>
                  <th>Auteur de la ligne</th>
                  <th></th>
                  <th>Modifié par</th>
                  <th>Le</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,conventions_sorties.nom,sorties.commentaire,sorties.adherent , sorties.classe classe
                       FROM sorties ,conventions_sorties
                       WHERE conventions_sorties.id = sorties.id_convention  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesc"
                       ORDER BY sorties.timestamp DESC');
                $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

                while ($donnees = $req->fetch()) { ?>
                  <tr>
                    <td><?= $donnees['id']; ?></td>
                    <td><?= $donnees['timestamp']; ?></td>
                    <td><?= $donnees['nom']; ?></td>
                    <td><?= $donnees['commentaire']; ?></td>

                    <td>

                      <?php
                      $req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
                      $req2->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees2 = $req2->fetch()) { ?>

                        <?= $donnees2['masse']; ?>
                      <?php } ?>
                    </td>

                    <td>
                      <?php
                      $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs ,sorties
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_createur');
                      $req3->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees3 = $req3->fetch()) { ?>

                        <?= $donnees3['mail']; ?>
                      <?php } ?>
                    </td>

                    <td>

                      <form action="modification_verification_sortiesc.php?nsortie=<?= $donnees['id']; ?>" method="post">

                        <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                        <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                        <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                        <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                        <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                        <button  class="btn btn-warning btn-sm" >Modifier</button>
                      </form>

                    <td><?php
                      $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM sorties, utilisateurs
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_last_hero
                       ');
                      $req5->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees5 = $req5->fetch()) { ?>

                        <?= $donnees5['mail']; ?>

                      <?php } ?></td>
                    <td><?php
                      $req4 = $bdd->prepare('SELECT sorties.last_hero_timestamp lht
                       FROM sorties
                       WHERE  sorties.id = :id_sortie
                       ');
                      $req4->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees4 = $req4->fetch()) {
                        if ($donnees4['lht'] !== '0000-00-00 00:00:00') {
                          echo $donnees4['lht'];
                        }
                      }
                      ?></td>

                    </td>
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
          </div>
        </div>
        <?php
      } else {
        echo 'Pas de sorties en direction des partenaires sur cette période<br><br>';
        $req->closeCursor();
      }
    }

    // On recupère toute la liste des filieres de sortie
    //   $reponse = $bdd->query('SELECT * FROM grille_objets');

    $req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties`
                       WHERE sorties.id_point_sortie = :id_point_sortie  AND DATE(sorties.timestamp) BETWEEN :du AND :au   AND classe = "sortiesr" ');
    $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $req->fetch()) {
      if ($donnees['nid'] > 0) {
        $req->closeCursor();
        ?>
        <div class="panel panel-info">
          <div class="panel-heading"><h3 class="panel-title">Sorties recyclage:</h3> </div>
          <div class="panel-body">            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date de création</th>
                  <th>Nom de l'entreprise</th>
                  <th>Commentaire</th>
                  <th>Masse totale</th>
                  <th>Auteur de la ligne</th>
                  <th></th>
                  <th>Modifié par</th>
                  <th>Le</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,filieres_sortie.nom,sorties.commentaire , sorties.classe classe
                       FROM sorties ,filieres_sortie
                       WHERE filieres_sortie.id = sorties.id_filiere  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesr"
                       ORDER BY sorties.timestamp DESC');
                $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

                while ($donnees = $req->fetch()) { ?>
                  <tr>
                    <td><?= $donnees['id']; ?></td>
                    <td><?= $donnees['timestamp']; ?></td>
                    <td><?= $donnees['nom']; ?></td>
                    <td><?= $donnees['commentaire']; ?></td>

                    <td>

                      <?php
                      $req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
                      $req2->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees2 = $req2->fetch()) { ?>

                        <?= $donnees2['masse']; ?>
                      <?php } ?>
                    </td>
                    <td>
                      <?php
                      $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs ,sorties
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_createur');
                      $req3->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees3 = $req3->fetch()) { ?>

                        <?= $donnees3['mail']; ?>
                      <?php } ?>
                    </td>
                    <td>

                      <form action="modification_verification_sortiesr.php?nsortie=<?= $donnees['id']; ?>" method="post">

                        <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                        <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                        <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                        <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                        <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                        <button  class="btn btn-warning btn-sm" >Modifier</button>
                      </form>

                    <td><?php
                      $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM sorties, utilisateurs
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_last_hero
                       ');
                      $req5->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees5 = $req5->fetch()) { ?>

                        <?= $donnees5['mail']; ?>

                      <?php } ?></td>
                    <td><?php
                      $req4 = $bdd->prepare('SELECT sorties.last_hero_timestamp lht
                       FROM sorties
                       WHERE  sorties.id = :id_sortie
                       ');
                      $req4->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees4 = $req4->fetch()) {
                        if ($donnees4['lht'] !== '0000-00-00 00:00:00') {
                          echo $donnees4['lht'];
                        }
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
          </div></div>
        <?php
      } else {
        echo 'Pas de sorties recyclage sur cette période<br><br>';
        $req->closeCursor();
      }
    }

    // On recupère toute la liste des filieres de sortie
    //   $reponse = $bdd->query('SELECT * FROM grille_objets');

    $req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties`
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesp" ');
    $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $req->fetch()) {
      if ($donnees['nid'] > 0) {
        $req->closeCursor();
        ?>
        <div class="panel panel-info">
          <div class="panel-heading"><h3 class="panel-title">Sorties Poubelles:</h3> </div>
          <div class="panel-body">            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date de création</th>
                  <th>Masse totale</th>
                  <th>Auteur de la ligne</th>
                  <th></th>
                  <th>Modifié par</th>
                  <th>Le</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $req = $bdd->prepare('SELECT sorties.id,sorties.timestamp , sorties.classe classe
                       FROM sorties
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesp"
                       ORDER BY sorties.timestamp DESC');
                $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

                while ($donnees = $req->fetch()) { ?>
                  <tr>
                    <td><?= $donnees['id']; ?></td>
                    <td><?= $donnees['timestamp']; ?></td>
                    <td>

                      <?php
                      $req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
                      $req2->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees2 = $req2->fetch()) { ?>

                        <?= $donnees2['masse']; ?>
                      <?php } ?>
                    </td>
                    <td>
                      <?php
                      $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs ,sorties
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_createur');
                      $req3->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees3 = $req3->fetch()) { ?>

                        <?= $donnees3['mail']; ?>
                      <?php } ?>
                    </td>
                    <td>

                      <form action="modification_verification_sortiesp.php?nsortie=<?= $donnees['id']; ?>" method="post">

                        <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                        <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                        <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                        <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                        <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                        <button  class="btn btn-warning btn-sm" >Modifier</button>
                      </form>
                    </td>

                    <td><?php
                      $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM sorties, utilisateurs
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_last_hero
                       ');
                      $req5->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees5 = $req5->fetch()) { ?>

                        <?= $donnees5['mail']; ?>

                      <?php } ?></td>
                    <td><?php
                      $req4 = $bdd->prepare('SELECT sorties.last_hero_timestamp lht
                       FROM sorties
                       WHERE  sorties.id = :id_sortie
                       ');
                      $req4->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees4 = $req4->fetch()) {
                        if ($donnees4['lht'] !== '0000-00-00 00:00:00') {
                          echo $donnees4['lht'];
                        }
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
          </div></div>
        <?php
      } else {
        echo 'Pas de poubelles evacuées sur cette période<br><br>';
        $req->closeCursor();
      }
    }

    // On recupère toute la liste des filieres de sortie
    //   $reponse = $bdd->query('SELECT * FROM grille_objets');

    $req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties`
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesd" ');
    $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

    while ($donnees = $req->fetch()) {
      if ($donnees['nid'] > 0) {
        $req->closeCursor();
        ?>
        <div class="panel panel-info">
          <div class="panel-heading"><h3 class="panel-title">Sorties déchetterie:</h3> </div>
          <div class="panel-body">            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Date de création</th>
                  <th>Commentaire</th>
                  <th>Masse totale</th>
                  <th>Auteur de la ligne</th>
                  <th></th>
                  <th>Modifié par</th>
                  <th>Le</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,sorties.commentaire , sorties.classe classe
                       FROM sorties ,conventions_sorties
                       WHERE  sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) BETWEEN :du AND :au AND classe = "sortiesd"
                       GROUP BY id ORDER BY sorties.timestamp DESC
                       ');
                $req->execute(['id_point_sortie' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);

                while ($donnees = $req->fetch()) { ?>
                  <tr>
                    <td><?= $donnees['id']; ?></td>
                    <td><?= $donnees['timestamp']; ?></td>
                    <td><?= $donnees['commentaire']; ?></td>

                    <td>

                      <?php
                      $req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
                      $req2->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees2 = $req2->fetch()) { ?>

                        <?= $donnees2['masse']; ?>
                      <?php } ?>
                    </td>

                    <td>
                      <?php
                      $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs ,sorties
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_createur');
                      $req3->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees3 = $req3->fetch()) { ?>

                        <?= $donnees3['mail']; ?>
                      <?php } ?>
                    </td>

                    <td>

                      <form action="modification_verification_sortiesd.php?nsortie=<?= $donnees['id']; ?>" method="post">

                        <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                        <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                        <input type="hidden" name ="date1" id="date1" value="<?= $_GET['date1']; ?>">
                        <input type="hidden" name ="date2" id="date2" value="<?= $_GET['date2']; ?>">
                        <input type="hidden" name ="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                        <button  class="btn btn-warning btn-sm" >Modifier</button>
                      </form>

                    <td><?php
                      $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM sorties, utilisateurs
                       WHERE  sorties.id = :id_sortie
                       AND  utilisateurs.id = sorties.id_last_hero
                       ');
                      $req5->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees5 = $req5->fetch()) { ?>

                        <?= $donnees5['mail']; ?>

                      <?php } ?></td>
                    <td><?php
                      $req4 = $bdd->prepare('SELECT sorties.last_hero_timestamp lht
                       FROM sorties
                       WHERE  sorties.id = :id_sortie
                       ');
                      $req4->execute(['id_sortie' => $donnees['id']]);

                      while ($donnees4 = $req4->fetch()) {
                        if ($donnees4['lht'] !== '0000-00-00 00:00:00') {
                          echo $donnees4['lht'];
                        }
                      }
                      ?></td>

                    </td>
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
          </div>
        </div>
        <?php
      } else {
        echo 'Pas de sorties en direction de la dechetterie sur cette periode<br><br>';
        $req->closeCursor();
      }
    }
    ?>

  </div><!-- /.container -->
  <script type="text/javascript">
    'use strict';
    $(document).ready(() => {
      const get = process_get();
      const url = 'verif_sorties';
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
