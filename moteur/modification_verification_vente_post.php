<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'h') !== false))
{ 


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
$req = $bdd->prepare('UPDATE vente SET commentaire =:commentaire , id_last_hero = :id_last_hero, last_hero_timestamp = NOW()
	WHERE id = :id');



$req->execute(array('id' => $_POST['id'],'id_last_hero' => $_SESSION['id'],'commentaire' => $_POST['commentaire']));

  $req->closeCursor();




// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/verif_collecte.php?numero='.$_POST['npoint'].'&date1='.$_POST['date1'].'&date2='.$_POST['date2']);

}
else { 
header('Location:../moteur/destroy.php');
     }
?>
