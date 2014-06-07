
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../images/favicon.ico">

    <title>Oressource</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/oressource.css" rel="stylesheet">
    <link rel="stylesheet" href="../js/morris.js/morris.css">
  </head>
  <body>

    
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../">Oressource</a>
        </div>
        
        <div class="navbar-collapse collapse  navbar-right">
          

 <ul class="nav navbar-nav">
  <?php
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
      { ?> 

<ul class="nav navbar-nav">
      <?php 
      if(strpos($_SESSION['niveau'], 'c') !== false)
          { ?>
             <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Points de collecte<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><?php 
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
            $reponse = $bdd->query('SELECT * FROM points_collecte');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
             
      if(strpos($_SESSION['niveau'], 'c'.$donnees['id']) !== false)
          { ?>
          <li>
              <a href="<?php echo  "collecte.php?numero=" . $donnees['id']. "&nom=" . $donnees['nom']. "&adresse=".$donnees['adresse']; ?>">
               <?php echo $donnees['nom']; ?> 
              </a>
          </li>
          <br><?php } ?>
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>  </li> </ul> </li><?php } else{}?>


           
        
      
    </ul>

<ul class="nav navbar-nav">
<?php 
      if(strpos($_SESSION['niveau'], 's') !== false)
          { ?>
             <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sorties hors boutique<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><?php 
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
           {
             
      if(strpos($_SESSION['niveau'], 's'.$donnees['id']) !== false)
          { ?>
          <li>
              <a href="<?php echo  "sorties.php?numero=" . $donnees['id']. "&nom=" . $donnees['nom']. "&adresse=".$donnees['adresse']; ?>">
               <?php echo $donnees['nom']; ?> 
              </a>
          </li>
          <br><?php } ?>
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
               ?>  </li> </ul> </li><?php } else{}?>


           
        
      
    </ul>

    

<ul class="nav navbar-nav">
<?php 
      if(strpos($_SESSION['niveau'], 'v') !== false)
          { ?>
             <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Points de vente<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><?php 
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
           {
            
      if(strpos($_SESSION['niveau'], 's'.$donnees['id']) !== false)
          { ?>
          <li>
              <a href="<?php echo  "ventes.php?numero=" . $donnees['id']. "&nom=" . $donnees['nom']. "&adresse=".$donnees['adresse']; ?>">
               <?php echo $donnees['nom']; ?> 
              </a>
          </li>
          <br><?php } ?>
   
              <?php }
              $reponse->closeCursor(); // Termine le traitement de la requête
               ?>  </li> </ul> </li><?php } else{}?>


           
        
      
    </ul>



<?php if(strpos($_SESSION['niveau'], 'p') !== false)
          { ?>
<li><a href="saisie.php">Prets</a></li>
          <?php }
              
                 else{}?>

<?php if(strpos($_SESSION['niveau'], 'a') !== false)
          { ?>
<li><a href="adhesions.php">Adhesion</a></li>
<?php }
              
                 else{}?>
                 <?php if(strpos($_SESSION['niveau'], 'm') !== false)
          { ?>
<li><a href="saisie.php">Mailing</a></li>
<?php }
              
                 else{}?>
<?php if(strpos($_SESSION['niveau'], 'bi') !== false)
          { ?>
<li><a href="bilans.php">Bilans</a></li>
<?php }
              
                 else{}?>




<?php if(strpos($_SESSION['niveau'], 'g') !== false)
          { ?>

<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Gestion<b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="affectations.php">recettes points de sorties</a></li>
          <li><a href="utilisateurs.php">Utilisateurs</a></li><li><a href="affectations.php">grilles de prix</a></li>
          <li><a href="poles.php">Filieres de sorties</a></li>
          <li><a href="poles.php">Points de collecte</a></li>
          <li><a href="poles.php">Points de sorties hors boutique</a></li>
          <li><a href="moyens.php">Points de ventes</a></li>
          <li><a href="affectations.php">Types de collectes</a></li>
          <li><a href="affectations.php">Types de dechets</a></li>
          <li><a href="affectations.php">Types dobjets redistribués</a></li>
          <li><a href="poles.php">Localités</a></li>
          <li><a href="mdp.php">Description de la structure</a></li>
          
      </ul>
      </li>
      <?php }
              
                 else{}?>



 <?php }
    else{ }?>
<li><a href="aide.php">Aide</a></li>
</ul>

        </div><!--/.navbar-collapse -->
       
      </div>
    </div>
    
    
