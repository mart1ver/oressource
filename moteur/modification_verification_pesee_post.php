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
$req = $bdd->prepare('UPDATE pesees_collectes SET id_type_dechet = :id_type_dechet, masse = :masse WHERE id = :id');
$req->execute(array('id_type_dechet' => $_POST['id_type_dechet'],'masse' => $_POST['masse'],'id' => $_POST['id']));

  $req->closeCursor();


















// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/verif_collecte.php?numero='.$_POST['npoint'].'&date='.$_POST['date']);

?>
