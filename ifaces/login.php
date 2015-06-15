<?php include "tete.php" ?>
<br>
<div class="container">
<div class="starter-template">
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
