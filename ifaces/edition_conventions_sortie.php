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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'j') !== false)) {
  require_once 'tete.php';
  //Oressource 2014, formulaire de référencement des conventions avec les partenaires de la structure
  //Simple formulaire de saisie , liste des conventions déjà référencées et possibilité de les cacher à l'utilisateur ou de modifier les données
  //
  ?>

  <div class="container">
    <h1>Gestion des conventions avec les partenaires</h1>
    <div class="panel-heading">Gérez ici la liste de vos partenaires de réemploi.</div>
    <p>Permet de différencier les partenaires au moment de la mise en bilan </p>
    <div class="panel-body">
      <div class="row">
        <form action="../moteur/convention_sortie_post.php" method="post">
          <div class="col-md-3"><label for="nom">Nom:</label> <input type="text"                 value ="<?= $_GET['nom']; ?>" name="nom" id="nom" class="form-control " required autofocus></div>
          <div class="col-md-2"><label for="description">Description:</label> <input type="text" value ="<?= $_GET['description']; ?>" name="description" id="description" class="form-control" required></div>

          <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color" value="<?= '#' . $_GET['couleur']; ?>" name="couleur" id="couleur" class="form-control" required></div>
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
          <th>Couleur</th>
          <th>Visible</th>
          <th></th>

        </tr>
      </thead>
      <tbody>
        <?php
        $reponse = $bdd->query('SELECT * FROM conventions_sorties');
        while ($donnees = $reponse->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['description']; ?></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td>
              <form action="../moteur/convention_sortie_visible.php" method="post">

                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name="visible" id="visible" value="<?php
                if ($donnees['visible'] === 'oui') {
                  echo 'non';
                } else {
                  echo 'oui';
                }
                ?>">
                       <?php
                       if ($donnees['visible'] === 'oui') { // SI on a pas de message d'erreur
                         ?>
                  <button  class="btn btn-info btn-sm " >
                    <?php
                  } else { // SINON
                    ?>
                    <button  class="btn btn-danger btn-sm " >
                      <?php
                    }
                    echo $donnees['visible'];
                    ?>
                  </button>
              </form>
            </td>
            <td>

              <form action="modification_convention_sortie.php" method="post">

                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                <input type="hidden" name ="description" id="description" value="<?= $donnees['description']; ?>">
                <input type="hidden" name ="couleur" id="couleur" value="<?= substr($_POST['couleur'], 1); ?>">

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
