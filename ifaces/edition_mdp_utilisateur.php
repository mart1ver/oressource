                    <?php session_start(); 

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page: 
                    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
                    { 
                    include "tete.php" ?>
<div class="container">
<h1>Édition de votre mot de passe:</h1> 
<p>Votre E-mail est: <?php echo $_SESSION['mail'] ?>, il vous est demandé au login.</p>
<br>     
<div class="panel-body">
  <div class="row">
  <form action="../moteur/edition_mdp_utilisateur_post.php" method="post">
    <div class="col-md-3 col-md-offset-2">
<div class="panel panel-default">
  <div class="panel-body">


      
      <label for="passold">Mot de passe actuel:</label> 
      <input type="password"  name="passold" id="passold" class="form-control" required >
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
{
  header('Location: ../moteur/destroy.php') ;
}
?>
       
      
