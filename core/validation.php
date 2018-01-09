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

function bool_to_oui_non(bool $b): string {
  if ($b === true) {
    return 'oui';
  }
  return 'non';
}

function structure_validate(array $json): array {
  $structure = [
    'siret' => filter_var($json['siret'], FILTER_SANITIZE_STRING),
    'nom' => filter_var($json['nom'], FILTER_SANITIZE_STRING),
    'id_localite' => filter_var($json['id_localite'], FILTER_VALIDATE_INT),
    'adresse' => filter_var($json['adresse'], FILTER_SANITIZE_STRING),
    'description' => filter_var($json['description'], FILTER_SANITIZE_STRING),
    'telephone' => filter_var($json['telephone'], FILTER_SANITIZE_STRING), // TODO: regex sur les nombres.
    'mail' => filter_var($json['mail'], FILTER_VALIDATE_EMAIL),
    'lot' => filter_var($json['lot'], FILTER_VALIDATE_BOOLEAN),
    'viz' => filter_var($json['viz'], FILTER_VALIDATE_BOOLEAN),
    'saisiec' => filter_var($json['saisiec'], FILTER_VALIDATE_BOOLEAN),
    'affsp' => filter_var($json['affsp'], FILTER_VALIDATE_BOOLEAN),
    'affss' => filter_var($json['affss'], FILTER_VALIDATE_BOOLEAN),
    'affsr' => filter_var($json['affsr'], FILTER_VALIDATE_BOOLEAN),
    'affsde' => filter_var($json['affsde'], FILTER_VALIDATE_BOOLEAN),
    'affsd' => filter_var($json['affsd'], FILTER_VALIDATE_BOOLEAN),
    'pes_vente' => filter_var($json['pes_vente'], FILTER_VALIDATE_BOOLEAN),
    'force_pes_vente' => filter_var($json['force_pes_vente'], FILTER_VALIDATE_BOOLEAN),
    'tva_active' => filter_var($json['tva_active'], FILTER_VALIDATE_BOOLEAN),
    'taux_tva' => filter_var($json['taux_tva'], FILTER_VALIDATE_FLOAT),
    'nb_viz' => filter_var($json['nb_viz'], FILTER_VALIDATE_INT),
    'cr' => filter_var($json['cr'], FILTER_SANITIZE_STRING), // TODO: regex sur les nombres.
  ];
  return $structure;
}

function validate_json_login(array $unsafe_json) {
  $unsafe_json['username'] = filter_var($unsafe_json['username'], FILTER_SANITIZE_STRING);
  return $unsafe_json;
}

function validate_json_sorties(array $unsafe_json) {
  $filters = [
    'id_type_action' => FILTER_DEFAULT, // Peux etre NULL
    'date' => FILTER_DEFAULT, // Peux etre NULL validation faite plus tard.
    'localite' => FILTER_DEFAULT, // Peux etre NULL
    'id_point' => FILTER_VALIDATE_INT,
    'id_user' => FILTER_VALIDATE_INT,
    'items' => FILTER_DEFAULT,
    'evacs' => FILTER_DEFAULT,
    'commentaire' => FILTER_SANITIZE_STRING,
    // TODO: remplacer par une regex sortie|sortier...
    // Ou remplacer par des id.
    'classe' => FILTER_SANITIZE_STRING
  ];
  $flag = ['flags' => FILTER_NULL_ON_FAILURE];
  $flags = [];
  foreach ($filters as $key => $_v) {
    $flags[$key] = $flag;
  }
  $flags['id_type_action'] = FILTER_REQUIRE_SCALAR;
  $flags['localite'] = FILTER_REQUIRE_SCALAR;
  $flags['items'] = FILTER_REQUIRE_ARRAY | FILTER_NULL_ON_FAILURE;
  $flags['evacs'] = FILTER_REQUIRE_ARRAY | FILTER_NULL_ON_FAILURE;
  $flags['commentaire'] = ['flags' => FILTER_FLAG_STRIP_BACKTICK];
  $json = [];
  foreach ($unsafe_json as $k => $v) {
    $filtered = filter_var($v, $filters[$k], $flags[$k]);
    if ($filtered === null) {
      throw new UnexpectedValueException('Erreur: Donnee JSON invalide: ' . $k);
    }
    $json[$k] = $filtered;
  }
  return $json;
}

function validate_json_collecte(array $unsafe_json): array {
  $filters = [
    'id_type_action' => FILTER_VALIDATE_INT,
    'date' => FILTER_DEFAULT,
    'localite' => FILTER_VALIDATE_INT,
    'id_point' => FILTER_VALIDATE_INT,
    'id_user' => FILTER_VALIDATE_INT,
    'items' => FILTER_DEFAULT,
    'commentaire' => FILTER_SANITIZE_STRING,
    'classe' => FILTER_SANITIZE_STRING
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
    if ($filtered === null) {
      throw new UnexpectedValueException('Erreur: Donnee JSON invalide: ' . $k);
    } else {
      $json[$k] = $filtered;
    }
  }
  return $json;
}

function parseDate_Post(string $key): DateTime {
  $result = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
  if ($result) {
    return DateTime::createFromFormat('Y-m-d', $result);
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide date attendu.');
  }
}

function parseDate(string $str): DateTime {
  if ($str) {
    return DateTime::createFromFormat('Y-m-d', $str);
  } else {
    throw new UnexpectedValueException('Erreur: Date invalide.');
  }
}

function parseFloat(string $key): float {
  $result = filter_var($key, FILTER_VALIDATE_FLOAT);
  if ($result === false) {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide float attendu.');
  } else {
    return $result;
  }
}

function parseInt(string $key): int {
  $result = filter_var($key, FILTER_VALIDATE_INT);
  if ($result === false) {
    throw new UnexpectedValueException('Erreur: Donnee invalide int attendu.');
  } else {
    return $result;
  }
}
