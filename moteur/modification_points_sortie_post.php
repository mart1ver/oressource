<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) and $_SESSION['systeme'] = "oressource" and (strpos($_SESSION['niveau'], 'k') !== false)) {

  //martin vert
  try {
    // On se connecte à MySQL
    include('../moteur/dbconfig.php');
  } catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : '.$e->getMessage());
  }

  // Si tout va bien, on peut continuer
  $req = $bdd->prepare("SELECT SUM(id) FROM points_sortie WHERE nom = :nom AND id <> :id ");//SELECT `titre_affectation` FROM affectations WHERE titre_affectation = "conssomables" LIMIT 1
  $req->execute(array('nom' => $_POST['nom'],'id' => $_POST['id'] ));
  $donnees = $req->fetch();
  $req->closeCursor(); // Termine le traitement de la requête

  if ($donnees['SUM(id)'] > 0) { // SI le titre existe
    header("Location:../ifaces/modification_points_sortie.php?err=Un point de sortie porte deja le meme nom!&nom=".$_POST['nom']."&adresse=".$_POST['adresse']."&pesee_max=".$_POST['pesee_max']."&commentaire=".$_POST['commentaire']."&couleur=".substr($_POST['couleur'], 1));
    $req->closeCursor(); // Termine le traitement de la requête
  } else {
    // Connexion à la base de données
    try {
      include('dbconfig.php');
    } catch (Exception $e) {
      die('Erreur : '.$e->getMessage());
    }

    // Insertion du post à l'aide d'une requête préparée
    // mot de passe crypté md5

    // Insertion du post à l'aide d'une requête préparée
    $req = $bdd->prepare('UPDATE points_sortie SET nom = :nom, adresse = :adresse , commentaire = :commentaire, pesee_max = :pesee_max, couleur = :couleur  WHERE id = :id');
    $req->execute(array('nom' => $_POST['nom'],'adresse' => $_POST['adresse'],'commentaire' => $_POST['commentaire'],'pesee_max' => $_POST['pesee_max'],'couleur' => $_POST['couleur'],'id' => $_POST['id']));

    $req->closeCursor();

    // Redirection du visiteur vers la page de gestion des points de collecte
    header('Location:../ifaces/edition_points_sorties.php');
  }
} else {
  header('Location:../moteur/destroy.php');
}
?>
