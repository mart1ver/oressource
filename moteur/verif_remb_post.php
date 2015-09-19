



                






    <?php session_start();
   require_once('../moteur/dbconfig.php');

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'v'.$_GET['numero']) !== false))
{


  // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT cr FROM `description_structure`');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
          $code = $donnees['cr'];
}
                $reponse->closeCursor(); // Termine le traitement de la requête


if ($_POST['passrmb'] == $code){

header("Location:../ifaces/remboursement.php?numero=".$_GET['numero']."&nom=".$_GET['nom']."&adresse=".$_GET['adresse']);

}
else{


}

header("Location:../ifaces/ventes.php?numero=".$_GET['numero']."&nom=".$_GET['nom']."&adresse=".$_GET['adresse']);

}
else 
{ 
header('Location:../moteur/destroy.php');
}
?>

