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

global $_SESSION;

// Appellée au login.
function set_session($user, $structure) {
  // Nouvelle méthode
  $_SESSION['systeme'] = 'oressource';
  $_SESSION['structure'] = $structure;
  $_SESSION['user'] = $user;

// Code déprécié mais à gardé encore un moment.
  $_SESSION['id'] = $user['id'];
  $_SESSION['niveau'] = $user['niveau'];
  $_SESSION['nom'] = $user['nom'];
  $_SESSION['prenom'] = $user['prenom'];
  $_SESSION['mail'] = $user['mail'];

  $_SESSION['tva_active'] = $structure['tva_active'] === 'oui';
  $_SESSION['taux_tva'] = $structure['taux_tva'];
  $_SESSION['structure'] = $structure['nom'];
  $_SESSION['siret'] = $structure['siret'];
  $_SESSION['adresse'] = $structure['adresse'];
  $_SESSION['texte_adhesion'] = $structure['texte_adhesion'];
  $_SESSION['lot_caisse'] = $structure['lot'] === 'oui';
  $_SESSION['viz_caisse'] = $structure['viz'] === 'oui';
  $_SESSION['nb_viz_caisse'] = $structure['nb_viz'];
  $_SESSION['saisiec'] = $structure['saisiec'];
  $_SESSION['affsp'] = $structure['affsp'];
  $_SESSION['affss'] = $structure['affss'];
  $_SESSION['affsr'] = $structure['affsr'];
  $_SESSION['affsd'] = $structure['affsd'];
  $_SESSION['affsde'] = $structure['affsde'];
  $_SESSION['pes_vente'] = $structure['pes_vente'] === 'oui';
  $_SESSION['force_pes_vente'] = $structure['force_pes_vente'] === 'oui';
}

function destroy_session() {
  session_unset();
  session_destroy();
  setcookie('login', '');
  setcookie('pass', '');
}

function user() {
  return $_SESSION['user'];
}

function configuration() {
  return $_SESSION['structure'];
}

/**
 * Renvoie `true` si la session est valide.
 */
function is_valid_session(): bool {
  return (isset(user()['id']) && $_SESSION['systeme'] === 'oressource');
}

function affichage_sortie_don(): bool {
  return configuration()['affsd'];
}

function affichage_sortie_poubelle(): bool {
  return configuration()['affsp'];
}

function affichage_sortie_partenaires(): bool {
  return configuration()['affss'];
}

function affichage_sortie_dechetterie(): bool {
  return configuration()['affsde'];
}

function affichage_sortie_recyclage(): bool {
  return configuration()['affsr'];
}



/**
 * Renvoie `true` si la session est autorisee a voir les bilans.
 * On suppose que la session a deja ete verifiee avant.
 */
function is_allowed_bilan(): bool {
  // FIXME: Pourquoi pas mettre la session en parametre?
  return (strpos(user()['niveau'], 'bi') !== false);
}

function is_allowed_vente(): bool {
  return strpos(user()['niveau'], 'v') !== false;
}

function is_allowed_vente_id(int $id): bool {
  return strpos(user()['niveau'], 'v' . $id) !== false;
}

function is_allowed_sortie(): bool {
  return strpos(user()['niveau'], 's') !== false;
}

// Test si l'utilisateur a les droits sur un point de collecte donnee.
function is_allowed_sortie_id(int $id): bool {
  return strpos(user()['niveau'], 's' . ((string) $id)) !== false;
}

function is_allowed_gestion(): bool {
  return strpos(user()['niveau'], 'g') !== false;
}

function is_allowed_gestion_id(int $id): bool {
  return strpos(user()['niveau'], 'g' . ((string) $id)) !== false;
}

// Test si l'utilisateur a les droits sur un point de collecte donnee.
function is_allowed_collecte_id(int $id): bool {
  return strpos(user()['niveau'], 'c' . ((string) $id)) !== false;
}

function is_allowed_collecte(): bool {
  return strpos(user()['niveau'], 'c') !== false;
}

function is_allowed_partners(): bool {
  return strpos(user()['niveau'], 'j') !== false;
}

function is_allowed_config(): bool {
  return strpos(user()['niveau'], 'k') !== false;
}

function is_allowed_users(): bool {
  return strpos(user()['niveau'], 'l') !== false;
}

function is_allowed_verifications(): bool {
  return strpos(user()['niveau'], 'h') !== false;
}

function is_allowed_edit_date(): bool {
  return strpos(user()['niveau'], 'e') !== false;
}

function is_allowed_saisie_collecte(): bool {
  return configuration()['saisiec'];
}

function is_collecte_visible(array $point_collecte): bool {
  return is_allowed_collecte_id($point_collecte['id']) && $point_collecte['visible'] === "oui";
}

function is_sortie_visible(array $point_sortie): bool {
  return is_allowed_sortie_id($point_sortie['id']) && $point_sortie['visible'] === "oui";
}

function is_vente_visible(array $point_vente): bool {
  return is_allowed_vente_id($point_vente['id']) && $point_vente['visible'] === "oui";
}
