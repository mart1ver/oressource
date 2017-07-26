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
    <h1>Modifier la pesée n° <?= $_POST['id']; ?> appartenant à la sortie <?= $_POST['nsortie']; ?> </h1>
    <div class="panel-body">
      <br>
      <div class="row">

        <form action="../moteur/modification_verification_pesee_sorties_post.php" method="post">
          <input type="hidden" name ="nsortie" id="ncnsortie" value="<?= $_POST['nsortie']; ?>">
          <input type="hidden" name ="id" id="id" value="<?= $_POST['id']; ?>">
          <input type="hidden" name ="masse" id="masse" value="<?= $_POST['masse']; ?>">
          <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">

          <?php if (isset($_POST['nomtypo'])) { ?>
            <div class="col-md-3">
              <label for="id_type_dechet">Type d'objet:</label>
              <select name="id_type_dechet" id="id_type_dechet" class="form-control " required>
                <?php
                // On affiche une liste deroulante des type de collecte visibles
                $reponse = $bdd->query('SELECT * FROM type_dechets');

                while ($donnees = $reponse->fetch()) {
                  if ($_POST['nomtypo'] === $donnees['nom']) {  // SI on a pas de message d'erreur
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

            <?php
          } else { ?>
            <div class="col-md-3">
              <label for="id_type_dechet">Dechets et materiaux:</label>
              <select name="id_type_dechet_evac" id="id_type_dechet_evac" class="form-control " required>
                <?php
                // On affiche une liste deroulante des type de collecte visibles
                $reponse = $bdd->query('SELECT * FROM type_dechets_evac ');

                while ($donnees = $reponse->fetch()) {
                  if ($_POST['nomtypo_evac'] === $donnees['nom']) {  // SI on a pas de message d'erreur
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
          <?php } ?>
          <div class="col-md-3">

            <label for="masse">Masse:</label>
            <br><input type="text" value="<?= $_POST['masse']; ?>" name="masse" id="masse" class="form-control" required>
            <br>
            <button name="creer" class="btn btn-warning">Modifier</button>
          </div>
        </form>
      </div>

    </div>

  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
