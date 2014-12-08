<?php session_start(); 

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page:
     if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
      {  include "tete.php" ?>



    <div class="container">
        <h1>Édition du profil utilisateur n°:<?php echo $_POST['id']?>, <?php echo $_POST['mail']?></h1> 
         
         
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
<div class="panel-body">
        <div class="row">
            <form action="../moteur/modification_utilisateur_post.php" method="post">
  <div class="col-md-2"><label for="nom">Nom:</label> <input type="text" value ="<?php echo $_POST['nom']?>" name="nom" id="nom" class="form-control " required autofocus><br>
                        <label for="prenom">Prénom:</label> <input type="text" value ="<?php echo $_POST['prenom']?>" name="prenom" id="prenom" class="form-control " required><br>
                        <label for="mail">Mail:</label> <input type="email" value ="<?php echo $_POST['mail']?>" name="mail" id="mail" class="form-control " required ><br>
<a href="edition_mdp_admin.php?id=<?php echo $_POST['id']?>&mail=<?php echo $_POST['mail']?>">
<button name="creer" type="button" class="btn btn btn-danger">Changer le mot de passe</button>
</a>

                          </div>
  <div class="col-md-4"><div class="alert alert-info"><label>Pérmissions d'accès</label> <br>
          
          <input type="checkbox" name="niveaubi" id="niveaubi" value="bi"<?php if((strpos($_POST['niveau'], 'bi') !== false)){ echo "checked";} ?>> <label for="niveaubi">Bilans</label><br>

          <input type="checkbox" name="niveaug" id="niveaug" value="g"<?php if((strpos($_POST['niveau'], 'g') !== false)){ echo "checked";} ?>> <label for="niveaug">Gestion quotidienne</label><br>
          <input type="checkbox" name="niveauh" id="niveauh" value="h"<?php if((strpos($_POST['niveau'], 'h') !== false)){ echo "checked";} ?>> <label for="niveauh">Verif. formulaires</label><br>
          <input type="checkbox" name="niveaul" id="niveaul" value="l"<?php if((strpos($_POST['niveau'], 'l') !== false)){ echo "checked";} ?>> <label for="niveaul">Utilisateurs</label><br>
          <input type="checkbox" name="niveauj" id="niveauj" value="j"<?php if((strpos($_POST['niveau'], 'j') !== false)){ echo "checked";} ?>> <label for="niveauj">Recycleurs et convention partenaires</label><br>
          <input type="checkbox" name="niveauk" id="niveauk" value="k"<?php if((strpos($_POST['niveau'], 'k') !== false)){ echo "checked";} ?>> <label for="niveauk">Configuration de Oressource</label><br>


     </div>
          
  <input type="hidden" name ="id" id="id" value="<?php echo $_POST['id']?>">
  </div>
<div class="col-md-4"><div class="alert alert-info"><label for="niveauc">Points de collecte:</label><br>
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
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM points_collecte');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>
         

            <input type="checkbox" name="niveauc<?php echo $donnees['id']; ?>" id="niveauc<?php echo $donnees['id']; ?>" <?php if((strpos($_POST['niveau'], 'c'.$donnees['id']) !== false)){ echo "checked";} ?>> <?php echo '<label for="niveauc'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>
</div>
          
                                            <div class="alert alert-info"><label for="niveauv">Points de vente:</label><br>
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
            // On recupère tout le contenu de la table point de vente
            $reponse = $bdd->query('SELECT * FROM points_vente');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>
         

            <input type="checkbox" name="niveauv<?php echo $donnees['id']; ?>" id="niveauv<?php echo $donnees['id']; ?>" value="v<?php echo $donnees['id']; ?>"<?php if((strpos($_POST['niveau'], 'v'.$donnees['id']) !== false)){ echo "checked";} ?>> <?php echo '<label for="niveauv'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>
              
               
              
          
          
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?></div>
                                            <div class="alert alert-info"><label for="niveaus">Points de sortie hors boutique:</label><br>
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
            // On recupère tout le contenu de la table point de vente
            $reponse = $bdd->query('SELECT * FROM points_sortie');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>
                     <input type="checkbox" name="niveaus<?php echo $donnees['id']; ?>" id="niveaus<?php echo $donnees['id']; ?>" value="s<?php echo $donnees['id']; ?>"<?php if((strpos($_POST['niveau'], 's'.$donnees['id']) !== false)){ echo "checked";} ?>> <?php echo '<label for="niveaus'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>
           
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>







       




        </div></div>
  <div class="col-md-4"><br></div>
  

  

</div>
<div class="row"><div class="col-md-3 col-md-offset-3"><br><button name="modifier" class="btn btn-warning">MODIFIER!</button></form>
<a href="edition_utilisateurs.php">
<button name="creer" class="btn btn">Annuler</button>
</a>


</div></div>
      </div>
     
      
  </div>
  
  

<?php include "pied.php";
}
    else
{
    header('Location: ../moteur/destroy.php') ;
}
?>
       
      
