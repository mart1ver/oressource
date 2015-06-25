<?php session_start(); 

require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page:
     if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
      {  include "tete.php" ?>
      
    <div class="container">
        <h1>Gestion des utilisateurs</h1> 
         <ul class="nav nav-tabs">
  <li ><a href="utilisateurs.php">Inscription</a></li>
  <li class="active"><a>Édition</a></li>
  
</ul>
    <br>     
      
      <!-- Table -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Mail</th>
             <th>Éditer</th>
            <th>Supprimer!</th>
            
          </tr>
        </thead>
        <tbody>
        <?php 
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
  





 





   <button  class="btn btn-warning btn-sm ">ÉDITER! 
  </button>
</form>
</td>
            <td>







<form action="../moteur/sup_utilisateur.php" method="post">

  <input type="hidden" name ="id" id="id" value="<?php echo $donnees['id']?>">
  





 





   <button  class="btn btn-danger btn-sm ">SUPPRIMER! 
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
 


<?php include "pied.php";
}
    else
{
   header('Location: ../moteur/destroy.php') ;
}      
?>
