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

// Oressource 2014, formulaire de sorties hors-boutique
// Simple formulaire de saisie des matieres d'ouevres sortantes de la structure. (Dons)
// Doit etre fonctionnel avec un ecran tactille.
// Du javascript permet l'interactivité du keypad et des boutons centraux avec le bon de collecte

namespace sorties;

use DateTime;
use PDO;

require_once('../core/requetes.php');
require_once('../core/session.php');
require_once('../moteur/dbconfig.php');

session_start();

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_sortie_id($numero)) {
  
  if ($_SESSION['affsd'] !== "oui") {
    header("Location:sortiesd.php?numero=" . $numero);
    die();
  }
  
  require_once('tete.php');

  $point_sortie = point_sorties_id($bdd, $numero);
  $types_action = types_sorties($bdd);

  $date = new Datetime('now');
  ?>


    <h2 class="ui-title"><?php echo($point_sortie['nom']); ?></h2>
      
    <div class="row">
      <div class="col-md-7 col-md-offset-1" >
        <ul class="nav nav-tabs">
          <?php if ($_SESSION['affsp'] === "oui") { ?><li><a href="sortiesp.php?numero=<?php echo $numero ?>">Poubelles</a></li><?php } ?>
          <?php if ($_SESSION['affss'] === "oui") { ?><li><a href="sortiesc.php?numero=<?php echo $numero ?>">Sorties partenaires</a></li><?php } ?>
          <?php if ($_SESSION['affsr'] === "oui") { ?><li><a href="sortiesr.php?numero=<?php echo $numero ?>">Recyclage</a></li><?php } ?>
          <?php if ($_SESSION['affsd'] === "oui") { ?><li class="active"><a>Don</a></li><?php } ?>
          <?php if ($_SESSION['affsde'] == "oui") { ?><li><a href="sortiesd.php?numero=<?php echo $numero ?>">Déchetterie</a></li><?php } ?>
          </ul>
        </div>
    </div>

    <div class="panel-body">
      <div class="row">
          <div class="col-md-3 col-md-offset-2">
            <div class="panel panel-info" >
              <div class="panel-heading">
                <h3 class="panel-title"><label id="massetot">Bon de sortie hors-boutique: 0 Kg.</label></h3>
              </div>
              <div class="panel-body">
                <form id="formulaire">
                  <?php if (is_allowed_edit_date()) { ?>
                    <label for="antidate">Date de la sortie: </label>
                    <input type="date" id="antidate" name="antidate" style="width:130px; height:20px;" value="<?php echo($date->format('Y-m-d')); ?>">
                  <?php } ?>
                  <ul class="list-group" id="transaction">  <!--start Ticket Caisse -->
                    <!-- Remplis via JavaScript voir script de la page -->
                  </ul> <!--end TicketCaisse -->
                </form>
              </div>
            </div>
          </div>

    <div class="col-md-2" >
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title"><label>Informations :</label></h3>
        </div>
        <div class="panel-body">
          <label for="id_type_action">Type de collecte:</label>
            <select name="id_type_action" form="formulaire" id="type_action" class="form-control" style="font-size: 12pt" required>
            <?php foreach ($types_action as $type_action) { ?>
              <option value="<?php echo $type_action['id'] ?>"><?php echo $type_action['nom'] ?></option>
            <?php } ?>
          </select>
          <label for="loc">Localité :</label>
          <select name="localite" id="loc" form="formulaire" class="form-control" style="font-size: 12pt" required>
            <?php foreach (localites($bdd) as $localite) { ?>
              <option value="<?php echo $localite['id'] ?>"><?php echo $localite['nom'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <!-- Pavee de saisie numerique. -->
      <div class="col-md-3" style="width: 220px;" >
        <div class="panel panel-info">
          <div class="panel-body">
            <div class="row">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Masse" id="number" name="num" style="margin-left:8px;">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style=" margin-right:8px;">
                    <span class="glyphicon glyphicon-minus"></span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <!-- need style sur les boutons mais OK -->
                    <?php foreach (types_contenants($bdd) as $conteneur) { ?>
                      <li><button onclick="submanut(<?php echo((float) $conteneur['masse']); ?>);"><?php echo $conteneur['nom']; ?></button></li>
                    <?php } ?>
                  </ul>
                </div><!-- /btn-group -->
              </div><!-- /input-group -->
            </div>

            <div class="row">
              <button class="btn btn-default btn-lg" onclick="number_write('1');" data-value="1" style="margin-left:8px; margin-top:8px;">1</button>
              <button class="btn btn-default btn-lg" onclick="number_write('2');" data-value="2" style="margin-left:8px; margin-top:8px;">2</button>
              <button class="btn btn-default btn-lg" onclick="number_write('3');" data-value="3" style="margin-left:8px; margin-top:8px;">3</button>
            </div>
            <div class="row">
              <button class="btn btn-default btn-lg" onclick="number_write('4');" data-value="4" style="margin-left:8px; margin-top:8px;">4</button>
              <button class="btn btn-default btn-lg" onclick="number_write('5');" data-value="5" style="margin-left:8px; margin-top:8px;">5</button>
              <button class="btn btn-default btn-lg" onclick="number_write('6');" data-value="6" style="margin-left:8px; margin-top:8px;">6</button>
            </div>
            <div class="row">
              <button class="btn btn-default btn-lg" onclick="number_write('7');" data-value="7" style="margin-left:8px; margin-top:8px;">7</button>
              <button class="btn btn-default btn-lg" onclick="number_write('8');" data-value="8" style="margin-left:8px; margin-top:8px;">8</button>
              <button class="btn btn-default btn-lg" onclick="number_write('9');" data-value="9" style="margin-left:8px; margin-top:8px;">9</button>
            </div>
            <div class="row">
              <button class="btn btn-default btn-lg" onclick="number_clear();" data-value="C" style="margin-left:8px; margin-top:8px;">C</button>
              <button class="btn btn-default btn-lg" onclick="number_write('0');" data-value="0" style="margin-left:8px; margin-top:8px;">0</button>
              <button class="btn btn-default btn-lg" onclick="number_write('.');" data-value="," style="margin-left:8px; margin-top:8px;">,</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3" >
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title"><label>Type d'objet:</label></h3>
        </div>
        <div class="panel-body">
          <div id="list_item" class="btn-group" >
            <!-- Cree via JS -->
          </div>
        </div>
      </div>
      <div class="panel panel-info">
        <div class="panel-body">
          <input type="text" form="formulaire" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire">
        </div>

      </div>
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title"><label>Materiaux et déchets:</label></h3>
        </div>
        <div class="panel-body">
          <div id="list_evac" class="btn-group">
            <!-- Rempli via JS -->
          </div>
        </div>
      </div>
      <button id="encaissement" class="btn btn-primary btn-lg">C'est pesé!</button>
      <button id="impression" class="btn btn-primary btn-lg" value="Print" ><span class="glyphicon glyphicon-print"></span></button>
      <button id="reset" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-refresh"></button>
    </div> <!-- col-md-3 -->
    </div> <!-- row -->
  </div> <!--class="pannel-body"-->

  <script type="text/javascript">
    // Variables d'environnement de Oressource.
    'use scrict';
    window.OressourceEnv = {
      structure: <?php echo(json_encode($_SESSION['structure'])); ?>,
      adresse: <?php echo(json_encode($_SESSION['adresse'])); ?>,
      id_user: <?php echo(json_encode($_SESSION['id'], JSON_NUMERIC_CHECK)); ?>,
      saisie_collecte: <?php echo json_encode(is_allowed_saisie_collecte()); ?>,
      user_droit: <?php echo json_encode($_SESSION['niveau']); ?>,
      id_point: <?php echo json_encode($numero, JSON_NUMERIC_CHECK); ?>,
      id_type_action: <?php echo(json_encode($types_action, JSON_NUMERIC_CHECK & JSON_FORCE_OBJECT)); ?>,
      types_dechet: <?php echo(json_encode(types_dechets($bdd), JSON_NUMERIC_CHECK & JSON_FORCE_OBJECT)); ?>,
      masse_max: <?php echo(json_encode($point_sortie['pesee_max'], JSON_NUMERIC_CHECK)); ?>,
      types_evac: <?php echo(json_encode(types_dechets_evac($bdd), JSON_NUMERIC_CHECK)); ?>
    };
  </script>
  <script src="../js/ticket.js" type="text/javascript"></script>
  <script src="../js/numpad.js" type="text/javascript"></script>
  <script type="text/javascript">
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
      const numpad = new NumPad(document.getElementById('number'));

      // Hack en attendant de trouver une solution pour gerer differament les dechets
      // et les objets qui ont les memes id...
      // On retourne une closure avec connection_UI_ticket du coup...

      const typesItems = window.OressourceEnv.types_dechet;
      const ticketItems = new Ticket();
      const pushItem = connection_UI_ticket(numpad, ticketItems, typesItems );

      const div_list_item = document.getElementById('list_item');
      typesItems.forEach((item) => {
        const button = html_saisie_item(item, pushItem);
        div_list_item.appendChild(button);
      });

      const typesEvacs = window.OressourceEnv.types_evac;
      const ticketEvac = new Ticket();
      const pushEvac = connection_UI_ticket(numpad, ticketEvac, typesEvacs);

      const div_list_evac = document.getElementById('list_evac');
      typesEvacs.forEach((item) => {
        const button = html_saisie_item(item, pushEvac);
        div_list_evac.appendChild(button);
      });

      const metadata = { classe: 'sortie' };
      const encaisse = make_encaissement('../api/sorties.php', {
        items: ticketItems,
        evacs: ticketEvac,
      }, metadata);

      document.getElementById('encaissement').addEventListener('click', encaisse, false);
      document.getElementById('impression').addEventListener('click', impression_ticket, false);
      document.getElementById('reset').addEventListener('click', tickets_clear, false);

      window.tickets = [ ticketItems, ticketEvac ];
    }, false);
  </script>

  <?php
  include "pied.php";
} else {
  header('Location:../moteur/destroy.php');
}
