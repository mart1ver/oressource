<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'g') !== false))
{ 

//martin vert
 try
            {
            // On se connecte à MySQL
            include('../moteur/dbconfig.php');
            }
            catch(Exception $e)
            {
            // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
            }
 
            // Si tout va bien, on peut continuer
 $req = $bdd->prepare("SELECT SUM(id) FROM grille_objets WHERE nom = :nom AND id <> :id ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('nom' => $_POST['nom'],'id' => $_POST['id'] ));
$donnees = $req->fetch();
$req->closeCursor(); // Termine le traitement de la requête
     


if ($donnees['SUM(id)'] > 0) // SI le titre existe

{
header("Location:../ifaces/grilles_prix.php?err=Un objet porte deja le meme nom!&typo=".$_POST['typo']);
$req->closeCursor(); // Termine le traitement de la requête
}

else 
{
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
$req = $bdd->prepare('UPDATE grille_objets SET nom = :nom, description = :description , prix = :prix WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'description' => $_POST['description'],'prix' => $_POST['prix'],'id' => $_POST['id']));

  $req->closeCursor();





// Redirection du visiteur vers la page de gestion des points de collecte
header("Location:../ifaces/grilles_prix.php?typo=".$_POST['typo']);
}
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

