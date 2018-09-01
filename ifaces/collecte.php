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
// Oressource 2017, formulaire de collecte
// Simple formulaire de saisie des types et quantités de matériel entrant dans la structure.
// Pensé pour être fonctionnel sur ecran tactile.
// Du javascript permet l'interactivité du keypad et des boutons centraux avec le bon de collecte.

global $bdd;

require_once '../core/requetes.php';
require_once '../core/session.php';
require_once '../core/composants.php';

session_start();

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (is_valid_session() && is_allowed_collecte_id($numero)) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  $point_collecte = points_collecte_id($bdd, $numero);
  $types_action = filter_visibles(types_collectes($bdd));
  $date = new Datetime('now');
  ?>

  <div class="container">
    <div class="header-header">
      <h1><?= $point_collecte['nom']; ?></h1>
    </div>
    <div class="row">
      <?= cartList(['text' => "Bon d'apport: 0 Kg.", 'date' => $date->format('Y-m-d')]) ?>

      <div class="col-md-4"  >
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title"><label>Informations :</label></h3>
          </div>
          <div class="panel-body" style="padding-top:5px">
            <label for="id_type_action">Type de collecte:</label>
            <select name="id_type_action" form="formulaire" id="id_type_action" class="form-control" style="font-size: 12pt" required>
              <option value="" hidden disabled selected>Selectionez un type de collecte</option>
              <!-- Remplis via JavaScript voir script de la page -->
            </select>

            <label for="localite">Localité :</label>
            <select name="localite" id="localite" form="formulaire" class="form-control" style="font-size: 12pt" required>
              <option value="" hidden disabled selected>Selectionez une localité</option>
              <!-- Remplis via JavaScript voir script de la page -->
            </select>

          </div>
        </div>

        <!-- Pavee de saisie numerique vcir numpad.js -->
        <div id="numpad" class="col-md-8 col-md-offset-2" style="width: 220px;"></div>
      </div>

      <div class="col-md-4" >
        <?= listSaisie(['text' => "Type d'objet:", 'key' => 'list_item']) ?>
        <?= buttonCollectesSorties() ?>
      </div>
    </div> <!-- row -->
  </div> <!--container-->

  <script type="text/javascript">
    // Variables d'environnement de Oressource.
    'use scrict';
    window.OressourceEnv = {
      structure: <?= json_encode($_SESSION['structure']); ?>,
      adresse: <?= json_encode($_SESSION['adresse']); ?>,
      id_user: <?= json_encode($_SESSION['id'], JSON_NUMERIC_CHECK); ?>,
      id_point: <?= json_encode($numero, JSON_NUMERIC_CHECK); ?>,
      id_type_action: <?= json_encode($types_action, JSON_NUMERIC_CHECK); ?>,
      types_dechet: <?= json_encode(filter_visibles(types_dechets($bdd)), JSON_NUMERIC_CHECK); ?>,
      masse_max: <?= json_encode($point_collecte['pesee_max'], JSON_NUMERIC_CHECK); ?>,
      conteneurs: <?= json_encode(filter_visibles(types_contenants($bdd)), JSON_NUMERIC_CHECK); ?>,
      types_action: <?= json_encode($types_action, JSON_NUMERIC_CHECK); ?>,
      localites: <?= json_encode(filter_visibles(localites($bdd)), JSON_NUMERIC_CHECK); ?>,
    };
  </script>

  <script type="text/javascript">
    'use strict';
    document.addEventListener('DOMContentLoaded', () => {
      const numpad = new NumPad(document.getElementById('numpad'),
              window.OressourceEnv.conteneurs);
      const typesItems = window.OressourceEnv.types_dechet;
      const ticketItem = new Ticket();
      const pushItems = connection_UI_ticket(numpad, ticketItem, typesItems);

      fillItems(document.getElementById('list_item'), typesItems, pushItems);
      fillSelect(document.getElementById('id_type_action'), window.OressourceEnv.types_action);
      fillSelect(document.getElementById('localite'), window.OressourceEnv.localites);

      const encaisse = prepare_data({
        items: ticketItem,
      }, {classe: 'collecte'});
      initUI('../api/collectes.php', encaisse);

      window.OressourceEnv.tickets = [ ticketItem ];
    }, false);
  </script>
  <?php
  include_once 'pied.php';
} else {
  header('Location:../moteur/destroy.php?motif=1');
}
