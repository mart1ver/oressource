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
  $id = $_GET['id'] ?? 0;
  $req = $bdd->prepare('SELECT * FROM points_sortie WHERE id = :id ');
  $req->execute(['id' => $id]);
  $donnees = $req->fetch(PDO::FETCH_ASSOC);
  $req->closeCursor();
  ?>
<div class="container">
    <h1>Gestions des points de sortie hors-boutique</h1>
    <div class="panel-heading">Modifier les données concernant le point de sortie n°<?= $id ?>, <?= $donnees['nom']; ?>.</div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/modification_points_sortie_post.php" method="post">
          <input type="hidden" name="id" value="<?= $id; ?>">
          <div class="col-md-3"><label for="nom">Nom:</label><br><br><input type="text" value="<?= $_GET['nom'] ?? $donnees['nom'] ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-2"><label for="addresse">Adresse:</label><br><br><input type="text" value="<?= $_GET['adresse'] ?? $donnees['adresse'] ?>" name="adresse" id="adresse" class="form-control" required></div>
          <div class="col-md-2"><label for="commentaire">Commentaire:</label><br><br><input type="text" value ="<?= $_GET['commentaire'] ?? $donnees['commentaire'] ?>" name="commentaire" id="commentaire" class="form-control" required></div>
          <div class="col-md-1"><label for="pesee_max">Pesée maxi:</label><input type="text" value="<?= $_GET['pesee_max'] ?? $donnees['pesee_max'] ?>" name="pesee_max" id="pesee_max" class="form-control" required></div>
          <div class="col-md-1"><label for="couleur">Couleur:</label><br><br><input type="color" value="<?= $donnees['couleur'] ?>" name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><br><button class="btn btn-warning">Modifier</button></div>
        </form>
        <br><br>
        <a href="edition_points_sorties.php">
          <button name="creer" class="btn btn">Annuler</button>
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
