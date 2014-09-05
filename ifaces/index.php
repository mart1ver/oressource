


<?php session_start(); ?>
<?php
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
      { include "tete.php" ?>




    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="page-header">
      <div class="container">
        <h1>Bienvenue à bord d'Oressource <?php echo $_SESSION['nom']?> </h1>
        <p>L'outil libre de quantification et de mise en bilan dédié aux structures du ré-emploi</p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Collecté aujourd'hui:</h2>
          <p><div id="graphj" style="height: 180px;"></div></p>
          <p><a class="btn btn-default" href="#" role="button">Détails &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Collecté ce mois-ci:</h2>
          <p><div id="graphm" style="height: 180px;"></div></p>
          <p><a class="btn btn-default" href="#" role="button">Détails &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Collecté cette année:</h2>
          <p><div id="grapha" style="height: 180px;"></div></p>
          <p><a class="btn btn-default" href="#" role="button">Détails &raquo;</a></p>
        </div>
      </div>
      <hr>
       </div> <!-- /container -->


    <!-- Bootstrap core JavaScript+morris+raphael
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
      <script src="../js/jquery-2.0.3.min.js"></script>
      <script src="../js/raphael.js"></script>
      <script src="../js/morris/morris.js"></script>
  <script>       Morris.Donut({
    element: 'graphj',
    data: [
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "{value:".$donnees['somme'].", label:'".$donnees['nom']."'},";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND DATE(pesees_collectes.timestamp) = CURDATE()
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "'".$donnees['couleur']."'".",";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>

<script>       Morris.Donut({
    element: 'graphm',
    data: [
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND MONTH(pesees_collectes.timestamp) = MONTH(CURDATE())
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "{value:".$donnees['somme'].", label:'".$donnees['nom']."'},";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND MONTH(pesees_collectes.timestamp) = MONTH(CURDATE())
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "'".$donnees['couleur']."'".",";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>

<script>       Morris.Donut({
    element: 'grapha',
    data: [
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND YEAR(pesees_collectes.timestamp) = YEAR(CURDATE())
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "{value:".$donnees['somme'].", label:'".$donnees['nom']."'},";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
],
    backgroundColor: '#ccc',
    labelColor: '#060',
    colors: [
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme FROM type_dechets,pesees_collectes WHERE type_dechets.id = pesees_collectes.id_type_dechet AND YEAR(pesees_collectes.timestamp) = YEAR(CURDATE())
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "'".$donnees['couleur']."'".",";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " Kg."}
    });
</script>
  



<?php include "pied.php" ?>
<?php }
    else{
     include "login.php"; }?>


