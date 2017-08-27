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
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Permissions d'accès</h3>
            </div>
            <div class="panel-body form-group custom-controls-stacked">
              <?= checkBox(['name' => 'niveaubi', 'text' => "Bilans"], false) ?>
              <?= checkBox(['name' => 'niveaug', 'text' => "Gestion quotidienne"], false) ?>
              <?= checkBox(['name' => 'niveauh', 'text' => "Verif. formulaires"], false) ?>
              <?= checkBox(['name' => 'niveaul', 'text' => "Utilisateurs"], false) ?>
              <?= checkBox(['name' => 'niveauj', 'text' => "Recycleurs et convention partenaires"], false) ?>
              <?= checkBox(['name' => 'niveauk', 'text' => "Configuration des structures"], false) ?>
              <?php if ($_SESSION['saisiec'] === 'oui') { ?>
                <?= checkBox(['name' => 'niveaue', 'text' => "Saisir la date dans les formulaires"], false) ?>
              <?php } ?>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Points de collecte:</h3>
            </div>
            <div class="panel-body custom-controls-stacked">
              <?php foreach (points_collectes($bdd) as $collecte) { ?>
                <?= checkBox(['name' => "niveauc{$collecte['id']}", 'text' => $collecte['nom']], false) ?>
              <?php } ?>
            </div>
          </div>

          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Points de vente:</h3>
            </div>
            <div class="panel-body custom-controls-stacked">
              <?php foreach (points_ventes($bdd) as $vente) { ?>
                <?= checkBox(['name' => "niveauv{$vente['id']}", 'text' => $vente['nom']], false) ?>
              <?php } ?>
            </div>
          </div>

          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Points de sortie hors-boutique:</h3>
            </div>
            <div class="panel-body custom-controls-stacked">
              <?php foreach (points_sorties($bdd) as $sortie) { ?>
                <?= checkBox(['name' => "niveaus{$sortie['id']}", 'text' => $sortie['nom']], false) ?>
              <?php } ?>
            </div>
          </div>
        </div>
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
