



                






    <?php session_start();
  

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
 if (isset($_SESSION['id']) AND $_SESSION['systeme'] = "oressource" AND (strpos($_SESSION['niveau'], 'v'.$_GET['numero']) !== false))
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
  // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT cr FROM `description_structure`');
 
           // On affiche chaque entree une à une
           while ($donnees = $reponse->fetch())
           {
          $code = $donnees['cr'];
}
                $reponse->closeCursor(); // Termine le traitement de la requête


if $_POST['passrmb'] == $code{

//header("Location:../ifaces/remboursement.php?numero=".$_GET['numero']);

}
else{


}
echo $code;
echo "<br>";
echo $_POST['passrmb'];
//header("Location:../ifaces/ventes.php?numero=".$_GET['numero']."&err=mauvais mot de passe");

}
else 
{ 
header('Location:../moteur/destroy.php');
}
?>

