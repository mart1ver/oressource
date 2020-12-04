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

require_once '../moteur/dbconfig.php';
require_once '../core/composants.php';

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'g') !== false)) {
  require_once 'tete.php';
  $types_poubelles = $bdd->query('SELECT * FROM types_poubelles');
  ?>
  <div class="container">
    <h1>Gestion de la typologie et de la masse des différentes poubelles mises à disposition de la structure par la ville</h1>
    <div class="panel-heading">Gérez ici la liste de vos bacs à déchets.</div>
    <p>Cet outil vous permet notamment de discerner les bacs de matières recyclables de ceux dont le contenu est destiné à un enfouissement ou une incinération.</p>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/type_poubelles_post.php" method="post">
          <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"                 value ="<?= $_GET['nom'] ?? ''; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-2"><label for="description">Description:</label> <input type="text" value ="<?= $_GET['description'] ?? ''; ?>" name="description" id="description" class="form-control" required></div>
          <div class="col-md-2"><label for="masse_bac">Masse du bac:</label> <input type="text" value ="<?= $_GET['masse_bac'] ?? ''; ?>" name="masse_bac" id="masse_bac" class="form-control" required></div>
          <div class="col-md-2"><label for="ultime">Déchet ultime ?</label><br> <input <?= ($_GET['ultime'] ?? true) ? 'checked' : '' ?> name="ultime" id="ultime" type="checkbox" value ="TRUE">Oui</div>
          <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color" value="#<?= $_GET['couleur'] ?? ''; ?>" name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><button name="creer" class="btn btn-default">Créer!</button></div>
        </form>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Masse du bac:</th>
          <th>Déchet ultime?</th>
          <th>Couleur</th>
          <th>Visible</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($types_poubelles as $donnees) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['description']; ?></td>
            <td><?= $donnees['masse_bac']; ?></td>
            <td class="garbage ultime-<?= $donnees['ultime']; ?>"></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td><?= configBtnVisible(['url' => 'types_poubelles', 'id' => $donnees['id'], 'visible' => $donnees['visible']]) ?></td>
            <td>
              <form action="modification_type_poubelles.php" method="post">
                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <button  class="btn btn-warning btn-sm">Modifier!</button>
              </form>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div><!-- /.container -->

  <?php
  $types_poubelles->closeCursor();
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
