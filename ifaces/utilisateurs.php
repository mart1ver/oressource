<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND (strpos($_SESSION['niveau'], 'a') !== false))
      {  include "tete.php" ?>
    <div class="container">
        <h1>Gestions des utilisateurs</h1> 
         <ul class="nav nav-tabs">
  <li class="active"><a href="#">Inscription</a></li>
  <li><a href="edition_utilisateurs.php">Edition</a></li>
  
</ul>
         
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
            <form action="../moteur/mdp_post.php" method="post">
  <div class="col-md-2"><label for="saisietitre">Nom:</label> <input type="text" value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus><br>
                        <label for="saisiedescription">Mail:</label> <input type="email" value ="<?php echo $_GET['mail']?>" name="mail" id="mail" class="form-control " required ><br>
                        <label for="saisiedescription">Mot de passe</label> <input type="password" value ="<?php echo $_GET['pass1']?>" name="pass1" id="pass1" class="form-control " required ><br>
                                                       Repetez le mot de passe</label> <input type="password" value ="<?php echo $_GET['pass2']?>" name="pass2" id="pass2" class="form-control " required >
  </div>
  
  <div class="col-md-3"><div class="alert alert-info"><label for="saisiedescription">Permissions d'acces</label> <br>
          <input type="checkbox" name="niveauh" id="niveauh" value="a"> Adhesions<br>
          <input type="checkbox" name="niveauh" id="niveauh" value="bi"> Bilans<br>
          <input type="checkbox" name="niveaug" id="niveaug" value="g"> gestion<br>
          <input type="checkbox" name="niveauc" id="niveauc" value="m"> Mails des adherents<br><br></div>
  </div>
<div class="col-md-3"><div class="alert alert-info"><label for="saisiedescription">Points de collecte:</label><br>


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
         

            <input type="checkbox" name="niveauc<?php echo $donnees['id']; ?>" id="niveauc<?php echo $donnees['id']; ?>" value="c<?php echo $donnees['id']; ?>"> <?php echo $donnees['nom']; ?> <br><br>
              
               
              
          
          
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>
</div>

          
                                            <div class="alert alert-info"><label for="saisiedescription">Points de vente:</label><br>
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
         

            <input type="checkbox" name="niveauv<?php echo $donnees['id']; ?>" id="niveauv<?php echo $donnees['id']; ?>" value="v<?php echo $donnees['id']; ?>"> <?php echo $donnees['nom']; ?> <br><br>
              
               
              
          
          
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?></div>
                                            <div class="alert alert-info"><label for="saisiedescription">Points de sortie hors boutique:</label><br>
          <input type="checkbox" name="niveaus" id="niveaus" value="s"> sorties hors boutique<br><br></div></div>
  <div class="col-md-4"><br><button name="creer" class="btn btn-default">Creer!</button></div>
  
</form>
</div>
      </div>
     
      
  </div>
  
  

<?php include "pied.php" ?>
<?php }
    else
    header('Location: ../') ;
?>
       
      