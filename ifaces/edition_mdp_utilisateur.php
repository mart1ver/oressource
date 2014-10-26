                    <?php session_start(); ?>
                    <?php
                    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
                    { 
                    include "tete.php" ?>
<div class="container">
<h1>Édition de votre mot de passe:</h1> 
<p>Votre E-mail est: <?php echo $_SESSION['mail'] ?>, il vous est demandé au login.</p>
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
  <form action="../moteur/edition_mdp_utilisateur_post.php" method="post">
    <div class="col-md-3 col-md-offset-2">
<div class="panel panel-default">
  <div class="panel-body">


      
      <label for="passold">Mot de passe actuel:</label> 
      <input type="password"  name="passold" id="passold=" class="form-control" required >
      <br>
      <label for="pass1">Nouveau mot de passe:</label> 
      <input type="password"  name="pass1" id="pass1" class="form-control" required >
      <br>
      <label for="pass2">Répétez le nouveau mot de passe:</label> <input type="password"  name="pass2" id="pass2" class="form-control" required >
      <br>
      <button name="creer" class="btn btn-warning">Modifier!</button>


  </div>
</div>

    </div>
  
  </div>
  
  </div>
  </div>
                    <?php include "pied.php" ?>
                    <?php }
                        else
                        header('Location: ../') ;
                    ?>
       
      
