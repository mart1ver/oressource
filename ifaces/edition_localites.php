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
                if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'k') !== false))
                { 
                include "tete.php" ?>
<div class="container">
<h1>Gestion des localités de collecte</h1> 
  <div class="panel-heading">Définissez ici les localité d'origine possibles pour les matériaux entrant.</div>
  <p>Ces localités sont renseignées au moment de la collecte et permettent d'estimer l'impact territorial de la structure </p>
<div class="panel-body">
  <div class="row">
 	<form action="../moteur/edition_localites_post.php" method="post">
    <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"                 value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
    <div class="col-md-2"><label for="commentaire">Commentaire:</label> <input type="text" value ="<?php echo $_GET['commentaire']?>" name="commentaire" id="commentaire" class="form-control " required ></div>
    <div class="col-md-3"><label for="lien">Lien externe:</label> <input type="url" value ="<?php echo $_GET['lien']?>" name="lien" id="lien" class="form-control "  ></div>
    <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color"        value ="<?php echo "#".$_GET['couleur']?>" name="couleur" id="couleur" class="form-control " required ></div>
    <div class="col-md-1"><br><button name="creer" class="btn btn-default">Créer!</button></div>
  </form>
  </div>
</div>
<!-- Table -->
<table class="table">
<thead>
  <tr>
    <th>#</th>
    <th>Date de création</th>
    <th>Nom</th>
    <th>Commentaire:</th>
    <th>Couleur</th>
    <th>Lien</th>
    <th>Visible</th>
    <th></th>
  </tr>
</thead>
<tbody>
            <?php 
            $reponse = $bdd->query('SELECT * FROM localites');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
            ?>
<tr> 
  <td><?php echo $donnees['id']?></td>
  <td><?php echo $donnees['timestamp']?></td>
  <td><?php echo $donnees['nom']?></td>
  <td><?php echo $donnees['commentaire']?></td>
  <td><span class="badge" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['couleur']?></span></td> 
  <td><a href="<?php echo $donnees['relation_openstreetmap']?>" target="_blank"><p style="text-align:center"><span class="glyphicon glyphicon-link"></span></p></a></td>
  <td>
  <form action="../moteur/localites_visibles.php" method="post"><input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
  <input type="hidden"name ="visible" id ="visible" value="<?php if ($donnees['visible'] == "oui"){echo "non";}else{echo "oui";}?>">
            <?php
            if ($donnees['visible'] == "oui") // SI on a pas de message d'erreur
            {?>
  <button  class="btn btn-info btn-sm " >
            <?php
            }
            else // SINON 
            {?>
  <button  class="btn btn-danger btn-sm " >
            <?php
            }
            echo $donnees['visible']?> 
  </button>
  </form>
  </td>
  <td>
    <form action="modification_localites.php" method="post">
      <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
      <input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
      <input type="hidden" name ="lien" id="lien" value="<?php echo $donnees['relation_openstreetmap']?>">
      <input type="hidden" name ="commentaire" id="commentaire" value="<?php echo $donnees['commentaire']?>">
      <input type="hidden" name ="couleur" id="couleur" value="<?php echo substr($_POST['couleur'],1)?>">
      <button  class="btn btn-warning btn-sm" >Modifier!</button>
    </form>
  </td>
</tr>
           <?php }
           $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
</tbody>
<tfoot>
  <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
</tfoot>
</table>
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
{ header('Location: ../moteur/destroy.php') ;}
            ?>
