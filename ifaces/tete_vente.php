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
    <link rel="stylesheet" href="../js/morris/morris.css">
<link href="../css/bootstrap-switch.css" rel="stylesheet">
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-switch.js"></script>
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
//session_start(); déjà inclu dans chacun des fichiers appelant ce fichier

require_once('../moteur/dbconfig.php');

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
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sorties hors-boutique<b class="caret"></b></a>
            <ul class="dropdown-menu">
            <li><?php 
            // On recupère tout le contenu de la table point de vente
            $reponse = $bdd->query('SELECT * FROM points_sortie');
            // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
             
      if(strpos($_SESSION['niveau'], 's'.$donnees['id']) !== false AND $donnees['visible'] == "oui")
          { ?>
          <li>
              <a href="<?php echo  "sortiesc.php?numero=" . $donnees['id']. "&nom=" . $donnees['nom']. "&adresse=".$donnees['adresse']; ?>">
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


<?php 
if(strpos($_SESSION['niveau'], 'bi') !== false)
{ ?>
<li><a href=" bilanc.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=0">Bilans</a></li>
<?php }
              else{}?>
<?php if(strpos($_SESSION['niveau'], 'g') !== false OR strpos($_SESSION['niveau'], 'h') !== false OR strpos($_SESSION['niveau'], 'l') !== false OR strpos($_SESSION['niveau'], 'j') !== false OR strpos($_SESSION['niveau'], 'k') !== false)
          { ?>
<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Gestion<b class="caret"></b></a>
        <ul class="dropdown-menu">
<?php if(strpos($_SESSION['niveau'], 'g') !== false)//Grille des prix et masse des bacs(gestion quotidienne)
          { ?>
          <li><a href="grilles_prix.php?typo=1">Grille des prix</a></li>
          <li><a href="edition_types_contenants.php">Bacs et chariots</a></li>
          <li><a href="edition_types_poubelles.php">Types de poubelles</a></li>
          <li class="divider"></li>
<?php } ?>
<?php if(strpos($_SESSION['niveau'], 'h') !== false)//gestion verif
          { ?>
          <li><a href="verif_collecte.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=1">Vérifier les collectes</a></li>
          <li><a href="verif_sorties.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=1">Vérifier les sorties hors-boutique</a></li>
          <li><a href="verif_vente.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=1">Vérifier les ventes</a></li>
          <li class="divider"></li>
<?php } ?>
<?php if(strpos($_SESSION['niveau'], 'l') !== false)//utilisateurs
          { ?>
         <li><a href="utilisateurs.php">Utilisateurs</a></li>
         <li class="divider"></li>
<?php } ?> 
<?php if(strpos($_SESSION['niveau'], 'j') !== false)//recycleur et conventions de sortie
          { ?>
         
          <li><a href="edition_filieres_sortie.php">Entreprises de recyclage</a></li>
          <li><a href="edition_conventions_sortie.php">Conventions avec les partenaires</a></li>
             <li class="divider"></li>
<?php } ?> 
<?php if(strpos($_SESSION['niveau'], 'k') !== false)//configuration de oressource
          { ?>

          <li><a href="edition_types_sortie.php">Types de sorties hors-boutique</a></li>
          <li><a href="types_collecte.php">Types de collectes</a></li>
          <li class="divider"></li>
          <li><a href="types_dechets.php">Types d'objets collectés</a></li>
          <li><a href="types_dechets_evac.php">Types de déchets evacués</a></li>       
          <li class="divider"></li>
          <li><a href="edition_points_collecte.php">Points de collecte</a></li>
          <li><a href="edition_points_sorties.php">Points de sortie hors-boutique</a></li>
          <li><a href="edition_points_vente.php">Points de vente</a></li>
          <li class="divider"></li>
          <li><a href="moyens_paiment.php">Moyens de paiment</a></li>
          <li><a href="edition_localites.php">Localités</a></li>
          <li><a href="edition_description.php">Configuration de Oressource</a></li>
<?php } ?>
          
          
      </ul>
      </li>
      <?php }
              
                 else{}?>


      <li class="dropdown">   
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><b class="caret"></b></a>
        <ul class="dropdown-menu">
        <li><a href="edition_mdp_utilisateur.php">Mot de passe</a></li>
        <li><a href="../moteur/destroy.php">Déconnexion</a></li>

        </ul>  
      </li>        
<?php }
    else{ }?>

</ul>
</div><!--/.navbar-collapse -->
      </div>
    </div>
