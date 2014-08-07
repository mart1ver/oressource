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
 $req = $bdd->prepare("SELECT SUM(id) FROM grille_objets WHERE nom = :nom ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('nom' => $_POST['nom']));
$donnees = $req->fetch();
$req->closeCursor(); // Termine le traitement de la requête
     


if ($donnees['SUM(id)'] > 0) // SI le titre existe

{
header("Location:../ifaces/grilles_prix.php?err=Un objet porte deja le meme nom!&nom=".$_POST['nom']."&description=".$_POST['description']."&typo=".$_POST['typo']."&prix=".$_POST['prix']);
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
$req = $bdd->prepare('INSERT INTO grille_objets (nom,  prix, description, id_type_dechet, visible) VALUES(?, ?, ?, ?,? )');
$req->execute(array($_POST['nom'],  $_POST['prix'] , $_POST['description'], $_POST['typo'], "oui"));
  $req->closeCursor();

// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/grilles_prix.php?msg=Objet enregistré avec succes!'."&typo=".$_POST['typo']);
 }
?>
