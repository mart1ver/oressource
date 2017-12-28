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
    <h1>Modifier la sortie n° <?= $_GET['nsortie']; ?></h1>
    <div class="panel-body">
      <br>
      <div class="row">

        <form action="../moteur/modification_verification_sortiesd_post.php?nsortie=<?= $_GET['nsortie']; ?>" method="post">
          <input type="hidden" name ="id" id="id" value="<?= $_GET['nsortie']; ?>">

          <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">
          <div class="col-md-3">

            <label for="commentaire">Commentaire</label>

            <textarea name="commentaire" id="commentaire" class="form-control"><?php
              // On affiche le commentaire
              $reponse = $bdd->prepare('SELECT commentaire FROM sorties WHERE id = :id_sortie');
              $reponse->execute(['id_sortie' => $_GET['nsortie']]);

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
    <h1>Pesées incluses dans cette sortie déchetterie</h1>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Type de dechet:</th>
          <th>Masse</th>
          <th>Auteur de la ligne</th>
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

          SELECT pesees_collectes.id ,pesees_collectes.timestamp  ,type_dechets.nom  , pesees_collectes.masse
          FROM pesees_collectes ,type_dechets
          WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.id_collecte = :id_collecte
         */

        // On recupère toute la liste des filieres de sortie
        //   $reponse = $bdd->query('SELECT * FROM grille_objets');

        $req = $bdd->prepare('SELECT pesees_sorties.id ,pesees_sorties.timestamp  ,type_dechets_evac.nom , pesees_sorties.masse ,type_dechets_evac.couleur
                       FROM pesees_sorties ,type_dechets_evac
                       WHERE type_dechets_evac.id = pesees_sorties.id_type_dechet_evac AND pesees_sorties.id_sortie = :id_sortie
                       GROUP BY pesees_sorties.id
                       ');
        $req->execute(['id_sortie' => $_GET['nsortie']]);

        while ($donnees = $req->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><span class="badge" id="cool" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['nom']; ?></span></td>
            <td><?= $donnees['masse']; ?></td>

            <td>
              <?php
              $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs ,pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie
                       AND  utilisateurs.id = pesees_sorties.id_createur
                       GROUP BY mail');
              $req3->execute(['id_sortie' => $_GET['nsortie']]);

              while ($donnees3 = $req3->fetch()) { ?>

                <?= $donnees3['mail']; ?>
              <?php } ?>
            </td>

            <td>

              <form action="modification_verification_pesee_sortiesd.php" method="post">

                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name ="nomtypo_evac" id="nomtypo_evac" value="<?= $donnees['nom']; ?>">
                <input type="hidden" name ="nsortie" id="nsortie" value="<?= $_GET['nsortie']; ?>">
                <input type="hidden" name ="masse" id="masse" value="<?= $donnees['masse']; ?>">
                <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
                <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
                <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">

                <button  class="btn btn-warning btn-sm" >Modifier</button>
              </form>

            </td>

            <td><?php
              $req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM pesees_sorties, utilisateurs
                       WHERE  pesees_sorties.id = :id_sortie
                       AND  utilisateurs.id = pesees_sorties.id_last_hero
                       ');
              $req5->execute(['id_sortie' => $donnees['id']]);

              while ($donnees5 = $req5->fetch()) { ?>

                <?= $donnees5['mail']; ?>

              <?php } ?></td>
            <td><?php
              $req4 = $bdd->prepare('SELECT pesees_sorties.last_hero_timestamp lht
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id = :id_sortie
                       ');
              $req4->execute(['id_sortie' => $donnees['id']]);

              while ($donnees4 = $req4->fetch()) {
                if ($donnees4['lht'] !== '0000-00-00 00:00:00') {
                  echo $donnees4['lht'];
                }
              }
              ?>

          </tr>
          <?php
        }
        $req->closeCursor();
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
