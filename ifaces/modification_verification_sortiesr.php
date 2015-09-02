<?php session_start();

require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>Modifier la sortie n° <?php echo $_GET['nsortie']?></h1> 
 <div class="panel-body">




<br>


<div class="row">
   
          <form action="../moteur/modification_verification_sortiesr_post.php?nsortie=<?php echo $_GET['nsortie']?>" method="post">
            <input type="hidden" name ="id" id="id" value="<?php echo $_GET['nsortie']?>">

<input type="hidden" name ="date1" id="date1" value="<?php echo $_POST['date1']?>">
  <input type="hidden" name ="date2" id="date2" value="<?php echo $_POST['date2']?>">
    <input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">



<div class="col-md-3">

<label for="id_filiere">Nom de l'entreprise de recyclage:</label>
<select name="id_filiere" id="id_filiere" class="form-control " required>
            <?php 
            // On affiche une liste deroulante des type de sortie visibles
            $reponse = $bdd->query('SELECT * FROM filieres_sortie WHERE visible = "oui"');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
              if ($_POST['nom'] == $donnees['nom'])  // SI on a pas de message d'erreur
{
  ?>
    <option value = "<?php echo$donnees['id']?>" selected ><?php echo$donnees['nom']?></option>
<?php
} else {
            ?>

      <option value = "<?php echo$donnees['id']?>" ><?php echo$donnees['nom']?></option>
            <?php }}
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
    </select>
      
  </div>
  <textarea name="commentaire" id="commentaire" class="form-control"><?php 
            // On affiche le commentaire
            $reponse = $bdd->prepare('SELECT commentaire FROM sorties WHERE id = :id_sortie');
            $reponse->execute(array('id_sortie' => $_GET['nsortie']));
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch()){
     
           echo $donnees['commentaire'];
             }
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?></textarea>
  <div class="col-md-3">
  
  <br>
<button name="creer" class="btn btn-warning">Modifier</button>
</div>
</form>
</div>



</div>
<h1>Pesées incluses dans cette sortie</h1> 
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date de création</th>
           
            <th>Masse</th>
            <th></th>
            
          </tr>
        </thead>
        <tbody>
        <?php 
 
            // Si tout va bien, on peut continuer
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
          
$req = $bdd->prepare('SELECT pesees_sorties.id ,pesees_sorties.timestamp  ,type_dechets_evac.nom  , pesees_sorties.masse ,type_dechets_evac.couleur
                       FROM pesees_sorties ,type_dechets_evac
                       WHERE type_dechets_evac.id = pesees_sorties.id_type_dechet_evac AND pesees_sorties.id_sortie = :id_sortie');
$req->execute(array('id_sortie' => $_GET['nsortie']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
           
            <td><?php echo $donnees['masse']?></td>
           




<td>

<form action="modification_verification_pesee_sortiesr.php" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="ncollecte" id="ncollecte" value="<?php echo $_GET['ncollecte']?>">
<input type="hidden" name ="masse" id="masse" value="<?php echo $donnees['masse']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_POST['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_POST['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">

  <button  class="btn btn-warning btn-sm" >Modifier</button>


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
       
      
