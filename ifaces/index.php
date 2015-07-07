<?php session_start(); 

require_once("../moteur/dbconfig.php");

//Vérification du renseignement du champ "id" (dans le tableau $_SESSION) et du fait que la variable "système" de ce même tableau a bien la valeur "oressource" avant d'afficher quoique ce soit:    
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
      
{ include "tete.php" ?>




    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="page-header">
      <div class="container">
        <h1>Bienvenue à bord d'Oressource <?php echo $_SESSION['prenom']?>! </h1>
        <p>Oressource est un outil libre de quantification et de mise en bilan dédié aux structures du ré-emploi</p>
      </div>
    </div>

    <div class="container" id="actualise">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4" >
          <?php 
//on determine les masses collectés et evacuées ansi que le nombre d'objets vendus aujoud'hui 


           
            $reponse = $bdd->query('SELECT  sum(vendus.quantite) qv, sum(pesees_collectes.masse) mc, sum(pesees_sorties.masse) me  FROM pesees_collectes ,pesees_sorties,vendus
                                    WHERE  DATE(pesees_collectes.timestamp) = CURDATE() AND DATE(pesees_sorties.timestamp) = CURDATE() AND DATE(vendus.timestamp) = CURDATE() AND vendus.remboursement = 0');
 
           //on envoie la réponse dans trois variables distinctes
           while ($donnees = $reponse->fetch())
           {

           $qv = $donnees['qv'];
           if ($qv == NULL){
            $qv = "zero" ;
           }
           $qv = $donnees['mc'];
           $qv = $donnees['me'];


             }
              $reponse->closeCursor(); // Termine le traitement de la requête
                
          ?>
          <h2>Collecté aujourd'hui:<?php echo $mc."Kgs.";?></h2>
          <p><div id="graphj" style="height: 180px;"></div></p>
<?php 
//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage des bilans de collecte en première page:
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi') !== false))
      { ?>
          <p><a href=" bilanc.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>&numero=0" class="btn btn-default"  role="button">Détails &raquo;</a></p>
<?php } ?>

        </div>
        <div class="col-md-4">
          <h2>Evacué aujourd'hui:<?php echo $me."Kgs.";?></h2>
          <p><div id="grapha" style="height: 180px;"></div></p>
<?php
//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage des bilans de sortie hors-boutique en première page:
          if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi') !== false))
      { ?>
          <p><a class="btn btn-default" href=" bilanhb.php?date1=<?php echo date("d-m-Y")?>&date2=<?php echo date("d-m-Y")?>" role="button">Détails &raquo;</a></p>
          <?php } ?>
       </div>
        <div class="col-md-4">
          <h2>Vendu aujourd'hui:<?php echo $qv."Pcs.";?></h2>
          <p><div id="graphm" style="height: 180px;"></div></p>
          <?php
//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage des bilans de vente en première page:
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
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme FROM type_dechets,vendus WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
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
 
            // On recupère tout le contenu de la table affectations
            $reponse = $bdd->query('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme FROM type_dechets,vendus WHERE type_dechets.id = vendus.id_type_dechet AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
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


