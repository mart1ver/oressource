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
require_once('../moteur/dbconfig.php');
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'j') !== false)) {
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
          <div class="col-md-1"><label for="couleur">Couleur:</label> <input type="color" value="<?= '#' . $_GET['couleur'] ?? ''; ?>" name="couleur" id="couleur" class="form-control" required></div>
          <div class="col-md-1"><br><button name="creer" class="btn btn-default">Créer!</button></div>
      </div>
      <div class="row">
        <div class="col-md-9"><br>
          <label for="tde">Type de déchets enlevés:</label>
          <div class="alert alert-info">
            <?php
            $reponse = $bdd->query('SELECT * FROM type_dechets_evac');
            while ($donnees = $reponse->fetch()) {
              ?>
              <input type="checkbox" name="tde<?= $donnees['id']; ?>" id="tde<?= $donnees['id']; ?>"> <?= '<label for="tde' . $donnees['id'] . '">' . $donnees['nom'] . '.   </label>'; ?>
              <?php
            }
            $reponse->closeCursor();
            ?>
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
        <?php
        $reponse = $bdd->query('SELECT filieres_sortie.id,
                                           filieres_sortie.timestamp,
                                           filieres_sortie.nom,
                                           filieres_sortie.description,
                                           filieres_sortie.id_type_dechet_evac,
                                           filieres_sortie.couleur,
                                           filieres_sortie.visible
                                             FROM filieres_sortie
                                               ');

        while ($donnees = $reponse->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['nom']; ?></td>
            <td><?= $donnees['description']; ?></td>
            <td><?php
              foreach (explode('a', $donnees['id_type_dechet_evac']) as $arrayElement) {
                $req2 = $bdd->prepare('SELECT nom FROM type_dechets_evac WHERE id = :id ');
                $req2->execute(['id' => $arrayElement]);
                $donnees2 = $req2->fetch();
                if ($donnees2['nom'] !== '') {
                  echo $donnees2['nom'] . ', ';
                }
                $req2->closeCursor();
              }
              ?></td>
            <td><span class="badge" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['couleur']; ?></span></td>
            <td>
              <form action="../moteur/filiere_sortie_visible.php" method="post">
                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name="visible" id="visible" value="<?= $props['visible'] === 'oui' ? 'non' : 'oui' ?>">
                <button class="btn btn-info btn-sm <?= $props['visible'] === 'oui' ? 'btn-info' : 'btn-danger' ?>"><?= $props['visible'] ?></button>
              </form>
            </td>
            <td>
              <form action="modification_filiere_sortie.php" method="post">
                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name ="nom" id="nom" value="<?= $donnees['nom']; ?>">
                <input type="hidden" name ="description" id="description" value="<?= $donnees['description']; ?>">
                <input type="hidden" name ="couleur" id="couleur" value="<?= substr($_POST['couleur'], 1); ?>">
                <input type="hidden" name ="id_type_dechet_evac" id="id_type_dechet_evac" value="<?= $donnees['id_type_dechet_evac']; ?>">
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
