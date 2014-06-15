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
$req = $bdd->prepare('UPDATE localites SET nom = :nom, relation_openstreetmap = :relation_openstreetmap , commentaire = :commentaire, couleur = :couleur  WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'relation_openstreetmap' => $_POST['lien'],'commentaire' => $_POST['commentaire'],'couleur' => $_POST['couleur'],'id' => $_POST['id']));

  $req->closeCursor();


















// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/edition_localites.php');
?>
