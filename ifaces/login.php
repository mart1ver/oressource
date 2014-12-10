<?php include "tete.php" ?>
<br>
<div class="container">
<div class="starter-template">
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
<br>
        <h1>Veuillez vous identifier:</h1>
<br>
<br>
<br>
<br>
<div class="row">
  <div class="col-md-3 col-md-offset-4"><form action="../moteur/login_post.php" method="post" >
  <div class="form-group"> 
    <label class="sr-only" for="mail">Mail:</label>
    <input type="email" class="form-control" id="mail" name="mail" placeholder="Mail:" autofocus>
  </div>
  <div class="form-group">
    <label class="sr-only" for="pass">Mot de passe:</label>
    <input type="password" class="form-control" id="pass" name="pass" placeholder="PASS:">
  </div>
  <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-log-in"></span> Login</button>
</form></div>
</div>
</div><!-- /.container -->
</div>
<?php include "pied.php"; ?>
