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

require_once '../core/session.php';
require_once '../core/validation.php';
require_once '../core/requetes.php';
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_verifications()) {

  require_once('../moteur/dbconfig.php');
  $points_ventes = filter_visibles(points_ventes($bdd));
  $date1 = $_GET['date1'];
  $date2 = $_GET['date2'];
  $numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);
  $time_debut = DateTime::createFromFormat('d-m-Y', $date1)->format('Y-m-d') . ' 00:00:00';
  $time_fin = DateTime::createFromFormat('d-m-Y', $date2)->format('Y-m-d') . ' 23:59:59';

  $users = map_by(utilisateurs($bdd), 'id');

  $req = $bdd->prepare('SELECT
    ventes.id,
    ventes.id_createur,
    ventes.last_hero_timestamp,
    ventes.timestamp,
    ventes.id_last_hero,
    ventes.commentaire,
    moyens_paiement.nom moyen,
    moyens_paiement.couleur,
    SUM(' . vendus_case_lot_unit(). ') vente,
    SUM(vendus.quantite) quantite,
    SUM(vendus.remboursement * vendus.quantite) remb,
    SUM(pesees_vendus.masse) masse
  FROM ventes
  INNER JOIN moyens_paiement
  ON ventes.id_moyen_paiement = moyens_paiement.id
  INNER JOIN vendus
  ON vendus.id_vente = ventes.id
  LEFT JOIN pesees_vendus
  ON pesees_vendus.id = vendus.id
  WHERE ventes.id_point_vente = :id_point_vente
  AND DATE(ventes.timestamp) BETWEEN :du AND :au
  GROUP BY ventes.id,
    ventes.id_createur,
    ventes.last_hero_timestamp,
    ventes.timestamp,
    ventes.id_last_hero,
    ventes.commentaire,
    moyens_paiement.nom,
    moyens_paiement.couleur');
  $req->bindParam(':id_point_vente', $numero, PDO::PARAM_INT);
  $req->bindParam(':du', $time_debut, PDO::PARAM_STR);
  $req->bindParam(':au', $time_fin, PDO::PARAM_STR);
  $req->execute();
  $data = $req->fetchAll(PDO::FETCH_ASSOC);

  require_once 'tete.php';
  ?>

  <div class="container">
    <h1>verification des ventes</h1>
    <div class="panel-body">
      <ul class="nav nav-tabs">
        <?php foreach ($points_ventes as $point) { ?>
          <li <?= $numero === $point['id'] ? 'class="active"' : '' ?>>
            <a href="verif_vente.php?numero=<?= $point['id'] ?>&date1=<?= $date1 ?>&date2=<?= $date2 ?>"><?= $point['nom']; ?></a>
          </li>
        <?php } ?>
      </ul>

      <br>
      <div class="row">
        <?= datePicker() ?>
        <?= $date1 === $date2 ? " Le {$date1}," : " Du {$date1} au {$date2}," ?>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Momment de la vente</th>
          <th>Crédit</th>
          <th>Débit</th>
          <th>Nombre d'objets</th>
          <th>Moyen de paiement</th>
          <th>Masse pesée</th>
          <th>Commentaire</th>
          <th>Auteur</th>
          <th>Modifié par</th>
          <th></th>
          <th style="width:100px">Le</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($data as $v) {
          $ventes = $v['vente'];
          $remboursements = $v['remb'];
          $quantite = $v['quantite'];
          $masse = $v['masse'];
          $rembo = ($remboursements > 0.00);
          ?>
          <tr>
            <td>
              <span <?= $rembo
                ? '(class="badge" style="background-color:red"'
                : '' ?>><?= $v['id'] ?></span>
            </td>
            <td><?= $v['timestamp']; ?></td>
            <td><?= $ventes ?></td>
            <td><?= $remboursements ?></td>
            <td><?= $quantite ?></td>
            <td>
              <span class="badge" style="background-color:<?= $v['couleur']; ?>"><?= $v['moyen']; ?></span>
            </td>
            <td><?= $masse > 0 ? $masse : '' ?></td>
            <td style="width:100px"><?= $v['commentaire']; ?></td>
            <td><?= $users[$v['id_createur']]['mail'] ?></td>
            <td>
              <form action="modification_verification_<?= $rembo ? 'remboursement' : 'vente' ?>.php?nvente=<?= $v['id']; ?>"
                    method="post">
                <input type="hidden" name="moyen" value="<?= $v['moyen']; ?>">
                <input type="hidden" name="id" value="<?= $v['id']; ?>">
                <input type="hidden" name="date1" value="<?= $date1 ?>">
                <input type="hidden" name="date2" value="<?= $date2; ?>">
                <input type="hidden" name="npoint" value="<?= $numero; ?>">
                <button class="btn btn-warning btn-sm">Modifier</button>
              </form>
            </td>
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
