<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'j') !== false))
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
 $req = $bdd->prepare("SELECT SUM(id) FROM filieres_sortie WHERE nom = :nom ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
$req->execute(array('nom' => $_POST['nom']));
$donnees = $req->fetch();
$req->closeCursor(); // Termine le traitement de la requête
     


if ($donnees['SUM(id)'] > 0) // SI le titre existe

{
header("Location:../ifaces/edition_filieres_sortie.php?err=Une filiere porte deja le meme nom!&nom=".$_POST['nom']."&description=".$_POST['description']."&couleur=".substr($_POST['couleur'],1));
$req->closeCursor(); // Termine le traitement de la requête
}

else 
{



$req->closeCursor(); // Termine le traitement de la requête

//on determine le nombre total de type dechets evac
$id_dechets = "";
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$reponsesa = $bdd->query('SELECT id FROM type_dechets_evac');
            // On affiche chaque entree une à une
           while ($donneessa = $reponsesa->fetch())
       {
            if( isset($_POST['tde'.$donneessa['id']] ))
           {
            $id_dechets = $id_dechets."a".$donneessa['id'];
           }
       }
$reponses->closeCursor(); // Termine le traitement de la requête


//on inssere la filiere de recyclage en base
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
$req = $bdd->prepare('INSERT INTO filieres_sortie (nom,  couleur, description, id_type_dechet_evac, visible) VALUES(?, ?, ?,  ?, ?)');
$req->execute(array($_POST['nom'],  $_POST['couleur'] , $_POST['description'], $id_dechets, "oui"));
  $req->closeCursor();

// Redirection du visiteur vers la page de gestion des affectation
header('Location:../ifaces/edition_filieres_sortie.php?msg=Filiere de sortie enregistrée avec succes!');
 }
}
else { 
header('Location:../moteur/destroy.php');
     }
?>

