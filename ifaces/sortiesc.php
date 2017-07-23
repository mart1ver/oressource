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

// Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_sortie_id($numero)) {

  if ($_SESSION['affss'] !== "oui") {
    header("Location:sortiesr.php?numero=" . $numero);
    die();
  }
  require_once('tete.php');

  // Simple formulaire de saisie des matieres d'ouevres sortantes de la structure. (structures partenaires, conventiionnées)
  // Doit etre fonctionnel avec un ecran tactille.

  $point_sortie = point_sorties_id($bdd, $numero);
  $conventions = convention_sortie($bdd);
  $pesee_max = (float) $point_sortie['pesee_max'];

  $date = new Datetime('now');
  ?>

  <div class="container">

    <nav class="navbar">
      <div class="header-header">
        <h1><?= $point_sortie['nom'] ?></h1>
      </div>
      <ul class="nav nav-tabs">
        <?php if ($_SESSION['affsp'] === "oui") { ?><li><a href="sortiesp.php?numero=<?= $numero; ?>">Poubelles</a></li><?php } ?>
        <li class="active"><a href="#">Sorties partenaires</a></li>
        <?php if ($_SESSION['affsr'] === "oui") { ?><li><a href="sortiesr.php?numero=<?= $numero; ?>">Recyclage</a></li><?php } ?>
        <?php if ($_SESSION['affsd'] === "oui") { ?><li><a href="sorties.php?numero=<?= $numero; ?>">Don</a></li><?php } ?>
        <?php if ($_SESSION['affsde'] === "oui") { ?><li><a href="sortiesd.php?numero=<?= $numero; ?>">Déchetterie</a></li><?php } ?>
      </ul>
    </nav>

    <div class="col-md-4">
      <div id="ticket" class="panel panel-info" >
        <div class="panel-heading">
          <h3 class="panel-title">
            <label id="massetot">Masse totale: 0 Kg.</label>
          </h3>
        </div>
        <div class="panel-body">
          <form id="formulaire">
            <?php if (is_allowed_edit_date()) { ?>
              <label for="antidate">Date de la sortie: </label>
              <input form="formulaire" type="date" id="antidate" name="antidate" style="width:130px; height:20px;" value="<?=($date->format('Y-m-d')); ?>">
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
              <option value="<?= $convention['id'] ?>"><?= $convention['nom'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <!-- Pavee de saisie numerique vcir numpad.js -->
      <div id="numpad" class="col-md-8 col-md-offset-2" style="width: 220px;"></div>
    </div>

    <div class="col-md-4">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">
            <label>Type d'objet:</label>
          </h3>
        </div>
        <div class="panel-body">
          <div id="list_item" class="btn-group" >
            <!-- Cree via JS -->
          </div>
        </div>
      </div>

      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">
            <label>Materiaux et déchets:</label>
          </h3>
        </div>
        <div class="panel-body">
          <div id="list_evac" class="btn-group">
            <!-- Rempli via JS -->
          </div>
        </div>
      </div>

      <div class="btn-group" role="group">
        <button id="encaissement" class="btn btn-success btn-lg">C'est pesé!</button>
        <button id="impression" class="btn btn-primary btn-lg" value="Print"><span class="glyphicon glyphicon-print"></span></button>
        <button id="reset" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-refresh"></button>
      </div>

    </div><!-- .col-md-4 -->
  </div>


  <script type="text/javascript">
    // Variables d'environnement de Oressource.
    'use scrict';
    window.OressourceEnv = {
      structure: <?= json_encode($_SESSION['structure']) ?>,
      adresse: <?= json_encode($_SESSION['adresse']) ?>,
      id_user: <?= json_encode($_SESSION['id'], JSON_NUMERIC_CHECK) ?>,
      saisie_collecte: <?= json_encode(is_allowed_saisie_collecte()) ?>,
      user_droit: <?= json_encode($_SESSION['niveau']) ?>,
      id_point: <?= json_encode($numero, JSON_NUMERIC_CHECK) ?>,
      id_type_action: <?= json_encode($conventions, JSON_NUMERIC_CHECK) ?>,
      types_dechet: <?= json_encode(types_dechets($bdd), JSON_NUMERIC_CHECK) ?>,
      masse_max: <?= json_encode($point_sortie['pesee_max'], JSON_NUMERIC_CHECK) ?>,
      types_evac: <?= json_encode(types_dechets_evac($bdd), JSON_NUMERIC_CHECK) ?>,
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

      // Hack en attendant de trouver une solution pour gerer differament les dechets
      // et les objets qui ont les memes id...
      // On retourne une closure avec connection_UI_ticket du coup...

      const typesItems = window.OressourceEnv.types_dechet;
      const ticketItems = new Ticket();
      const pushItem = connection_UI_ticket(numpad, ticketItems, typesItems);

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

      const metadata = {classe: 'sortiesc'};
      const encaisse = make_encaissement('../api/sorties.php', {
        items: ticketItems,
        evacs: ticketEvac
      }, metadata);

      document.getElementById('encaissement').addEventListener('click', encaisse, false);
      document.getElementById('impression').addEventListener('click', () => {
        impression_ticket(encaisse);
      }, false);
      document.getElementById('reset').addEventListener('click', tickets_clear, false);

      window.tickets = [ticketItems, ticketEvac];
    }, false);
  </script>

  <?php
  include "pied.php";
} else {
  header('Location:../moteur/destroy.php');
}