<?php session_start(); 

require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
      {  include "tete.php" ?>
    <div class="container">
        <h1>Gestion des utilisateurs</h1> 
         <ul class="nav nav-tabs">
  <li class="active"><a>Inscription</a></li>
  <li><a href="edition_utilisateurs.php">Édition</a></li>
  
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
<div class="panel-body">
        <div class="row">
            <form action="../moteur/inscription_post.php" method="post">
  <div class="col-md-2"><label for="nom">Nom:</label> <input type="text" value ="<?php echo $_GET['nom']?>" name="nom" id="nom" class="form-control " required autofocus><br>
                        <label for="prenom">Prénom:</label> <input type="text" value ="<?php echo $_GET['prenom']?>" name="prenom" id="prenom" class="form-control " required><br>
                        <label for="mail">Mail:</label> <input type="email" value ="<?php echo $_GET['mail']?>" name="mail" id="mail" class="form-control " required ><br>
                        <label>Mot de passe</label> <input type="password"  name="pass1" id="pass1" class="form-control" required ><br>
                                                       Répetez le mot de passe</label> <input type="password"  name="pass2" id="pass2" class="form-control" required >
  </div>
  <div class="col-md-4"><div class="alert alert-info"><label for="niveau">Permissions d'accès</label> <br>
          
          <input type="checkbox" name="niveaubi" id="niveaubi" value="bi"><label for="niveaubi">Bilans</label><br>
          
          <input type="checkbox" name="niveaug" id="niveaug" value="g"> <label for="niveaug">Gestion quotidienne</label><br>
          <input type="checkbox" name="niveauh" id="niveauh" value="h"> <label for="niveauh">Verif. formulaires</label><br>
          <input type="checkbox" name="niveaul" id="niveaul" value="l"> <label for="niveaul">Utilisateurs</label><br>
          <input type="checkbox" name="niveauj" id="niveauj" value="j"> <label for="niveauj">Recycleurs et convention partenaires</label><br>
          <input type="checkbox" name="niveauk" id="niveauk" value="k"> <label for="niveauk">Configuration de Oressource</label><br>
       <?php if ($_SESSION['saisiec'] == 'oui'){ ?>
          <input type="checkbox" name="niveaue" id="niveaue" value="e"> <label for="niveaue">Saisir la date dans les formulaires</label><br>
       <?php }?>
<br></div>
  </div>
<div class="col-md-4"><div class="alert alert-info"><label for="niveauc">Points de collecte:</label><br>
<?php 
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM points_collecte');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>
         

            <input type="checkbox" name="niveauc<?php echo $donnees['id']; ?>" id="niveauc<?php echo $donnees['id']; ?>"> <?php echo '<label for="niveauc'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>
</div>
          
                                            <div class="alert alert-info"><label for="niveauv">Points de vente:</label><br>
          <?php 
            // On recupère tout le contenu de la table point de vente
            $reponse = $bdd->query('SELECT * FROM points_vente');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>
         

            <input type="checkbox" name="niveauv<?php echo $donnees['id']; ?>" id="niveauv<?php echo $donnees['id']; ?>"> <?php echo '<label for="niveauv'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>
              
               
              
          
          
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?></div>
                                            <div class="alert alert-info"><label for="niveaus">Points de sortie hors-boutique:</label><br>
          <?php 
            // On recupère tout le contenu de la table point de vente
            $reponse = $bdd->query('SELECT * FROM points_sortie');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {?>
                     <input type="checkbox" name="niveaus<?php echo $donnees['id']; ?>" id="niveaus<?php echo $donnees['id']; ?>"> <?php echo '<label for="niveaus'.$donnees['id'].'">'.$donnees['nom'].'</label>'; ?> <br><br>
           
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                 ?>







       




        </div></div>
  <div class="col-md-4"><br></div>
  

  

</div>
<div class="row"><div class="col-md-3 col-md-offset-3"><br><button name="creer" class="btn btn-default">Créer!</button></div></div>
      </div>
     
      
  </div>
  
  

<?php include "pied.php";
}
    else
{    
    header('Location: ../moteur/destroy.php') ;
}
?>
       
      
