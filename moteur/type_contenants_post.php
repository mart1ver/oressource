<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'g') !== false))
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
 $req = $bdd->prepare("SELECT SUM(id) FROM type_contenants WHERE nom = :nom ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('nom' => $_POST['nom']));
$donnees = $req->fetch();
$req->closeCursor(); // Termine le traitement de la requête
     


if ($donnees['SUM(id)'] > 0) // SI le titre existe

{
header("Location:../ifaces/edition_types_contenants.php?err=Un moyen de manutention porte deja le meme nom!&nom=".$_POST['nom']."&description=".$_POST['description']."&masse_bac=".$_POST['masse_bac']."&couleur=".substr($_POST['couleur'],1));
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
$req = $bdd->prepare('INSERT INTO type_contenants (nom,  couleur, description, masse , visible) VALUES(?, ?, ?,  ?, ?)');
$req->execute(array($_POST['nom'],  $_POST['couleur'] , $_POST['description'], $_POST['masse_bac'], "oui"));
  $req->closeCursor();

// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/edition_types_contenants.php?msg=Moyen de manutention enregistré avec succes!');
 }

}
else { 
header('Location:../moteur/destroy.php');
     }
?>

