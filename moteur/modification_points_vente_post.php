<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'k'.$_GET['numero']) !== false))
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
 $req = $bdd->prepare("SELECT SUM(id) FROM points_vente WHERE nom = :nom AND id <> :id ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('nom' => $_POST['nom'],'id' => $_POST['id'] ));
$donnees = $req->fetch();
$req->closeCursor(); // Termine le traitement de la requête
     


if ($donnees['SUM(id)'] > 0) // SI le titre existe

{
header("Location:../ifaces/modification_points_vente.php?err=Un point de vente porte deja le meme nom!&nom=".$_POST['nom']."&adresse=".$_POST['adresse']."&surface=".$_POST['surface']."&commentaire=".$_POST['commentaire']."&couleur=".substr($_POST['couleur'],1));
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
$req = $bdd->prepare('UPDATE points_vente SET nom = :nom, adresse = :adresse , commentaire = :commentaire, surface_vente = :surface_vente, couleur = :couleur  WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'adresse' => $_POST['adresse'],'commentaire' => $_POST['commentaire'],'surface_vente' => $_POST['surface'],'couleur' => $_POST['couleur'],'id' => $_POST['id']));

  $req->closeCursor();




// Redirection du visiteur vers la page de gestion des points de collecte
header('Location:../ifaces/edition_points_vente.php');
}
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

