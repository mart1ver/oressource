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

namespace collecte;

use Datetime;
use PDO;

global $bdd;

require_once('../core/session.php');
require_once('../core/requetes.php');
require_once('../moteur/dbconfig.php');

session_start();

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_collecte_id($numero)) {

  require_once('tete.php');

  $point_collecte = point_collecte_id($bdd, $numero);
  $types_action = types_collectes($bdd);
  $date = new Datetime('now');
  ?>


  <div class="container">
    <div class="header-header">
      <h1><?= $point_collecte['nom'] ?></h1>
    </div>
    <div class="row">
      <div class="col-md-4 " >
        <div class="panel panel-info" >
          <div class="panel-heading">
            <h3 class="panel-title"><label id="massetot">Bon d'apport: 0 Kg.</label></h3>
          </div>
          <div class="panel-body" style="padding-top:5px">
            <form id="formulaire">
              <?php if (is_allowed_saisie_collecte() && is_allowed_edit_date()) { ?>
                <label  for="antidate">Date de l'apport: </label>
                <input type="date" id="antidate" name="antidate" style="width:120px; height:20px;" value="<?= $date->format('Y-m-d') ?>">
              <?php } ?>
              <ul class="list-group" id="transaction">  <!--start Ticket Caisse -->
                <!-- Remplis via JavaScript voir script de la page -->
              </ul> <!--end TicketCaisse -->
            </form>
          </div>
          <div class="panel-footer">
            <input type="text" form="formulaire" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire">
          </div>
        </div>
      </div>

      <div class="col-md-4"  >
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title"><label>Informations :</label></h3>
          </div>
          <div class="panel-body" style="padding-top:5px">
            <label for="id_type_action">Type de collecte:</label>
            <select name ="id_type_action" form="formulaire" id ="id_type_action" class="form-control" style="font-size: 12pt" required>
            <option value="0" disabled selected>Selectionez un type de collecte</option>
              <?php foreach ($types_action as $type_collecte) { ?>
                <option value="<?= $type_collecte['id'] ?>"><?= $type_collecte['nom'] ?></option>
              <?php } ?>
            </select>

            <label for="loc">Localité :</label>
            <select name="localite" id="loc" form="formulaire" class="form-control" style="font-size: 12pt" required>
            <option value="0" disabled selected>Selectionez une localité</option>
              <?php foreach (localites($bdd) as $localite) { ?>
                <option value="<?= $localite['id'] ?>"><?= $localite['nom'] ?></option>
              <?php } ?>
            </select>

          </div>
        </div>

        <!-- Pavee de saisie numerique vcir numpad.js -->
        <div id="numpad" class="col-md-8 col-md-offset-2" style="width: 220px;"></div>
      </div>

      <div class="col-md-4" >
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title"><label>Type d'objet:</label></h3>
          </div>
          <div class="panel-body" style="padding-top:0px">
            <div class="btn-group" id="list_item">
              <!-- Cree via JS -->
            </div>
          </div>
        </div>
        <div class="btn-group" role="group">
          <button id="encaissement" class="btn btn-success btn-lg">C'est pesé!</button>
          <button id="impression" class="btn btn-primary btn-lg" value="Print" ><span class="glyphicon glyphicon-print"></span></button>
          <button id="reset" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-refresh"></button></div>

      </div>
    </div> <!-- row -->
  </div> <!--container-->

  <script type="text/javascript">
    // Variables d'environnement de Oressource.
    'use scrict';
    window.OressourceEnv = {
      structure: <?= json_encode($_SESSION['structure']) ?>,
      adresse: <?= json_encode($_SESSION['adresse']) ?>,
      id_user: <?= json_encode($_SESSION['id'], JSON_NUMERIC_CHECK) ?>,
      id_point: <?= json_encode($numero, JSON_NUMERIC_CHECK) ?>,
      id_type_action: <?= json_encode($types_action, JSON_NUMERIC_CHECK) ?>,
      types_dechet: <?= json_encode(types_dechets($bdd), JSON_NUMERIC_CHECK) ?>,
      masse_max: <?= json_encode($point_collecte['pesee_max'], JSON_NUMERIC_CHECK) ?>,
      conteneurs: <?= json_encode(types_contenants($bdd), JSON_NUMERIC_CHECK) ?>
    };
  </script>
  <script src="../js/ticket.js" type="text/javascript"></script>
  <script src="../js/numpad.js" type="text/javascript"></script>
  <script type="text/javascript">
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
      const numpad = new NumPad(document.getElementById('numpad'),
              window.OressourceEnv.conteneurs);
      // Passer ticket en param aux evenements?
      // object global attention!

      const typesItems = window.OressourceEnv.types_dechet;
      const ticketsItem = new Ticket();
      const pushItems = connection_UI_ticket(numpad, ticketsItem, typesItems);

      const div_list_item = document.getElementById('list_item');
      typesItems.forEach((item) => {
        const button = html_saisie_item(item, pushItems);
        div_list_item.appendChild(button);
      });

      const encaisse = make_encaissement('../api/collectes.php', {items: ticketsItem});

      document.getElementById('encaissement').addEventListener('click', encaisse, false);
      document.getElementById('impression').addEventListener('click', impression_ticket, false);
      document.getElementById('reset').addEventListener('click', tickets_clear, false);

      window.tickets = [ticketsItem];
    }, false);
  </script>

  <?php
  include_once "pied.php";
} else {
  header('Location:../moteur/destroy.php?motif=1');
}
