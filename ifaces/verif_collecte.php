<?php session_start(); ?>
<script>
function submitform()
{
  document.formdate.submit();
}
</script>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      {  include "tete.php" ?>
   <div class="container">
        <h1>verification des collectes</h1> 
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
            $reponse = $bdd->query('SELECT * FROM points_collecte');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           ?> 
            <li<?php if ($_GET['numero'] == $donnees['id']){ echo ' class="active"';}?>><a href="<?php echo  "verif_collecte.php?numero=" . $donnees['id']."&date=" . $_GET['date']?>"><?php echo$donnees['nom']?></a></li>
           <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
           ?>
       </ul>



<br>


<div class="row">
        	<form id='formdate' action="../moteur/verif_collecte_date_post.php" method="post">
  <div class="col-md-3"><label for="date">Date:</label> <input type="date"value ="<?php echo $_GET['date']?>" name="date" id="date" class="form-control "  autofocus>

  </div>
    
    <br>
<input type="hidden" name ="point" id="point" value="<?php echo $_GET['numero']?>">
    <div class="col-md-1"><button name="creer" class="btn btn-default">Go!</button></div>
</form>
</div>

</div>


  <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Momment de creation:</th>
            <th>type de collecte:</th>
            <th>Adhérent?:</th>
            <th>Localisation:</th>
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
/*
'SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme 
FROM type_dechets,pesees_collectes 
WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
GROUP BY nom'
*/


 
            // On recupère toute la liste des filieres de sortie
            //   $reponse = $bdd->query('SELECT * FROM grille_objets');
          
$req = $bdd->prepare("SELECT * 
                       FROM collectes 
                       WHERE id_point_collecte = :id_point_collecte AND DATE(timestamp) = :tdate ");
$req->execute(array('id_point_collecte' => $_GET['numero'], 'tdate' => $_GET['date']));

 $i = 1;
           // On affiche chaque entree une à une
           while ($donnees = $req->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $i;$i++;?></td>
            <td><?php echo $donnees['timestamp']?></td>
            <td><?php echo $donnees['id_type_collecte']?></td>
            <td><?php echo $donnees['adherent']?></td>
            <td><?php echo $donnees['localisation']?></td>
           <td> masse</td> 





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
            
          </tfoot>
        
      </table>





  </div><!-- /.container -->
<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      