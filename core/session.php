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

/**
 * Renvoie `true` si la session est valide.
 */
function is_valid_session() {
// FIXME: Pourquoi pas mettre la session en parametre?
  return (isset($_SESSION['id'])
    && $_SESSION['systeme'] === "oressource");
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

function is_allowed_gestion() {
  return strpos($_SESSION['niveau'], 'g') !== false;
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
