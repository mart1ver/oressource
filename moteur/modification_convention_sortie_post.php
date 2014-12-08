<?php session_start(); 

//Vérification des autorisations de l'utilisateur et des variables de session requisent pour l'affichage de cette page:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'j') !== false))

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
$req = $bdd->prepare('UPDATE conventions_sorties SET nom = :nom,  description = :description, couleur = :couleur  WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'description' => $_POST['description'],'couleur' => $_POST['couleur'],'id' => $_POST['id']));

  $req->closeCursor();


// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/edition_conventions_sortie.php');
}
else
{    
header('Location: ../moteur/destroy.php') ;
}
?>
