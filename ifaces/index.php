


<?php session_start(); 

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page:
    if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
      { include "tete.php" ?>




    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="page-header">
      <div class="container">
        <h1>Bienvenue à bord d'Oressource <?php echo $_SESSION['nom']?> </h1>
        <p>L'outil libre de quantification et de mise en bilan dédié aux structures du ré-emploi</p>
      </div>
    </div>

    <div class="container" id="actualise">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4" >
          <h2>Collecté aujourd'hui:</h2>
          <p><div id="graphj" style="height: 180px;"></div></p>
<?php 
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi') !== false))
      { ?>
          <p><a href=" bilanc.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=0" class="btn btn-default"  role="button">Détails &raquo;</a></p>
<?php } ?>

        </div>
        <div class="col-md-4">
          <h2>Evacué aujourd'hui:</h2>
          <p><div id="grapha" style="height: 180px;"></div></p>
<?php
          if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi') !== false))
      { ?>
          <p><a class="btn btn-default" href=" bilanhb.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>" role="button">Détails &raquo;</a></p>
          <?php } ?>
       </div>
        <div class="col-md-4">
          <h2>Vendu aujourd'hui:</h2>
          <p><div id="graphm" style="height: 180px;"></div></p>
          <?php
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi') !== false))
      { ?>
          <p><a class="btn btn-default" href=" bilanv.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>" role="button">Détails &raquo;</a></p>
          <?php } ?>
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme FROM type_dechets,vendus WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE()
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme FROM type_dechets,vendus WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE()
GROUP BY nom');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {

            echo "'".$donnees['couleur']."'".",";


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                ?>
    ],
    formatter: function (x) { return x + " pcs."}
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_sorties.masse) somme 
FROM type_dechets,pesees_sorties 
WHERE type_dechets.id = pesees_sorties.id_type_dechet 
AND DATE(pesees_sorties.timestamp) = CURDATE()
GROUP BY nom
UNION
SELECT types_poubelles.couleur,types_poubelles.nom, sum(pesees_sorties.masse) somme 
FROM types_poubelles,pesees_sorties 
WHERE types_poubelles.id = pesees_sorties.id_type_poubelle 
AND DATE(pesees_sorties.timestamp) = CURDATE()
GROUP BY nom
UNION
SELECT type_dechets_evac.couleur,type_dechets_evac.nom, sum(pesees_sorties.masse) somme 
FROM type_dechets_evac ,pesees_sorties 
WHERE type_dechets_evac.id=pesees_sorties.id_type_dechet_evac 
AND DATE(pesees_sorties.timestamp) = CURDATE()
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
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_sorties.masse) somme 
FROM type_dechets,pesees_sorties 
WHERE type_dechets.id = pesees_sorties.id_type_dechet 
AND DATE(pesees_sorties.timestamp) = CURDATE()
GROUP BY nom
UNION
SELECT types_poubelles.couleur,types_poubelles.nom, sum(pesees_sorties.masse) somme 
FROM types_poubelles,pesees_sorties 
WHERE types_poubelles.id = pesees_sorties.id_type_poubelle 
AND DATE(pesees_sorties.timestamp) = CURDATE()
GROUP BY nom
UNION
SELECT type_dechets_evac.couleur,type_dechets_evac.nom, sum(pesees_sorties.masse) somme 
FROM type_dechets_evac ,pesees_sorties 
WHERE type_dechets_evac.id=pesees_sorties.id_type_dechet_evac 
AND DATE(pesees_sorties.timestamp) = CURDATE()
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
var temps_reload = 240
for (var i = 1; i <= temps_reload; i++) {
    var tick = function(i) {
        return function() {
            
            if (i == temps_reload) {
              window.location.reload();
   
}
        }

    };
    setTimeout(tick(i), 500 * i);

}

</script>
  



<?php include "pied.php";
}
    else
{
     header('Location: login.php') ; 
}
?>


