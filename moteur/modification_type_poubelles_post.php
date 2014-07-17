<?php


if($_POST['ultime'] = "oui")
    {
    $ultime = "oui";
    }
  else
  {
   $ultime = "non";
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
$req = $bdd->prepare('UPDATE types_poubelles SET nom = :nom,  description = :description,  masse_bac = :masse_bac,  ultime = :ultime , couleur = :couleur  WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'description' => $_POST['description'],'masse_bac' => $_POST['masse_bac'],'ultime' => $ultime,'couleur' => $_POST['couleur'],'id' => $_POST['id']));

  $req->closeCursor();


















// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/edition_types_poubelles.php');
?>
