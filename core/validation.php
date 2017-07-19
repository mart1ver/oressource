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

// TODO a virer une fois la base nettoyee des oui et non.
function oui_non_to_bool($s) {
  if ($s === 'oui') {
    return true;
  } else if ($s === 'non') {
    return false;
  } else {
    throw new InvalidArgumentException('$s different de oui ou non.');
  }
}

// TODO a virer une fois la base nettoyee des oui et non.
function bool_to_oui_non($b) {
  if ($b === true) {
    return 'oui';
  } else {
    return 'non';
  }
}

function structure_validate(Array $json) {
  $structure = [
    'nom' => filter_input($json, 'nom', FILTER_SANITIZE_STRING),
    'description' => filter_input($json, 'description', FILTER_SANITIZE_STRING),
    'mail' => filter_input($json, 'mail', FILTER_VALIDATE_EMAIL),
    'lot' => filter_input($json, 'lot', FILTER_VALIDATE_BOOLEAN),
    'viz' => filter_input($json, 'viz', FILTER_VALIDATE_BOOLEAN),
    'saisiec' => filter_input($json, 'saisiec', FILTER_VALIDATE_BOOLEAN),
    'affsp' => filter_input($json, 'affsp', FILTER_VALIDATE_BOOLEAN),
    'affss' => filter_input($json, 'affss', FILTER_VALIDATE_BOOLEAN),
    'affsr' => filter_input($json, 'affsr', FILTER_VALIDATE_BOOLEAN),
    'affsde' => filter_input($json, 'affsde', FILTER_VALIDATE_BOOLEAN),
    'pes_vente' => filter_input($json, 'pes_vente', FILTER_VALIDATE_BOOLEAN),
    'force_pes_vente' => filter_input($json, 'force_pes_vente', FILTER_VALIDATE_BOOLEAN),
    'atva' => filter_input($json, 'atva', FILTER_VALIDATE_BOOLEAN),
    'taux_tva' => filter_input($json, 'taux_tva', FILTER_VALIDATE_FLOAT),
    'cr' => filter_input($json, 'cr', FILTER_VALIDATE_INT), // devrait etre une regex sur les nombres.
  ];
  return $structure;
}

function validate_json_login($unsafe_json) {
  $unsafe_json['username'] = filter_var($unsafe_json['username'], FILTER_VALIDATE_EMAIL);
  return $unsafe_json;
}

function validate_json_sorties($unsafe_json) {
  $filters = [
    'id_type_action' => FILTER_DEFAULT, // Peux etre NULL
    'antidate' => FILTER_DEFAULT, // Peux etre NULL validation faite plus tard.
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
    if ($filtered === NULL) {
      throw new UnexpectedValueException('Erreur: Donnee JSON invalide: ' . $k);
    } else {
      $json[$k] = $filtered;
    }
  }
  return $json;
}

function validate_json_collecte($unsafe_json) {
  $filters = [
    'id_type_action' => FILTER_VALIDATE_INT,
    'antidate' => FILTER_DEFAULT,
    'localite' => FILTER_VALIDATE_INT,
    'id_point' => FILTER_VALIDATE_INT,
    'id_user' => FILTER_VALIDATE_INT,
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

function parseDate_Post($key) {
  $result = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
  if ($result) {
    return DateTime::createFromFormat('Y-m-d', $result);
  } else {
    throw new UnexpectedValueException('Erreur: Donnee POST invalide date attendu.');
  }
}

function parseDate($str) {
  if ($str) {
    return DateTime::createFromFormat('Y-m-d', $str);
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
