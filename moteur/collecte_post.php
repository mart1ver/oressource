<?php 
//on definit $adh en fonction $_POST['adh']
if(isset($_POST['adh']))
    {
    $adh = "oui";
    }
  else
  {
   $adh = "non";
  }


    
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
	$req = $bdd->prepare('INSERT INTO collectes (id_type_collecte,  adherent, localisation, id_point_collecte, commentaire) VALUES(?, ?, ?, ?, ?)');
	$req->execute(array($_POST['id_type_collecte'],$adh,  $_POST['loc'] , $_POST['id_point_collecte'] , $_POST['comm']));
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
$req = $bdd->prepare('INSERT INTO pesees_collectes (masse,  id_collecte, id_type_dechet) VALUES(?, ?, ?)');
$req->execute(array($_POST[$i],  $id_collecte , $i));
  $req->closeCursor();
}
    $i++;
}
// Redirection du visiteur vers la page de gestion des affectation
	header("Location:../ifaces/collecte.php?numero=".$_POST['id_point_collecte']);
	 ?>