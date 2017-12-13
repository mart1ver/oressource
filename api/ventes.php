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

session_start();

require_once '../core/session.php';
require_once '../core/requetes.php';
require_once '../core/validation.php';

header("content-type:application/json");

// TODO Faire le neccessaire pour les ventes en lot.
function vendus_insert(PDO $bdd, int $id_vente, array $vente): int {
  $sql = 'INSERT INTO vendus (
      timestamp, id_vente, id_type_dechet,
      id_objet, quantite, prix, id_createur
    ) VALUES (
      :timestamp, :id_vente, :id_type_dechet,
      :id_objet, :quantite, :prix, :id_createur)';
  $req = $bdd->prepare($sql);
  $req->bindValue(':timestamp', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_vente', $id_vente, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $vente['id_user'], PDO::PARAM_INT);
  foreach ($vente['items'] as $vendu) {
    $prix = parseFloat($vendu['prix']);
    $quantite = parseInt($vendu['quantite']);
    if ($prix >= 0.000 && $quantite > 0) {
      $req->bindValue(':prix', $prix);
      $req->bindValue(':id_type_dechet', $vendu['id_type'], PDO::PARAM_INT);
      $req->bindValue(':id_objet', $vendu['id_objet'] ?? 0);
      $req->bindValue(':quantite', $quantite, PDO::PARAM_INT);
      $req->execute();
    } else {
      $req->closeCursor();
      throw new UnexpectedValueException('prix < 0.00 ou type item inconnu');
    }
  }
  $req->closeCursor();
  return $bdd->lastInsertId();
}

function pesee_vendu_insert(PDO $bdd, int $id_vendus, array $vente): int {
  $sql = 'INSERT INTO pesees_vendus (
      timestamp, id_vendu, masse,
      quantite, id_createur
    ) VALUES (
      :timestamp, :id_vendu,
      :masse, :quantite, :id_createur)';
  $req = $bdd->prepare($sql);
  $req->bindValue(':timestamp', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_vendu', $id_vendus, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $vente['id_user'], PDO::PARAM_INT);
  foreach ($vente['items'] as $vendu) {
    $masse = parseFloat($vendu['masse']);
    $quantite = parseInt($vendu['quantite']);
    if ($masse > 0.00 && $quantite > 0) {
      $req->bindValue(':masse', $masse);
      $req->bindValue(':quantite', $quantite, PDO::PARAM_INT);
      $req->execute();
    } elseif ($masse < 0.000) {
      $req->closeCursor();
      throw new UnexpectedValueException('masse < 0.00 ou type item inconnu');
    }
  }
  $req->closeCursor();
  return $bdd->lastInsertId();
}

function vente_insert(PDO $bdd, array $vente): int {
  $sql = 'INSERT INTO ventes (
    timestamp, commentaire,
    id_point_vente, id_moyen_paiement, id_createur
    ) VALUES (
     :timestamp, :commentaire,
     :id_point, :id_moyen, :id_createur)';
  $req = $bdd->prepare($sql);
  $req->bindValue(':timestamp', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindParam(':commentaire', $vente['commentaire'], PDO::PARAM_STR);
  $req->bindValue(':id_point', $vente['id_point'], PDO::PARAM_INT);
  $req->bindValue(':id_moyen', $vente['id_moyen'], PDO::PARAM_INT);
  $req->bindValue(':id_createur', $vente['id_user'], PDO::PARAM_INT);
  $req->execute();
  $id = $bdd->lastInsertId();
  $req->closeCursor();
  return $id;
}

if (is_valid_session()) {
  require_once '../moteur/dbconfig.php';

  $json_raw = file_get_contents('php://input');
  $unsafe_json = json_decode($json_raw, true);
  $json = $unsafe_json;

  if (!is_allowed_vente_id($json['id_point'])) {
    http_response_code(403); // Forbiden.
    echo(json_encode(['error' => 'Action interdite.'], JSON_FORCE_OBJECT));
    die();
  }

  try {
    $json['date'] = allowDate($json) ? parseDate($json['date']) : new DateTime('now');
  } catch (UnexpectedValueException $ex) {
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->getMessage()]));
    die();
  }

  $bdd->beginTransaction();
  try {
    $vente_id = vente_insert($bdd, $json);
    $vendu_id = vendus_insert($bdd, $vente_id, $json);
    if (pesees_ventes()) {
      pesee_vendu_insert($bdd, $vendu_id, $json);
    }
    $bdd->commit();
    http_response_code(200); // Created
    echo(json_encode(['id_vente' => $vente_id], JSON_NUMERIC_CHECK));
  } catch (UnexpectedValueException $e) {
    $bdd->rollback();
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->getMessage()]));
  }
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => "Session Invalide ou expiree."], JSON_FORCE_OBJECT));
  die();
}
