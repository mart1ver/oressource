<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 's'.$_GET['numero']) !== false))
{ 


// Connexion à la base de données
		try
{
		include('dbconfig.php');
}
		catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
// Insertion de la collecte (sans les pesées) l'aide d'une requête préparée
	$req = $bdd->prepare('INSERT INTO sorties ( classe, id_point_sortie, commentaire, id_createur) VALUES(?,?, ?, ?)');
	$req->execute(array( "sortiesd", $_POST['id_point_sortie'], $_POST['commentaire'], $_SESSION['id']));
  $id_sortie = $bdd->lastInsertId();
    $req->closeCursor();


//insertion des pessés dans la table pesées_collectes
//on determine '$nombrecat' le nombre de categories maxi (soit l'id maximum)  
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
            // On ecrit le nombre de categories maxi dans la varialble $nombrecat 
            $reponse = $bdd->query('SELECT MAX(id) AS nombrecat FROM type_dechets_evac');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {      
            $nombrecat = $donnees['nombrecat'];
             }
            $reponse->closeCursor(); // Termine le traitement de la requête
         $i = 1;
while ($i <= $nombrecat)
{
   //on inserre les valeures pour chaque 'i' ($i = id_type dechet) si elles sonts superieures à 0  
if ($_POST[$i] > 0) 
{
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
// Insertion du post à l'aide d'une requête préparée
$req = $bdd->prepare('INSERT INTO pesees_sorties (masse,  id_sortie, id_type_dechet_evac,id_createur) VALUES(?,?, ?, ?)');
$req->execute(array($_POST[$i],  $id_sortie , $i, $_SESSION['id']));
  $req->closeCursor();
}
    $i++;
}
// Redirection du visiteur vers la page de gestion des affectation
	header("Location:../ifaces/sortiesd.php?numero=".$_POST['id_point_sortie']);

	 }
else { 
header('Location:../moteur/destroy.php');
     }
?>

