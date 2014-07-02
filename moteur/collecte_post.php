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
// mot de passe crypté md5 

// Insertion du post à l'aide d'une requête préparée
$req = $bdd->prepare('INSERT INTO collectes (id_type_collecte,  adherent, localisation, id_point_collecte, commentaire) VALUES(?, ?,  ?, ?, ?)');
$req->execute(array($_POST['nom'],$_POST['nom'],  $_POST['couleur'] , $_POST['description'], $_POST['nom']));
  $req->closeCursor();

// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/collecte.php?numero=');
 
?>
