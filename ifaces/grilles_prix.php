<?php session_start(); ?>
<?php
  if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'g') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>grilles de prix</h1> 
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
<ul class="nav nav-tabs">


 <?php 
          //on affiche un onglet par type d'objet
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
 
            // On recupère tout le contenu des visibles de la table type_dechets
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui" ');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['typo'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "grilles_prix.php?typo=" . $donnees['id']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
       </ul>



<br>


<div class="row">
        	<form action="../moteur/grilles_prix_post.php" method="post">
  <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus>

  </div>
    <div class="col-md-3"><label for="description">Déscription:</label> <input type="text" value ="<?php echo $_GET['description']?>" name="description" id="description" class="form-control " required >
      
    
    </div>
    <div class="col-md-1"><label for="prix">Prix:</label> <input type="text" value ="<?php echo $_GET['prix']?>" name="prix" id="prix" class="form-control " required >
     
     <input type="hidden" value ="<?php echo $_GET['typo']?>" name="typo" id="typo" class="form-control "  >
    </div>

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
            <th>Prix</th>
            <th>Supprimer</th>
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





 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare("SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet  ");
$req->execute(array('id_type_dechet' => $_GET['typo']));

 $i = 1;
           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $i;$i++;?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
            <td><?php echo $donnees['description']?></td>
            <td><?php echo $donnees['prix']?></td>
           <td> <form action="../moteur/objet_sup.php" method="post">

   <input type="hidden" name ="typo" id="typo" value="<?php echo $_GET['typo']?>">
  <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
  <button  class="btn btn-danger btn-sm " >supprimer</button></form></td> 
<td>
<form action="../moteur/objet_visible.php" method="post">

   <input type="hidden" name ="typo" id="typo" value="<?php echo $_GET['typo']?>">
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

<form action="modification_objet.php" method="post">
<input type="hidden" name ="typo" id="typo" value="<?php echo $_GET['typo']?>">
<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="description" id="description" value="<?php echo $donnees['description']?>">
<input type="hidden" name ="prix" id="prix" value="<?php echo $donnees['prix']?>">
  <button  class="btn btn-warning btn-sm" >modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
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





  </div><!-- /.container -->
<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../moteur/destroy.php') ;
?>
       
      