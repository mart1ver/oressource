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

require_once('../core/session.php');
require_once('../core/validation.php');
require_once('../core/requetes.php');

if (is_valid_session() && is_allowed_verifications()) {
  require_once 'tete.php';
  require_once('../moteur/dbconfig.php');
  $points_ventes = filter_visibles(points_ventes($bdd));
  $date1 = $_GET['date1'];
  $date2 = $_GET['date2'];

  $date1ft = DateTime::createFromFormat('d-m-Y', $date1);
  $time_debut = $date1ft->format('Y-m-d');
  $time_debut = $time_debut . ' 00:00:00';

  $date2ft = DateTime::createFromFormat('d-m-Y', $date2);
  $time_fin = $date2ft->format('Y-m-d');
  $time_fin = $time_fin . ' 23:59:59';

  $req = $bdd->prepare('SELECT ventes.id,ventes.timestamp ,moyens_paiement.nom moyen, moyens_paiement.couleur coul, ventes.commentaire ,ventes.last_hero_timestamp lht
                       FROM ventes, moyens_paiement
                       WHERE ventes.id_point_vente = :id_point_vente
                       AND ventes.id_moyen_paiement = moyens_paiement.id
                       AND DATE(ventes.timestamp) BETWEEN :du AND :au ');
  $req->execute(['id_point_vente' => $_GET['numero'], 'du' => $time_debut, 'au' => $time_fin]);
  $data = $req->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <div class="container">
    <h1>verification des ventes</h1>
    <div class="panel-body">
      <ul class="nav nav-tabs">
        <?php foreach ($points_ventes as $point) { ?>
          <li<?= $_GET['numero'] === $point['id'] ? 'class="active"' : '' ?>>
            <a href="<?= "verif_vente.php?numero={$point['id']}&date1={$date1}&date2={$date2}" ?>"><?= $point['nom']; ?></a>
          </li>
        <?php } ?>
      </ul>

      <br>
      <div class="row">
        <div class="col-md-3 col-md-offset-9" >
          <label for="reportrange">Choisissez la période à inspecter::</label><br>
          <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
            <i class="fa fa-calendar"></i>
            <span></span><b class="caret"></b>
          </div>
        </div>
        <?= $date1 === $date2 ? "le '{$date1['date1']}:" : "du {$date1} au  {$date1}:" ?>
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
        <?php foreach ($data as $donnees) {
          $req_ventes = $bdd->prepare('SELECT SUM(vendus.prix * vendus.quantite) vente
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente');
          $req_ventes->execute(['id_vente' => $donnees['id']]);
          $ventes = $req_ventes->fetch()['vente'];
          $req_ventes->closeCursor();

          $req_remb = $bdd->prepare('SELECT SUM(vendus.remboursement * vendus.quantite) remb
                       FROM vendus
                       WHERE vendus.id_vente = :id_vente
                       ');
          $req_remb->execute(['id_vente' => $donnees['id']]);
          $remboursements = $req_remb->fetch()['remb'];
          $req_remb->closeCursor();

          $req_quantite = $bdd->prepare('SELECT SUM(vendus.quantite) quantite
                       FROM vendus
                       WHERE  vendus.id_vente = :id_vente');
          $req_quantite->execute(['id_vente' => $donnees['id']]);
          $quantite = $req_quantite->fetch()['quantite'];
          $req_quantite->closeCursor();

          $req_masse = $bdd->prepare('SELECT SUM(pesees_vendus.masse) masse
                       FROM vendus,pesees_vendus
                       WHERE vendus.id_vente = :id_vente
                       AND pesees_vendus.id_vendu =  vendus.id');
          $req_masse->execute(['id_vente' => $donnees['id']]);
          $masse = $req_masse->fetch()['masse'];
          $req_masse->closeCursor();

          $req_createur = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, ventes
                       WHERE  ventes.id = :id_vente
                       AND utilisateurs.id = ventes.id_createur');
          $req_createur->execute(['id_vente' => $donnees['id']]);
          $createur = $req_createur->fetch();
          $req_createur->closeCursor();
          $req_editeur = $bdd->prepare('SELECT utilisateurs.mail mail
                       FROM utilisateurs, ventes
                       WHERE  ventes.id = :id_vente
                       AND utilisateurs.id = ventes.id_last_hero');
          $req_editeur->execute(['id_vente' => $donnees['id']]);
          $editeur = $req_editeur->fetch();
          $req_editeur->closeCursor();

          $rembo = $ventes > 0 && $remboursements > 0;
          ?>
          <tr>
            <td><?= $donnees['id']; ?></td>
            <td><?= $donnees['timestamp']; ?></td>
            <td><?= !$rembo ? $ventes : '' ?></td>
            <td><?= $rembo ? $remboursements : '' ?></td>
            <td><?= $quantite ?></td>
            <td>
              <span class="badge" style="background-color:<?= $donnees['coul']; ?>"><?= $donnees['moyen']; ?></span>
            </td>
            <td><?= $masse > 0 ? $masse : '' ?></td>
            <td style="width:100px"><?= $donnees['commentaire']; ?></td>
            <td><?= $createur['mail']; ?></td>
            <td>
              <form action="modification_verification_<?= $rembo ? 'remboursement' : 'vente' ?>.php?nvente=<?= $donnees['id']; ?>"
                    method="post">
                <input type="hidden" name="moyen" id="moyen" value="<?= $donnees['moyen']; ?>">
                <input type="hidden" name="id" id="id" value="<?= $donnees['id']; ?>">
                <input type="hidden" name="date1" id="date1" value="<?= $date1 ?>">
                <input type="hidden" name="date2" id="date2" value="<?= $date2; ?>">
                <input type="hidden" name="npoint" id="npoint" value="<?= $_GET['numero']; ?>">
                <button class="btn btn-warning btn-sm" >Modifier</button>
              </form>
            </td>
            <td><?= $editeur['mail']; ?></td>
            <td><?= $donnees['lht'] !== '0000-00-00 00:00:00' ? $donnees['lht'] : '' ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div><!-- /.container -->
  <script type="text/javascript">
    'use strict';
    $(document).ready(() => {
      const get = process_get();
      const options = set_datepicker(get, url);
      const url = 'verif_vente';
      bind_datepicker(options, get, url);
    });
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
