<?php

session_start();

require_once('dbconfig.php');

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_sortie($numero)
  && $_SESSION['saisiec_user'] === "oui"()) {

  $timestamp = (is_allowed_edit_date() ? parseDate($json['antidate']) : new DateTime('now'));

  $sortie = [
      'timestamp' => $timestamp,
      'type_sortie' => $json['id_type_action'],
      'localite' => $json['localite'],
      'classe' => 'sortiesr',
      'id_point_sortie' => $json['id_point'],
      'commentaire' => $json['commentaire'],
      'id_user' => $json['id_user'],
  ];

  $bdd->beginTransaction();
  $id_sortie = insert_sortie($bdd, $sortie);
  try {
    if (count($json['evacs'])) {
      // Verifier que les objets correspondent bien possibilites du recycleur.
      insert_evac_sorties($bdd, $id_sortie, $sortie, $json['evacs']);
      $bdd->commit();
    } else {
      throw new InvalidArgumentException("Sortie sans pesees abbandon!");
    }
    http_response_code(200); // Created
    // TODO: Valider la transaction.
    // Note: Renvoyer l'url d'acces a la ressource
    echo(json_encode(['id_sortie' => $id_sortie]));
  } catch (InvalidArgumentException $e) {
    $bdd->rollback();
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->msg]));
  }
}
/*
//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette fonction:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && is_allowed_sortie($numero)) {

} else {
  header('Location:../moteur/destroy.php?motif=1');
  die();
}
*/
