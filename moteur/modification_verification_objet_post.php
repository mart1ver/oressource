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
$req = $bdd->prepare('UPDATE vendus SET  prix = :prix, quantite = :quantite, id_last_hero = :id_last_hero, last_hero_timestamp = NOW() 
  WHERE id = :id');
$req->execute(array('prix' => $_POST['prix'],'quantite' => $_POST['quantite'],'id' => $_POST['id'],'id_last_hero' => $_SESSION['id']));

  $req->closeCursor();




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
$req = $bdd->prepare('UPDATE ventes SET  id_last_hero = :id_last_hero, last_hero_timestamp = NOW() 
  WHERE id = :id');
$req->execute(array('id' => $_POST['nvente'],'id_last_hero' => $_SESSION['id']));

  $req->closeCursor();



// si la masse et superieure a 0 on l'ecrit dans la base
  if($_POST['quantite'] > 0)
  {
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
$req = $bdd->prepare('UPDATE pesees_vendus SET  id_last_hero = :id_last_hero,masse = :masse, last_hero_timestamp = NOW() 
  WHERE id_vendu = :id');
$req->execute(array('id' => $_POST['id'],'id_last_hero' => $_SESSION['id'],'masse' => $_POST['masse']));

  $req->closeCursor();
  }



// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/verif_vente.php?numero='.$_POST['npoint'].'&date1='.$_POST['date1'].'&date2='.$_POST['date2']);

}
else { 
header('Location:../moteur/destroy.php');
     }
?>
