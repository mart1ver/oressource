<?php session_start();




if ($_POST['saisiec_user'] == "oui" AND (strpos($_POST['niveau_user'], 'e') !== false) )
{
$antidate = $_POST['antidate'].date(" H:i:s");






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
  $req = $bdd->prepare('INSERT INTO sorties (timestamp,classe, id_point_sortie, id_createur) VALUES(?,?, ?, ?)');
  $req->execute(array($antidate,"sortiesp", $_POST['id_point_sortie'], $_POST['id_user']));
  $id_sortie = $bdd->lastInsertId();
    $req->closeCursor();


//insertion des pessés dans la table pesées_sorties
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
            // On ecrit le nombre de types de poubelles maxi dans la varialble $nombrecat 
            $reponse = $bdd->query('SELECT MAX(id) AS nombrecat FROM types_poubelles');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {      
            $nombrecat = $donnees['nombrecat'];
             }
            $reponse->closeCursor(); // Termine le traitement de la requête
         $i = 1;
while ($i <= $nombrecat)
{
   //on inserre les valeures pour chaque 'i' ($i = id_type poubelle) si elles sonts superieures à 0  
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
$req = $bdd->prepare('INSERT INTO pesees_sorties (timestamp,masse,  id_sortie, id_type_poubelle, id_createur) VALUES(?,?, ?, ?, ?)');
$req->execute(array($antidate, $_POST[$i],  $id_sortie , $i, $_POST['id_user']));
  $req->closeCursor();
}
    $i++;
}
// Redirection du visiteur vers la page de gestion des affectation
  header("Location:../ifaces/sortiesp.php?numero=".$_POST['id_point_sortie']);








}
else{


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
	$req = $bdd->prepare('INSERT INTO sorties (classe, id_point_sortie, id_createur) VALUES(?,?, ?)');
	$req->execute(array("sortiesp", $_POST['id_point_sortie'], $_POST['id_user']));
  $id_sortie = $bdd->lastInsertId();
    $req->closeCursor();


//insertion des pessés dans la table pesées_sorties
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
            // On ecrit le nombre de types de poubelles maxi dans la varialble $nombrecat 
            $reponse = $bdd->query('SELECT MAX(id) AS nombrecat FROM types_poubelles');
            // On affiche chaque entree une à une
            while ($donnees = $reponse->fetch())
            {      
            $nombrecat = $donnees['nombrecat'];
             }
            $reponse->closeCursor(); // Termine le traitement de la requête
         $i = 1;
while ($i <= $nombrecat)
{
   //on inserre les valeures pour chaque 'i' ($i = id_type poubelle) si elles sonts superieures à 0  
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
$req = $bdd->prepare('INSERT INTO pesees_sorties (masse,  id_sortie, id_type_poubelle, id_createur) VALUES(?,?, ?, ?)');
$req->execute(array($_POST[$i],  $id_sortie , $i, $_POST['id_user']));
  $req->closeCursor();
}
    $i++;
}
// Redirection du visiteur vers la page de gestion des affectation
	header("Location:../ifaces/sortiesp.php?numero=".$_POST['id_point_sortie']);

}


//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette fonction:
if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 's'.$_GET['numero']) !== false))
{

}
else{
 header('Location:../moteur/destroy.php?motif=1');}
?>

