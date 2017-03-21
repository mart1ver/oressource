<?php session_start();
/*
+  Oressource
+  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers
+
+  This program is free software: you can redistribute it and/or modify
+  it under the terms of the GNU Affero General Public License as
+  published by the Free Software Foundation, either version 3 of the
+  License, or (at your option) any later version.
+
+  This program is distributed in the hope that it will be useful,
+  but WITHOUT ANY WARRANTY; without even the implied warranty of
+  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
+  GNU Affero General Public License for more details.
+
+  You should have received a copy of the GNU Affero General Public License
+  along with this program.  If not, see <http://www.gnu.org/licenses/>.
+ */
+
+
+// Oressource 2017,



//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page: 
                    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
                    {include "tete.php" ?>
<div class="container">
<h1>Édition du mot de passe de l'utilisateur n°:<?php echo $_GET['id']?>, <?php echo $_GET['mail']?></h1> 
<p>L'identifiant de cet utilisateur est son adresse E-mail: <?php echo $_GET['mail']?>, elle lui est demandée à chaque connexion à Oressource.</p>
<br>     
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
<?php include "pied.php" ;
}
else
{ 
header('Location: ../moteur/destroy.php') ;
}
?>
       
      
