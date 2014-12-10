<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource")
{


//on obtien le pass en db (md5) dans $bddpass
 
           


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
            /*
SELECT SUM(masse),timestamp FROM pesees_collectes WHERE  `timestamp`BETWEEN '2014-09-18 00:00:00' AND '2014-09-24 23:59:59'
            */
 $req = $bdd->prepare("SELECT pass FROM utilisateurs WHERE  id = :id ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('id' => $_SESSION['id'] ));
$donnees = $req->fetch();
     
$bddpass = $donnees['pass'];

$req->closeCursor(); // Termine le traitement de la requête



// si le mot de passe actuel == le nouveau mot de passe == la verif , message fun
if ($_POST['pass1'] == $_POST['pass2'] AND $_POST['pass2'] == $_POST['passold'])
{
	header('Location: ../ifaces/edition_mdp_utilisateur.php?msg='.$_SESSION['nom'].', vous venez de tenter de modifier votre mot de passe par le même mot de passe, à quoi bon? Par faute de sens dans cette operation administrative, oressource ne procedera à aucun changement.');	
}
else
{

// si passold = pass en bdd 

if (md5($_POST['passold']) == $bddpass)
{

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
$req->execute(array('pass' => md5($_POST['pass1']),'id' => $_SESSION['id']));

  $req->closeCursor();






header('Location: ../ifaces/edition_mdp_utilisateur.php?msg=Mot de passe modifié avec succes, utilisateur: '.$_SESSION['nom']." mail: ".$_SESSION['mail']);	
}
else
{

header( "Location: ../ifaces/edition_mdp_utilisateur.php?err=Veuillez inscrire deux mots de passe semblables");
}
}
else
{
header( "Location: ../ifaces/edition_mdp_utilisateur.php?err=Mauvais mot de passe actuel");
}
}

}
else { 
header('Location:../moteur/destroy.php');
     }
?>

