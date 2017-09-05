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

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (is_valid_session() && is_allowed_sortie_id($numero)) {
  if (!affichage_sortie_poubelle()) {
    header("Location:sortiesc.php?numero=" . $numero);
    die();
  }

  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  $point_sortie = points_sorties_id($bdd, $numero);
  $pesee_max = (float) $point_sortie['pesee_max'];
  $types_poubelles = types_poubelles($bdd);
  $date = new Datetime('now');
  ?>

  <div class="container">

    <nav class="navbar">
      <div class="header-header">
        <h1><?= $point_sortie['nom']; ?></h1>
      </div>
      <ul class="nav nav-tabs">
        <li class="active"><a href="#">Poubelles</a></li>
        <?php if (affichage_sortie_partenaires()) { ?><li><a href="sortiesc.php?numero=<?= $numero; ?>">Sorties partenaires</a></li><?php } ?>
        <?php if (affichage_sortie_recyclage()) { ?><li><a href="sortiesr.php?numero=<?= $numero; ?>">Recyclage</a></li><?php } ?>
        <?php if (affichage_sortie_don()) { ?><li><a href="sorties.php?numero=<?= $numero; ?>">Don</a></li><?php } ?>
        <?php if (affichage_sortie_dechetterie()) { ?><li><a href="sortiesd.php?numero=<?= $numero; ?>">Déchetterie</a></li><?php } ?>
      </ul>
    </nav>

    <?= cartList(['text' => "Masse totale: 0 Kg.", 'date' => $date->format('Y-m-d')]) ?>

    <div class="col-md-4">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">
            <label>Bacs de sortie des poubelles:</label>
          </h3>
        </div>
        <div class="panel-body">
          <div id="list_poubelle" class="btn-group">
            <!-- Rempli via JS -->
          </div>
        </div>
      </div>

      <div class="btn-group" role="group">
        <button id="encaissement" class="btn btn-success btn-lg">C'est pesé!</button>
        <button id="impression" class="btn btn-primary btn-lg" value="Print"><span class="glyphicon glyphicon-print"></span></button>
        <button id="reset" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-refresh"></button>
      </div>
    </div> <!-- .col-md-4 -->

  </div> <!-- .container -->
  <script type="text/javascript">
    // Variables d'environnement de Oressource.
    'use scrict';
    window.OressourceEnv = {
      structure: <?= json_encode($_SESSION['structure']); ?>,
      adresse: <?= json_encode($_SESSION['adresse']); ?>,
      id_user: <?= json_encode($_SESSION['id'], JSON_NUMERIC_CHECK); ?>,
      saisie_collecte: <?= json_encode(is_allowed_saisie_date()); ?>,
      user_droit: <?= json_encode($_SESSION['niveau']); ?>,
      id_point: <?= json_encode($numero, JSON_NUMERIC_CHECK); ?>,
      masse_max: <?= json_encode($point_sortie['pesee_max'], JSON_NUMERIC_CHECK); ?>,
      types_evac: <?= json_encode(types_poubelles($bdd), JSON_NUMERIC_CHECK); ?>
    };
  </script>
  <script type="text/javascript">
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
      const numpad = new NumPad(document.getElementById('numpad'), [ ]);

      // Hack en attendant de trouver une solution pour gerer differament les dechets
      // et les objets qui ont les memes id...
      // On retourne une closure avec connection_UI_ticket du coup...
      const typesEvacs = window.OressourceEnv.types_evac;
      const ticketEvac = new Ticket();
      // TODO prendre en compte la masse des bacs!!
      const sub_masse_poubelle = (masse, masse_poubelle) => {
        return masse - masse_poubelle;
      }
      const pushEvac = connection_UI_ticket(numpad, ticketEvac, typesEvacs, sub_masse_poubelle);

      const div_list_evac = document.getElementById('list_poubelle');
      const fragment = document.createDocumentFragment();
      typesEvacs.forEach(({ id, nom, couleur, masse_bac }) => {
        const button = document.createElement('button');
        button.setAttribute('id', id);
        button.setAttribute('class', 'btn btn-default');
        button.setAttribute('style', 'padding: 8px 3px 8px 3px; margin: 4px 0px 4px 2px');
        button.innerHTML = `<span class="badge" id="cool" style="background-color:${couleur}">${nom}: ${masse_bac}kg</span>`;
        button.addEventListener('click', pushEvac, false);
        fragment.appendChild(button);
      });
      div_list_evac.appendChild(fragment);

      const metadata = { classe: 'sortiesp' };
      const encaisse = make_encaissement('../api/sorties.php', {
        evacs: ticketEvac
      }, metadata);

      document.getElementById('encaissement').addEventListener('click', encaisse, false);
      document.getElementById('impression').addEventListener('click', impression_ticket, false);
      document.getElementById('reset').addEventListener('click', () => {
        tickets_clear(metadata);
      }, false);

      window.tickets = [ ticketEvac ];
    }, false);
  </script>
  <?php
  require_once 'pied.php';
} else {
  header('Location:../moteur/destroy.php');
}
?>
