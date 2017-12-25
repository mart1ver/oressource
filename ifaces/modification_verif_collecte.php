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
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'h') !== false)) {
  require_once 'tete.php';

    $users = array_reduce(utilisateurs($bdd), function ($acc, $e) {
    $acc[$e['id']] = $e;
    return $acc;
  }, []);
  $types_collectes = $bdd->query('SELECT * FROM type_collecte WHERE visible = "oui"')->fetchAll(PDO::FETCH_ASSOC);
  $reponse = $bdd->query('SELECT * FROM localites WHERE visible = "oui"');
  $reponse = $bdd->prepare('SELECT commentaire FROM collectes WHERE id = :id_collecte');
  $reponse->execute(['id_collecte' => $_POST['id']]);
  $commentaire = $reponse->fetch(PDO::FETCH_ASSOC)['commentaire'];

  $req = $bdd->prepare('SELECT
      pesees_collectes.id,
      pesees_collectes.masse,
      pesees_collectes.timestamp,
      pesees_collectes.last_hero_timestamp lht,
      pesees_collectes.id_createur,
      pesees_collectes.id_last_hero,
      type_dechets.nom,
      type_dechets.couleur
    FROM collectes
    INNER JOIN pesees_collectes
    ON pesees_collectes.id_collecte = :id_collecte
    INNER JOIN type_dechets
    ON type_dechets.id = pesees_collectes.id_type_dechet
    GROUP BY pesees_collectes.id,
      pesees_collectes.masse,
      pesees_collectes.timestamp,
      pesees_collectes.last_hero_timestamp,
      pesees_collectes.id_createur,
      pesees_collectes.id_last_hero,
      type_dechets.nom,
      type_dechets.couleur');
  $req->execute(['id_collecte' => $_POST['id']]);
  $pesees_collectes = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();
  ?>
  <div class="container">
    <h1>Modifier la collecte n° <?= $_POST['id'] ?></h1>
    <div class="panel-body">
      <br>
      <div class="row">
        <form action="../moteur/modification_verification_collecte_post.php" method="post">
          <input type="hidden" name="id" id="id" value="<?= $_POST['id']; ?>">
          <input type="hidden" name="date1" id="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name="date2" id="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">
          <div class="col-md-3">
            <label for="id_type_collecte">Type de collecte:</label>
            <select name="id_type_collecte" id="id_type_collecte" class="form-control " required>
              <?php foreach ($types_collectes as $donnees) { ?>
                <option value="<?= $donnees['id']; ?>" <?= $_POST['nom'] === $donnees['nom'] ? 'selected' : '' ?>><?= $donnees['nom']; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="col-md-3">
            <label for="id_localite">Localisation:</label>
            <select name="id_localite" id="id_localite" class="form-control " required>
              <?php foreach ($types_collectes as $donnees) { ?>
                <option value="<?= $donnees['id']; ?>" <?= $_POST['localisation'] === $donnees['nom'] ? 'selected' : '' ?>><?= $donnees['nom']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="commentaire">Commentaire</label>
            <textarea name="commentaire" id="commentaire" class="form-control"><?= $commentaire ?></textarea>
          </div>
          <div class="col-md-3">
            <br>
            <button name="creer" class="btn btn-warning">Modifier</button>
          </div>
        </form>
      </div>
    </div>

    <h2>Pesées incluses dans cette collecte</h2>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Moment de la collecte</th>
          <th>Type de déchet:</th>
          <th>Masse</th>
          <th>Auteur de la ligne</th>
          <th></th>
          <th>Modifié par</th>
          <th>Le:</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pesees_collectes as $d) { ?>
          <tr>
            <td><?= $d['id']; ?></td>
            <td><?= $d['timestamp']; ?></td>
            <td><span class="badge" id="cool" style="background-color:<?= $d['couleur']; ?>"><?= $d['nom']; ?></span></td>
            <td><?= $d['masse']; ?></td>
            <td><?= $users[$d['id_createur']]['mail']; ?></td>
            <td>
              <form action="modification_verification_pesee.php" method="post">
                <input type="hidden" name="id" id="id" value="<?= $d['id']; ?>">
                <input type="hidden" name="nomtypo" id="nomtypo" value="<?= $d['nom']; ?>">
                <input type="hidden" name="ncollecte" id="ncollecte" value="<?= $_POST['id'] ?>">
                <input type="hidden" name="masse" id="masse" value="<?= $d['masse']; ?>">
                <input type="hidden" name="date1" id="date1" value="<?= $_POST['date1']; ?>">
                <input type="hidden" name="date2" id="date2" value="<?= $_POST['date2']; ?>">
                <input type="hidden" name="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">
                <button class="btn btn-warning btn-sm">Modifier</button>
              </form>
            </td>
            <td><?= $d['lht'] !== $d['timestamp'] ? $users[$d['id_last_hero']]['mail'] : '' ?></td>
            <td><?= $d['lht'] !== $d['timestamp'] ? $donnees['lht'] : '' ?></td>
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
