<?php session_start(); 

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
  if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>Modifier l'objet remboursé n° <?php echo $_POST['id']?> appartenant au remboursement n° <?php echo $_POST['nvente']?> </h1> 
 <div class="panel-body">




<br>


<div class="row">
   
        	<form action="../moteur/modification_verification_objet_remboursement_post.php" method="post">
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

    <label for="remboursement">Prix:</label>
<br><input type="text"       value ="<?php echo $_POST['remboursement']?>" name="remboursement" id="remboursement" class="form-control " required >
  
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
       
      
