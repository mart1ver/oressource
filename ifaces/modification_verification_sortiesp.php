<?php session_start(); 

require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
  if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>Modifier la sortie n° <?php echo $_GET['nsortie']?></h1> 
 <div class="panel-body">




<br>






</div>
<h1>Pesées incluses dans cette sortie poubelles:</h1> 
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>Type de poubelle:</th>
            <th>Masse</th>
            <th>Auteur de la ligne</th>
            <th></th>
            <th>Modifié par</th>
            <th>Le:</th>
            
          </tr>
        </thead>
        <tbody>
        <?php 
/*
'SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
FROM type_dechets,pesees_collectes 
WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
GROUP BY nom'

SELECT pesees_collectes.id ,pesees_collectes.timestamp  ,type_dechets.nom  , pesees_collectes.masse
                       FROM pesees_collectes ,type_dechets
                       WHERE type_dechets.id = pesees_collectes.id_type_dechet AND pesees_collectes.id_collecte = :id_collecte
*/


 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare('SELECT pesees_sorties.id ,pesees_sorties.timestamp  ,types_poubelles.nom , pesees_sorties.masse ,types_poubelles.couleur
                       FROM pesees_sorties ,types_poubelles 
                       WHERE types_poubelles.id = pesees_sorties.id_type_poubelle AND pesees_sorties.id_sortie = :id_sortie
                       GROUP BY pesees_sorties.id
                      

                       ');
$req->execute(array('id_sortie' => $_GET['nsortie']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span></td>
            <td><?php echo $donnees['masse']?></td>
           


<td>
 <?php 
          
$req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs ,pesees_sorties
                       WHERE  pesees_sorties.id_sortie = :id_sortie
                       AND  utilisateurs.id = pesees_sorties.id_createur
                       GROUP BY mail');
$req3->execute(array('id_sortie' => $_GET['nsortie']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           { ?>



<?php echo $donnees3['mail']?>


         <?php }
            
                ?>
</td>

<td>

<form action="modification_verification_pesee_sortiesp.php" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="id_type_poubelle" id="id_type_poubelle" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="nsortie" id="nsortie" value="<?php echo $_GET['nsortie']?>">
<input type="hidden" name ="masse" id="masse" value="<?php echo $donnees['masse']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_POST['date1']?>">
  <input type="hidden" name ="date2" id="date2" value="<?php echo $_POST['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">

  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>

<td><?php 
          
$req5 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM pesees_sorties, utilisateurs
                       WHERE  pesees_sorties.id = :id_sortie
                       AND  utilisateurs.id = pesees_sorties.id_last_hero
                       ');
$req5->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees5 = $req5->fetch())
           { ?>



<?php echo $donnees5['mail'];?>

         <?php }
            
                ?></td>
<td><?php 
$req4 = $bdd->prepare('SELECT pesees_sorties.last_hero_timestamp lht
                       FROM pesees_sorties
                       WHERE  pesees_sorties.id = :id_sortie
                       ');
$req4->execute(array('id_sortie' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees4 = $req4->fetch())
           { ?>



<?php if ($donnees4['lht'] !== '0000-00-00 00:00:00'){echo $donnees4['lht'];}?>

         <?php }
            
?>



          </tr>
           <?php }
              $req->closeCursor(); // Termine le traitement de la requête
              $req3->closeCursor(); // Termine le traitement de la requête3
              $req4->closeCursor(); // Termine le traitement de la requête3
              $req5->closeCursor(); // Termine le traitement de la requête3
                  

                
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
<?php include "pied.php";
}
    else
{
    header('Location: ../moteur/destroy.php') ;
}
?>
       
      
