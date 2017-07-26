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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'g') !== false)) {
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Gestion des types de bacs et de moyens de manutention.</h1>
    <div class="panel-heading">Modifier les données concernant le moyen de manutention n° <?= $_POST['id']; ?>, <?= $_POST['nom']; ?>. </div>
    <?php
//on obtien la couleur de la localité dans la base

    $req = $bdd->prepare('SELECT couleur FROM type_contenants WHERE id = :id ');
    $req->execute(['id' => $_POST['id']]);
    $donnees = $req->fetch();

    $couleur = $donnees['couleur'];

    $req->closeCursor();
    ?>

    <div class="panel-body">
      <div class="row">
        <form action="../moteur/modification_type_contenant_post.php" method="post">
          <input type="hidden" name ="id" id="id" value="<?= $_POST['id']; ?>">

          <div class="col-md-2"><label for="nom">Nom:</label> <input type="text"value ="<?= $_POST['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-3"><label for="description">Description:</label> <input type="text"value ="<?= $_POST['description']; ?>" name="description" id="description" class="form-control" required></div>
          <div class="col-md-2"><label for="masse_bac">Masse de l'objet (Kg):</label> <input type="text"value ="<?= $_POST['masse_bac']; ?>" name="masse_bac" id="masse_bac" class="form-control" required></div>

          <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color" value="<?= $couleur; ?>" name="couleur" id="couleur" class="form-control " required autofocus></div>
          <div class="col-md-1"><br><button name="creer" class="btn btn-warning">Modifier</button></div>
        </form>
        <br>
        <a href="edition_types_contenants.php">
          <button name="creer" class="btn btn">Anuler</button>
        </a>
      </div>
    </div>

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
