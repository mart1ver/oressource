<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      { include "tete.php" ?>
    <div class="container">
        <h1>Gestions des points de sortie hors boutique</h1> 
         <div class="panel-heading">Modifier les données concernant le point numero <?php echo $_POST['id']?> </div>
<?php
if ($_GET['err'] == "") // SI on a pas de message d'erreur
{
   echo'';
}

else // SINON 
{
  echo'<div class="alert alert-danger">'.$_GET['err'].'</div>';
}


if ($_GET['msg'] == "") // SI on a pas de message positif
{
   echo '';
}

else // SINON (la variable ne contient ni Oui ni Non, on ne peut pas agir)
{
  echo'<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$_GET['msg'].'</div>';
}
?>





      <div class="panel-body">
        <div class="row">
        	<form action="../moteur/modification_points_sortie_post.php" method="post">
            <input type="hidden" name ="id" id="id" value="<?php echo $_POST['id']?>">

  <div class="col-md-3"><label for="saisienom">Nom:</label> <input type="text"                 value ="<?php echo $_POST['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
  <div class="col-md-3"><label for="saisieaddresse">Addresse:</label> <input type="text"       value ="<?php echo $_POST['adresse']?>" name="adresse" id="adresse" class="form-control " required ></div>
  <div class="col-md-3"><label for="saisiecommentaire">Commentaire:</label> <input type="text" value ="<?php echo $_POST['commentaire']?>" name="commentaire" id="commentaire" class="form-control " required ></div>
  <div class="col-md-1"><label for="saisiecouleur">Couleur:</label> <input type="color"        value ="<?php if(isset($_POST['couleur']))echo "#".$_POST['couleur']?>" name="couleur" id="couleur" class="form-control " required ></div>
  <div class="col-md-1"><br><button name="creer" class="btn btn-warning">Modifier!</button></div>
</form>
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
   
<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>