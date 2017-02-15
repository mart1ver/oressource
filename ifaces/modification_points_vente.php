<?php

session_start();

require_once('../moteur/dbconfig.php');
require_once('../core/session.php');

// Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && is_allowed_config()) {
  include_once("tete.php");

  // POST ou GET ?
  if (isset($_POST['id']) !== false) {
    $id = $_POST['id'];
  } else {
    $id = $_GET['id'];
  }

  $req = $bdd->prepare('SELECT couleur, nom, adresse, commentaire, surface_vente as surface
                        FROM points_vente
                        WHERE id = :id
                        LIMIT 1');
  $req->bindValue(':id', $id, PDO::PARAM_INT);
  $req->execute();
  $point_vente = $req->fetch(PDO::FETCH_ASSOC);

  ?>
  <div class="container">
    <h1>Gestion des points de vente</h1>
    <div class="panel-heading">Modifier les données concernant le point de vente n°<?php echo $id; ?>, <?php echo $point_vente['nom']; ?>. </div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/modification_points_vente_post.php" method="post">
          <input type="hidden" name ="id" id="id" value="<?php echo $id; ?>">
          <div class="col-md-3">
            <label for="nom">Nom:</label>
            <input type="text" value="<?php echo $point_vente['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus>
          </div>
          <div class="col-md-2">
            <label for="addresse">Adresse:</label>
            <input type="text" value="<?php echo $point_vente['adresse']; ?>" name="adresse" id="adresse" class="form-control " required>
          </div>
          <div class="col-md-2">
            <label for="commentaire">Commentaire:</label>
            <input type="text" value="<?php echo $point_vente['commentaire']; ?>" name="commentaire" id="commentaire" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label for="surface">Surface de vente (m²):</label>
            <input type="text"value="<?php echo $point_vente['surface'];?>" name="surface" id="surface" class="form-control " required>
          </div>
          <div class="col-md-1">
            <label for="couleur">Couleur:</label>
            <input type="color" value="<?php echo $point_vente['couleur']; ?>" name="couleur" id="couleur" class="form-control" required>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-warning">Modifier</button>
          </div>
        </form>
        <a href="edition_points_vente.php">
          <button name="Annuler" class="btn btn">Annuler</button>
        </a>
      </div>
    </div>
  </div> <!-- /.container -->

<?php
  include_once("pied.php");
} else {
  header('Location: ../moteur/destroy.php');
}