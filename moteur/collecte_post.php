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
	$req = $bdd->prepare('INSERT INTO collectes (id_type_collecte,  adherent, localisation, id_point_collecte) VALUES(?, ?, ?, ?)');
	$req->execute(array($_POST['id_type_collecte'],$adh,  $_POST['loc'] , $_POST['id_point_collecte']));
    $req->closeCursor();





//insertion des pessés dans la table pesées_collectes














// Redirection du visiteur vers la page de gestion des affectation
	header("Location:../ifaces/collecte.php?numero=".$_POST['id_point_collecte']);
	//echo $_POST['id_type_collecte'].$adh.$_POST['loc'].$_POST['id_point_collecte']
	 
 ?>
