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

if (is_valid_session() && is_allowed_users()) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';
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
  ?>

  <div class="container">
    <nav class="navbar">
      <div class="header-header">
        <h1>Édition du profil utilisateur n°:<?= $utilisateur['id']; ?> - <?= $utilisateur['mail']; ?></h1>
      </div>
      <ul class="nav nav-tabs">
        <li>
          <a href="utilisateurs.php">Inscription</a>
        </li>
        <li>
          <a href="edition_utilisateurs.php">Édition</a>
        </li>
      </ul>
    </nav>
    <form action="../moteur/modification_utilisateur_post.php" method="post">
      <input type="hidden" name="id" id="id" value="<?= $utilisateur['id']; ?>">
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Informations</h3>
            </div>
            <div class="panel-body">
              <?= textInput(['name' => 'nom', 'text' => "Nom:"], $utilisateur['nom']) ?>
              <?= textInput(['name' => 'prenom', 'text' => "Prénom:"], $utilisateur['prenom']) ?>
              <label>Mail:
                <input type="email" value ="<?= $utilisateur['mail'] ?>" name="mail" id="mail" class="form-control" required>
              </label>
              <a href="edition_mdp_admin.php?id=<?= $utilisateur['id']; ?>&mail=<?= $utilisateur['mail'] ?>">
                <button name="creer" type="button" class="btn btn btn-danger">Changer le mot de passe</button>
              </a>
            </div>
          </div>
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
          <div class="col-md-6 col-md-offset-6">
            <button name="modifier" class="btn btn-warning">Modifier</button>
            <a href="edition_utilisateurs.php">
              <button name="creer" class="btn btn">Annuler</button>
            </a>
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
