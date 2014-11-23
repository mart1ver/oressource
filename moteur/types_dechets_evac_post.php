<?php 
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
 $req = $bdd->prepare("SELECT SUM(id) FROM type_dechets_evac WHERE nom = :nom ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('nom' => $_POST['nom']));
$donnees = $req->fetch();
$req->closeCursor(); // Termine le traitement de la requête
     


if ($donnees['SUM(id)'] > 0) // SI le titre existe

{
header("Location:../ifaces/types_dechets_evac.php?err=Un type de dechet sortant porte deja le meme nom!&nom=".$_POST['nom']."&description=".$_POST['description']."&couleur=".substr($_POST['couleur'],1));
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
$req = $bdd->prepare('INSERT INTO type_dechets_evac (nom,  couleur, description, visible) VALUES(?, ?,  ?, ?)');
$req->execute(array($_POST['nom'],  $_POST['couleur'] , $_POST['description'], "oui"));
  $req->closeCursor();

// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/types_dechets_evac.php?msg=Type de dechet sortant enregistrée avec succes!');
 }
?>
