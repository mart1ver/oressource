<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'g'.$_GET['numero']) !== false))
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


// suppression de l'utilisateur à l'aide d'une requête préparée "DELETE FROM utilisateurs WHERE id = :id"
$req = $bdd->prepare('DELETE FROM grille_objets WHERE id = :id');
$req->execute(array('id' => $_POST['id']));

  $req->closeCursor();





// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/grilles_prix.php?msg=Objet definitivement supprimé des grilles de prix.'."&typo=".$_POST['typo']);

}
else { 
header('Location:../moteur/destroy.php');
     }
?>

