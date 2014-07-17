<?php session_start(); 
//Oressource 2014, formulaire de referencement des conventions avec les partenaires de la structure
//Simple formulaire de saisie , lise des conventions deja référencées et possibilité de les cacher à l'utilisateur ou de modifier les données
//
//
//
//
//
//
//
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      { include "tete.php" ?>
    <div class="container">
        <h1>Gestions des conventions avec les partenaires</h1> 
         <div class="panel-heading">Gerez ici la liste de vos partenaires de réemploi.</div>
         <p>Permet de diffferencier les partenaires au momment de la mise en bilan </p>
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
        	<form action="../moteur/convention_sortie_post.php" method="post">
  <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"                 value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus></div>
    <div class="col-md-2"><label for="description">Déscription:</label> <input type="text" value ="<?php echo $_GET['description']?>" name="description" id="description" class="form-control " required ></div>
    
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
            <th>Description:</th>
            <th>Couleur:</th>
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
 
            // Si tout va bien, on peut continuer
 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->query('SELECT * FROM conventions_sorties');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
            <td><?php echo $donnees['description']?></td>
            <td bgcolor="<?php echo $donnees['couleur']?>"><?php echo $donnees['couleur']?></td> 
<td>
<form action="../moteur/convention_sortie_visible.php" method="post">

  <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
  <input type="hidden"name ="visible" id ="visible" value="<?php if ($donnees['visible'] == "oui") 
{echo "non";}
else 
{echo "oui";}?>">
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

<form action="modification_convention_sortie.php" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="description" id="description" value="<?php echo $donnees['description']?>">
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
   
<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>