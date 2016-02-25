<?php
session_start();
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
 


// extraction du nom de la structure




$req = $bdd->prepare('SELECT * FROM description_structure');
$req->execute();
    
$resultat = $req->fetch();
 
$_SESSION['session_timeout'] = $resultat['session_timeout'];
$_SESSION['tva_active'] = $resultat['tva_active'];
$_SESSION['taux_tva'] = $resultat['taux_tva'];
$_SESSION['structure'] = $resultat['nom'];
$_SESSION['siret'] = $resultat['siret'];
$_SESSION['adresse'] = $resultat['adresse'];
$_SESSION['texte_adhesion'] = $resultat['texte_adhesion'];
$_SESSION['lot_caisse'] = $resultat['lot'];
$_SESSION['viz_caisse'] = $resultat['viz'];
$_SESSION['nb_viz_caisse'] = $resultat['nb_viz'];
$_SESSION['saisiec'] = $resultat['saisiec'];

$_SESSION['affsp'] = $resultat['affsp'];
$_SESSION['affss'] = $resultat['affss'];
$_SESSION['affsr'] = $resultat['affsr'];
$_SESSION['affsd'] = $resultat['affsd'];
$_SESSION['affsde'] = $resultat['affsde'];
  
  

$req->closeCursor();




 
// Vérification des identifiants
$req = $bdd->prepare('SELECT * FROM utilisateurs WHERE mail = :mail AND pass = :pass');
$req->execute(array(
    'mail' => $_POST['mail'],
    'pass' => md5($_POST['pass'])));
    
$resultat = $req->fetch();
 
if (!$resultat)
{
    header ('location:../ifaces/login.php?err=Mauvais identifiant ou mot de passe !');
}
else
{

$_SESSION['id'] =$resultat['id'];
$_SESSION['niveau'] = $resultat['niveau'];
$_SESSION['nom'] = $resultat['nom'];
$_SESSION['prenom'] = $resultat['prenom'];
$_SESSION['mail'] = $resultat['mail'];
$_SESSION['systeme'] = "oressource";

$req->closeCursor();
    header ('location:../index.php');
}

 


?>
