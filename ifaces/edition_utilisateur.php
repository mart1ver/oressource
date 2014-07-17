<?php session_start(); 
     if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'g') !== false))
      {  include "tete.php" ?>
    <div class="container">
        <h1>Edition du profil utilisateur n°:<?php echo $_POST['id']?>, <?php echo $_POST['mail']?></h1> 
         
         
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
                          </div>
  <div class="col-md-3"><div class="alert alert-info"><label>Pérmissions d'accès</label> <br>
          <input type="checkbox" name="niveaua" id="niveaua" value="a" <?php if((strpos($_POST['niveau'], 'a') !== false)){ echo "checked";} ?> > Adhésions<br>
          <input type="checkbox" name="niveaubi" id="niveaubi" value="bi"<?php if((strpos($_POST['niveau'], 'bi') !== false)){ echo "checked";} ?>> Bilans<br>
          <input type="checkbox" name="niveaug" id="niveaug" value="g"<?php if((strpos($_POST['niveau'], 'g') !== false)){ echo "checked";} ?>> Gestion<br>
          <input type="checkbox" name="niveaum" id="niveaum" value="m"<?php if((strpos($_POST['niveau'], 'm') !== false)){ echo "checked";} ?>> Mails des adhérents<br>
          <input type="checkbox" name="niveaup" id="niveaup" value="p"<?php if((strpos($_POST['niveau'], 'p') !== false)){ echo "checked";} ?>> Prèts<br><br></div>
  <input type="hidden" name ="id" id="id" value="<?php echo $_POST['id']?>">
  </div>
<div class="col-md-3"><div class="alert alert-info"><label for="niveauc">Points de collecte:</label><br>
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
         

            <input type="checkbox" name="niveauc<?php echo $donnees['id']; ?>" id="niveauc<?php echo $donnees['id']; ?>" <?php if((strpos($_POST['niveau'], 'c'.$donnees['id']) !== false)){ echo "checked";} ?>> <?php echo $donnees['nom']; ?> <br><br>
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
         

            <input type="checkbox" name="niveauv<?php echo $donnees['id']; ?>" id="niveauv<?php echo $donnees['id']; ?>" value="v<?php echo $donnees['id']; ?>"<?php if((strpos($_POST['niveau'], 'v'.$donnees['id']) !== false)){ echo "checked";} ?>> <?php echo $donnees['nom']; ?> <br><br>
              
               
              
          
          
   
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
                     <input type="checkbox" name="niveaus<?php echo $donnees['id']; ?>" id="niveaus<?php echo $donnees['id']; ?>" value="s<?php echo $donnees['id']; ?>"<?php if((strpos($_POST['niveau'], 'v'.$donnees['id']) !== false)){ echo "checked";} ?>> <?php echo $donnees['nom']; ?> <br><br>
           
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>







       




        </div></div>
  <div class="col-md-4"><br></div>
  

  

</div>
<div class="row"><div class="col-md-3 col-md-offset-3"><br><button name="modifier" class="btn btn-warning">Modifier!</button></form>
<a href="edition_utilisateurs.php">
<button name="creer" class="btn btn">Anuler</button>
</a>


</div></div>
      </div>
     
      
  </div>
  
  

<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      