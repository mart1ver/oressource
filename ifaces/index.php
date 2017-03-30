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

require_once("../moteur/dbconfig.php");
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

  $graphm = data_graphs($bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme
                  FROM type_dechets, vendus
                  WHERE type_dechets.id = vendus.id_type_dechet
                  AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
                  GROUP BY nom'));

  $grapha = data_graphs($bdd->prepare('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_sorties.masse) somme
                      FROM type_dechets,pesees_sorties
                      WHERE type_dechets.id = pesees_sorties.id_type_dechet
                      AND DATE(pesees_sorties.timestamp) = CURDATE()
                      GROUP BY nom
                      UNION
                      SELECT types_poubelles.couleur,types_poubelles.nom, sum(pesees_sorties.masse) somme
                      FROM types_poubelles,pesees_sorties
                      WHERE types_poubelles.id = pesees_sorties.id_type_poubelle
                      AND DATE(pesees_sorties.timestamp) = CURDATE()
                      GROUP BY nom
                      UNION
                      SELECT type_dechets_evac.couleur,type_dechets_evac.nom, sum(pesees_sorties.masse) somme
                      FROM type_dechets_evac ,pesees_sorties
                      WHERE type_dechets_evac.id=pesees_sorties.id_type_dechet_evac
                      AND DATE(pesees_sorties.timestamp) = CURDATE()
                      GROUP BY nom'));

  $graphj = data_graphs($bdd->prepare('SELECT type_dechets.couleur, type_dechets.nom, sum(pesees_collectes.masse) somme
                  FROM type_dechets, pesees_collectes
                  WHERE type_dechets.id = pesees_collectes.id_type_dechet
                  AND DATE(pesees_collectes.timestamp) = CURDATE()
                  GROUP BY nom'));
  ?>

  <div class="page-header">
    <div class="container">
      <h1>Bienvenue à bord d'Oressource <?php echo $_SESSION['prenom'] ?>!</h1>
      <p>Oressource est un outil libre de quantification et de mise en bilan dédié aux structures du ré-emploi</p>
    </div>
  </div> <!-- /container -->

  <div class="container" id="actualise">
    <div class="row">
      <div class="col-md-4" >
        <h3>Collecté aujourd'hui: <?php echo $masse_collectes . " Kgs."; ?></h3>
        <?php if ($masse_collectes > 0.000) { ?>
          <div id="graphj" style="height: 180px;"></div>
          <?php if ($validUser) { ?>
            <p><a href="../ifaces/bilanc.php?date1=<?php echo date("d-m-Y") ?>&date2=<?php echo date("d-m-Y") ?>&numero=0" class="btn btn-default"  role="button">Détails &raquo;</a></p>
          <?php } ?>
        <?php } else { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } ?>
      </div>
      <div class="col-md-4">
        <h3>Evacué aujourd'hui: <?php echo $masse_sorties . " Kgs."; ?></h3>
        <?php if ($masse_sorties > 0.000) { ?>
          <div id="grapha" style="height: 180px;"></div>
          <?php if ($validUser) { ?>
            <p><a class="btn btn-default" href="../ifaces/bilanhb.php?date1=<?php echo date("d-m-Y") ?>&date2=<?php echo date("d-m-Y") ?>" role="button">Détails &raquo;</a></p>
          <?php } ?>
        <?php } else { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } ?>
      </div>
      <div class="col-md-4">
        <h3>Vendu aujourd'hui: <?php echo $quantite_vendu . " Pcs."; ?></h3>
        <?php if ($quantite_vendu > 0) { ?>
          <div id="graphm" style="height: 180px;"></div>
          <?php if ($validUser) { ?>
            <p><a class="btn btn-default" href="../ifaces/bilanv.php?date1=<?php echo date("d-m-Y") ?>&date2=<?php echo date("d-m-Y") ?>" role="button">Détails &raquo;</a></p>
          <?php } ?>
        <?php } else { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } ?>
      </div>
    </div> <!-- /row -->
  </div> <!-- /container -->

  <!-- Bootstrap core JavaScript + morris + raphael
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="../js/raphael.js"></script>
  <script src="../js/morris/morris.js"></script>
  <script type="text/javascript">
    'use strict';
    // FIXME: Recuperer les donnees en AJAX au lieu de recalculer toute la page a chaque fois.
    // Actuellement tout est recuperer via PHP a la generation de la page.
    document.addEventListener('DOMContentLoaded', () => {
      // graphj
      const graphj = <?php echo(json_encode($graphj, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT)); ?>;
      if (graphj.data.length !== 0) {
        Morris.Donut({
          element: 'graphj',
          data: graphj.data,
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: graphj.colors,
          formatter: (x) => `${x} Kg.`
        });
      }

      // graphm
      const graphm = <?php echo(json_encode($graphm, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT)); ?>;
      if (graphm.data.length !== 0) {
        Morris.Donut({
          element: 'graphm',
          data: graphm.data,
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: graphm.colors,
          formatter: (x) => `${x} pcs.`
        });
      }

      // grapha
      const grapha = <?php echo(json_encode($grapha, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT)); ?>;
      if (grapha.data.length !== 0) {
        Morris.Donut({
          element: 'grapha',
          data: grapha.data,
          backgroundColor: '#ccc',
          labelColor: '#060',
          colors: grapha.colors,
          formatter: (x) => `${x} Kg.`
        });
      }
      // Refresh each 300000 msec = 300 secs
      window.setTimeout(window.location.reload, 300000);
    });
  </script>
  <?php
  require_once "pied.php";
} else {
  header('Location: ./login.html');
}
