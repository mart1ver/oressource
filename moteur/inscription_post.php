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
 
// Insertion du post à l'aide d'une requête préparée
$req = $bdd->prepare('INSERT INTO utilisateurs (mail, pass, niveau) VALUES( ?, ?, ?)');
$req->execute(array($_POST['mail'], md5($_POST['pass']), $_POST['niveaua'].$_POST['niveaub'].$_POST['niveauc'].$_POST['niveaue'].$_POST['niveaus'].$_POST['niveaug'].$_POST['niveauh'] ));
  $req->closeCursor();

// Redirection du visiteur vers la page des inscriptions
header('Location: ../ifaces/utilisateurs.php');
?>