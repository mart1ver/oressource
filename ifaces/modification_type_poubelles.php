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
require_once('../core/requetes.php');

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'g') !== false)) {
  require_once 'tete.php';
  $id = (int) $_POST['id'];
  $poubelle = types_poubelles_id($bdd, $id);
  ?>
  <div class="container">
    <h1>Gestion des types et des masses des poubelles de la ville utilisées par la structure</h1>
    <div class="panel-heading">Modifier les données concernant le type de bac n° <?= $id ?>, <?= $poubelle['nom'] ?>. </div>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/modification_type_poubelles_post.php" method="post">
          <input type="hidden" name="id" value="<?= $id; ?>">
          <div class="col-md-2"><label for="nom">Nom:</label><input type="text" value="<?= $poubelle['nom']; ?>" name="nom" id="nom" class="form-control" required autofocus></div>
          <div class="col-md-3"><label for="description">Description:</label><input type="text" value="<?= $poubelle['description']; ?>" name="description" id="description" class="form-control" required></div>
          <div class="col-md-2"><label for="masse_bac">Masse du bac(Kg):</label><input type="text" value="<?= $poubelle['masse_bac']; ?>" name="masse_bac" id="masse_bac" class="form-control" required></div>
          <div class="col-md-2"><label for="ultime">Déchet ultime ?</label><br><input name="ultime" id="ultime" type="checkbox" value="true" <?= $poubelle['ultime'] ? 'checked' : '' ?>>Oui.</div>
          <div class="col-md-1"><label for="couleur">Couleur:</label><input type="color" value="<?= $poubelle['couleur']; ?>" name="couleur" id="couleur" class="form-control " required autofocus></div>
          <div class="col-md-1"><br><button class="btn btn-warning">Modifier</button></div>
        </form>
        <br>
        <a href="edition_types_poubelles.php">
          <button name="creer" class="btn btn">Anuler</button>
        </a>
      </div>
    </div>
  </div>
  </div>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
