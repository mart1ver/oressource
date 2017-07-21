<?php

require_once('../core/session.php');

session_start();

// Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette requête:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && is_allowed_config()) {
  include_once('../moteur/dbconfig.php');

  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
  $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
  $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);
  $surface = filter_input(INPUT_POST, 'surface', FILTER_VALIDATE_INT);
  $commentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING);
  // La regex capture SEULEMENT les couleurs en HEXA.
  $couleur = filter_input(INPUT_POST, 'couleur', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' =>'/(^#[0-9A-Fa-f]{6})/']]);

  // TODO: rejeter si une des variables nulle.

  $req = $bdd->prepare("SELECT
    id
    FROM points_vente
    WHERE
    id = :id
    LIMIT 1");
  $req->bindValue(':id', (int) $id, PDO::PARAM_INT);
  $donnees = $req->fetch(PDO::FETCH_ASSOC);

  // Si il existe pas
  if (isset($donnees['id'])) {
    $base = 'Location:../ifaces/edition_points_vente.php?';
    $error = 'err=Aucun point de vente porte deja le meme nom mais vous pouvez le creer!';
    header($base . $error . '&nom=' . $nom . '&adresse=' . $adresse . '&surface='
      . $surface . '&commentaire=' . $commentaire . '&couleur=' . substr($couleur, 1));
  } else {
    $req = $bdd->prepare('UPDATE points_vente
      SET nom = :nom,
      adresse = :adresse ,
      commentaire = :commentaire,
      surface_vente = :surface_vente,
      couleur = :couleur
      WHERE id = :id');
    $req->bindvalue(':adresse', $adresse, PDO::PARAM_STR);
    $req->bindvalue(':commentaire', $commentaire, PDO::PARAM_STR);
    $req->bindvalue(':surface_vente', $surface, PDO::PARAM_INT);
    $req->bindvalue(':couleur', $couleur, PDO::PARAM_STR);
    $req->bindvalue(':nom', $nom, PDO::PARAM_STR);
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
    // Redirection du visiteur vers la page de gestion des points de collecte
    header('Location:../ifaces/edition_points_vente.php?msg=' . $nom . ' bien mis a jour');
  }
} else {
  header('Location:../moteur/destroy.php');
}
