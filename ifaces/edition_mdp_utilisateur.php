                    <?php session_start(); ?>
                    <?php
                    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
                    { 
                    include "tete.php" ?>
<div class="container">
<h1>Edition du profil utilisateur n°:</h1> 
<ul class="nav nav-tabs">
  <li><a href="edition_utilisateur.php">Profil</a></li>
  <li class="active" ><a>Mot de passe</a></li>
</ul>
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
    <div class="col-md-2">
      <label>Mot de passe</label> 
      <input type="password"  name="pass1" id="pass1" class="form-control" required >
      <br>
      <label>Repetez le mot de passe</label> <input type="password"  name="pass2" id="pass2" class="form-control" required >
    </div>
  <div class="col-md-3"></div>
  <div class="col-md-3"></div>
  <div class="col-md-4"><br></div>
  </div>
  <div class="row">
    <div class="col-md-3 col-md-offset-3">
      <br>
      <button name="creer" class="btn btn-default">Modifier!</button>
    </div>
  </div>
  </div>
  </div>
                    <?php include "pied.php" ?>
                    <?php }
                        else
                        header('Location: ../') ;
                    ?>
       
      