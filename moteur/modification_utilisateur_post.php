<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'l'.$_GET['numero']) !== false))
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
// si l'addresse mail est deja utillisée
$req = $bdd->prepare("SELECT SUM(id) FROM utilisateurs WHERE mail = :amail ");
$req->execute(array('amail' => $_POST['mail']));
$donnees = $req->fetch();
if ($donnees['SUM(id)'] > 0) 
{
$req->closeCursor(); // Termine le traitement de la requête puis redirige vers la page utilisateurs.php avec message d'erreur
header( "Location:../ifaces/utilisateurs.php?err=Cette addresse email est deja utilisée par un autre utilisateur");
}
else 
{
$req->closeCursor(); // Termine le traitement de la requête 
}


   //recuperation des autorisations simples (adh,bilans,g,h,l,j,k,mailing,prets) concatenés dans la variable $niveau 
     $niveau = $_POST['niveaua'].$_POST['niveaubi'].$_POST['niveaug'].$_POST['niveauh'].$_POST['niveaul'].$_POST['niveauj'].$_POST['niveauk'].$_POST['niveaum'].$_POST['niveaup'];  
	 //recuperation des eventuelles autorisations liées au points de collectes à concatener avec la variable $niveau (c1,c2,c3...)
     $niveaucollecte = "";
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$reponsec = $bdd->query('SELECT id FROM points_collecte');
            // On affiche chaque entree une à une
           while ($donneesc = $reponsec->fetch())
	   {
			if( isset($_POST['niveauc'.$donneesc['id']] ))
       	   {
           	$niveaucollecte = $niveaucollecte."c".$donneesc['id'];
       	   }
       }
$reponsec->closeCursor(); // Termine le traitement de la requête

     //recuperation des eventuelles autorisations liées au points de vente à concatener avec la variable $niveau (v1,v2,v3...)
 $niveauvente = "";
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$reponsev = $bdd->query('SELECT id FROM points_vente');
            // On affiche chaque entree une à une
           while ($donneesv = $reponsev->fetch())
	   {
			if( isset($_POST['niveauv'.$donneesv['id']] ))
       	   {
           	$niveauvente = $niveauvente."v".$donneesv['id'];
       	   }
       }
$reponsev->closeCursor(); // Termine le traitement de la requête
	 //recuperation des eventuelles autorisations liées au points de sortie hors boutique à concatener avec la variable $niveau (s1,s2,s3...)
$niveausortie = "";
try
{
include('dbconfig.php');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$reponses = $bdd->query('SELECT id FROM points_sortie');
            // On affiche chaque entree une à une
           while ($donneess = $reponses->fetch())
	   {
			if( isset($_POST['niveaus'.$donneess['id']] ))
       	   {
           	$niveausortie = $niveausortie."s".$donneess['id'];
       	   }
       }
$reponses->closeCursor(); // Termine le traitement de la requête

// Insertion du post à l'aide d'une requête préparée pass crypté


$req = $bdd->prepare('UPDATE utilisateurs SET nom = :nom, prenom = :prenom , mail = :mail, niveau = :niveau  WHERE id = :id');
$req->execute(array('nom' => $_POST['nom'],'prenom' => $_POST['prenom'],'mail' => $_POST['mail'],'niveau' => $niveaucollecte.$niveauvente.$niveausortie.$niveau,'id' => $_POST['id']));

  $req->closeCursor();





// Redirection du visiteur vers la page des inscriptions avec message positif 
header('Location: ../ifaces/edition_utilisateurs.php?msg=Utilisateur modifié avec succes!');	

}
else { 
header('Location:../moteur/destroy.php');
     }
?>
