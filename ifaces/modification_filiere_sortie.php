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


if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'j') !== false)) {
  require_once '../moteur/dbconfig.php';
  require_once 'tete.php';
  $req = $bdd->prepare('SELECT couleur FROM filieres_sortie WHERE id = :id ');
  $req->execute(['id' => $_POST['id']]);
  $donnees = $req->fetch();
  $couleur = $donnees['couleur'];
  $id_type_dechet_evac_current = $_POST['id_type_dechet_evac'];
  $id_type_dechet_evac_current_tab = explode('a', $id_type_dechet_evac_current);
  $req->closeCursor();
  ?>
  <div class="container">
    <h1>Modifier un recycleur</h1>
    <div class="panel-heading">Modifier les données concernant la filiere n° <?= $_POST['id']; ?>, <?= $_POST['nom']; ?>. </div>
    <div class="panel-body">
      <form action="../moteur/modification_filiere_sortie_post.php" method="post">
        <div class="row">
          <input type="hidden" name ="id" id="id" value="<?= $_POST['id']; ?>">
          <div class="col-md-2"><label for="nom">Nom:</label> <input type="text"value ="<?= $_POST['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-3"><label for="description">Description:</label> <input type="text"value ="<?= $_POST['description']; ?>" name="description" id="description" class="form-control" required></div>
          <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color"value ="<?= $couleur; ?>"name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><button name="creer" class="btn btn-warning">Modifier</button></div>
          <br>
          <a href="edition_filieres_sortie.php">
            <button name="creer" class="btn btn">Anuler</button>
          </a>
        </div>

        <div class="row">
          <div class="col-md-9"><br>
            <label for="tde">Type de déchets enlevés:</label>
            <div class="alert alert-info">
              <?php
              $reponse = $bdd->query('SELECT * FROM type_dechets_evac');
              while ($donnees = $reponse->fetch()) { ?>
                <input type="checkbox"
                <?= array_key_exists($donnees['id'], $id_type_dechet_evac_current_tab) ? 'checked' : '' ?>
                       name="tde<?= $donnees['id']; ?>"
                       id="tde<?= $donnees['id']; ?>" >
                <label for="tde<?= $donnees['id'] ?>"><?= $donnees['nom'] ?></label>
                <?php
              }
              $reponse->closeCursor();
              ?>
            </div>
          </div>
        </div>
      </form>
    </div>
    <br>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
