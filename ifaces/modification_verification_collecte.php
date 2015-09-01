<?php session_start();

require_once("../moteur/dbconfig.php");

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
   if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>Modifier la collecte n° <?php echo $_GET['ncollecte']?></h1> 
 <div class="panel-body">




<br>


<div class="row">
   
        	<form action="../moteur/modification_verification_collecte_post.php?ncollecte=<?php echo $_GET['ncollecte']?>" method="post">
            <input type="hidden" name ="id" id="id" value="<?php echo $_GET['ncollecte']?>">

  <input type="hidden" name ="date1" id="date1" value="<?php echo $_POST['date1']?>">
  <input type="hidden" name ="date2" id="date2" value="<?php echo $_POST['date2']?>">
    <input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">



<div class="col-md-3">

<label for="id_type_collecte">Type de collecte:</label>
<select name="id_type_collecte" id="id_type_collecte" class="form-control " required>
            <?php 
            // On affiche une liste deroulante des type de collecte visibles
            $reponse = $bdd->query('SELECT * FROM type_collecte WHERE visible = "oui"');
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
  <div class="col-md-3">

    <label for="id_localite">Localisation:</label>
<select name="id_localite" id="id_localite" class="form-control " required>
            <?php 
            // On affiche une liste deroulante des type de collecte visibles
            $reponse = $bdd->query('SELECT * FROM localites WHERE visible = "oui"');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {
              if ($_POST['localisation'] == $donnees['nom']) // SI on a pas de message d'erreur
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
 <div class="col-md-3">

    <label for="commentaire">Commentaire</label>

           
            <?php 
            // On affiche le commentaire
            $reponse = $bdd->prepare('SELECT commentaire FROM collectes WHERE id = :id_collecte');
            $reponse->execute(array('id_collecte' => $_GET['ncollecte']));
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch()){
     
           echo' <input name="commentaire" id="commentaire" class="form-control" value="'.$donnees['commentaire'].'"">'
             }
            $reponse->closeCursor(); // Termine le traitement de la requête
            ?>

    </input>
  
 </div>
  <div class="col-md-3">
  
  <br>
<button name="creer" class="btn btn-warning">Modifier</button>
</div>
</form>
</div>



</div>
<h1>Pesées incluses dans cette collecte</h1> 
  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Moment de la collecte</th>
            <th>Type de déchet:</th>
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
          
$req = $bdd->prepare('SELECT pesees_collectes.id ,pesees_collectes.timestamp  ,type_dechets.nom  , pesees_collectes.masse ,type_dechets.couleur , utilisateurs.mail mail , pesees_collectes.last_hero_timestamp lht
                      

 FROM pesees_collectes ,type_dechets ,utilisateurs,collectes
                       WHERE type_dechets.id = pesees_collectes.id_type_dechet 
                       AND utilisateurs.id = pesees_collectes.id_createur
                       AND pesees_collectes.id_collecte = :id_collecte
GROUP BY nom');
$req->execute(array('id_collecte' => $_GET['ncollecte']));


           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><span class="badge" id="cool" style="background-color:<?php echo$donnees['couleur']?>"><?php echo$donnees['nom']?></span></td>
            <td><?php echo $donnees['masse']?></td>
           


<td><?php echo $donnees['mail']?></td>

<td>

<form action="modification_verification_pesee.php" method="post">

<input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
<input type="hidden" name ="nomtypo" id="nomtypo" value="<?php echo $donnees['nom']?>">
<input type="hidden" name ="ncollecte" id="ncollecte" value="<?php echo $_GET['ncollecte']?>">
<input type="hidden" name ="masse" id="masse" value="<?php echo $donnees['masse']?>">
<input type="hidden" name ="date1" id="date1" value="<?php echo $_POST['date1']?>">
<input type="hidden" name ="date2" id="date2" value="<?php echo $_POST['date2']?>">
<input type="hidden" name ="npoint" id="npoint" value="<?php echo $_POST['npoint']?>">

  <button  class="btn btn-warning btn-sm" >Modifier</button>


</form>



</td>




<td><?php 
$req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, pesees_collectes
                       WHERE  pesees_collectes.id = :id_collecte 
                       AND utilisateurs.id = pesees_collectes.id_last_hero');
$req3->execute(array('id_collecte' => $donnees['id']));


           // On affiche chaque entree une à une
           while ($donnees3 = $req3->fetch())
           { ?>



<?php echo $donnees3['mail']?>


         <?php }
            $req3->closeCursor(); // Termine le traitement de la requête 3
                ?></td>


<td><?php if ($donnees['lht'] !== '0000-00-00 00:00:00'){echo $donnees['lht'];}?></td>



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
       
      
