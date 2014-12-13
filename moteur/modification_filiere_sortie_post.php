<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'j'.$_GET['numero']) !== false))
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
$req = $bdd->prepare('UPDATE filieres_sortie SET nom = :nom, id_type_dechet_evac = :id_type_dechet_evac, description = :description, couleur = :couleur  WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'id_type_dechet_evac' => $_POST['id_dechet'],
      'description' => $_POST['description'],'couleur' => $_POST['couleur'],'id' => $_POST['id']));

  $req->closeCursor();


















// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/edition_filieres_sortie.php');

}
else { 
header('Location:../moteur/destroy.php');
     }
?>

