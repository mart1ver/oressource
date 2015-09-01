<?php session_start();

require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
   if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>Modifier la vente n° <?php echo $_GET['nvente']?></h1> 
 <div class="panel-body">




<br>
<div class="row">
   
          <form action="../moteur/modification_verification_collecte_post.php?nvente=<?php echo $_GET['nvente']?>" method="post">
      <input type="hidden" name ="id" id="id" value="<?php echo $_GET['nvente']?>">

 



 <div class="col-md-3">

    <label for="commentaire">Commentaire</label>

           
            <textarea name="commentaire" id="commentaire" class="form-control"><?php 
            // On affiche le commentaire
            $reponse = $bdd->prepare('SELECT commentaire FROM collectes WHERE id = :id_collecte');
            $reponse->execute(array('id_collecte' => $_GET['ncollecte']));
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch()){
     
           echo $donnees['commentaire'];
             }
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?></textarea>

    
  
 </div>
  <div class="col-md-3">
  
  <br>
<button name="creer" class="btn btn-warning">Modifier</button>
</div>
</form>
</div>








</div>
<h1>Objets inclus dans cette vente</h1> 
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Momment de la vente</th>
            <th>Type d'objet:</th>
            <th>Objet:</th>
            <th>Quantité</th>
            <th>Prix</th>
            <th>Auteur de la ligne</th>
            <th></th>
            <th>Modifié par</th>
            <th>Le:</th>

            
          </tr>
        </thead>
        <tbody>


        <?php 
          
$req = $bdd->prepare('SELECT 
vendus.id ,vendus.timestamp,
type_dechets.nom type,
IF(vendus.id_objet > 0 ,grille_objets.nom, "autre") objet,
vendus.quantite ,
vendus.prix,
utilisateurs.mail
FROM
vendus, type_dechets, grille_objets ,utilisateurs
WHERE 
vendus.id_vente = :id_vente
AND type_dechets.id = vendus.id_type_dechet
AND (grille_objets.id = vendus.id_objet OR vendus.id_objet = 0 )
AND utilisateurs.id = vendus.id_createur
GROUP BY id');
$req->execute(array('id_vente' => $_GET['nvente']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['type']?></td>
            <td><?php echo $donnees['objet']?></td>
           


<td><?php echo $donnees['quantite']?></td>
<td><?php echo $donnees['prix']?></td>
<td><?php echo $donnees['mail']?></td>

<td><form action="modification_verification_objet.php" method="post">
<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nvente" id="nvente" value="<?php echo $_GET['nvente']?>">
<input type="hidden" name ="quantite" id="quantite" value="<?php echo $donnees['quantite']?>">
<input type="hidden" name ="prix" id="prix" value="<?php echo $donnees['prix']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_POST['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_POST['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">

  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>
</td>




<td><?php 
$req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, vendus
                       WHERE  vendus.id = :id_vendu 
                       AND utilisateurs.id = vendus.id_last_hero');
$req3->execute(array('id_vendu' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           { ?>



<?php echo $donnees3['mail']?>


         <?php }
            $req3->closeCursor(); // Termine le traitement de la requête 3
                ?></td>


<td><?php 
$req3 = $bdd->prepare('SELECT vendus.last_hero_timestamp lht
                       FROM  vendus
                       WHERE  vendus.id = :id_vendu 
                       ');
$req3->execute(array('id_vendu' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           {  if ($donnees3['lht'] !== '0000-00-00 00:00:00'){echo $donnees3['lht'];} }
            $req3->closeCursor(); // Termine le traitement de la requête 3
                ?></td>



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
       
      
