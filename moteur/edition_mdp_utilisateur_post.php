<?php
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

// si pass = pass1 
if ($_POST['pass1'] = $_POST['pass2'])
{

header('Location: ../ifaces/utilisateurs.php?msg=Mot de passe modifié avec succes, utilisateur'.$_POST['mail'] );	
}
else
{
$req->closeCursor(); // Termine le traitement de la requête puis redirige vers la page utilisateurs.php avec message d'erreur
header( "Location: ../ifaces/edition_mdp_utilisateur.php?err=Veuillez inscrire deux mots de passe semblables");
}
?>