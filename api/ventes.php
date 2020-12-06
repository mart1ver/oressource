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
function vendus_insert(PDO $bdd, int $id_vente, array $vente): array {
  $sql = 'INSERT INTO vendus (
      timestamp,
      last_hero_timestamp,
      id_vente,
      id_type_dechet,
      lot,
      id_objet,
      quantite,
      prix,
      remboursement,
      id_createur,
      id_last_hero
    ) VALUES (
      :timestamp,
      :timestamp1,
      :id_vente,
      :id_type_dechet,
      :lot,
      :id_objet,
      :quantite,
      :prix,
      :remboursement,
      :id_createur,
      :id_createur1)';
  $req = $bdd->prepare($sql);
  $req->bindValue(':timestamp', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':timestamp1', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_vente', $id_vente, PDO::PARAM_INT);
  $req->bindValue(':id_createur', $vente['id_user'], PDO::PARAM_INT);
  $req->bindValue(':id_createur1', $vente['id_user'], PDO::PARAM_INT);
  $id_vendus = [];
  foreach ($vente['items'] as $vendu) {
    $prix = parseFloat($vendu['prix']);
    $quantite = parseInt($vendu['quantite']);
    if ($prix >= 0.000 && $quantite > 0) {
      $req->bindValue(':prix', $prix, PDO::PARAM_STR);
      $req->bindValue(':remboursement', 0, PDO::PARAM_STR);
      $req->bindValue(':lot', $vendu['lot'], PDO::PARAM_INT);
      $req->bindValue(':id_type_dechet', $vendu['id_type'], PDO::PARAM_INT);
      $req->bindValue(':id_objet', $vendu['id_objet'], PDO::PARAM_INT);
      $req->bindValue(':quantite', $quantite, PDO::PARAM_INT);
      $req->execute();
      $id_vendus[] = $bdd->lastInsertId();
    } else {
      $req->closeCursor();
      throw new UnexpectedValueException('prix < 0.00 ou type item inconnu');
    }
  }
  $req->closeCursor();
  return $id_vendus;
}

function pesee_vendu_insert(PDO $bdd, array $id_vendus, array $vente): int {
  $sql = 'INSERT INTO pesees_vendus (
      timestamp,
      last_hero_timestamp,
      id,
      masse,
      quantite,
      id_createur,
      id_last_hero
    ) VALUES (
      :timestamp,
      :timestamp1,
      :id_vendu,
      :masse,
      :quantite,
      :id_createur,
      :id_createur1)';
  $req = $bdd->prepare($sql);
  $req->bindValue(':timestamp', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':timestamp1', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':id_createur', $vente['id_user'], PDO::PARAM_INT);
  $req->bindValue(':id_createur1', $vente['id_user'], PDO::PARAM_INT);
  foreach ($vente['items'] as $vendu) {
    $masse = parseFloat($vendu['masse']);
    $quantite = parseInt($vendu['quantite']);
    $id_vendu = current($id_vendus);
    next($id_vendus);
    if ($masse > 0.000 && $quantite > 0) {
      $req->bindValue(':masse', $masse);
      $req->bindValue(':id_vendu', $id_vendu, PDO::PARAM_INT);
      $req->bindValue(':quantite', $quantite, PDO::PARAM_INT);
      $req->execute();
    } elseif ($masse < 0.000 && $quantite === 0) {
      $req->closeCursor();
      throw new UnexpectedValueException('masse < 0.00 ou type item inconnu');
    }
  }
  $req->closeCursor();
  return $bdd->lastInsertId();
}

function vente_insert(PDO $bdd, array $vente): int {
  $sql = 'INSERT INTO ventes (
    timestamp,
    last_hero_timestamp,
    commentaire,
    id_point_vente,
    id_moyen_paiement,
    id_createur,
    id_last_hero
    ) VALUES (
     :timestamp,
     :timestamp1,
     :commentaire,
     :id_point,
     :id_moyen,
     :id_createur,
     :id_createur1)';
  $req = $bdd->prepare($sql);
  $req->bindValue(':timestamp', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindValue(':timestamp1', $vente['date']->format('Y-m-d H:i:s'), PDO::PARAM_STR);
  $req->bindParam(':commentaire', $vente['commentaire'], PDO::PARAM_STR);
  $req->bindValue(':id_point', $vente['id_point'], PDO::PARAM_INT);
  $req->bindValue(':id_moyen', $vente['id_moyen'], PDO::PARAM_INT);
  $req->bindValue(':id_createur', $vente['id_user'], PDO::PARAM_INT);
  $req->bindValue(':id_createur1', $vente['id_user'], PDO::PARAM_INT);
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
    echo(json_encode(['error' => $ex->getMessage()]));
    die();
  }

  $bdd->beginTransaction();
  try {
    $vente_id = vente_insert($bdd, $json);
    $vendu_ids = vendus_insert($bdd, $vente_id, $json);
    if (pesees_ventes()) {
      pesee_vendu_insert($bdd, $vendu_ids, $json);
    }
    $bdd->commit();
    http_response_code(200); // Created
    echo(json_encode(['id' => $vente_id], JSON_NUMERIC_CHECK));
  } catch (UnexpectedValueException $e) {
    $bdd->rollback();
    http_response_code(400); // Bad Request
    echo(json_encode(['error' => $e->getMessage()]));
  } catch (PDOException $e) {
    $bdd->rollback();
    http_response_code(500); // Internal Server Error
    echo(json_encode(['error' => 'Une erreur est survenue dans Oressource vente annulée.']));
    throw $e;
  }
} else {
  http_response_code(401); // Unauthorized.
  echo(json_encode(['error' => "Session Invalide ou expiree."], JSON_FORCE_OBJECT));
  die();
}
