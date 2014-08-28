<?php session_start(); ?>

<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>modifier la pesée numero <?php echo $_POST['id']?> appartenant à la collecte <?php echo $_POST['ncollecte']?> </h1> 
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
   
        	<form action="../moteur/modification_verification_pesee_post.php" method="post">
            <input type="hidden" name ="id" id="id" value="<?php echo $_POST['id']?>">
            <input type="hidden" name ="masse" id="masse" value="<?php echo $_POST['masse']?>">
  <input type="hidden" name ="date" id="date" value="<?php echo $_POST['date']?>">
    <input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">



<div class="col-md-3">

<label for="id_type_dechet">Type de dechet:</label>
<select name="id_type_dechet" id="id_type_dechet" class="form-control " required>
            <?php 
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
            // On affiche une liste deroulante des type de collecte visibles
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
              if ($_POST['nomtypo'] == $donnees['nom'])  // SI on a pas de message d'erreur
{
  ?>
    <option value = "<?php echo$donnees['id']?>" selected ><?php echo$donnees['nom']?></option>
<?php
} else {
            ?>

      <option value = "<?php echo$donnees['id']?>" ><?php echo$donnees['nom']?></option>
            <?php }}
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
    </select>
      
  </div>
  <div class="col-md-3">

    <label for="masse">Masse:</label>
<br><input type="text"       value ="<?php echo $_POST['masse']?>" name="masse" id="masse" class="form-control " required >
  
 </div>
  <div class="col-md-3">
  
  <br>
<button name="creer" class="btn btn-warning">Modifier</button>
</div>
</form>
</div>



</div>

      





  </div><!-- /.container -->
<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      