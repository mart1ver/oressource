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
  <div class="container">
    <h1>Modifier la collecte n° <?= $_GET['ncollecte']; ?></h1>
    <div class="panel-body">
      <br>
      <div class="row">

        <form action="../moteur/modification_verification_collecte_post.php?ncollecte=<?= $_GET['ncollecte']; ?>" method="post">
          <input type="hidden" name ="id" id="id" value="<?= $_GET['ncollecte']; ?>">

          <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">

          <div class="col-md-3">

            <label for="id_type_collecte">Type de collecte:</label>
            <select name="id_type_collecte" id="id_type_collecte" class="form-control " required>
              <?php
              // On affiche une liste deroulante des type de collecte visibles
              $reponse = $bdd->query('SELECT * FROM type_collecte WHERE visible = "oui"');

              while ($donnees = $reponse->fetch()) {
                if ($_POST['nom'] === $donnees['nom']) {  // SI on a pas de message d'erreur
                  ?>
                  <option value="<?= $donnees['id']; ?>" selected ><?= $donnees['nom']; ?></option>
                  <?php
                } else { ?>

                  <option value="<?= $donnees['id']; ?>"><?= $donnees['nom']; ?></option>
                  <?php
                }
              }
              $reponse->closeCursor();
              ?>
            </select>

          </div>
          <div class="col-md-3">

            <label for="id_localite">Localisation:</label>
            <select name="id_localite" id="id_localite" class="form-control " required>
              <?php
              // On affiche une liste deroulante des type de collecte visibles
              $reponse = $bdd->query('SELECT * FROM localites WHERE visible = "oui"');

              while ($donnees = $reponse->fetch()) {
                if ($_POST['localisation'] === $donnees['nom']) { // SI on a pas de message d'erreur
                  ?>
                  <option value="<?= $donnees['id']; ?>" selected ><?= $donnees['nom']; ?></option>
                  <?php
                } else { ?>

                  <option value="<?= $donnees['id']; ?>"><?= $donnees['nom']; ?></option>
                  <?php
                }
              }
              $reponse->closeCursor();
              ?>
            </select>

          </div>
          <div class="col-md-3">

            <label for="commentaire">Commentaire</label>
            <textarea name="commentaire" id="commentaire" class="form-control"><?php
              // On affiche le commentaire
              $reponse = $bdd->prepare('SELECT commentaire FROM collectes WHERE id = :id_collecte');
              $reponse->execute(['id_collecte' => $_GET['ncollecte']]);

              while ($donnees = $reponse->fetch()) {
                echo $donnees['commentaire'];
              }
              $reponse->closeCursor();
              ?></textarea>

          </div>
          <div class="col-md-3">

            <br>
            <button name="creer" class="btn btn-warning">Modifier</button>
          </div>
        </form>
      </div>

    </div>
    <h1>Pesées incluses dans cette collecte</h1>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Moment de la collecte</th>
          <th>Type de déchet:</th>
          <th>Masse</th>

          <th>Auteur de la ligne</th>
          <th></th>
          <th>Modifié par</th>
          <th>Le:</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $req = $bdd->prepare('SELECT pesees_collectes.id ,pesees_collectes.timestamp  ,type_dechets.nom  , pesees_collectes.masse ,type_dechets.couleur , utilisateurs.mail mail , pesees_collectes.last_hero_timestamp lht
 FROM pesees_collectes ,type_dechets ,utilisateurs,collectes
                       WHERE type_dechets.id = pesees_collectes.id_type_dechet
                       AND utilisateurs.id = pesees_collectes.id_createur
                       AND pesees_collectes.id_collecte = :id_collecte
GROUP BY id');
        $req->execute(['id_collecte' => $_GET['ncollecte']]);

        while ($donnees = $req->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><span class="badge" id="cool" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['nom']; ?></span></td>
            <td><?= $donnees['masse']; ?></td>

            <td><?= $donnees['mail']; ?></td>

            <td>

              <form action="modification_verification_pesee.php" method="post">

                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name ="nomtypo" id="nomtypo" value="<?= $donnees['nom']; ?>">
                <input type="hidden" name ="ncollecte" id="ncollecte" value="<?= $_GET['ncollecte']; ?>">
                <input type="hidden" name ="masse" id="masse" value="<?= $donnees['masse']; ?>">
                <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
                <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
                <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">

                <button  class="btn btn-warning btn-sm" >Modifier</button>
              </form>

            </td>
            <td><?php
              $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, pesees_collectes
                       WHERE  pesees_collectes.id = :id_collecte
                       AND utilisateurs.id = pesees_collectes.id_last_hero');
              $req3->execute(['id_collecte' => $donnees['id']]);

              while ($donnees3 = $req3->fetch()) { ?>

                <?= $donnees3['mail']; ?>
                <?php
              }
              $req3->closeCursor();
              3;
              ?></td>
            <td><?php
              if ($donnees['lht'] !== '0000-00-00 00:00:00') {
                echo $donnees['lht'];
              }
              ?></td>

          </tr>
          <?php
        }
        $req->closeCursor();
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th></th>

          <th></th>

          <th></th>
          <th></th>
          <th></th>

      </tfoot>

    </table>

  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
