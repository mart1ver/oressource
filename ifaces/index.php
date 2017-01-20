<?php
session_start();
require_once("../moteur/dbconfig.php");

// Vérification du renseignement du champ "id" (dans le tableau $_SESSION)
// et du fait que la variable "système" de ce même tableau a bien la valeur "oressource" avant d'afficher quoique ce soit:
if (isset($_SESSION['id']) && $_SESSION['systeme'] = "oressource") {
  include "tete_vente.php"
  ?>
  <div class="page-header">
    <div class="container">
      <h1>Bienvenue à bord d'Oressource <?php echo $_SESSION['prenom'] ?>! </h1>
      <p>Oressource est un outil libre de quantification et de mise en bilan dédié aux structures du ré-emploi</p>
    </div>
  </div>

  <div class="container" id="actualise">
    <!-- Example row of columns -->
    <div class="row">
      <div class="col-md-4" >
        <?php
        // On determine les masses collectés et evacuées ansi que le nombre d'objets vendus aujoud'hui
        $stmt = $bdd->query('SELECT COALESCE(SUM( vendus.quantite), 0.0) qv
                                      FROM vendus
                                      WHERE DATE( vendus.timestamp ) = CURDATE( )
                                      AND vendus.remboursement = 0
                                      LIMIT 1');
        $qv = $stmt->fetch()['qv'];
        $reponse = $bdd->query('SELECT COALESCE(sum(pesees_collectes.masse), 0.0) mc
                                      FROM pesees_collectes
                                      WHERE DATE(pesees_collectes.timestamp ) = CURDATE()
                                      LIMIT 1');
        $mc = $reponse->fetch()['mc'];
        $reponse = $bdd->query('SELECT COALESCE(sum(pesees_sorties.masse), 0.0) me
                                      FROM pesees_sorties
                                      WHERE DATE(pesees_sorties.timestamp ) = CURDATE()
                                      LIMIT 1');
        $me = $reponse->fetch()['me'];

        // Vérification des autorisations de l'utilisateur et des variables de session requises pour
        // l'affichage des bilans de collecte, sortie hors-boutique et bilans de vente
        $validUser = isset($_SESSION['id']) && $_SESSION['systeme'] = "oressource" && (strpos($_SESSION['niveau'], 'bi') !== false);
        ?>
        <h3>Collecté aujourd'hui: <?php echo $mc . " Kgs."; ?></h3>
        <?php if ($mc == "0.0") { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } else { ?>
          <p><div id="graphj" style="height: 180px;"></div></p>
          <?php
          if ($validUser) {
            ?>
            <p><a href=" bilanc.php?date1=<?php echo date("d-m-Y") ?>&date2=<?php echo date("d-m-Y") ?>&numero=0" class="btn btn-default"  role="button">Détails &raquo;</a></p>
            <?php
          }
        }
        ?>
      </div>
      <div class="col-md-4">
        <h3>Evacué aujourd'hui: <?php echo $me . " Kgs."; ?></h3>
        <?php if ($me == "0") { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } else { ?>
          <p><div id="grapha" style="height: 180px;"></div></p>
          <?php
          if ($validUser) {
            ?>
            <p><a class="btn btn-default" href=" bilanhb.php?date1=<?php echo date("d-m-Y") ?>&date2=<?php echo date("d-m-Y") ?>" role="button">Détails &raquo;</a></p>
            <?php
          }
        }
        ?>
      </div>
      <div class="col-md-4">
        <h3>Vendu aujourd'hui: <?php echo $qv . " Pcs."; ?></h3>
        <?php if ($qv == "0.0") { ?>
          <img src="../images/nodata.jpg" class="img-responsive" alt="Responsive image">
        <?php } else { ?>
          <p><div id="graphm" style="height: 180px;"></div></p>
          <?php
          if ($validUser) {
            ?>
            <p><a class="btn btn-default" href=" bilanv.php?date1=<?php echo date("d-m-Y") ?>&date2=<?php echo date("d-m-Y") ?>" role="button">Détails &raquo;</a></p>
            <?php
          }
        }
        ?>
      </div>
    </div>
    <hr>
  </div> <!-- /container -->

  <!-- Bootstrap core JavaScript+morris+raphael
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="../js/jquery-2.0.3.min.js"></script>
  <script src="../js/raphael.js"></script>
  <script src="../js/morris/morris.js"></script>
  <?php

  function data_graphs($sql) {
    global $bdd; // Definie dans dbconfig.php
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
    $graphm_data = array();
    $graphm_colors = array();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $data) {
      array_push($graphm_data, ['value' => $data['somme'], 'label' => $data['nom']]);
      array_push($graphm_colors, $data['couleur']);
    }
    return ['data' => $graphm_data, 'colors' => $graphm_colors];
  }

  $graphm = data_graphs('SELECT type_dechets.couleur,type_dechets.nom, sum(vendus.quantite ) somme
                  FROM type_dechets, vendus
                  WHERE type_dechets.id = vendus.id_type_dechet
                  AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
                  GROUP BY nom');

  $grapha = data_graphs('SELECT type_dechets.couleur,type_dechets.nom, sum(pesees_sorties.masse) somme
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
                      GROUP BY nom');

  $graphj = data_graphs('SELECT type_dechets.couleur, type_dechets.nom, sum(pesees_collectes.masse) somme
                  FROM type_dechets, pesees_collectes
                  WHERE type_dechets.id = pesees_collectes.id_type_dechet
                  AND DATE(pesees_collectes.timestamp) = CURDATE()
                  GROUP BY nom');
  ?>
  <script type="text/javascript">
    "use strict";
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
  include "pied.php";
} else {
  header('Location: login.php');
}