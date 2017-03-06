<?php

session_start();
//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && ( strpos($_SESSION['niveau'], 'g') !== false)) {
//martin vert
// Connexion à la base de données
  include('dbconfig.php');
  // Insertion du post à l'aide d'une requête préparée
  $req = $bdd->prepare('UPDATE grille_objets SET visible = :visible WHERE id = :id');
  $req->execute(array('visible' => $_POST['visible'], 'id' => $_POST['id']));
// Redirection du visiteur vers la page de gestion des affectation
  header('Location:../ifaces/grilles_prix.php' . "?typo=" . $_POST['typo']);
} else {
  header('Location:../moteur/destroy.php');
}
?>

