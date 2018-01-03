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
require_once('../core/session.php');
require_once('../core/requetes.php');

if (is_valid_session()) {
  require_once 'tete.php';

  // On determine les masses collectés...
  $stmt = $bdd->query('SELECT COALESCE(SUM(vendus.quantite), 0) qv
                              FROM vendus
                              WHERE DATE(vendus.timestamp) = CURDATE()
                              AND vendus.remboursement = 0
                              LIMIT 1');
  $quantite_vendu = (int) $stmt->fetch()['qv'];
  // et masses evacuées...
  $stmt = $bdd->query('SELECT COALESCE(sum(pesees_collectes.masse), 0.0) mc
                              FROM pesees_collectes
                              WHERE DATE(pesees_collectes.timestamp) = CURDATE()
                              LIMIT 1');
  $masse_collectes = (float) $stmt->fetch()['mc'];
  // ainsi que le nombre d'objets vendus aujoud'hui.
  $stmt = $bdd->query('SELECT COALESCE(sum(pesees_sorties.masse), 0.0) ms
                              FROM pesees_sorties
                              WHERE DATE(pesees_sorties.timestamp) = CURDATE()
                              LIMIT 1');
  $masse_sorties = (float) $stmt->fetch()['ms'];

  // Vérification des autorisations de l'utilisateur et des variables de session requises pour
  // l'affichage des bilans de collecte, sortie hors-boutique et bilans de vente
  $validUser = is_allowed_bilan();


  $graphm = data_graphs(fetch_all('SELECT type_dechets.couleur, type_dechets.nom, sum(vendus.quantite) somme
                  FROM type_dechets
                  INNER JOIN vendus
                  ON type_dechets.id = vendus.id_type_dechet
                  AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
                  GROUP BY type_dechets.nom, type_dechets.couleur', $bdd));

  $grapha = data_graphs(fetch_all('SELECT type_dechets.couleur, type_dechets.nom, sum(pesees_sorties.masse) somme
                      FROM type_dechets
                      INNER JOIN pesees_sorties
                      ON type_dechets.id = pesees_sorties.id_type_dechet
                      AND DATE(pesees_sorties.timestamp) = CURDATE()
                      GROUP BY type_dechets.id, type_dechets.nom, type_dechets.couleur
                      UNION
                      SELECT types_poubelles.couleur, types_poubelles.nom, sum(pesees_sorties.masse) somme
                      FROM types_poubelles
                      INNER JOIN pesees_sorties
                      ON types_poubelles.id = pesees_sorties.id_type_poubelle
                      AND DATE(pesees_sorties.timestamp) = CURDATE()
                      GROUP BY types_poubelles.id, types_poubelles.nom, types_poubelles.couleur
                      UNION
                      SELECT type_dechets_evac.couleur, type_dechets_evac.nom, sum(pesees_sorties.masse) somme
                      FROM type_dechets_evac
                      INNER JOIN pesees_sorties
                      ON type_dechets_evac.id = pesees_sorties.id_type_dechet_evac
                      AND DATE(pesees_sorties.timestamp) = CURDATE()
                      GROUP BY type_dechets_evac.id, type_dechets_evac.nom, type_dechets_evac.couleur', $bdd));

  $graphj = data_graphs(fetch_all('SELECT type_dechets.couleur, type_dechets.nom, sum(pesees_collectes.masse) somme
                  FROM type_dechets
                  INNER JOIN pesees_collectes
                  ON type_dechets.id = pesees_collectes.id_type_dechet
                  AND DATE(pesees_collectes.timestamp) = CURDATE()
                  GROUP BY type_dechets.id, type_dechets.nom, type_dechets.couleur', $bdd));
  ?>

  <div class="page-header">
    <div class="container">
      <h1>Bienvenue à bord d'Oressource <?= $_SESSION['prenom']; ?>!</h1>
      <p>Oressource est un outil libre de quantification et de mise en bilan dédié aux structures du ré-emploi</p>
    </div>
  </div> <!-- /container -->

  <div class="container" id="actualise">
    <div class="row">
      <div class="col-md-4" >
        <h3>Collecté aujourd'hui: <?= $masse_collectes . ' Kgs.'; ?></h3>
        <?php if ($masse_collectes > 0.000) { ?>
          <div id="graphj" style="height: 180px;"></div>
          <?php if ($validUser) { ?>
            <p><a href="../ifaces/bilanc.php?date1=<?= date('d-m-Y'); ?>&date2=<?= date('d-m-Y'); ?>&numero=0" class="btn btn-default"  role="button">Détails &raquo;</a></p>
            <?php
          }
        } else { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } ?>
      </div>
      <div class="col-md-4">
        <h3>Evacué aujourd'hui: <?= $masse_sorties . ' Kgs.'; ?></h3>
        <?php if ($masse_sorties > 0.000) { ?>
          <div id="graphSortie" style="height: 180px;"></div>
          <?php if ($validUser) { ?>
            <p><a class="btn btn-default" href="../ifaces/bilanhb.php?date1=<?= date('d-m-Y'); ?>&date2=<?= date('d-m-Y'); ?>" role="button">Détails &raquo;</a></p>
            <?php
          }
        } else { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } ?>
      </div>
      <div class="col-md-4">
        <h3>Vendu aujourd'hui: <?= $quantite_vendu . ' Pcs.'; ?></h3>
        <?php if ($quantite_vendu > 0) { ?>
          <div id="graphm" style="height: 180px;"></div>
          <?php if ($validUser) { ?>
            <p><a class="btn btn-default" href="../ifaces/bilanv.php?date1=<?= date('d-m-Y'); ?>&date2=<?= date('d-m-Y'); ?>" role="button">Détails &raquo;</a></p>
            <?php
          }
        } else { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } ?>
      </div>
    </div> <!-- /row -->
  </div> <!-- /container -->

  <script type="text/javascript">
    'use strict';
    const graphj = <?= (json_encode($graphj, JSON_NUMERIC_CHECK)); ?>;
    const grapha = <?= (json_encode($grapha, JSON_NUMERIC_CHECK)); ?>;
    const graphm = <?= (json_encode($graphm, JSON_NUMERIC_CHECK)); ?>;

    // FIXME: Recuperer les donnees en AJAX au lieu de recalculer toute la page a chaque fois.
    document.addEventListener('DOMContentLoaded', () => {
      graphMorris(graphj, 'graphj');
      graphMorris(grapha, 'graphSortie');
      graphMorris(graphm, 'graphm');
      // Refresh each 300000 msec = 300 secs
      window.setTimeout(window.location.reload, 300000);
    });
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ./login.html');
}
