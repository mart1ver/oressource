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

namespace collecte;

use PDO;

global $bdd;

session_start();
if (isset($_SESSION['id']) && $_SESSION['systeme'] === "oressource"
  && (strpos($_SESSION['niveau'], 'c' . $_GET['numero']) !== false)) {
  require_once('../moteur/dbconfig.php');

  include_once "tete.php";
  // Oressource 2014, formulaire de collecte
  // Simple formulaire de saisie des types et quantités de matériel entrant dans la structure.
  // Pensé pour être fonctionnel sur ecran tactile.
  // Du javascript permet l'interactivité du keypad et des boutons centraux avec le bon de collecte.
  // On obtient la masse maximum suportée par la balance de ce point de collecte dans la variable $pesee_max
  // On obtient le nom du point de collecte designé par $GET['numero'].
  $req = $bdd->prepare('SELECT pesee_max, nom
                      FROM points_collecte
                      WHERE id = :id
                      LIMIT 1');
  $req->bindValue(':id', $_GET['numero'], PDO::PARAM_INT);
  $req->execute();
  $point_collecte = $req->fetch(PDO::FETCH_ASSOC);
  $pesee_max = (float) $point_collecte['pesee_max'];

  $reponse = $bdd->query('SELECT id, nom, couleur FROM type_dechets WHERE visible = "oui"');
  $types_dechet = $reponse->fetchAll(PDO::FETCH_ASSOC);

  $reponse = $bdd->query('SELECT id, nom FROM type_collecte WHERE visible = "oui"');
  $types_collecte = $reponse->fetchAll(PDO::FETCH_ASSOC);

  $reponse = $bdd->query('SELECT masse, nom FROM type_contenants WHERE visible = "oui"');
  $conteneurs = $reponse->fetchAll(PDO::FETCH_ASSOC);

  $reponse = $bdd->query('SELECT id, nom FROM localites WHERE visible = "oui"');
  $localites = $reponse->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <h2><?php echo($point_collecte['nom']); ?></h2>
  <div class="row">
    <div class="col-md-3 col-md-offset-2" >
      <!--startprint-->
      <div class="panel panel-info" >
        <form id="formulaire">
          <!--<legend></legend>-->
          <div class="panel-heading">
            <h3 class="panel-title"><label id="massetot">Bon d'apport: 0 Kg.</label></h3>
          </div>
          <?php if (is_allowed_saisie_collecte() && is_allowed_edit_date()) { ?>
            <label>Date de l'apport: <input type="date" id="antidate" name="antidate" style="width:130px; height:20px;" value="<?php echo date("Y-m-d"); ?>"></label>
          <?php } ?>
          <div class="panel-body">
            <ul class="list-group" id="transaction">
              <!-- Remplis via JavaScript voir script de la page -->
            </ul>
          </div>
        </form>
      </div>
      <!--endprint-->

    </div>
    <div class="col-md-2" >
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title"><label>Informations :</label></h3>
        </div>
        <div class="panel-body">
          <label for="id_type_collecte">Type de collecte:</label>
          <select name ="id_type_collecte" form="formulaire" id ="id_type_collecte" class="form-control" style="font-size: 12pt" autofocus required>
            <?php foreach ($types_collecte as $type_collecte) { ?>
              <option value="<?php echo $type_collecte['id'] ?>"><?php echo $type_collecte['nom'] ?></option>
            <?php } ?>
          </select>

          <label for="loc">Localité :</label>
          <select name="localite" id="loc" form="formulaire" class="form-control" style="font-size: 12pt" required>
            <?php foreach ($localites as $localite) { ?>
              <option value="<?php echo $localite['id'] ?>"><?php echo $localite['nom'] ?></option>
            <?php } ?>
          </select>
          <br>
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
                    <?php foreach ($conteneurs as $conteneur) { ?>
                      <!-- need style sur les boutons mais OK -->
                      <li><button onClick="submanut(<?php echo((float) $conteneur['masse']); ?>);"><?php echo $conteneur['nom']; ?></button></li>
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
          <div class="btn-group" id="list_item">
            <!-- Cree via JS -->
          </div>
        </div>
      </div>
      <div class="panel panel-info">
        <div class="panel-body">
          <input type="text" form="formulaire" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire">
        </div>
      </div>
      <button id="encaissement" class="btn btn-primary btn-lg">C'est pesé!</button>
      <button id="impression" class="btn btn-primary btn-lg" value="Print" ><span class="glyphicon glyphicon-print"></span></button>
      <button id="reset" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-refresh"></button>
    </div>
  </div>

  <script type="text/javascript">
  // Variables d'environnement de Oressource.
    'use scrict';
    window.OressourceEnv = {
      structure: <?php echo(json_encode($_SESSION['structure'])); ?>,
      adresse: <?php echo(json_encode($_SESSION['adresse'])); ?>,
      id_user: <?php echo(json_encode($_SESSION['id'], JSON_NUMERIC_CHECK)); ?>,
      saisie_collecte: <?php echo json_encode(is_allowed_saisie_collecte()); ?>,
      user_droit: <?php echo json_encode($_SESSION['niveau']); ?>,
      id_point_collecte: <?php echo json_encode(filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT), JSON_NUMERIC_CHECK); ?>,
      types_collecte: <?php echo(json_encode($types_collecte, JSON_NUMERIC_CHECK &
                    JSON_FORCE_OBJECT)) ?>,
      types_dechet: <?php echo(json_encode($types_dechet, JSON_NUMERIC_CHECK & JSON_FORCE_OBJECT)); ?>,
      masse_max: <?php echo(json_encode($point_collecte['pesee_max'], JSON_NUMERIC_CHECK)); ?>,
    };
  </script>
  <script src="../js/ticket.js" type="text/javascript"></script>
  <script src="../js/utilitaire.js" type="text/javascript"></script>
  <script src="../js/numpad.js" type="text/javascript"></script>

  <script type="text/javascript">
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
      const ul_transact = document.getElementById('transaction');
      const span_total = document.getElementById('massetot');
      const numpad = new NumPad(document.getElementById('number'));

      // Passer ticket en param aux evenements?
      // object global attention!
      window.ticket = new Ticket();
      // TODO: separer la logique de l'UI.
      function ticket_push_item() {
        const value = numpad.value;
        if (value > 0.00) {
          if (value <= window.OressourceEnv.masse_max) {
            // Update du ticket en cours.
            window.ticket.push({
              masse: value,
              type: parseInt(this.id, 10),
            });

            // Update UI pour du ticket
            const type_dechet = OressourceEnv.types_dechet[parseInt(this.id) - 1];
            ticket_update_ui(ul_transact, span_total, type_dechet, value, window.ticket.total);

            // Clear du numpad.
            numpad.reset_numpad();
          } else {
            numpad.error('Masse supérieure aux limites de pesée de la balance.');
          }
        } else {
          numpad.error('Masse entrée inférieure au poids du conteneur ou inférieure ou égale à 0.');
        }
      }

      const div_list_item = document.getElementById('list_item');
      window.OressourceEnv.types_dechet.forEach((item) => {
        const button = html_saisie_item(item, ticket_push_item);
        div_list_item.appendChild(button);
      });

      document.getElementById('encaissement').addEventListener('click', encaisse, false);
      document.getElementById('impression').addEventListener('click', impression_ticket, false);
      document.getElementById('reset').addEventListener('click', ticket_clear, false);
    }, false);
  </script>
  <?php
  include_once "pied.php";
} else {
  header('Location:../moteur/destroy.php?motif=1');
}
