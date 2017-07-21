<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) and $_SESSION['systeme'] = "oressource" and (strpos($_SESSION['niveau'], 'l') !== false)) {

  //martin vert
  // Connexion à la base de données
  try {
    include('dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
  }

  // Insertion du post à l'aide d'une requête préparée

  // suppression de l'utilisateur à l'aide d'une requête préparée "DELETE FROM utilisateurs WHERE id = :id"
  $req = $bdd->prepare('DELETE FROM utilisateurs WHERE id = :id');
  $req->execute(array('id' => $_POST['id']));

  $req->closeCursor();

  // Redirection du visiteur vers la page de gestion des affectation
  header('Location:../ifaces/edition_utilisateurs.php?msg=Utilisateur definitivement supprimé.');
} else {
  header('Location:../moteur/destroy.php');
}
?>
