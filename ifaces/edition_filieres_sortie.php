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

// Oressource 2014, formulaire de référencement des filières de sortie (entreprises de recyclage, associations, etc) en lien avec la structure
// Simple formulaire de saisie qui permet de lister des filières de sortie déjà référencées et s'accompagne de la possibilité de les cacher à l'utilisateur ou d'en modifier les données

session_start();
require_once '../moteur/dbconfig.php';
require_once '../core/composants.php';
require_once '../core/requetes.php';
require_once '../core/session.php';

if (is_valid_session() && is_allowed_partners()) {
  $filieres_sortie = filieres_sorties($bdd);
  $types_dechets_evac = map_by(types_dechets_evac($bdd), 'id');
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Gestion des partenaires de recyclage</h1>
    <div class="panel-heading">Gérez ici la liste de ceux de vos partenaires qui traitent vos sorties destinées au recyclage.</div>
    <p>Permet notamment de différencier les "sorties recyclage" des "sorties ré-emploi" au moment de la mise en bilan.</p>

    <div class="panel-body">
      <div class="row">
        <form action="../moteur/filiere_sortie_post.php" method="post">
          <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"value ="<?= $_GET['nom'] ?? ''; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-4"><label for="description">Description:</label> <input type="text" value ="<?= $_GET['description'] ?? ''; ?>" name="description" id="description" class="form-control" required></div>
          <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color" value="#<?= $_GET['couleur'] ?? ''; ?>" name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><button name="creer" class="btn btn-default">Créer!</button></div>
      </div>
      <div class="row">
        <div class="col-md-9"><br>
          <label for="tde">Type de déchets enlevés:</label>
          <div class="alert alert-info">
            <?php foreach ($types_dechets_evac as $donnees) { ?>
              <input type="checkbox" name="tde<?= $donnees['id']; ?>" id="tde<?= $donnees['id']; ?>">
              <label for="tde<?= $donnees['id'] ?>"><?= $donnees['nom'] ?></label>
            <?php } ?>
          </div>
        </div>
      </div>
      </form>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Type de déchets enlevés:</th>
          <th>Couleur</th>
          <th>Visible</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($filieres_sortie as $donnees) {
          // FIXME corriger la base et format de stockage de la liste.
          $types = [];
          foreach ($donnees['accepte_type_dechet'] as $id => $_) {
            array_push($types, $types_dechets_evac[$id]['nom']);
          } ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['description']; ?></td>
            <td><?= implode(', ', $types) ?></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td><?= configBtnVisible(['url' => 'filieres_sortie', 'id' => $donnees['id'], 'visible' => $donnees['visible']]) ?></td>
            <td>
              <form action="modification_filiere_sortie.php" method="post">
                <input type="hidden" name="id" value="<?= $donnees['id']; ?>">
                <button  class="btn btn-warning btn-sm" >Modifier!</button>
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
