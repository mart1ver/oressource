<?php session_start();

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) and $_SESSION['systeme'] = "oressource" and (strpos($_SESSION['niveau'], 'j') !== false)) {

  //on determine le nombre total de type dechets evac
  $id_dechets = "";
  try {
    include('dbconfig.php');
  } catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
  }
  $reponsesa = $bdd->query('SELECT id FROM type_dechets_evac');
  // On affiche chaque entree une à une
  while ($donneessa = $reponsesa->fetch()) {
    if (isset($_POST['tde'.$donneessa['id']])) {
      $id_dechets = $id_dechets."a".$donneessa['id'];
    }
  }
  $reponsesa->closeCursor(); // Termine le traitement de la requête

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
  $req = $bdd->prepare('UPDATE filieres_sortie SET nom = :nom, id_type_dechet_evac = :id_type_dechet_evac, description = :description, couleur = :couleur  WHERE id = :id');
  $req->execute(array('nom' => $_POST['nom'],'id_type_dechet_evac' => $id_dechets,
    'description' => $_POST['description'],'couleur' => $_POST['couleur'],'id' => $_POST['id']));

  $req->closeCursor();

  // Redirection du visiteur vers la page de gestion des points de collecte
  header('Location:../ifaces/edition_filieres_sortie.php');
} else {
  header('Location:../moteur/destroy.php');
}
?>
