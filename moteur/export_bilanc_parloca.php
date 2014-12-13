<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi') !== false))
{ 



//on convertit les deux dates en un format compatible avec la bdd

$txt1  = $_GET['date1'];
$date1ft = DateTime::createFromFormat('d-m-Y', $txt1);
$time_debut = $date1ft->format('Y-m-d');
$time_debut = $time_debut." 00:00:00";

$txt2  = $_GET['date2'];
$date2ft = DateTime::createFromFormat('d-m-Y', $txt2);
$time_fin = $date2ft->format('Y-m-d');
$time_fin = $time_fin." 23:59:59";



  

//Premiere ligne = nom des champs (

// on affiche la periode visée
  if($_GET['date1'] == $_GET['date2']){
    
    $xls_output = ' Le '.$_GET['date1']."\t";

  }
  else
  {
  
  $xls_output = ' Du '.$_GET['date1']." au ".$_GET['date2']."\t"; 
}













        if ($_GET['numero'] == 0) {
          $xls_output .= "\n\r";
$xls_output .= "Pour tout les points de collecte"."\t"; 
$xls_output .= "\n\r";
$xls_output .= "\n\r";
$xls_output .= "\n\r";
$xls_output .= "localité:"."\t"."masse collecté:"."\t"."nombre de collectes:"."\t";
$xls_output .= "\n\r";

// on determine les masses totales collèctés sur cete periode(pour tout les points)
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

            $reponse = $bdd->prepare('SELECT 
localites.nom,SUM(pesees_collectes.masse) somme,pesees_collectes.timestamp,localites.id id,COUNT(distinct collectes.id) ncol
FROM 
pesees_collectes,collectes,localites

WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
GROUP BY id');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           $xls_output .= $donnees['nom']."\t".$donnees['somme']."\t".$donnees['ncol']."\t"."\n";
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
            $reponse2 = $bdd->prepare('SELECT localites.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,localites , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au 
AND type_dechets.id = pesees_collectes.id_type_dechet 
AND localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
AND localites.id = :id_loc
GROUP BY nom
ORDER BY somme DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin ,'id_type_collecte' => $donnees['id'] ));
           // On affiche chaque entree une à une
$xls_output .= "objets collectés pour ce type de collecte:"."\t"."masse collecté:"."\t";
$xls_output .= "\n\r";

           while ($donnees2 = $reponse2->fetch())
           {       

            $xls_output .= $donnees2['nom']."\t".$donnees2['somme']."\t"."\n";
            
             }

              $reponse2->closeCursor(); // Termine le traitement de la requête
                ?>
               
      <?php

$xls_output .= "\n\r";

           }
              $reponse->closeCursor(); // Termine le traitement de la requête
               }else

               {

$xls_output .= "\n\r";
$xls_output .= " pour le point numero:  ".$_GET['numero']."\t"; 
$xls_output .= "\n\r";
$xls_output .= "\n\r";
$xls_output .= "\n\r";
$xls_output .= "type de collecte:"."\t"."masse collecté:"."\t"."nombre de collectes:"."\t";
$xls_output .= "\n\r";

// on determine les masses totales collèctés sur cete periode(pour un point donné)
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

            $reponse = $bdd->prepare('SELECT 
localites.nom,SUM(pesees_collectes.masse) somme,pesees_collectes.timestamp,localites.id,COUNT(distinct collectes.id) ncol
FROM 
pesees_collectes,collectes,localites

WHERE
  pesees_collectes.timestamp BETWEEN :du AND :au AND
localites.id =  collectes.localisation AND pesees_collectes.id_collecte = collectes.id
AND collectes.id_point_collecte = :numero
GROUP BY id');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero']  ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {


$xls_output .= $donnees['nom']."\t".$donnees['somme']."\t".$donnees['ncol']."\t"."\n";




           






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
            $reponse2 = $bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_collectes.masse) somme
 FROM type_dechets,pesees_collectes ,type_collecte , collectes
WHERE
pesees_collectes.timestamp BETWEEN :du AND :au 
AND type_dechets.id = pesees_collectes.id_type_dechet 
AND type_collecte.id =  collectes.id_type_collecte AND pesees_collectes.id_collecte = collectes.id
AND type_collecte.id = :id_type_collecte AND collectes.id_point_collecte = :numero
GROUP BY nom
ORDER BY somme DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ,'id_type_collecte' => $donnees['id'] ));
  $xls_output .= "objets collectés pour ce type de collecte:"."\t"."masse collecté:"."\t";
$xls_output .= "\n\r";
           // On affiche chaque entree une à une
           while ($donnees2 = $reponse2->fetch())
           {    


$xls_output .= $donnees2['nom']."\t".$donnees2['somme']."\t"."\n";


            
             }
              $reponse2->closeCursor(); // Termine le traitement de la requête
                ?>
      <?php
      $xls_output .= "\n\r";
           }
              $reponse->closeCursor(); // Termine le traitement de la requête

               } 


























//=====================================================================================================================================





         
           









 
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=type-dechets_" . date("Ymd").".xls");
print $xls_output;
exit;
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

