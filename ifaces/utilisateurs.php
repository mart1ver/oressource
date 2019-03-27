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
require_once '../core/composants.php';
function _page_droit(array $utilisateur = []): array {
  if ($utilisateur === []) {
    $utilisateur['niveau'] = '';
  }
  return [
    'text' => "Permissions d'accès",
    'data' => [
      [['name' => 'niveaubi', 'text' => "Bilans"], utilisateur_bilan($utilisateur)],
      [['name' => 'niveaug', 'text' => "Gestion quotidienne"], utilisateur_gestion($utilisateur)],
      [['name' => 'niveauk', 'text' => "Configuration de Oressource"], utilisateur_config($utilisateur)],
      [['name' => 'niveauh', 'text' => "Verif. formulaires"], utilisateur_verifications($utilisateur)],
      [['name' => 'niveaul', 'text' => "Utilisateurs"], utilisateur_users($utilisateur)],
      [['name' => 'niveauj', 'text' => "Recycleurs et convention partenaires"], utilisateur_partners($utilisateur)],
      [['name' => 'niveaue', 'text' => "Saisir la date dans les formulaires"], utilisateur_edit_date($utilisateur)]
    ]
  ];
}

if (is_valid_session() && is_allowed_users()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';
  $url = null;
  $droits = null;
  $collectes = null;
  $ventes = null;
  $sorties = null;
  $nav = null;
  $info = null;
  if (!isset($_GET['id'])) {
    $urlPost = '../moteur/inscription_post.php';
    $droits = _page_droit();

    $collectes = [
      'text' => "Points de collecte:",
      'data' => array_map(function ($a) {
          return [['name' => "niveauc{$a['id']}", 'text' => $a['nom']], false];
        }, filter_visibles(points_collectes($bdd)))
    ];

    $ventes = [
      'text' => "Points de vente:",
      'data' => array_map(function ($a) {
          return [['name' => "niveauv{$a['id']}", 'text' => $a['nom']], false];
        }, filter_visibles(points_ventes($bdd)))
    ];

    $sorties = [
      'text' => "Points de sortie hors-boutique:",
      'data' => array_map(function ($a) {
          return [['name' => "niveaus{$a['id']}", 'text' => $a['nom']], false];
        }, filter_visibles(points_sorties($bdd)))
    ];

    $nav = [
      'text' => "Gestion des utilisateurs",
      'links' => [
        ['href' => 'utilisateurs.php', 'text' => 'Inscription', 'state' => 'active'],
        ['href' => 'edition_utilisateurs.php', 'text' => 'Édition']
      ]
    ];

    $info = [
      'type' => 'create',
      'nom' => $_GET['nom'] ?? '',
      'prenom' => $_GET['prenom'] ?? '',
      'mail' => $_GET['mail'] ?? ''
    ];
  } else {
    $urlPost = '../moteur/modification_utilisateur_post.php';
    $utilisateur = utilisateurs_id($bdd, $_GET['id']);
    $droits = _page_droit($utilisateur);

    $collectes = [
      'text' => "Points de collecte:",
      'data' => array_map(function ($a) use ($utilisateur) {
          return [['name' => "niveauc{$a['id']}", 'text' => $a['nom']], utilisateur_collecte($utilisateur, $a['id'])];
        }, filter_visibles(points_collectes($bdd)))
    ];

    $ventes = [
      'text' => "Points de vente:",
      'data' => array_map(function ($a) use ($utilisateur) {
          return [['name' => "niveauv{$a['id']}", 'text' => $a['nom']], utilisateur_vente($utilisateur, $a['id'])];
        }, filter_visibles(points_ventes($bdd)))
    ];

    $sorties = [
      'text' => "Points de sortie hors-boutique:",
      'data' => array_map(function ($a) use ($utilisateur) {
          return [['name' => "niveaus{$a['id']}", 'text' => $a['nom']], utilisateur_sortie($utilisateur, $a['id'])];
        }, filter_visibles(points_sorties($bdd)))
    ];

    $nav = [
      'text' => "Édition du profil utilisateur n°: {$utilisateur['id']} - {$utilisateur['mail']}",
      'links' => [
        ['href' => 'utilisateurs.php', 'text' => 'Inscription'],
        ['href' => 'edition_utilisateurs.php', 'text' => 'Édition', 'state' => 'active']
      ]
    ];
    $info = array_merge($utilisateur, ['type' => 'edit']);
  }
  ?>
  <div class="container">
    <?= configNav($nav); ?>
    <form action="<?= $urlPost ?>" method="post" autocomplete="off">
      <?php if (isset($_GET['id'])) { ?>
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
      <?php } ?>
      <div class="row">
        <div class="col-md-4">
          <?= configInfo($info) ?>
        </div>

        <div class="col-md-4">
          <?= configCheckboxArea($droits) ?>
        </div>
        <div class="col-md-4">
          <?= configCheckboxArea($collectes) ?>
          <?= configCheckboxArea($ventes) ?>
          <?= configCheckboxArea($sorties) ?>
        </div>

        <div class="row">
          <div class="col-md-5 col-md-offset-5">
            <?php if (isset($_GET['id'])) { ?>
              <button class="btn btn-warning">Modifier</button>
              <a class="btn btn-default" href="edition_utilisateurs.php">Annuler</a>
            </div>
          <?php } else { ?>
            <button class="btn btn-success">Créer</button>
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
  </div>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
