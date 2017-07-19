<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) and $_SESSION['systeme'] = "oressource" and (strpos($_SESSION['niveau'], 'h') !== false)) {

  //martin vert
  // Connexion à la base de données
  try {
    include('dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
  }
  // Insertion du post à l'aide d'une requête préparée
  // mot de passe crypté md5
  // Insertion du post à l'aide d'une requête préparée
  $req = $bdd->prepare('UPDATE sorties SET id_filiere = :id_filiere,  id_last_hero = :id_last_hero, last_hero_timestamp = NOW(),  commentaire = :commentaire
    WHERE id = :id');
  $req->execute(array('id_filiere' => $_POST['id_filiere'],'id' => $_POST['id'],'id_last_hero' => $_SESSION['id'],'commentaire' => $_POST['commentaire']));
  $req->closeCursor();

  //on determine le type de dechet_evac par rapport $_POST['id_filiere']
  // Connexion à la base de données
  try {
    include('dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
  }

  $req = $bdd->prepare('SELECT filieres_sortie.id_type_dechet_evac id
    FROM filieres_sortie
    WHERE filieres_sortie.id = :id_filiere');
  $req->execute(array('id_filiere' => $_POST['id_filiere']));

  // On affiche chaque entree une à une
  while ($donnees = $req->fetch()) {
    $id_type_dechet_evac = $donnees['id'];
  }
  $req->closeCursor(); // Termine le traitement de la requête

  //
  try {
    include('dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
  }
  // Insertion du post à l'aide d'une requête préparée
  // mot de passe crypté md5
  // Insertion du post à l'aide d'une requête préparée
  $req = $bdd->prepare('UPDATE pesees_sorties SET id_type_dechet_evac = :id_type_dechet_evac,  id_last_hero = :id_last_hero, last_hero_timestamp = NOW()
    WHERE id_sortie = :id_sortie');
  $req->execute(array('id_type_dechet_evac' => $id_type_dechet_evac,'id_sortie' => $_POST['id'],'id_last_hero' => $_SESSION['id']));
  $req->closeCursor();

  // Redirection du visiteur vers la page de gestion des points de collecte
  header('Location:../ifaces/verif_sorties.php?numero='.$_POST['npoint'].'&date1='.$_POST['date1'].'&date2='.$_POST['date2']);
} else {
  header('Location:../moteur/destroy.php');
}
?>
