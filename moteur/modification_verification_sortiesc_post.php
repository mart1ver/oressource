<?php session_start(); 
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
$req = $bdd->prepare('UPDATE sorties SET id_convention = :id_convention,  id_last_hero = :id_last_hero, last_hero_timestamp = NOW() 
	WHERE id = :id');
$req->execute(array('id_convention' => $_POST['id_convention'],'id' => $_POST['id'],'id_last_hero' => $_SESSION['id']));
  $req->closeCursor();
// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/verif_sorties.php?numero='.$_POST['npoint'].'&date1='.$_POST['date1'].'&date2='.$_POST['date2']);
?>