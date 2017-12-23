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
  //POST ou GET ?
  if (isset($_POST['id']) !== false) {
    $id = $_POST['id'];
  } else {
    $id = $_GET['id'];
  }

  $req = $bdd->prepare('SELECT couleur FROM points_collecte WHERE id = :id ');
  $req->execute(['id' => $id]);
  $donnees = $req->fetch();
  $couleur = $donnees['couleur'];
  $req->closeCursor();
  ?>
  <div class="container">
    <h1>Gestions des points de collecte</h1>
    <div class="panel-heading">Modifier les données concernant le point numero <?= $_POST['id']; ?>, <?= $_POST['nom']; ?>. </div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/modification_points_collecte_post.php" method="post">
          <input type="hidden" name ="id" id="id" value="<?= $id; ?>">
          <div class="col-md-3"><label for="nom">Nom:</label><br><br> <input type="text"                 value ="<?= $_POST['nom'] . $_GET['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-2"><label for="addresse">Addresse:</label><br><br> <input type="text" value="<?= $_POST['adresse'] . $_GET['adresse']; ?>" name="adresse" id="adresse" class="form-control" required></div>
          <div class="col-md-2"><label for="commentaire">Commentaire:</label><br><br> <input type="text" value ="<?= $_POST['commentaire'] . $_GET['commentaire']; ?>" name="commentaire" id="commentaire" class="form-control" required></div>
          <div class="col-md-1"><label for="pesee_max">Pesée maxi:</label> <input type="text" value ="<?= $_POST['pesee_max'] . $_GET['pesee_max']; ?>" name="pesee_max" id="pesee_max" class="form-control" required></div>
          <div class="col-md-1"><label for="couleur">Couleur:</label><br><br> <input type="color" value="<?= $couleur; ?>" name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><br><button name="creer" class="btn btn-warning">Modifier</button></div>
        </form>
        <br><br>
        <a href="edition_points_collecte.php">
          <button name="creer" class="btn btn">Anuler</button>
        </a>
      </div>
    </div>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
