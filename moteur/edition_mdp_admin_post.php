<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l') !== false))
{

//on va jouer avec les sessions
session_start();





// si passold = pass en bdd 



// et si pass = pass1 
if ($_POST['pass1'] == $_POST['pass2'])
{

// inscription du nouveau mot de passe en bdd
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
$req = $bdd->prepare('UPDATE utilisateurs SET pass = :pass WHERE id = :id');
$req->execute(array('pass' => md5($_POST['pass1']),'id' => $_POST['id']));

  $req->closeCursor();






header('Location: ../ifaces/edition_utilisateurs.php?msg=Mot de passe modifié avec succes, utilisateur: '.$_SESSION['nom']." mail: ".$_SESSION['mail']);	
}
else
{

header( "Location: ../ifaces/edition_mdp_admin.php?err=Veuillez inscrire deux mots de passe semblables");
}
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

