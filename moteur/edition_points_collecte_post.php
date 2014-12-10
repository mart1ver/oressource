<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'k'.$_GET['numero']) !== false))
{
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
 $req = $bdd->prepare("SELECT SUM(id) FROM points_collecte WHERE nom = :nom ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('nom' => $_POST['nom']));
$donnees = $req->fetch();
$req->closeCursor(); // Termine le traitement de la requête
     


if ($donnees['SUM(id)'] > 0) // SI le titre existe

{
header("Location:../ifaces/edition_points_collecte.php?err=Un point de collecte porte deja le meme nom!&nom=".$_POST['nom']."&adresse=".$_POST['adresse']."&pesee_max=".$_POST['pesee_max']."&commentaire=".$_POST['commentaire']."&couleur=".substr($_POST['couleur'],1));
$req->closeCursor(); // Termine le traitement de la requête
}

else 
{
$req->closeCursor(); // Termine le traitement de la requête
                
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
$req = $bdd->prepare('INSERT INTO points_collecte (nom, adresse, couleur, commentaire, pesee_max , visible) VALUES(? ,? , ?, ?, ?, ?)');
$req->execute(array($_POST['nom'], $_POST['adresse'] , $_POST['couleur'] , $_POST['commentaire'], $_POST['pesee_max'], "oui"));
  $req->closeCursor();

// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/edition_points_collecte.php?msg=Point de collecte cree avec succes!');
 }
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

