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

require_once '../core/requetes.php';
require_once '../core/session.php';

session_start();

if (is_valid_session() && is_allowed_partners()) {
  require_once '../moteur/dbconfig.php';
  require_once 'tete.php';
  $filieres = filieres_sorties_id($bdd, $_POST['id']);
  $types_evac = map_by(types_dechets_evac($bdd), 'id');
  ?>
  <div class="container">
    <h1>Modifier un recycleur</h1>
    <div class="panel-heading">Modifier les données concernant la filiere n° <?= $_POST['id']; ?>, <?= $filieres['nom']; ?>. </div>
    <div class="panel-body">
      <form action="../moteur/modification_filiere_sortie_post.php" method="post">
        <div class="row">
          <input type="hidden" name="id" value="<?= $_POST['id']; ?>">
          <div class="col-md-2"><label for="nom">Nom:</label><input type="text" value="<?= $filieres['nom']; ?>" name="nom" class="form-control" required autofocus></div>
          <div class="col-md-3"><label for="description">Description:</label><input type="text" value="<?= $filieres['description']; ?>" name="description" class="form-control" required></div>
          <div class="col-md-1"><label for="couleur">Couleur:</label><input type="color"value="<?= $filieres['couleur']; ?>"name="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><button name="creer" class="btn btn-warning">Modifier</button></div>
          <br>
          <a href="edition_filieres_sortie.php">
            <button class="btn btn">Annuler</button>
          </a>
        </div>

        <div class="row">
          <div class="col-md-9"><br>
            <label for="tde">Type de déchets enlevés:</label>
            <div class="alert alert-info">
              <?php foreach ($types_evac as $donnees) {?>
                <input type="checkbox"
                <?= array_key_exists($donnees['id'], $filieres['accepte_type_dechet']) ? 'checked' : '' ?>
                       name="tde<?= $donnees['id']; ?>"
                       id="tde<?= $donnees['id']; ?>" >
                <label for="tde<?= $donnees['id'] ?>"><?= $donnees['nom'] ?></label>
                <?php } ?>
            </div>
          </div>
        </div>
      </form>
    </div>
    <br>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
