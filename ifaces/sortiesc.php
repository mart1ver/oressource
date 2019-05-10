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

// Simple formulaire de saisie des matieres d'ouevres sortantes de la structure. (structures partenaires, conventiionnées)
// Doit etre fonctionnel avec un ecran tactille.

session_start();

require_once '../core/requetes.php';
require_once '../core/session.php';
require_once '../core/composants.php';

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (is_valid_session() && is_allowed_sortie_id($numero)) {
  if (!affichage_sortie_partenaires()) {
    header("Location:index.php");
    die();
  }

  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  $point_sortie = points_sorties_id($bdd, $numero);
  $conventions = filter_visibles(convention_sortie($bdd));
  $date = new Datetime('now');
  $nav = new_nav($point_sortie['nom'], $numero, 1);
  ?>

  <div class="container">
    <?= configNav($nav) ?>
    <?= cartList(['text' => "Masse totale: 0 Kg.", 'date' => $date->format('Y-m-d')]) ?>

    <div class="col-md-4">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">
            <label>Nom du partenaire:</label>
          </h3>
        </div>
        <div class="panel-body">
          <select id="id_type_action" form="formulaire" name="id_type_action" class="form-control printable" required>
            <option value="" hidden disabled selected>Selectionez un partenaire</option>
            <?php foreach ($conventions as $convention) { ?>
              <option value="<?= $convention['id']; ?>"><?= $convention['nom']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <!-- Pavee de saisie numerique vcir numpad.js -->
      <div id="numpad" class="col-md-8 col-md-offset-2" style="width: 220px;"></div>
    </div>

    <div class="col-md-4">
      <?= listSaisie(['text' => "Type d'objet:", 'key' => 'list_item']) ?>
      <?= listSaisie(['text' => 'Materiaux et déchets:', 'key' => 'list_evac']) ?>
      <?= buttonCollectesSorties() ?>
    </div><!-- .col-md-4 -->
  </div>
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
      id_type_action: <?= json_encode($conventions, JSON_NUMERIC_CHECK); ?>,
      types_dechet: <?= json_encode(filter_visibles(types_dechets($bdd), JSON_NUMERIC_CHECK)); ?>,
      masse_max: <?= json_encode($point_sortie['pesee_max'], JSON_NUMERIC_CHECK); ?>,
      types_evac: <?= json_encode(filter_visibles(types_dechets_evac($bdd), JSON_NUMERIC_CHECK)); ?>,
      conteneurs: <?= json_encode(types_contenants($bdd), JSON_NUMERIC_CHECK); ?>
    };
  </script>

  <script type="text/javascript">
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
      const numpad = new NumPad(document.getElementById('numpad'),
              window.OressourceEnv.conteneurs);

      const typesItems = window.OressourceEnv.types_dechet;
      const ticketItem = new Ticket();
      const pushItem = connection_UI_ticket(numpad, ticketItem, typesItems);
      fillItems(document.getElementById('list_item'), typesItems, pushItem);

      const typesEvacs = window.OressourceEnv.types_evac;
      const ticketEvac = new Ticket();
      const pushEvac = connection_UI_ticket(numpad, ticketEvac, typesEvacs);
      fillItems(document.getElementById('list_evac'), typesEvacs, pushEvac);

      const encaisse = prepare_data({
        items: ticketItem,
        evacs: ticketEvac,
      }, {classe: 'sortiesc'});
      initUI('../api/sorties.php', encaisse);

      window.OressourceEnv.tickets = [ ticketItem, ticketEvac ];
    }, false);
  </script>

  <?php
  require_once 'pied.php';
} else {
  header('Location:../moteur/destroy.php');
}
