<?php session_start(); 

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
  if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>Modifier l'objet vendu n° <?php echo $_POST['id']?> appartenant à la vente <?php echo $_POST['nvente']?> </h1> 
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




<br>


<div class="row">
   
        	<form action="../moteur/modification_verification_objet_post.php" method="post">
            <input type="hidden" name ="nvente" id="nvente" value="<?php echo $_POST['nvente']?>">
            <input type="hidden" name ="id" id="id" value="<?php echo $_POST['id']?>">
         <input type="hidden" name ="date1" id="date1" value="<?php echo $_POST['date1']?>">
  <input type="hidden" name ="date2" id="date2" value="<?php echo $_POST['date2']?>">
    <input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">



<div class="col-md-3">

<label for="quantite">Quantité:</label>
<br><input type="text"       value ="<?php echo $_POST['quantite']?>" name="quantite" id="quantite" class="form-control " required >

      
  </div>
  <div class="col-md-3">

    <label for="prix">Prix:</label>
<br><input type="text"       value ="<?php echo $_POST['prix']?>" name="prix" id="prix" class="form-control " required >
  
 </div>
  <div class="col-md-3">
  
  <br>
<button name="creer" class="btn btn-warning">Modifier</button>
</div>
</form>
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
       
      
