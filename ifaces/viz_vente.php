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

require_once '../core/requetes.php';
require_once '../core/session.php';

if (is_valid_session() && is_allowed_verifications() && $_SESSION['viz_caisse']) {
  require_once '../moteur/dbconfig.php';
  $users = map_by(utilisateurs($bdd), 'id');

  $req = $bdd->prepare('SELECT
  vendus.id,
  vendus.timestamp,
  type_dechets.nom type,
  IF(vendus.id_objet > 0, grille_objets.nom, "autre") objet,
  vendus.quantite,
  vendus.prix,
  utilisateurs.mail
  FROM
  vendus, type_dechets, grille_objets
  WHERE
  vendus.id_vente = :id_vente
  AND type_dechets.id = vendus.id_type_dechet
  AND (grille_objets.id = vendus.id_objet OR vendus.id_objet = 0)');
  $req->execute(['id_vente' => $_GET['nvente']]);
  $donnees = $req->fetchAll(PDO::FETCH_ASSOC);
  $req->closeCursor();

  require_once 'tete.php';
  ?>
  <div class="container">
    <h1>Visualiser la vente n° <?= $_GET['nvente']; ?></h1>
    <p align="right">
      <input class="btn btn-default btn-lg" type='button'name='quitter' value='Quitter' OnClick="window.close();"/></p>
    <div class="panel-body">
      <br>

    </div>
    <h1>Objets inclus dans cette vente</h1>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date de création</th>
          <th>Type d'objet:</th>
          <th>Objet:</th>
          <th>Quantité</th>
          <th>Prix</th>
          <th>Auteur de la ligne</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($donnees as $d) { ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= $donnees['type']; ?></td>
            <td><?= $donnees['objet']; ?></td>
            <td><?= $donnees['quantite']; ?></td>
            <td><?= $donnees['prix']; ?></td>
            <td><?= $users[$v['id_createur']]['mail'] ?></td>
            <td><?= $v['last_hero_timestamp'] !== $v['timestamp'] ? $users[$v['id_last_hero']]['mail'] : '' ?></td>
            <td><?= $v['last_hero_timestamp'] !== $v['timestamp'] ? $v['last_hero_timestamp'] : '' ?></td>
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
