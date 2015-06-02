<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'c'.$_GET['numero']) !== false))
{
//on definit $adh en fonction $_POST['adh']
if(isset($_POST['adh']))
    {
    $adh = "oui";
    }
  else
  {
   $adh = "non";
  }






if ($_SESSION['saisiec'] == "oui" AND (strpos($_SESSION['niveau'], 'e') !== false) )
{
$antidate = $_POST['antidate'].date(" H:i:s");


 if ($_POST['najout'] > 0) 
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
  $req = $bdd->prepare('INSERT INTO collectes (timestamp, id_type_collecte,  adherent, localisation, id_point_collecte, commentaire, id_createur) VALUES(?,?, ?, ?, ?, ?, ?)');
  $req->execute(array($antidate, $_POST['id_type_collecte'],$adh,  $_POST['loc'] , $_POST['id_point_collecte'] , $_POST['comm'] , $_SESSION['id']));
  $id_collecte = $bdd->lastInsertId();
    $req->closeCursor();

}
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
            $reponse = $bdd->query('SELECT MAX(id) AS nombrecat FROM type_dechets');
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
$req = $bdd->prepare('INSERT INTO pesees_collectes (timestamp, masse,  id_collecte, id_type_dechet,id_createur) VALUES(?,?,?, ?, ?)');
$req->execute(array($antidate,$_POST[$i],  $id_collecte , $i , $_SESSION['id']));
  $req->closeCursor();
}
    $i++;
}
// Redirection du visiteur vers la page de gestion des affectation
  header("Location:../ifaces/collecte.php?numero=".$_POST['id_point_collecte']);



     











   }
 else{



    
    if ($_POST['najout'] > 0) 
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
	$req = $bdd->prepare('INSERT INTO collectes (id_type_collecte,  adherent, localisation, id_point_collecte, commentaire, id_createur) VALUES(?, ?, ?, ?, ?, ?)');
	$req->execute(array($_POST['id_type_collecte'],$adh,  $_POST['loc'] , $_POST['id_point_collecte'] , $_POST['comm'] , $_SESSION['id']));
  $id_collecte = $bdd->lastInsertId();
    $req->closeCursor();

}
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
            $reponse = $bdd->query('SELECT MAX(id) AS nombrecat FROM type_dechets');
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
$req = $bdd->prepare('INSERT INTO pesees_collectes (masse,  id_collecte, id_type_dechet,id_createur) VALUES(?,?, ?, ?)');
$req->execute(array($_POST[$i],  $id_collecte , $i , $_SESSION['id']));
  $req->closeCursor();
}
    $i++;
}
// Redirection du visiteur vers la page de gestion des affectation
	header("Location:../ifaces/collecte.php?numero=".$_POST['id_point_collecte']);
}


}
else {
  header('Location:../moteur/destroy.php');
     }













?>

