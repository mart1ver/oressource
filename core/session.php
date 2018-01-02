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

require_once 'validation.php';

// Appellée au login.
function set_session(array $user, array $structure) {
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

function destroy_session() {
  setcookie('login', '');
  setcookie('pass', '');
  session_unset();
  session_destroy();
}

function pesees_ventes(): bool {
  return $_SESSION['pes_vente'];
}

function ventes_lots(): bool {
  return $_SESSION['lot_caisse'];
}

/**
 * Renvoie `true` si la session est valide.
 */
function is_valid_session(): bool {
  return isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource';
}

function affichage_sortie_don(): bool {
  return $_SESSION['affsd'];
}

function affichage_sortie_poubelle(): bool {
  return $_SESSION['affsp'];
}

function affichage_sortie_partenaires(): bool {
  return $_SESSION['affss'];
}

function affichage_sortie_dechetterie(): bool {
  return $_SESSION['affsde'];
}

function affichage_sortie_recyclage(): bool {
  return $_SESSION['affsr'];
}

/**
 * Renvoie `true` si la session est autorisee a voir les bilans.
 * On suppose que la session a deja ete verifiee avant.
 */
function is_allowed_bilan(): bool {
  return strpos($_SESSION['niveau'], 'bi') !== false;
}

function is_allowed_vente(): bool {
  return strpos($_SESSION['niveau'], 'v') !== false;
}

function is_allowed_vente_id(int $id): bool {
  return strpos($_SESSION['niveau'], 'v' . $id) !== false;
}

function is_allowed_sortie(): bool {
  return strpos($_SESSION['niveau'], 's') !== false;
}

function is_allowed_sortie_id(int $id): bool {
  return strpos($_SESSION['niveau'], 's' . ((string) $id)) !== false;
}

function is_allowed_gestion(): bool {
  return strpos($_SESSION['niveau'], 'g') !== false;
}

function is_allowed_gestion_id(int $id): bool {
  return strpos($_SESSION['niveau'], 'g' . ((string) $id)) !== false;
}

function is_allowed_collecte_id(int $id): bool {
  return strpos($_SESSION['niveau'], 'c' . ((string) $id)) !== false;
}

function is_allowed_collecte(): bool {
  return strpos($_SESSION['niveau'], 'c') !== false;
}

function is_allowed_partners(): bool {
  return strpos($_SESSION['niveau'], 'j') !== false;
}

function is_allowed_config(): bool {
  return strpos($_SESSION['niveau'], 'k') !== false;
}

function is_allowed_users(): bool {
  return strpos($_SESSION['niveau'], 'l') !== false;
}

function is_allowed_verifications(): bool {
  return strpos($_SESSION['niveau'], 'h') !== false;
}

function is_allowed_edit_date(): bool {
  return strpos($_SESSION['niveau'], 'e') !== false;
}

function is_allowed_saisie_date(): bool {
  return $_SESSION['saisiec'];
}

function is_collecte_visible(array $point_collecte): bool {
  return is_allowed_collecte_id($point_collecte['id']) && $point_collecte['visible'];
}

function is_sortie_visible(array $point_sortie): bool {
  return is_allowed_sortie_id($point_sortie['id']) && $point_sortie['visible'];
}

function is_vente_visible(array $point_vente): bool {
  return is_allowed_vente_id($point_vente['id']) && $point_vente['visible'];
}

function allowDate(array $json): bool {
  return (is_allowed_edit_date()
          && is_allowed_saisie_date()
          && isset($json['date']));
}

function droits(array $points, string $type, array $droits): string {
  $d = '';
  $niveau = 'niveau' . $type;
  foreach ($points as $p) {
    if (isset($droits[$niveau . $p['id']])) {
      $d .= $type . $p['id'];
    }
  }
  return $d;
}

function new_droits(PDO $bdd, array $droits): string {
  $f = function ($type) use ($droits) {
    return ($droits['niveau' . $type] ?? false) ? $type : '';
  };
  $collectes = filter_visibles(points_collectes($bdd));
  $ventes = filter_visibles(points_ventes($bdd));
  $sorties = filter_visibles(points_sorties($bdd));
  return ($f('a') . $f('bi') . $f('g')
    . $f('h') . $f('l') . $f('j')
    . $f('k') . $f('m') . $f('p')
    . $f('e')
    . droits($collectes, 'c', $droits)
    . droits($ventes, 'v', $droits)
    . droits($sorties, 's', $droits));
}

function new_utilisateur(string $nom, string $prenom, string $mail, string $droits, string $pass = ''): array {
  return [
    'nom' => $nom,
    'prenom' => $prenom,
    'mail' => $mail,
    'pass' => md5($pass),
    'niveau' => $droits
  ];
}

function utilisateur_vente(array $utilisateur, int $id): bool {
  return strpos($utilisateur['niveau'], 'v' . $id) !== false;
}

function utilisateur_sortie(array $utilisateur, int $id): bool {
  return strpos($utilisateur['niveau'], 's' . $id) !== false;
}

function utilisateur_collecte(array $utilisateur, int $id): bool {
  return strpos($utilisateur['niveau'], 'c' . $id) !== false;
}

function utilisateur_bilan(array $utilisateur): bool {
  return strpos($utilisateur['niveau'], 'bi') !== false;
}

function utilisateur_gestion(array $utilisateur): bool {
  return strpos($utilisateur['niveau'], 'g') !== false;
}

function utilisateur_partners(array $utilisateur): bool {
  return strpos($utilisateur['niveau'], 'j') !== false;
}

function utilisateur_config(array $utilisateur): bool {
  return strpos($utilisateur['niveau'], 'k') !== false;
}

function utilisateur_users(array $utilisateur): bool {
  return strpos($utilisateur['niveau'], 'l') !== false;
}

function utilisateur_verifications(array $utilisateur): bool {
  return strpos($utilisateur['niveau'], 'h') !== false;
}

function utilisateur_edit_date(array $utilisateur): bool {
  return strpos($utilisateur['niveau'], 'e') !== false;
}
