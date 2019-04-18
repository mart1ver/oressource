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
require_once '../core/composants.php';

if (is_valid_session() && is_allowed_verifications()) {
  require_once '../moteur/dbconfig.php';

  $users = map_by(utilisateurs($bdd), 'id');

  $numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);
  $time_debut = DateTime::createFromFormat('d-m-Y', $_GET['date1'])->format('Y-m-d') . ' 00:00:00';
  $time_fin = DateTime::createFromFormat('d-m-Y', $_GET['date2'])->format('Y-m-d') . ' 23:59:59';
  $req = $bdd->prepare('SELECT
        collectes.id,
        collectes.timestamp,
        collectes.id_createur,
        collectes.id_last_hero,
        collectes.last_hero_timestamp lht,
        type_collecte.nom, collectes.commentaire,
        localites.nom localisation,
        localites.id localite,
        SUM(pesees_collectes.masse) masse
      FROM collectes
      INNER JOIN pesees_collectes
      ON collectes.id = pesees_collectes.id_collecte
      INNER JOIN type_collecte
      ON type_collecte.id = collectes.id_type_collecte
      INNER JOIN localites
      ON localites.id = collectes.localisation
      WHERE collectes.id_point_collecte = :id_point_collecte
      AND DATE(collectes.timestamp) BETWEEN :du AND :au
      GROUP BY collectes.id, collectes.commentaire, collectes.timestamp,
         collectes.last_hero_timestamp, type_collecte.nom, localites.nom,
         collectes.id_createur, collectes.id_last_hero, localites.id');
  $req->execute(['id_point_collecte' => $numero, 'du' => $time_debut, 'au' => $time_fin]);
  $collectes = $req->fetchAll(PDO::FETCH_ASSOC);

  $props = [
    'h1' => 'Vérification des collectes',
    'points' => points_collectes($bdd),
    'numero' => $numero,
    'start' => $_GET['date1'],
    'end' => $_GET['date2'],
    'endpoint' => 'verif_collecte',
    'users' => $users,
    'th1' => 'Type de collecte',
    'th3' => 'Localité',
    'th4' => 'Masse totale',
    'data' => $collectes,
    'users' => $users,
  ];

  require_once 'tete.php';
  ?>

  <div class="container" style="width:1300px">
    <?= headerVerif($props); ?>
    <?= tableVerif($props); ?>
  </div><!-- /.container -->
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>
