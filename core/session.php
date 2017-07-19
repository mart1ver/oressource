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

// Appellée au login.
function set_session($user, $structure) {
  global $_SESSION;
  $_SESSION['systeme'] = 'oressource';
  $_SESSION['structure'] = $structure;
  $_SESSION['timeout'] = 3600;
  $_SESSION['user'] = $user;
}

function destroy_session() {
  session_unset();
  session_destroy();
  setcookie('login', '');
  setcookie('pass', '');
}

/**
 * Renvoie `true` si la session est valide.
 */
function is_valid_session() {
  global $_SESSION;
  return (isset($_SESSION['user']['id']) && $_SESSION['systeme'] === 'oressource');
}

function affichage_sortie_don(): bool {
  return $_SESSION['structure']['affsd'];
}

function affichage_sortie_poubelle(): bool {
  return $_SESSION['structure']['affsp'];
}

function affichage_sortie_partenaires(): bool {
  return $_SESSION['structure']['affss'];
}

function affichage_sortie_dechetterie(): bool {
  return $_SESSION['structure']['affsde'];
}

function affichage_sortie_recyclage(): bool {
  return $_SESSION['structure']['affsr'];
}

/**
 * Renvoie `true` si la session est autorisee a voir les bilans.
 * On suppose que la session a deja ete verifiee avant.
 */
function is_allowed_bilan() {
  // FIXME: Pourquoi pas mettre la session en parametre?
  return (strpos($_SESSION['user']['niveau'], 'bi') !== false);
}

function is_allowed_vente() {
  return strpos($_SESSION['user']['niveau'], 'v') !== false;
}

function is_allowed_vente_id(int $id): bool {
  return strpos($_SESSION['user']['niveau'], 'v' . $id) !== false;
}

function is_allowed_sortie() {
  return strpos($_SESSION['user']['niveau'], 's') !== false;
}

// Test si l'utilisateur a les droits sur un point de collecte donnee.
function is_allowed_sortie_id($id) {
  return strpos($_SESSION['user']['niveau'], 's' . ((string) $id)) !== false;
}

function is_allowed_gestion() {
  return strpos($_SESSION['user']['niveau'], 'g') !== false;
}

function is_allowed_gestion_id($id) {
  return strpos($_SESSION['user']['niveau'], 'g' . ((string) $id)) !== false;
}

// Test si l'utilisateur a les droits sur un point de collecte donnee.
function is_allowed_collecte_id($id) {
  return strpos($_SESSION['user']['niveau'], 'c' . ((string) $id)) !== false;
}

function is_allowed_collecte() {
  return strpos($_SESSION['user']['niveau'], 'c') !== false;
}

function is_allowed_partners() {
  return strpos($_SESSION['user']['niveau'], 'j') !== false;
}

function is_allowed_config() {
  return strpos($_SESSION['user']['niveau'], 'k') !== false;
}

function is_allowed_users() {
  return strpos($_SESSION['user']['niveau'], 'l') !== false;
}

function is_allowed_verifications() {
  return strpos($_SESSION['user']['niveau'], 'h') !== false;
}

function is_allowed_edit_date() {
  return strpos($_SESSION['user']['niveau'], 'e') !== false;
}

function is_allowed_saisie_collecte() {
  return $_SESSION['structure']['saisiec'];
}

function is_collecte_visible($point_collecte) {
  return (strpos($_SESSION['user']['niveau'], 'c' . $point_collecte['id']) !== false && $point_collecte['visible'] === "oui");
}

function is_sortie_visible($point_sortie) {
  return (strpos($_SESSION['user']['niveau'], 's' . $point_sortie['id']) !== false && $point_sortie['visible'] === "oui");
}

function is_vente_visible($point_vente) {
  return (strpos($_SESSION['user']['niveau'], 'v' . $point_vente['id']) !== false && $point_vente['visible'] === "oui");
}
