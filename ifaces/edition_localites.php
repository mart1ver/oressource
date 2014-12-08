                <?php session_start(); 

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page: 
                if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'k') !== false))
                { 
                include "tete.php" ?>
<div class="container">
<h1>Gestions des localités</h1> 
  <div class="panel-heading">Gerez ici les localités.</div>
  <p>Les localités sonts renseigées au momment de la collecte elles permettent d'estimmer la rayonnance géographique de la structure </p>
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
 	<form action="../moteur/edition_localites_post.php" method="post">
    <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"                 value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
    <div class="col-md-2"><label for="commentaire">Commentaire:</label> <input type="text" value ="<?php echo $_GET['commentaire']?>" name="commentaire" id="commentaire" class="form-control " required ></div>
    <div class="col-md-3"><label for="lien">Lien externe:</label> <input type="url" value ="<?php echo $_GET['lien']?>" name="lien" id="lien" class="form-control " required ></div>
    <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color"        value ="<?php echo "#".$_GET['couleur']?>" name="couleur" id="couleur" class="form-control " required ></div>
    <div class="col-md-1"><br><button name="creer" class="btn btn-default">Creer!</button></div>
  </form>
  </div>
</div>
<!-- Table -->
<table class="table">
<thead>
  <tr>
    <th>#</th>
    <th>Date de creation:</th>
    <th>Nom:</th>
    <th>Commentaire:</th>
    <th>Couleur:</th>
    <th>Lien:</th>
    <th>Visible:</th>
    <th>Modifier:</th>
  </tr>
</thead>
<tbody>
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
      <button  class="btn btn-warning btn-sm" >modifier</button>
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
