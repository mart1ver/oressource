<?php

declare(strict_types = 1);
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

require_once 'session.php';
function serve_config(string $endpoint, string $msg, callable $function) {
  session_start();
  if (is_valid_session() && is_allowed_config()) {
    global $bdd;
    require_once '../moteur/dbconfig.php';
    $base = "../ifaces/$endpoint.php";
    try {
      $function($bdd);
      return "$base?msg=Le $msg à été crée avec succes!";
    } catch (PDOException $e) {
      if ($e->getCode() == '23000') {
        return "$base?err=Un $msg porte deja le meme nom!";
      }
      error_log('serve_config: ' . $e->getMessage());
      return "$base?err=Oups une erreur viens de survenir.";
    }
  } else {
    return '../moteur/destroy.php';
  }
}

function generic_insert_config(PDO $bdd, string $table, array $data) {
  $sql = "INSERT INTO $table (
    nom,
    description,
    couleur,
    id_createur,
    id_last_hero
  ) VALUES (
    :nom, :description,
    :couleur,
    :id_createur,
    :id_createur1)";
  $req = $bdd->prepare($sql);
  $req->bindvalue(':nom', $data['nom'], PDO::PARAM_STR);
  $req->bindvalue(':description', $data['description'], PDO::PARAM_STR);
  $req->bindvalue(':couleur', $data['couleur'], PDO::PARAM_STR);
  $req->bindvalue(':id_createur', $data['createur'], PDO::PARAM_INT);
  $req->bindvalue(':id_createur1', $data['createur'], PDO::PARAM_INT);
  $req->execute();
  $req->closeCursor();
}

function generic_insert_5Config(PDO $bdd, string $table, string $str0, string $str1, string $int0, array $data) {
  $fields = "$str0, $str1, $int0";
  $values = ":$str0, :$str1, :$int0";
  $sql = "INSERT INTO $table (
    $fields,
    commentaire,
    couleur,
    id_createur,
    id_last_hero
  ) VALUES (
    $values,
    :description,
    :couleur,
    :id_createur,
    :id_createur1)";
  $req = $bdd->prepare($sql);
  $req->bindvalue(":$str0", $data[$str0], PDO::PARAM_STR);
  $req->bindvalue(":$str1", $data[$str1], PDO::PARAM_STR);
  $req->bindvalue(":$int0", $data[$int0], PDO::PARAM_INT);

  $req->bindvalue(':description', $data['description'], PDO::PARAM_STR);
  $req->bindvalue(':couleur', $data['couleur'], PDO::PARAM_STR);
  $req->bindvalue(':id_createur', $data['createur'], PDO::PARAM_INT);
  $req->bindvalue(':id_createur1', $data['createur'], PDO::PARAM_INT);
  $req->execute();
  $req->closeCursor();
}

function generic_ctor_post(): array {
  global $_SESSION;
  return [
    'nom' => filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING | FILTER_FLAG_STRIP_BACKTICK),
    'description' => filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING | FILTER_FLAG_STRIP_BACKTICK),
    'couleur' => filter_input(INPUT_POST, 'couleur', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/(^#[0-9A-Fa-f]{6})/']]),
    // La regex capture SEULEMENT les couleurs en HEXA.
    'createur' => $_SESSION['id'],
  ];
}

function conventions_sortiePost(PDO $bdd) {
  if (is_allowed_partners()) {
    generic_insert_config($bdd, 'conventions_sorties', generic_ctor_post());
  } else {
    header('Location:../moteur/destroy.php');
    die;
  }
}

function types_dechetsEvacPost(PDO $bdd) {
  generic_insert_config($bdd, 'type_dechets_evac', generic_ctor_post());
}

function types_collectePost(PDO $bdd) {
  generic_insert_config($bdd, 'type_collecte', generic_ctor_post());
}

function types_sortiePost(PDO $bdd) {
  generic_insert_config($bdd, 'type_sortie', generic_ctor_post());
}

function moyenPaiementPost(PDO $bdd) {
  generic_insert_config($bdd, 'moyens_paiement', generic_ctor_post());
}

function types_dechetPost(PDO $bdd) {
  generic_insert_config($bdd, 'type_dechets', generic_ctor_post());
}

function point_Post() {
  return array_merge(generic_ctor_post(), [
    'adresse' => filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING)
  ]);
}

function point_ventePost(PDO $bdd) {
  $data = array_merge(generic_ctor_post(), point_Post(), [
    'surface_vente' => (float) filter_input(INPUT_POST, 'surface', FILTER_VALIDATE_FLOAT),
  ]);
  generic_insert_5Config($bdd, 'points_vente', 'nom', 'adresse', 'surface_vente', $data);
}

function point_sortiePost(PDO $bdd) {
  $data = array_merge(generic_ctor_post(), point_Post(), [
    'pesee_max' => (float) filter_input(INPUT_POST, 'pesee_max', FILTER_VALIDATE_FLOAT),
  ]);
  generic_insert_5Config($bdd, 'points_sortie', 'nom', 'adresse', 'pesee_max', $data);
}

function point_collectePost(PDO $bdd) {
  $data = array_merge(generic_ctor_post(), point_Post(), [
    'pesee_max' => (float) filter_input(INPUT_POST, 'pesee_max', FILTER_VALIDATE_FLOAT),
  ]);
  generic_insert_5Config($bdd, 'points_collecte', 'nom', 'adresse', 'pesee_max', $data);
}
