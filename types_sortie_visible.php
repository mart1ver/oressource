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
$req = $bdd->prepare('UPDATE type_sortie SET visible = :visible WHERE id = :id');
$req->execute(array('visible' => $_POST['visible'],'id' => $_POST['id']));

  $req->closeCursor();


















// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/edition_types_sortie.php');
?>
