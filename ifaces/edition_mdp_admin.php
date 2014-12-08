                    <?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page: 
                    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource"AND (strpos($_SESSION['niveau'], 'l') !== false))
                    { 
                    include "tete.php" ?>
<div class="container">
<h1>Édition du mot de passe de l'ultisateur n°:<?php echo $_GET['id']?>, <?php echo $_GET['mail']?></h1> 
<p>L'identifiant de cet utilisateur est son adresse E-mail: <?php echo $_GET['mail']?>, elle lui est est demandé à la connexion à Oressource..</p>
<br>     
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
  <form action="../moteur/edition_mdp_admin_post.php" method="post">
    <div class="col-md-3 col-md-offset-2">
<div class="panel panel-default">
  <div class="panel-body">


      
      
      <input type="hidden"  name="id" id="id"  value="<?php echo $_GET['id']?>">
      <br>
      <label for="pass1">Nouveau mot de passe:</label> 
      <input type="password"  name="pass1" id="pass1" class="form-control" required >
      <br>
      <label for="pass2">Répétez le nouveau mot de passe:</label> <input type="password"  name="pass2" id="pass2" class="form-control" required >
      <br>
      <button name="creer" class="btn btn-danger">Modifier!</button>
</form>

  </div>
</div>

    </div>
  
  </div>
  
  </div>
  </div>
                    <?php include "pied.php"; 
}
else
{ header('Location: ../moteur/destroy.php') ;}
?>
       
      
