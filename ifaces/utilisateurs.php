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
    $droits = [
      'text' => "Permissions d'accès",
      'data' => [
        [['name' => 'niveaubi', 'text' => "Bilans"], false],
        [['name' => 'niveaug', 'text' => "Gestion quotidienne"], false],
        [['name' => 'niveaug', 'text' => "Gestion quotidienne"], false],
        [['name' => 'niveauh', 'text' => "Verif. formulaires"], false],
        [['name' => 'niveaul', 'text' => "Utilisateurs"], false],
        [['name' => 'niveauj', 'text' => "Recycleurs et convention partenaires"], false],
        [['name' => 'niveaue', 'text' => "Saisir la date dans les formulaires"], false]
      ]
    ];

    $collectes = [
      'text' => "Points de collecte:",
      'data' => array_map(function ($a) {
          return [['name' => "niveauc{$a['id']}", 'text' => $a['nom']], false];
        }, points_collectes($bdd))
    ];

    $ventes = [
      'text' => "Points de vente:",
      'data' => array_map(function ($a) {
          return [['name' => "niveauv{$a['id']}", 'text' => $a['nom']], false];
        }, points_ventes($bdd))
    ];

    $sorties = [
      'text' => "Points de sortie hors-boutique:",
      'data' => array_map(function ($a) {
          return [['name' => "niveaus{$a['id']}", 'text' => $a['nom']], false];
        }, points_sorties($bdd))
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
    $droits = [
      'text' => "Permissions d'accès",
      'data' => [
        [['name' => 'niveaubi', 'text' => "Bilans"], utilisateur_bilan($utilisateur)],
        [['name' => 'niveaug', 'text' => "Gestion quotidienne"], utilisateur_gestion($utilisateur)],
        [['name' => 'niveaug', 'text' => "Gestion quotidienne"], utilisateur_verifications($utilisateur)],
        [['name' => 'niveauh', 'text' => "Verif. formulaires"], utilisateur_users($utilisateur)],
        [['name' => 'niveaul', 'text' => "Utilisateurs"], utilisateur_partners($utilisateur)],
        [['name' => 'niveauj', 'text' => "Recycleurs et convention partenaires"], utilisateur_config($utilisateur)],
        [['name' => 'niveaue', 'text' => "Saisir la date dans les formulaires"], utilisateur_edit_date($utilisateur)]
      ]
    ];

    $collectes = [
      'text' => "Points de collecte:",
      'data' => array_map(function ($a) use ($utilisateur) {
          return [['name' => "niveauc{$a['id']}", 'text' => $a['nom']], utilisateur_collecte($utilisateur, $a['id'])];
        }, points_collectes($bdd))
    ];

    $ventes = [
      'text' => "Points de vente:",
      'data' => array_map(function ($a) use ($utilisateur) {
          return [['name' => "niveauv{$a['id']}", 'text' => $a['nom']], utilisateur_vente($utilisateur, $a['id'])];
        }, points_ventes($bdd))
    ];

    $sorties = [
      'text' => "Points de sortie hors-boutique:",
      'data' => array_map(function ($a) use ($utilisateur) {
          return [['name' => "niveaus{$a['id']}", 'text' => $a['nom']], utilisateur_sortie($utilisateur, $a['id'])];
        }, points_sorties($bdd))
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
    <form action="<?= $urlPost ?>" method="post">
      <div class="row">
        <div class="col-md-4">
          <?= configInfo($info) ?>
        </div>

        <div class="col-md-4">
          <?= configCheckboxArea($droits) ?>
        </div>
        <?php
        ?>
        <div class="col-md-4">
          <?= configCheckboxArea($collectes) ?>
          <?= configCheckboxArea($ventes) ?>
          <?= configCheckboxArea($sorties) ?>
        </div>

        <div class="row">
          <div class="col-md-5 col-md-offset-5">
            <?php if (isset($_GET['id'])) { ?>
              <button name="modifier" class="btn btn-warning">Modifier</button>
              <a href="edition_utilisateurs.php">
                <button name="creer" class="btn btn">Annuler</button>
              </a>
            </div>
          <?php } else { ?>
            <button name="creer" class="btn btn-default">Créer!</button>
          <?php } ?>
        </div>
      </div>
  </div>
  </form>
  </div>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
