<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      { include "tete.php" ?>
    <div class="container">
        <h1>Gestions des types de dechets</h1> 
         <div class="panel-heading">Modifier les données concernant le type de dechets n° <?php echo $_POST['id']?> </div>
<?php
//on obtien la couleur de la localité dans la base
            try
            {
            // On se connecte à MySQL
            include('../moteur/dbconfig.php');
            }
            catch(Exception $e)
            {
            // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
            }
            // Si tout va bien, on peut continuer
            // On recupère tout le contenu de la table point de vente


$req = $bdd->prepare("SELECT couleur FROM type_dechets WHERE id = :id ");
$req->execute(array('id' => $_POST['id']));
$donnees = $req->fetch();

$couleur = $donnees['couleur'];
            
              $reponse->closeCursor(); // Termine le traitement de la requête
               


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
        	<form action="../moteur/modification_types_dechets_post.php" method="post">
            <input type="hidden" name ="id" id="id" value="<?php echo $_POST['id']?>">

  <div class="col-md-2"><label for="nom">Nom:</label> <input type="text"value ="<?php echo $_POST['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
  <div class="col-md-3"><label for="addresse">Déscription:</label> <input type="text"value ="<?php echo $_POST['description']?>" name="description" id="description" class="form-control " required ></div>
  <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color"value ="<?php echo $couleur ?>"name="couleur" id="couleur" class="form-control " required ></div>
  <div class="col-md-1"><br><button name="creer" class="btn btn-warning">Modifier</button></div>
</form>
<br>
<a href="types_collecte.php">
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
   
<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>