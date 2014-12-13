<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'bi'.$_GET['numero']) !== false))
{ 




//Premiere ligne = nom des champs (
$xls_output = "Numéro d'indicateur"."\t"."date"."\t"."nom"."\t"."description";
$xls_output .= "\n\r";




          //on affiche un onglet par type d'objet
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
 
            // On recupère tout le contenu des visibles de la table type_dechets
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui" ');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
           


$xls_output .= $donnees['id']."\t".$donnees['timestamp']."\t".$donnees['nom']."\t".$donnees['description']."\n";


            }
              $reponse->closeCursor(); // Termine le traitement de la requête
           









 
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=type-dechets_" . date("Ymd").".xls");
print $xls_output;
exit;
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

