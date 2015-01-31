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
$xls_output .= "Pour tout les points de vente"."\t"; 
$xls_output .= "\n\r";
$xls_output .= "\n\r";
$xls_output .= "\n\r";
$xls_output .= "type d'objet':"."\t"."quantité vendue:"."\t"."chiffre dégagé:"."\t";
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
type_dechets.nom,type_dechets.id,SUM(vendus.quantite) sommeq,SUM(vendus.prix) sommep
FROM 
vendus,type_dechets, ventes
WHERE
vendus.timestamp BETWEEN :du AND :au AND
type_dechets.id =  vendus.id_type_dechet AND vendus.id_vente = ventes.id
GROUP BY id_type_dechet
ORDER BY sommep DESC');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            $someqtot = 0;
            $someptot = 0;
           $xls_output .= $donnees['nom']."\t".$donnees['sommeq']."\t".str_replace ( ".", ",",  $donnees['sommep'])."\t"."\n";
            
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
            $reponse2 = $bdd->prepare('SELECT IF(vendus.id_objet = 0, type_dechets.nom, grille_objets.nom) nom ,grille_objets.id, sum(vendus.quantite) sommeq, sum(vendus.prix) sommep
 FROM grille_objets, vendus ,ventes, type_dechets
WHERE vendus.timestamp BETWEEN :du AND :au 
AND grille_objets.id = vendus.id_objet 
AND type_dechets.id = vendus.id_type_dechet
AND vendus.id_vente = ventes.id
AND type_dechets.id = :id_type_dechet
GROUP BY nom
ORDER BY sommep DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin ,'id_type_dechet' => $donnees['id'] ));
           // On affiche chaque entree une à une
$xls_output .= "objets:"."\t"."quantité vendue:"."\t"."chiffre dégagé:"."\t";
$xls_output .= "\n\r";


           while ($donnees2 = $reponse2->fetch())
           {       

            $xls_output .= $donnees2['nom']."\t".$donnees2['sommeq']."\t".str_replace ( ".", ",",  $donnees['sommep'])."\t"."\n";
            



 $someqtot = $someqtot + $donnees2['sommeq'] ;
 $someptot = $someptot + $donnees2['sommep'] ;

             }

$xls_output .= 'autres'."\t".($donnees['sommeq'] - $someqtot)."\t".($donnees['sommep'] - $someptot)."\t"."\n";

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
$xls_output .= "type d'objet':"."\t"."quantité vendue:"."\t"."chiffre dégagé:"."\t";
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
type_dechets.nom,type_dechets.id,SUM(vendus.quantite) sommeq,SUM(vendus.prix) sommep
FROM 
vendus,type_dechets, ventes
WHERE
vendus.timestamp BETWEEN :du AND :au AND
type_dechets.id =  vendus.id_type_dechet AND vendus.id_vente = ventes.id AND ventes.id_point_vente = :numero
GROUP BY id_type_dechet
ORDER BY sommep DESC');
 $reponse->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero']  ));
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
            $someqtot = 0;
            $someptot = 0;

$xls_output .= $donnees['nom']."\t".$donnees['sommeq']."\t".str_replace ( ".", ",",  $donnees['sommep'])."\t"."\n";




           






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
            $reponse2 = $bdd->prepare('SELECT IF(vendus.id_objet = 0, type_dechets.nom, grille_objets.nom) nom ,grille_objets.id, sum(vendus.quantite) sommeq, sum(vendus.prix) sommep
 FROM grille_objets, vendus ,ventes, type_dechets
WHERE vendus.timestamp BETWEEN :du AND :au 
AND grille_objets.id = vendus.id_objet 
AND type_dechets.id = vendus.id_type_dechet
AND vendus.id_vente = ventes.id
AND type_dechets.id = :id_type_dechet
AND ventes.id_point_vente = :numero
GROUP BY nom
ORDER BY sommep DESC');
  $reponse2->execute(array('du' => $time_debut,'au' => $time_fin,'numero' => $_GET['numero'] ,'id_type_dechet' => $donnees['id'] ));
  $xls_output .= "objets:"."\t"."quantité vendue:"."\t"."chiffre dégagé:"."\t";
$xls_output .= "\n\r";
           // On affiche chaque entree une à une
           while ($donnees2 = $reponse2->fetch())
           {    

 $someqtot = $someqtot + $donnees2['sommeq'] ;
 $someptot = $someptot + $donnees2['sommep'] ;

$xls_output .= $donnees2['nom']."\t".$donnees2['sommeq']."\t".str_replace ( ".", ",",  $donnees['sommep'])."\t"."\n";


            
             }

//str_replace ( ".", ",",  ($donnees['sommep'] - $someptot))
             $xls_output .= 'autres'."\t".($donnees['sommeq'] - $someqtot)."\t".str_replace ( ".", ",",  ($donnees['sommep'] - $someptot))."\t"."\n";
              $reponse2->closeCursor(); // Termine le traitement de la requête
                ?>
      <?php
      $xls_output .= "\n\r";
           }
              $reponse->closeCursor(); // Termine le traitement de la requête

               } 


























//=====================================================================================================================================





         
           









 
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=collectes_par_types_objet_" . date("Ymd").".xls");
print $xls_output;
exit;
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

