<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
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
      if(strpos($_SESSION['niveau'], 'c'.$donnees['id']) !== false AND $donnees['visible'] == "oui")
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
      if(strpos($_SESSION['niveau'], 's') !== false )
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
             
      if(strpos($_SESSION['niveau'], 's'.$donnees['id']) !== false AND $donnees['visible'] == "oui")
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
            
      if(strpos($_SESSION['niveau'], 'v'.$donnees['id']) !== false AND $donnees['visible'] == "oui")
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
<li><a href="prets.php">Prêts</a></li>
          <?php }
              else{}?>
<?php if(strpos($_SESSION['niveau'], 'a') !== false)
          { ?>
<li><a href="adhesions.php">Adhésion</a></li>
<?php }
              else{}?>
                 <?php if(strpos($_SESSION['niveau'], 'm') !== false)
          { ?>
<li><a href="mailling.php">Mailing</a></li>
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
          <li><a href="recettes_filieres_sortie.php">Recettes points de sortie</a></li>
          <li><a href="edition_filieres_sortie.php">Filières de sortie</a></li>
          <li class="divider"></li>
          <li><a href="utilisateurs.php">Utilisateurs</a></li>
          <li><a href="grilles_prix.php">Grilles de prix</a></li>
          <li class="divider"></li>
          <li><a href="types_collecte.php">Types de collectes</a></li>
          <li><a href="types_dechets.php">Types de déchet</a></li>
          <li><a href="types_objets.php">Types d'objets redistribués</a></li>
          <li class="divider"></li>
          <li><a href="edition_points_collecte.php">Points de collecte</a></li>
          <li><a href="edition_points_sorties.php">Points de sortie hors boutique</a></li>
          <li><a href="edition_points_vente.php">Points de vente</a></li>
          <li class="divider"></li>
          <li><a href="edition_localites.php">Localités</a></li>
          <li><a href="edition_description.php">Déscription de la structure</a></li>
          
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