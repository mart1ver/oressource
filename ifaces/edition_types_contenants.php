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

require_once '../core/requetes.php';
require_once '../core/session.php';
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_gestion()) {
  require_once '../moteur/dbconfig.php';
  $contenants = types_contenants($bdd);
  $props = [
    'url' => 'type_contenants',
    'nom' => $_GET['nom'] ?? '',
    'masse' => $_GET['masse_bac'] ?? '',
    'couleur' => $_GET['couleur'] ?? '',
    'description' => $_GET['description'] ?? '',
    'textBtn' => 'Créer'
  ];
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Gestion de la typologie des bacs et des outils de manutention. </h1>
    <div class="panel-heading">Renseignez ici la masse de vos bacs et outils de manutention .</div>
    <p>Cet outil vous permet notamment d'indiquer le poids de vos bacs, chariots, diables, etc. de manière à pouvoir le soustraire automatiquement au moment de la pesée.</p>
    <div class="panel-body">
      <div class="row">
        <?= config_types4_form($props) ?>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Masse de l'objet (Kg):</th>
          <th>Couleur</th>
          <th>Visible</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contenants as $donnees) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['description']; ?></td>
            <td><?= $donnees['masse']; ?></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td><?= configBtnVisible(['url' => 'type_contenants', 'id' => $donnees['id'], 'visible' => $donnees['visible']]) ?></td>
            <td>
              <form action="modification_type_contenants.php" method="post">
                <input type="hidden" name="id" id="id" value="<?= $donnees['id']; ?>">
                <button class="btn btn-warning btn-sm">Modifier!</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div><!-- /.container -->

  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
