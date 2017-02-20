<?php

session_start();

require_once('dbconfig.php');

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_sortie($numero)
  && $_SESSION['saisiec_user'] === "oui"()) {

  $timestamp = (is_allowed_edit_date() ? parseDate($json['antidate']) : new DateTime('now'));
  /*
  $sortie = [
      'timestamp' => $timestamp,
      'type_sortie' => $json['id_type_action'],
      'localite' => $json['localite'],
      'classe' => 'sorties',
      'id_point_sortie' => $json['id_point'],
      'commentaire' => $json['commentaire'],
      'id_user' => $json['id_user'],
  ];
   */
    $sortie = [
      'timestamp' => $timestamp,
      'type_sortie' => $_POST['id_filiere'],
      'localite' => $json['localite'],
      'classe' => 'sortiesr',
      'id_point_sortie' => $_POST['id_point_sortie'],
      'commentaire' => $_POST['commentaire'],
      'id_user' => $_POST['id_user'],
  ];

  $id_sortie = insert_sortie($bdd, $sortie);
  // TODO: Faire une transaction SQL
  try {
    if (count($json['items'])) {
      insert_items_sorties($bdd, $id_sortie, $sortie, $json['items']);
    }
    if (count($json['evacs'])) {
      insert_evac_sorties($bdd, $id_sortie, $sortie, $json['evacs']);
    }
    http_response_code(200); // Created
    // TODO: Valider la transaction.
    // Note: Renvoyer l'url d'acces a la ressource
    echo(json_encode(['id_sortie' => $id_sortie], JSON_NUMERIC_CHECK));
  } catch (InvalidArgumentException $e) {
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => 'masse <= 0.0 ou type item inconnu.'], JSON_FORCE_OBJECT));
    // TODO: Invalider la transaction
  }
  
  $i = 1;

      $req = $bdd->prepare('INSERT INTO pesees_sorties (timestamp,masse,  id_sortie, id_type_dechet_evac, id_createur) VALUES(?,?, ?, ?, ?)');
      $req->execute(array($antidate, $_POST["d" . $i], $id_sortie, $i, $_POST['id_user']));
      $req->closeCursor();
    }
    $i++;
  }

  header("Location:../ifaces/sortiesr.php?numero=" . $_POST['id_point_sortie']);
  die();
}

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'utilisation de cette fonction:
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource" && is_allowed_sortie($numero)) {

} else {
  header('Location:../moteur/destroy.php?motif=1');
  die();
}
