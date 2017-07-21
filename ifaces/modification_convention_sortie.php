<?php

/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();
require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'j') !== false))
      { include "tete.php" ?>
    <div class="container">
        <h1>Gestion des partenaires de réemploi.</h1> 
         <div class="panel-heading">Modifier les données concernant le partenaire n° <?= $_POST['id']?>, <?= $_POST['nom']?>.  </div>
<?php
//on obtien la couleur de la localité dans la base

$req = $bdd->prepare("SELECT couleur FROM conventions_sorties WHERE id = :id ");
$req->execute(array('id' => $_POST['id']));
$donnees = $req->fetch();

$couleur = $donnees['couleur'];
            
              $req->closeCursor(); // Termine le traitement de la requête
               

?>


s


      <div class="panel-body">
        <div class="row">
        	<form action="../moteur/modification_convention_sortie_post.php" method="post">
            <input type="hidden" name ="id" id="id" value="<?= $_POST['id']?>">

  <div class="col-md-2"><label for="nom">Nom:</label> <input type="text"value ="<?= $_POST['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
  <div class="col-md-3"><label for="addresse">Description:</label> <input type="text"value ="<?= $_POST['description']?>" name="description" id="description" class="form-control " required ></div>
  <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color"value ="<?= $couleur ?>"name="couleur" id="couleur" class="form-control " required ></div>
  <div class="col-md-1"><br><button name="creer" class="btn btn-warning">Modifier</button></div>
</form>
<br>
<a href="edition_conventions_sortie.php">
<button name="creer" class="btn btn">Annuler</button>
</a>

</div>
      </div>
     
      <br>
      <div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-4"><br> </div>
  <div class="col-md-4"></div>
  </div>
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
