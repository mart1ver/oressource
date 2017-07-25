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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'k') !== false)) {
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Gestion des points de collecte</h1>
    <div class="panel-heading">Gérez ici les différents points de collecte.</div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/edition_points_collecte_post.php" method="post">
          <div class="col-md-2"><label for="nom">Nom:</label><br><br><input type="text" value ="<?= $_GET['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-3"><label for="adresse">Adresse:</label><br><br><input type="text" value ="<?= $_GET['adresse']; ?>" name="adresse" id="adresse" class="form-control" required></div>
          <div class="col-md-2"><label for="commentaire">Commentaire:</label><br><br> <input type="text" value ="<?= $_GET['commentaire']; ?>" name="commentaire" id="commentaire" class="form-control" required></div>
          <div class="col-md-2"><label for="pesee_max">Masse maxi. d'une pesée (Kg):</label> <input type="text" value ="<?= $_GET['pesee_max']; ?>" name="pesee_max" id="pesee_max" class="form-control" required></div>
          <div class="col-md-1"><label for="couleur">Couleur:</label><br><br><input type="color" value="<?php
            if (isset($_GET['couleur'])) {
              echo '#' . $_GET['couleur'];
            }
            ?>" name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><br><button name="creer" class="btn btn-default">Creer!</button></div>
        </form>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Adresse</th>
          <th>Couleur</th>
          <th>Commentaire</th>
          <th>Pesée maxi.</th>
          <th>Visible</th>
          <th>Modifier</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // On recupère tout le contenu de la table points-collecte
        $reponse = $bdd->query('SELECT * FROM points_collecte');

        while ($donnees = $reponse->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['adresse']; ?></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td><?= $donnees['commentaire']; ?></td>
            <td><?= $donnees['pesee_max']; ?></td>
            <td>
              <form action="../moteur/collectes_visibles_post.php" method="post">
                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name="visible" id="visible" value="<?php
                if ($donnees['visible'] === 'oui') {
                  echo 'non';
                } else {
                  echo 'oui';
                }
                ?>">
                       <?php
                       if ($donnees['visible'] === 'oui') { // SI on a pas de message d'erreur
                         ?>
                  <button  class="btn btn-info btn-sm" >
                    <?php
                  } else { // SINON
                    ?>
                    <button  class="btn btn-danger btn-sm " >
                      <?php
                    }
                    echo $donnees['visible'];
                    ?>
                  </button>
              </form>
            </td>
            <td>
              <form action="modification_points_collecte.php" method="post">
                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                <input type="hidden" name ="adresse" id="adresse" value="<?= $donnees['adresse']; ?>">
                <input type="hidden" name ="commentaire" id="commentaire" value="<?= $donnees['commentaire']; ?>">
                <input type="hidden" name ="pesee_max" id="pesee_max" value="<?= $donnees['pesee_max']; ?>">
                <input type="hidden" name ="couleur" id="couleur" value="<?= substr($_POST['couleur'], 1); ?>">
                <button  class="btn btn-warning btn-sm " >modifier</button>
              </form>
            </td>
          </tr>
          <?php
        }
        $reponse->closeCursor();
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </tfoot>
    </table>
    <br>
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4"><br> </div>
      <div class="col-md-4"></div>
    </div>
  </div>
  </div>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
