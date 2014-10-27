<?php session_start(); ?>
<?php
     if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
      {  include "tete.php" ?>
      
    <div class="container">
        <h1>Gestions des utilisateurs</h1> 
         <ul class="nav nav-tabs">
  <li ><a href="utilisateurs.php">Inscription</a></li>
  <li class="active"><a>Edition</a></li>
  
</ul>
    <br>     
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








      
      <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>nom</th>
            <th>prenom</th>
            <th>mail</th>
             <th>éditer</th>
            <th>supprimer</th>
            
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
            $reponse = $bdd->query('SELECT * FROM utilisateurs');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

           ?>
            <tr> 
            <td><?php echo $donnees['id']?></td>
            <td><?php echo $donnees['nom']?></td>
            <td><?php echo $donnees['prenom']?></td>
            <td><?php echo $donnees['mail']?></td>
            <td>







<form action="edition_utilisateur.php" method="post">

  <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
  <input type="hidden" name ="nom" id="nom" value="<?php echo $donnees['nom']?>">
  <input type="hidden" name ="prenom" id="prenom" value="<?php echo $donnees['prenom']?>">
  <input type="hidden" name ="mail" id="mail" value="<?php echo $donnees['mail']?>">
  <input type="hidden" name ="niveau" id="id" value="<?php echo $donnees['niveau']?>">
  





 





   <button  class="btn btn-warning btn-sm ">Editer! 
  </button>
</form>
</td>
            <td>







<form action="../moteur/sup_utilisateur.php" method="post">

  <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
  





 





   <button  class="btn btn-danger btn-sm ">Suprimer! 
  </button>
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
   header('Location: ../moteur/destroy.php') ;
       
      