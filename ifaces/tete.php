
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
          <a class="navbar-brand" href="#">Oressource</a>
        </div>
        
        <div class="navbar-collapse collapse  navbar-right">
          

 <ul class="nav navbar-nav">
  <?php
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
      { ?> 

<li><a href="saisie.php">Collectes</a></li>
<li><a href="saisie.php">Sorties hors boutiques</a></li>
<li><a href="saisie.php">Ventes</a></li>
<li><a href="saisie.php">Prets</a></li>
<li><a href="adhesions.php">Adhesion</a></li>
<li><a href="saisie.php">Mailing</a></li>





<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Gestion<b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="affectations.php">recettes points de sorties</a></li>
          <li><a href="affectations.php">Utilisateurs</a></li><li><a href="affectations.php">grilles de prix</a></li>
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
    else{ }?>
<li><a href="aide.php">Aide</a></li>
</ul>

        </div><!--/.navbar-collapse -->
       
      </div>
    </div>
    
    
