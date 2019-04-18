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

require_once('../moteur/dbconfig.php');
require_once('../core/session.php');
require_once('../core/requetes.php');

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && is_allowed_bilan()) {
  require_once 'tete.php';

  $id_obj = filter_input(INPUT_GET, 'id_obj', FILTER_VALIDATE_INT);

  $obj = objet_id($bdd, $id_obj);
  ?>
  <div class="container">
    <h1>Grille des prix</h1>
    <div class="panel-heading">Modifier les données concernant l'objet n° <?= $obj['id']; ?>, <?= $obj['nom']; ?>.</div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/modification_objet_post.php" method="post">
          <input type="hidden" name="id" id="id" value="<?= $obj['id']; ?>">
          <div class="col-md-2">
            <label for="nom">Nom:</label>
            <input type="text" value="<?= $obj['nom']; ?>" name="nom" id="nom" class="form-control" required autofocus>
          </div>
          <div class="col-md-3">
            <label for="description">Description:</label>
            <input type="text" value="<?= $obj['description']; ?>" name="description" id="description" class="form-control" required>
          </div>
          <div class="col-md-1">
            <label for="prix">Prix:</label>
            <input type="text" value="<?= $obj['prix']; ?>" name="prix" id="prix" class="form-control" required>
          </div>
          <div class="col-md-1">
            <br>
            <button name="creer" class="btn btn-warning">Modifier</button>
          </div>
        </form>
        <br>
        <a href="grilles_prix.php?type_dechet=<?= $obj['id_type_dechet']; ?>">
          <button name="creer" class="btn btn">Annuler</button>
        </a>
      </div>
    </div>
  </div>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
