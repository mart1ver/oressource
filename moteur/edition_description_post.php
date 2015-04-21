<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'k') !== false))
{
$atva = 'non' ;
if ($_POST['atva'] == 'oui')
{

	$atva = 'oui' ;
}
$lot = 'non' ;
if ($_POST['lot'] == 'oui')
{

	$lot = 'oui' ;
}
		





//martin vert
// Connexion à la base de données
		try
{
		include('dbconfig.php');
}
		catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
 
// Insertion du post à l'aide d'une requête préparée
// mot de passe crypté md5 

// Insertion du post à l'aide d'une requête préparée
	

$req = $bdd->prepare('UPDATE description_structure SET id = :id, nom = :nom, adresse = :adresse, description = :description , siret = :siret , telephone = :telephone , mail =:mail, taux_tva =:taux_tva, tva_active=:tva_active ,cr=:cr,lot=:lot
WHERE id = :id');



$req->execute(array('id' => 1,'nom' => $_POST['nom'], 'description' => $_POST['description'],'siret' => $_POST['siret'], 'mail' => $_POST['mail'], 'adresse' => $_POST['adresse'],'telephone' => $_POST['telephone'],'id_localite' => $_POST['localite'],'taux_tva' => $_POST['ttva'], 'tva_active' => $atva, 'lot' => $lot ,'cr' => $_POST['cr']));







    $req->closeCursor();
// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/edition_description.php');
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

