<?php
session_start();

require_once('../moteur/dbconfig.php');
require_once('../core/session.php');

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && is_allowed_config()) {
  include_once("tete.php");

  $nom = isset($_GET['nom']) ? $_GET['nom'] : '';
  $reponse = $bdd->prepare('SELECT id, timestamp, nom, adresse, couleur, commentaire, surface_vente, visible FROM points_vente');
  $reponse->execute();
  $points_ventes = $reponse->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <div class="container">
    <h1>Gestion des points de vente</h1>
    <div class="panel-heading">Gérez ici les différents points de vente.</div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/edition_points_vente_post.php" method="post">
          <div class="col-md-2">
            <label for="nom">Nom:</label>
            <br>
            <input type="text" value="" name="nom" id="nom" class="form-control " required autofocus>
          </div>
          <div class="col-md-3">
            <label for="adresse">Adresse:</label>
            <br>
            <input type="text" value ="" name="adresse" id="adresse" class="form-control " required>
          </div>
          <div class="col-md-2">
            <label for="commentaire">Commentaire:</label>
            <br>
            <input type="text" value ="" name="commentaire" id="commentaire" class="form-control " required>
          </div>
          <div class="col-md-2">
            <label for="surface">Surface de vente (m²):</label>
            <input type="text" value ="" name="surface" id="surface" class="form-control " required>
          </div>
          <div class="col-md-1">
            <label for="couleur">Couleur:</label><br>
            <input type="color" value="#11FFFF" name="couleur" id="couleur" class="form-control">
          </div>
          <div class="col-md-1"><br>
            <button name="creer" class="btn btn-default">Créer!</button>
          </div>
        </form>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Adresse:</th>
          <th>Couleur</th>
          <th>Commentaire:</th>
          <th>Surface de vente (m²):</th>
          <th>Visible</th>
          <th>Modifier</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($points_ventes as $point_vente) {
          $visible_bool = $point_vente['visible'] === 'oui';
          $vente_visible = ($visible_bool ? 'non' : 'oui');
          $btn_class = $visible_bool ? 'btn-info' : 'btn-danger';
          ?>
          <tr>
            <td><?php echo $point_vente['id']; ?></td>
            <td><?php echo $point_vente['timestamp']; ?></td>
            <td><?php echo $point_vente['nom']; ?></td>
            <td><?php echo $point_vente['adresse']; ?></td>
            <td><span class="badge" style="background-color:<?php echo $point_vente['couleur']; ?>"><?php echo $point_vente['couleur']; ?></span></td>
            <td><?php echo $point_vente['commentaire']; ?></td>
            <td><?php echo $point_vente['surface_vente']; ?></td>
            <td>
              <form action="../moteur/ventes_visibles_post.php" method="post">
                <input type="hidden" name="id" id="id" value="<?php echo $point_vente['id']; ?>">
                <input type="hidden" name="visible" id="visible" value="<?php echo($vente_visible); ?>">
                <button type="submit" class="btn btn-sm <?php echo($btn_class); ?>"><?php echo $point_vente['visible']; ?></button>
              </form>
            </td>
            <td>
              <form action="modification_points_vente.php" method="post">
                <input type="hidden" name="id" id="id" value="<?php echo $point_vente['id']; ?>">
                <button class="btn btn-warning btn-sm" type="submit" >Modifier!</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div> <!-- /.container -->
  <?php
  include_once("pied.php");
} else {
  header('Location: ../moteur/destroy.php');
}
?>
