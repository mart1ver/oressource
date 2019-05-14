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

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'k') !== false)) {
  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Gestion des localités de collecte</h1>
    <div class="panel-heading">Définissez ici les localité d'origine possibles pour les matériaux entrant.</div>
    <p>Ces localités sont renseignées au moment de la collecte et permettent d'estimer l'impact territorial de la structure </p>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/edition_localites_post.php" method="post">
          <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"                 value ="<?= $_GET['nom'] ?? ''; ?>" name="nom" id="nom" class="form-control" required autofocus></div>
          <div class="col-md-2"><label for="commentaire">Commentaire:</label> <input type="text" value ="<?= $_GET['commentaire'] ?? ''; ?>" name="commentaire" id="commentaire" class="form-control" required></div>
          <div class="col-md-3"><label for="lien">Lien externe:</label> <input type="url" value ="<?= $_GET['lien'] ?? ''; ?>" name="lien" id="lien" class="form-control"></div>
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
          <th>Commentaire:</th>
          <th>Couleur</th>
          <th>Lien</th>
          <th>Visible</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $reponse = $bdd->query('SELECT * FROM localites');

        while ($donnees = $reponse->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['commentaire']; ?></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td><a href="<?= $donnees['relation_openstreetmap']; ?>" target="_blank"><p style="text-align:center"><span class="glyphicon glyphicon-link"></span></p></a></td>
            <td><?= configBtnVisible(['url' => 'localites', 'id' => $donnees['id'], 'visible' => $donnees['visible']]) ?></td>
            <td>
              <form action="modification_localites.php" method="post">
                <input type="hidden" name="id" value="<?= $donnees['id']; ?>">
                <button  class="btn btn-warning btn-sm" >Modifier!</button>
              </form>
            </td>
          </tr>
          <?php
        }
        $reponse->closeCursor();
        ?>
      </tbody>
    </table>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
