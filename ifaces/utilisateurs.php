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
  ?>
  <div class="container">
    <nav class="navbar">
      <div class="header-header">
        <h1>Gestion des utilisateurs</h1>
      </div>
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="utilisateurs.php">Inscription</a>
        </li>
        <li>
          <a href="edition_utilisateurs.php">Édition</a>
        </li>
      </ul>
    </nav>

    <form action="../moteur/inscription_post.php" method="post">
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Nouvel utilisateur</h3>
            </div>
            <div class="panel-body">
              <?= textInput(['name' => 'nom', 'text' => "Nom:"], $_GET['nom'] ?? '') ?>
              <?= textInput(['name' => 'prenom', 'text' => "Prénom:"], $_GET['prenom'] ?? '') ?>
              <label>Mail:
                <input type="email" value ="<?= $_GET['mail'] ?? ''; ?>" name="mail" id="mail" class="form-control" required>
              </label>
              <label>Mot de passe
                <input type="password" name="pass1" id="pass1" class="form-control" required>
              </label>
              <label>Répetez le mot de passe
                <input type="password" name="pass2" id="pass2" class="form-control" required>
              </label>
            </div>
          </div>
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
            <br>
            <button name="creer" class="btn btn-default">Créer!</button>
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
