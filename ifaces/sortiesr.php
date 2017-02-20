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

require_once('../core/requetes.php');
require_once('../core/session.php');
require_once('../moteur/dbconfig.php');

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_sortie_id($numero)) {

  require_once('tete.php');

  if ($_SESSION['affsr'] !== "oui") {
    header("Location:sorties.php?numero=" . $numero);
    die();
  }

  $point_sortie = point_sorties_id($bdd, $numero);
  $pesee_max = (float) $point_sortie['pesee_max'];
  $filieres_sorties = filieres_sorties($bdd);

  $evacs = types_dechets_evac($bdd);

  $date = new Datetime('now');
  ?>

  <div class="panel-body">
    <fieldset>
      <legend><?php echo ($point_sortie['nom']); ?></legend>
    </fieldset>

    <div class="row">
      <div class="col-md-7 col-md-offset-1" >
        <ul class="nav nav-tabs">
          <?php if ($_SESSION['affsp'] === "oui") { ?><li><a href="sortiesp.php?numero=<?php echo $numero; ?>">Poubelles</a></li><?php } ?>
          <?php if ($_SESSION['affss'] === "oui") { ?><li><a href="sortiesc.php?numero=<?php echo $numero; ?>">Sorties partenaires</a></li><?php } ?>
          <?php if ($_SESSION['affsr'] === "oui") { ?><li class="active"><a>Recyclage</a></li><?php } ?>
          <?php if ($_SESSION['affsd'] === "oui") { ?><li><a href="sorties.php?numero=<?php echo $numero; ?>">Don</a></li><?php } ?>
          <?php if ($_SESSION['affsde'] === "oui") { ?><li><a href="sortiesd.php?numero=<?php echo $numero; ?>">Déchetterie</a></li><?php } ?>
        </ul>
      </div>
    </div>

      <div class="panel-body">
        <div class="row">
          <div class="col-md-3 col-md-offset-2">
            <div class="panel panel-info" >
              <div class="panel-heading">
                <h3 class="panel-title">
                  <label id="massetot">Bon de sortie hors-boutique: 0 Kg.</label>
                </h3>
              </div>
              <div class="panel-body">
                <form id="formulaire"> <!-- ONSUBMIT="EnableControl(true)" -->
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

          <div class="col-md-3">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">
                  <label for="sel_filiere">Nom de l'entreprise de recyclage:</label>
                </h3>
              </div>
              <div class="panel-body">
                <select id="sel_filiere" form="formulaire" name="sel_filiere" class="form-control" required>
                  <option value = "0" disabled selected></option>
                  <?php foreach ($filieres_sorties as $filiere_sortie) { ?>
                    <option value="<?php echo $filiere_sortie['id']; ?>|<?php echo $filiere_sortie['type_dechet']; ?>"><?php echo $filiere_sortie['nom']; ?></option>
                  <?php } ?>
                </select>
                <input type="text" class="form-control" name="commentaireini" id="commentaireini" placeholder="Commentaire">
              </div>
            </div>

            <div class="col-md-3" style="width: 220px;" >
              <div class="panel panel-info">
                <div class="panel-body">
                  <div class="row">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Masse" id="number" name="num" style="margin-left:8px;">
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="margin-right:8px;">
                          <span class="glyphicon glyphicon-minus"></span>
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                          <?php foreach (types_contenants($bdd) as $conteneur) { ?>
                            <li>
                              <a href="#" onClick="submanut('<?php echo $conteneur['masse']; ?>');"><?php echo $conteneur['nom']; ?></a>
                            </li>
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
                <h3 class="panel-title"><label>Materiaux et déchets:</label></h3>
              </div>
              <div class="panel-body">
                <div id="list_evac" class="btn-group">
                  <!-- Rempli via JS -->
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
      id_point: <?php echo json_encode(filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT), JSON_NUMERIC_CHECK); ?>,
      id_type_action: <?php echo(json_encode($filieres_sorties, JSON_NUMERIC_CHECK)) ?>,
      types_evac: <?php echo(json_encode(types_dechets_evac($bdd), JSON_NUMERIC_CHECK)); ?>,
      masse_max: <?php echo(json_encode($point_sortie['pesee_max'], JSON_NUMERIC_CHECK)); ?>,
    };
  </script>
  <script src="../js/ticket.js" type="text/javascript"></script>
  <script src="../js/numpad.js" type="text/javascript"></script>
  <script type="text/javascript">
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
      const numpad = new NumPad(document.getElementById('number'));
      // Passer ticket en param aux evenements?
      // object global attention!

      const typesItems = window.OressourceEnv.types_evac;
      const ticketsItem = new Ticket();
      const pushItems = connection_UI_ticket(numpad, ticketsItem, typesItems);

      const div_list_item = document.getElementById('list_evac');
      typesItems.forEach((item) => {
        const button = html_saisie_item(item, pushItems);
        div_list_item.appendChild(button);
      });

      const encaisse = make_encaissement('../moteur/sortiesr_post.php', {items: ticketsItem});

      document.getElementById('encaissement').addEventListener('click', encaisse, false);
      document.getElementById('impression').addEventListener('click', impression_ticket, false);
      document.getElementById('reset').addEventListener('click', tickets_clear, false);

      const recycleur_choix = ((ui, filieres, /*filiere_select*/) => {
        return (event) => {
          console.log(event);
          const select = event.target;
          const id = select.value;
          const accept = filieres[id];

          select.value = id;
          select.disabled = true;

          const list = accept.split('a');
          list.forEach((type_dechet) => {
            document.getElementById(type_dechet).style.display = "block";
          });
        };
      })(div_list_item, window.OressourceEnv.id_type_action);

      document.getElementById('sel_filiere').addEventListener('change', recycleur_choix, false);

      window.tickets = [ticketsItem];
    }, false);
  </script>

  <script defer src="../js/utilitaire.js"></script>
  <script defer type="text/javascript">
    "use strict";
    /*
     const headstr = "<html><head><title></title></head><body><small><?php echo $_SESSION['structure']; ?><br><?php echo $_SESSION['adresse']; ?><br><label>Bon de sortie recyclage</label><br>";
     const footstr = "<br>Masse totale : " + mtot + " Kgs.</body></small>";
     const newstr = document.all.item(divID).innerHTML;
     const oldstr = document.body.innerHTML;

     document.getElementById("formulaire").submit();
     document.body.innerHTML = headstr + newstr + footstr;
     window.print();
     document.body.innerHTML = oldstr;
     return false;

     function tdechet_clear() {
     document.getElementById("id_filiere").value = "";
     document.getElementById("id_type_dechet").value = "";
     document.getElementById("type_dechet").value = "";
     document.getElementById("sel_filiere").disabled = false;
     document.getElementById("sel_filiere").value = "0";
     document.getElementById("number").value = "";
     document.getElementById('d<?php echo $evac['nom']; ?>').textContent = "0";
     document.getElementById('d<?php echo $evac['id']; ?>').value = "0";
     document.getElementById('b<?php echo $evac['id']; ?>').style.display = "none";
     }
     */
  </script>
  <?php
  include "pied.php";
} else {
  header('Location:../moteur/destroy.php');
  die();
}