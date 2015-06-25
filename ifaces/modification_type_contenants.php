<?php session_start(); 

require_once("../moteur/dbconfig.php");

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'g') !== false))
      { include "tete.php" ?>
    <div class="container">
        <h1>Gestion des types de bacs et de moyens de manutention.</h1> 
         <div class="panel-heading">Modifier les données concernant le moyen de manutention n° <?php echo $_POST['id']?>, <?php echo $_POST['nom']?>. </div>
<?php
//on obtien la couleur de la localité dans la base
            // On recupère tout le contenu de la table point de vente


$req = $bdd->prepare("SELECT couleur FROM type_contenants WHERE id = :id ");
$req->execute(array('id' => $_POST['id']));
$donnees = $req->fetch();

$couleur = $donnees['couleur'];
            
              $reponse->closeCursor(); // Termine le traitement de la requête


?>





      <div class="panel-body">
        <div class="row">
          <form action="../moteur/modification_type_contenant_post.php" method="post">
            <input type="hidden" name ="id" id="id" value="<?php echo $_POST['id']?>">

  <div class="col-md-2"><label for="nom">Nom:</label> <input type="text"value ="<?php echo $_POST['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
  <div class="col-md-3"><label for="description">Description:</label> <input type="text"value ="<?php echo $_POST['description']?>" name="description" id="description" class="form-control " required ></div>
  <div class="col-md-2"><label for="masse_bac">Masse de l'objet (Kg):</label> <input type="text"value ="<?php echo $_POST['masse_bac']?>" name="masse_bac" id="masse_bac" class="form-control " required ></div>
 
  <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color"        value ="<?php echo $couleur?>" name="couleur" id="couleur" class="form-control " required autofocus></div>





  










  
  
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
   
<?php include "pied.php"; 
}
    else
{
    header('Location: ../moteur/destroy.php') ;
}
?>
