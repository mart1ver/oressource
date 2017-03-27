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

// Appellee au login.
function set_session($user, $structure) {
  global $_SESSION;
  $_SESSION['systeme'] = 'oressource';

  $_SESSION['id'] = $user['id'];
  $_SESSION['niveau'] = $user['niveau'];
  $_SESSION['nom'] = $user['nom'];
  $_SESSION['prenom'] = $user['prenom'];
  $_SESSION['mail'] = $user['mail'];

  $_SESSION['tva_active'] = $structure['tva_active'];
  $_SESSION['taux_tva'] = $structure['taux_tva'];
  $_SESSION['structure'] = $structure['nom'];
  $_SESSION['siret'] = $structure['siret'];
  $_SESSION['adresse'] = $structure['adresse'];
  $_SESSION['texte_adhesion'] = $structure['texte_adhesion'];
  $_SESSION['lot_caisse'] = $structure['lot'];
  $_SESSION['viz_caisse'] = $structure['viz'];
  $_SESSION['nb_viz_caisse'] = $structure['nb_viz'];
  $_SESSION['saisiec'] = $structure['saisiec'];
  $_SESSION['affsp'] = $structure['affsp'];
  $_SESSION['affss'] = $structure['affss'];
  $_SESSION['affsr'] = $structure['affsr'];
  $_SESSION['affsd'] = $structure['affsd'];
  $_SESSION['affsde'] = $structure['affsde'];
  $_SESSION['pes_vente'] = $structure['pes_vente'];
  $_SESSION['force_pes_vente'] = $structure['force_pes_vente'];
}

/**
 * Renvoie `true` si la session est valide.
 */
function is_valid_session() {
// FIXME: Pourquoi pas mettre la session en parametre?
  return (isset($_SESSION['id'])
    && $_SESSION['systeme'] === 'oressource');
}

/**
 * Renvoie `true` si la session est autorisee a voir les bilans.
 * On suppose que la session a deja ete verifiee avant.
 */
function is_allowed_bilan() {
  // FIXME: Pourquoi pas mettre la session en parametre?
  return (strpos($_SESSION['niveau'], 'bi') !== false);
}

function is_allowed_vente() {
  return strpos($_SESSION['niveau'], 'v') !== false;
}

function is_allowed_sortie() {
  return strpos($_SESSION['niveau'], 's') !== false;
}

// Test si l'utilisateur a les droits sur un point de collecte donnee.
function is_allowed_sortie_id($id) {
  return strpos($_SESSION['niveau'], 's' . ((string) $id)) !== false;
}

function is_allowed_gestion() {
  return strpos($_SESSION['niveau'], 'g') !== false;
}

function is_allowed_gestion_id($id) {
  return strpos($_SESSION['niveau'], 'g' . ((string) $id)) !== false;
}

// Test si l'utilisateur a les droits sur un point de collecte donnee.
function is_allowed_collecte_id($id) {
  return strpos($_SESSION['niveau'], 'c' . ((string) $id)) !== false;
}

function is_allowed_collecte() {
  return strpos($_SESSION['niveau'], 'c') !== false;
}

function is_allowed_partners() {
  return strpos($_SESSION['niveau'], 'j') !== false;
}

function is_allowed_config() {
  return strpos($_SESSION['niveau'], 'k') !== false;
}

function is_allowed_users() {
  return strpos($_SESSION['niveau'], 'l') !== false;
}

function is_allowed_verifications() {
  return strpos($_SESSION['niveau'], 'h') !== false;
}

function is_allowed_edit_date() {
  return strpos($_SESSION['niveau'], 'e') !== false;
}

function is_allowed_saisie_collecte() {
  return $_SESSION['saisiec'] === 'oui';
}

function is_collecte_visible($point_collecte) {
  return (strpos($_SESSION['niveau'], 'c' . $point_collecte['id']) !== false
    && $point_collecte['visible'] === "oui");
}

function is_sortie_visible($point_sortie) {
  return (strpos($_SESSION['niveau'], 's' . $point_sortie['id']) !== false
    && $point_sortie['visible'] === "oui");
}

function is_vente_visible($point_vente) {
  return (strpos($_SESSION['niveau'], 'v' . $point_vente['id']) !== false
    && $point_vente['visible'] === "oui");
}
