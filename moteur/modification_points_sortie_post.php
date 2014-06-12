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
$req = $bdd->prepare('UPDATE points_sortie SET nom = :nom, adresse = :adresse , commentaire = :commentaire, couleur = :couleur  WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'adresse' => $_POST['adresse'],'commentaire' => $_POST['commentaire'],'couleur' => $_POST['couleur'],'id' => $_POST['id']));

  $req->closeCursor();


















// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/edition_points_sorties.php');
?>
