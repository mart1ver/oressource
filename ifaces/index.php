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

  $validUser = is_allowed_bilan();

  $ventes = data_graphs(fetch_all('SELECT type_dechets.couleur, type_dechets.nom, sum(vendus.quantite) somme
                  FROM type_dechets
                  INNER JOIN vendus
                  ON type_dechets.id = vendus.id_type_dechet
                  AND DATE(vendus.timestamp) = CURDATE() AND vendus.prix > 0
                  GROUP BY type_dechets.nom, type_dechets.couleur', $bdd));

  $sorties = data_graphs(fetch_all('SELECT type_dechets.couleur, type_dechets.nom, sum(pesees_sorties.masse) somme
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

  $collectes = data_graphs(fetch_all('SELECT type_dechets.couleur, type_dechets.nom, sum(pesees_collectes.masse) somme
                  FROM type_dechets
                  INNER JOIN pesees_collectes
                  ON type_dechets.id = pesees_collectes.id_type_dechet
                  AND DATE(pesees_collectes.timestamp) = CURDATE()
                  GROUP BY type_dechets.id, type_dechets.nom, type_dechets.couleur', $bdd));

  $quantite_vendu = array_reduce($ventes['data'], function ($acc, $e) { return $acc + $e['value']; }, 0.0);
  $masse_sorties = array_reduce($sorties['data'], function ($acc, $e) { return $acc + $e['value']; }, 0.0);
  $masse_collectes = array_reduce($collectes['data'], function ($acc, $e) { return $acc + $e['value']; }, 0.0);
  ?>

  <!-- Script de vérification d'une nouvelle version d'Oressource -->
  <script>
  'use strict'
  // TODO: Pensez à me change a chaque publication de version
  // TODO-DO: si on a un build système change moi automatiquement.
  const user_name = `<?= $_SESSION['prenom']; ?>`;
  const current_version_number = 'v0.3.0';
  const current_version_published = new Date('2020-10-25T11:24:49Z');
  const greeting = `Bienvenue à bord d'Oressource ${current_version_number} ${user_name}!`;
  // Changement du mesage de bienvenue
  document.querySelector('#bienvenue > h1:nth-child(1)').innerHTML = greeting;

  fetch(`https://api.github.com/repos/mart1ver/oressource/releases`, {
    method: "GET"
  }).then((response) => response.json())
    .then((json) => {
      const latest = (
        json.sort((a, b) => {
        return new Date(b.published_at) - new Date(a.published_at)
        })[0]).published_at;
        const latest_date  = new Date(latest);

      if (latest_date > current_version_published) {
        const html = (
`<div class="alert alert-warning" role="alert">
  La version ${latest.tag_name} d'Oressource est disponible !
  <a href="https://github.com/mart1ver/oressource/blob/master/UPGRADE.md">Comment mettre à jour ?</a>
</div>`);
        document
          .getElementById('bienvenue')
          .insertAdjacentHTML('afterbegin', html)
      }
    });
    </script>

  <div class="page-header">
    <div class="container" id="bienvenue">
      <h1></h1>
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
    const ventes = <?= (json_encode($ventes, JSON_NUMERIC_CHECK)); ?>;
    const sorties = <?= (json_encode($sorties, JSON_NUMERIC_CHECK)); ?>;
    const collectes = <?= (json_encode($collectes, JSON_NUMERIC_CHECK)); ?>;

    // FIXME: Recuperer les donnees en AJAX au lieu de recalculer toute la page a chaque fois.
    document.addEventListener('DOMContentLoaded', () => {
      graphMorris(collectes, 'graphj');
      graphMorris(sorties, 'graphSortie');
      graphMorris(ventes, 'graphm','Pcs.');
      // Refresh each 300000 msec = 300 secs
      window.setTimeout(window.location.reload, 300000);
    });
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ./login.html');
}
