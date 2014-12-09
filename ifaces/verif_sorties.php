<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" 
//formulaire permettant la correction de sorties
?>


   <div class="container">
        <h1>Vérification des sorties hors boutique</h1> 
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
 
            // On recupère tout le contenu des visibles de la table points_sortie
            $reponse = $bdd->query('SELECT * FROM points_sortie');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "verif_sorties.php?numero=" . $donnees['id']."&date=" . $_GET['date']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
       </ul>



<br>


<div class="row">
        	<form id='formdate' action="../moteur/verif_sorties_date_post.php" method="post">
  <div class="col-md-3"><label for="date">Date:</label> <input type="date"value ="<?php echo $_GET['date']?>" name="date" id="date" class="form-control "  autofocus>

  </div>
    
    <br>
<input type="hidden" name ="point" id="point" value="<?php echo $_GET['numero']?>">
    <div class="col-md-1"><button name="creer" class="btn btn-default">Go!</button></div>
</form>
</div>

</div>
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
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sorties" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
            <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Dons simples:</h3> </div>
  <div class="panel-body">
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
            <th>type de collecte:</th>
            <th>Adhérent?:</th>
            
            <th>Masse totale</th>
            
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
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,type_sortie.nom,sorties.adherent ,sorties.classe classe 
                       FROM sorties ,type_sortie
                       WHERE type_sortie.id = sorties.id_type_sortie  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sorties" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
            <td><?php echo $donnees['adherent']?></td>
            
           <td> 

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
 
           
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sorties.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
       
        
      </table>
      </div>
</div>
      <?php
    } else{echo 'Pas de dons sur cette periode<br><br>';
    $req->closeCursor(); }
}
?>
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
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sortiesc" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
            <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Sorties conventionées:</h3> </div>
  <div class="panel-body">

  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
            <th>Nom du partenaire:</th>
            
            <th>Masse totale</th>
            
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
 
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,conventions_sorties.nom,sorties.adherent , sorties.classe classe
                       FROM sorties ,conventions_sorties
                       WHERE conventions_sorties.id = sorties.id_convention  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sortiesc" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
            
           <td> 

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
 
           
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sortiesc.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
        
        
      </table>
       </div>
</div>
      <?php
    } else{echo 'Pas de sorties en direction des partenaires sur cette periode<br><br>';
    $req->closeCursor(); }
}
?>
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
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sortiesr" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
             <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Sorties recyclage:</h3> </div>
  <div class="panel-body">

  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
            <th>Nom de l'entreprise:</th>
            <th>Masse totale</th>
            
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
 
           
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp ,filieres_sortie.nom , sorties.classe classe
                       FROM sorties ,filieres_sortie
                       WHERE filieres_sortie.id = sorties.id_filiere  AND sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sortiesr"');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['nom']?></td>
           
           <td> 

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
 
            
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sortiesr.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
        
        
      </table>
      </div></div>
      <?php
    } else{echo 'Pas de sorties recyclage sur cette periode<br><br>';
    $req->closeCursor(); }
}
?>
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
          
$req = $bdd->prepare('SELECT COUNT(id) nid
                        FROM `sorties` 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sortiesp" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           { 
if($donnees['nid'] > 0){ $req->closeCursor(); 





            ?>
            <div class="panel panel-info">
  <div class="panel-heading"><h3 class="panel-title">Sorties Poubelles:</h3> </div>
  <div class="panel-body">
 
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création:</th>
   
            
            <th>Masse totale</th>
            
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
 
            
          
$req = $bdd->prepare('SELECT sorties.id,sorties.timestamp , sorties.classe classe
                       FROM sorties 
                       WHERE sorties.id_point_sortie = :id_point_sortie AND DATE(sorties.timestamp) = :tdate AND classe = "sortiesp" ');
$req->execute(array('id_point_sortie' => $_GET['numero'], 'tdate' => $_GET['date']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            
            
           <td> 

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
 
           
          
$req2 = $bdd->prepare('SELECT SUM(pesees_sorties.masse) masse
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie ');
$req2->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees2 = $req2->fetch())
           { ?>



<?php echo $donnees2['masse']?>


         <?php }
            
                ?>




           </td> 





<td>

<form action="modification_verification_sortiesp.php?nsortie=<?php echo $donnees['id']?>" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="date" id="date" value="<?php echo $_GET['date']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_GET['numero']?>">
  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>






          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête2
                ?>
       </tbody>
        
        
      </table>
    </div></div>
<?php
    } else{echo 'Pas de poubelles evacuées sur cette periode<br><br>';
    $req->closeCursor(); }
}
?>



  </div><!-- /.container -->
<?php include "pied.php";
 }
    else
{
   header('Location: ../moteur/destroy.php') ;
}
?>
       
      
