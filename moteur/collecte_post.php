<?php

/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

global $bdd;

function parseDate_Post($key) {
  $result = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
  if ($result) {
    return DateTime::createFromFormat('Y-m-d', $result);
  } else if ($result === False) {
    return new DateTime('now');
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide date attendu.');
  }
}

function parseDate($str) {
  if ($str) {
    return DateTime::createFromFormat('Y-m-d', $str);
  } else if ($str === '' || $str === NULL) {
    return new DateTime('now');
  } else {
    throw new UnexpectedValueException('Erreur: Date invalide.');
  }
}

function parseFloat($key) {
  $result = filter_var($key, FILTER_VALIDATE_FLOAT);
  if ($result) {
    return (float) $result;
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide float attendu.');
  }
}

function parseInt($key) {
  $result = filter_var($key, FILTER_VALIDATE_INT);
  if ($result) {
    return (int) $result;
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide int attendu.');
  }
}

function parseInt_Post($key) {
  $result = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
  if ($result) {
    return (int) $result;
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide int attendu.');
  }
}

function parseFloat_Post($key) {
  $result = filter_input(INPUT_POST, $key, FILTER_VALIDATE_FLOAT);
  if ($result) {
    return (float) $result;
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide float attendu.');
  }
}

function parseString_Post($key) {
  $result = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
  if ($result !== NULL) {
    return $result;
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide chaine de caractere attendue.');
  }
}

// On definit $adh en fonction $_POST['adh']
function parseAdherant($key) {
  $adh = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
  if ($adh === 'oui') {
    return 'oui';
  } else {
    return 'non';
  }
}

function validate_json_collecte($unsafe_json) {
  $filters = [
      'id_type_collecte' => FILTER_VALIDATE_INT,
      'antidate' => FILTER_DEFAULT,
      'localite' => FILTER_VALIDATE_INT,
      'id_point_collecte' => FILTER_VALIDATE_INT,
      'id_user' => FILTER_VALIDATE_INT,
      'saisie_collecte' => FILTER_VALIDATE_BOOLEAN,
      'user_droit' => FILTER_SANITIZE_STRING,
      'nb_item' => FILTER_VALIDATE_INT,
      'items' => FILTER_DEFAULT,
      'commentaire' => FILTER_SANITIZE_STRING
  ];
  $flag = ['flags' => FILTER_NULL_ON_FAILURE];
  $flags = [];
  foreach ($filters as $key => $_v) {
    $flags[$key] = $flag;
  }
  $flags['items'] = FILTER_REQUIRE_ARRAY | FILTER_NULL_ON_FAILURE;
  $flags['commentaire'] = ['flags' => FILTER_FLAG_STRIP_BACKTICK];
  $json = [];
  foreach ($unsafe_json as $k => $v) {
    $filtered = filter_var($v, $filters[$k], $flags[$k]);
    if ($filtered === NULL) {
      throw new UnexpectedValueException('Erreur: Donnee JSON invalide: ' . $k);
    } else {
      $json[$k] = $filtered;
    }
  }
  return $json;
}

session_start();
header("content-type:application/json");
http_response_code(202); // Accepted
$json_raw = file_get_contents('php://input');
$unsafe_json = json_decode($json_raw, true);

// TODO: Revenir sur la validation plus tard c'est pas parfait maintenant.
// Parsing et filtrage des entrees pour eviter les failles et injections.
$json = validate_json_collecte($unsafe_json);

// TODO: Changer la verif de la session pour passer la collecte quand meme...
// A terme trouver une facon plus elegante que la session pour gerer ca.
if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && !strpos($_SESSION['niveau'], 'c' . filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT))) {
  if ($json['saisie_collecte']
    && strpos($json['user_droit'], 'e')
    && $json['nb_item'] > 0) {

    $timestamp = parseDate($json['antidate']);
    $id_type_collecte = $json['id_type_collecte'];
    $localite = $json['localite'];
    $id_point_collecte = $json['id_point_collecte'];
    $commentaire = $json['commentaire'];
    $id_user = $json['id_user'];

    // Connexion à la base de données
    include_once('dbconfig.php');

    // Insertion de la collecte (sans les pesées) l'aide d'une requête préparée.
    $req = $bdd->prepare('INSERT INTO collectes
                            (timestamp, id_type_collecte,  adherent,
                            localisation, id_point_collecte,
                            commentaire, id_createur)
                          VALUES (:timestamp, :id_type, :adherent,
                                  :localite, :id_point,
                                  :commentaire, :id_createur)');
    $req->bindValue(':timestamp', $timestamp->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $req->bindValue(':id_type', $id_type_collecte, PDO::PARAM_INT);
    // HACK: virer les adherants de la base ou juste changer le type en booleen serieusement...
    $req->bindValue(':adherent', 'non', PDO::PARAM_STR);
    $req->bindValue(':localite', $localite, PDO::PARAM_INT);
    $req->bindValue(':id_point', $id_point_collecte, PDO::PARAM_INT);
    $req->bindValue(':commentaire', $bdd->quote($commentaire), PDO::PARAM_STR);
    $req->bindValue(':id_createur', $id_user, PDO::PARAM_INT);
    $req->execute();
    $id_collecte = $bdd->lastInsertId();

    // On ecrit le nombre de categories maxi dans la varialble $nombreCategories
    // HACK C'est pas parfait il faudrait comparer aux differents ID de la base.
    // Si des utilisateurs suppriment des objetcs a la main c'est la galere...
    // Mais d'un cote niveau tracabilite ils devraient pas supprimer de categories...
    $reponse = $bdd->query('SELECT MAX(id) AS nombrecat FROM type_dechets LIMIT 1;');
    $nombreCategories = (int) $reponse->fetch()['nombrecat'];

    $req = $bdd->prepare('INSERT INTO pesees_collectes
                            (timestamp, masse, id_collecte,
                            id_type_dechet, id_createur)
                        VALUES (:timestamp, :masse,
                                :id_collecte, :id_type_dechet,
                                :id_createur)');
    $req->bindValue(':timestamp', $timestamp->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $req->bindValue(':id_collecte', $id_collecte, PDO::PARAM_INT);
    $req->bindValue(':id_createur', $id_user, PDO::PARAM_INT);

    foreach ($json['items'] as $item) {
      $masse = (double) parseFloat($item['masse']);
      $type_dechet = (int) parseInt($item['type']);
      if ($masse > 0.00 && $type_dechet <= $nombreCategories) {
        $req->bindValue(':masse', $masse);
        $req->bindValue(':id_type_dechet', $type_dechet, PDO::PARAM_INT);
        $req->execute();
      } else {
        http_response_code(400); // Bad Request
        echo(json_encode(['error' => 'masse <= 0.0 ou type item inconnu.'], JSON_FORCE_OBJECT));
        // TODO: Faire une transaction la rollback et crash car donnee invalide.
      }
    }
    echo(json_encode(['id_collecte' => $id_collecte], JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
  }
} else {

  http_response_code(403); // Forbiden.
  header('Location:../moteur/destroy.php?motif=1');
}
