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
  ?>
  <div class="container">
    <h1>Modifier la vente n° <?= $_GET['nvente']; ?></h1>
    <div class="panel-body">
      <br>
      <div class="row">

        <form action="../moteur/modification_verification_vente_post.php?nvente=<?= $_GET['nvente']; ?>" method="post">
          <input type="hidden" name ="id" id="id" value="<?= $_GET['nvente']; ?>">
          <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
          <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
          <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">
          <div class="col-md-3">

            <label for="commentaire">Commentaire:</label>

            <textarea name="commentaire" id="commentaire" class="form-control"><?php
              // On affiche le commentaire
              $reponse = $bdd->prepare('SELECT commentaire FROM ventes WHERE id = :id_vente');
              $reponse->execute(['id_vente' => $_GET['nvente']]);

              while ($donnees = $reponse->fetch()) {
                echo $donnees['commentaire'];
              }
              $reponse->closeCursor();
              ?></textarea>

          </div>

          <div class="col-md-3">

            <label for="moyen">Moyen de paiement:</label>
            <select name="moyen" id="moyen" class="form-control " required>
              <?php
              // On affiche une liste deroulante des type de collecte visibles
              $reponse = $bdd->query('SELECT * FROM moyens_paiement WHERE visible = "oui"');

              while ($donnees = $reponse->fetch()) {
                if ($_POST['moyen'] === $donnees['nom']) {  // SI on a pas de message d'erreur
                  ?>
                  <option value="<?= $donnees['id']; ?>" selected ><?= $donnees['nom']; ?></option>
                  <?php
                } else { ?>

                  <option value="<?= $donnees['id']; ?>"><?= $donnees['nom']; ?></option>
                  <?php
                }
              }
              $reponse->closeCursor();
              ?>
            </select>

          </div>
          <div class="col-md-3">

            <br>

            <button name="creer" class="btn btn-warning">Modifier</button>
            <a href="verif_vente.php?date1=<?= $_POST['date1']; ?>&date2=<?= $_POST['date2']; ?>&numero=<?= $_POST['npoint']; ?>">
              <button name="creer" class="btn btn" style="float: right;">Annuler</button>
            </a>
          </div>

        </form>
      </div>
    </div>
    <h1>Objets inclus dans cette vente</h1>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Momment de la vente</th>
          <th>Type d'objet:</th>
          <th>Objet:</th>
          <th>Quantité</th>
          <th>Prix</th>
          <th>masse</th>
          <th>Auteur de la ligne</th>
          <th></th>
          <th>Modifié par</th>
          <th>Le:</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $req = $bdd->prepare('SELECT
vendus.id ,vendus.timestamp,
type_dechets.nom type,
IF(vendus.id_objet > 0 ,grille_objets.nom, "autre") objet,
vendus.quantite ,
vendus.prix,
utilisateurs.mail
FROM
vendus, type_dechets, grille_objets ,utilisateurs
WHERE
vendus.id_vente = :id_vente
AND type_dechets.id = vendus.id_type_dechet
AND (grille_objets.id = vendus.id_objet OR vendus.id_objet = 0 )
AND utilisateurs.id = vendus.id_createur
GROUP BY id');
        $req->execute(['id_vente' => $_GET['nvente']]);

        while ($donnees = $req->fetch()) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['type']; ?></td>
            <td><?= $donnees['objet']; ?></td>

            <td><?= $donnees['quantite']; ?></td>
            <td><?= $donnees['prix']; ?></td>
            <?php
//si une masse correspond à ce vendu on l'affiche
            $masse_vendu = 0;

            $req2 = $bdd->prepare('SELECT
pesees_vendus.masse
FROM
pesees_vendus
WHERE
pesees_vendus.id_vendu = :id_vendu');
            $req2->execute(['id_vendu' => $donnees['id']]);

            while ($donnees2 = $req2->fetch()) {
              $masse_vendu = $donnees2['masse'];
            }
            $req2->closeCursor();
            2;
            ?>

            <td><?= $masse_vendu; ?></td>
            <td><?= $donnees['mail']; ?></td>

            <td><form action="modification_verification_objet.php" method="post">
                <input type="hidden" name ="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name ="nvente" id="nvente" value="<?= $_GET['nvente']; ?>">
                <input type="hidden" name ="quantite" id="quantite" value="<?= $donnees['quantite']; ?>">
                <input type="hidden" name ="prix" id="prix" value="<?= $donnees['prix']; ?>">
                <input type="hidden" name ="masse" id="masse" value="<?= $masse_vendu; ?>">
                <input type="hidden" name ="date1" id="date1" value="<?= $_POST['date1']; ?>">
                <input type="hidden" name ="date2" id="date2" value="<?= $_POST['date2']; ?>">
                <input type="hidden" name ="npoint" id="npoint" value="<?= $_POST['npoint']; ?>">

                <button  class="btn btn-warning btn-sm" >Modifier</button>
              </form>
            </td>
            <td><?php
              $req3 = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, vendus
                       WHERE  vendus.id = :id_vendu
                       AND utilisateurs.id = vendus.id_last_hero');
              $req3->execute(['id_vendu' => $donnees['id']]);

              while ($donnees3 = $req3->fetch()) { ?>

                <?= $donnees3['mail']; ?>
                <?php
              }
              $req3->closeCursor();
              3;
              ?></td>
            <td><?php
              $req3 = $bdd->prepare('SELECT vendus.last_hero_timestamp lht
                       FROM  vendus
                       WHERE  vendus.id = :id_vendu
                       ');
              $req3->execute(['id_vendu' => $donnees['id']]);

              while ($donnees3 = $req3->fetch()) {
                if ($donnees3['lht'] !== '0000-00-00 00:00:00') {
                  echo $donnees3['lht'];
                }
              }
              $req3->closeCursor();
              3;
              ?></td>

          </tr>
          <?php
        }
        $req->closeCursor();
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
