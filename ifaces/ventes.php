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

require_once('../core/session.php');

$numero = filter_input(INPUT_GET, 'numero', FILTER_VALIDATE_INT);

if (is_valid_session() && is_allowed_vente_id($numero)) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  // on détermine la référence de la prochaine vente.
  $req = $bdd->query("SHOW TABLE STATUS where name='ventes'");
  $numero_vente = $req->fetch()['Auto_increment'];
  $req->closeCursor();

  $point_vente = points_ventes_id($bdd, $numero);
  $dechets = types_dechets($bdd);
  $objets = objets_visibles($bdd);
  ?>

  <div class="container">
    <nav class="navbar">
      <div class="header-header">
        <h1><?= $point_vente['nom']; ?></h1>
      </div>
    </nav>

    <div class="col-md-4">
      <div id="ticket" class="panel panel-info" >
        <div class="panel-heading">
          <h3 class="panel-title" >Ticket de caisse: &#8470;
            <span id="num_vente"><?= $numero_vente; ?></span>
            <span class ="badge" id="recaptotal" style="float:right;">0 €</span>
          </h3>
          <!--
          <adresse>
            <strong><?= $point_vente['nom']; ?></strong><br>
          <?= $point_vente['adresse']; ?><br>
            Siret: <?= $_SESSION['siret']; ?>
          </adresse>
          -->
        </div>

        <div class="panel-body" id="ticket">
          <?php if (is_allowed_saisie_collecte() && is_allowed_edit_date()) { ?>
            <label for="date">Date de la vente:</label>
            <input type="date" id="date" name="antidate" style="width:130px; height:20px;" value="<?= date('Y-m-d'); ?>">
          <?php } ?>
          <ul class="list-group" id="transaction">
            <!-- Remplis via JavaScript voir script de la page -->
          </ul>
          <ul id="total"></ul>
        </div>
        <div class="panel-footer">
          <input type="text" form="formulaire" class="form-control"
                 name="commentaire" id="commentaire" placeholder="Commentaire">

          <button type="button" class="btn btn-warning" data-toggle="collapse"
                  data-target="#collapserendu" aria-expanded="false"
                  aria-controls="collapserendu">Rendu Monnaie</button>
          <div class="collapse" id="collapserendu">
            <ul class="list-group list-group-item-warning">
              <li class="list-group-item">
                Somme due:
                <input type="text" class="form-control" placeholder="€"
                       name="somme" id="somme" disabled>
              </li>
              <li class="list-group-item list-group-item-success">
                <b>Réglement</b>
                <input type="text" class="form-control" placeholder="€"
                       name="reglement" id="reglement"
                       oninput="update_rendu()">
              </li>
              <li class="list-group-item list-group-item-danger">
                <b>A rendre</b>
                <input type="text" class="form-control" placeholder="€"
                       name="difference" id="difference" disabled>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4" style="width: 220px;">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">
            <label id="nom_objet">Objet:</label>
          </h3>
        </div>

        <div class="panel-body" id="panelcalc">
          <?php if (ventes_lots()) { ?>
            <label id="labellot" for="typeVente">Vente à:</label>
              <input class="make-switch" id="typeVente" type="checkbox"
                     name="my-checkbox" checked
                     data-on-text="l'unité"
                     data-off-text="lot" data-handle-width="28"
                     data-size="small">
          <?php } ?>

          <label id="labelquantite" for="quantite">Quantité:</label>
            <input type="text" class="form-control"
                   placeholder="Quantité" id="quantite"
                   onfocus="fokus(this)">

          <label id="labelprix" for="prix">Prix unitaire:</label>
            <input type="text" class="form-control"
                   placeholder="€" id="prix" onfocus="fokus(this)">

          <?php if (pesees_ventes()) { ?>
          <label id="labelmasse" for="masse">Masse unitaire:</label>
              <input type="text" class="form-control" placeholder="Kgs."
                     id="masse" onfocus="fokus(this)">
          <?php } ?>
          <br/>

          <button type="button" class="btn btn-default btn-lg" onclick="add();">
            Ajouter
          </button>

          <div class="col-md-3" style="width: 200px;">
            <div class="row">
              <button class="btn btn-default btn-lg btn-num" value="1" onclick="numpad_input(this)">1</button>
              <button class="btn btn-default btn-lg btn-num" value="2" onclick="numpad_input(this);">2</button>
              <button class="btn btn-default btn-lg btn-num" value="3" onclick="numpad_input(this);">3</button>
            </div>
            <div class="row">
              <button class="btn btn-default btn-lg btn-num" value="4" onclick="numpad_input(this);">4</button>
              <button class="btn btn-default btn-lg btn-num" value="5" onclick="numpad_input(this);">5</button>
              <button class="btn btn-default btn-lg btn-num" value="6" onclick="numpad_input(this);">6</button>
            </div>
            <div class="row">
              <button class="btn btn-default btn-lg btn-num" value="7" onclick="numpad_input(this);">7</button>
              <button class="btn btn-default btn-lg btn-num" value="8" onclick="numpad_input(this);">8</button>
              <button class="btn btn-default btn-lg btn-num" value="9" onclick="numpad_input(this);">9</button>
            </div>
            <div class="row">
              <button class="btn btn-default btn-lg btn-num" value="c" onclick="reset_numpad()">C</button>
              <button class="btn btn-default btn-lg btn-num" value="0" onclick="numpad_input(this);">0</button>
              <button class="btn btn-default btn-lg btn-num" value="." onclick="numpad_input(this);">,</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4" >
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Type d'objet:</h3>
        </div>
        <div class="panel-body">
          <?php
          foreach ($dechets as $d) {
            $objs = array_filter($objets, function($e) use ($d) {
              return $e['id_type_dechet'] === $d['id'];
            });
            ?>
            <div class='btn-group'>
              <button type="button"
                      class="btn btn-default <?= count($objs) > 0 ? 'dropdown-toggle' : '' ?>"
                      <?php if (count($objs) > 0) { ?>
                        data-toggle="dropdown"
                      <?php } else { ?>
                        onclick='update_state({ type: <?= json_encode($d, JSON_NUMERIC_CHECK) ?> });
                            return false;'
                      <?php } ?>
                      style="margin-left:8px; margin-top:16px;">
                <span class='badge' id='cool' style='background-color:<?= $d['couleur'] ?>'>
                  <?= $d['nom'] ?>
                </span>
              </button>
              <?php if (count($objs) > 0) { ?>
                <ul class='dropdown-menu' role='menu'>
                  <li style="font-size:18px">
                    <a href="#" onclick='update_state({ type: <?= json_encode($d, JSON_NUMERIC_CHECK) ?> });
                        return false;'><?= $d['nom'] ?></a>
                  </li>
                  <li class='divider'></li>
                  <?php foreach ($objs as $objet) { ?>
                    <li style='font-size:18px'>
                      <a href="#" onclick='update_state({
                            type: <?= json_encode($d, JSON_NUMERIC_CHECK) ?>,
                            objet: <?= json_encode($objet, JSON_NUMERIC_CHECK) ?> });
                          return false;'><?= $objet['nom'] ?></a>
                    </li>
                  <?php } ?>
                </ul>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="panel panel-info">
        <div class="panel-body">
          <label>Moyen de paiement:</label>
          <div id="moyens" class="btn-group" data-toggle="buttons">
            <?php foreach (moyens_paiements_visibles($bdd) as $moyen) { ?>
              <label class="btn ors_btn_pay btn-default <?= $moyen['id'] === 1 ? 'active' : '' ?>"
                     onclick="moyens(<?= $moyen['id'] ?>);"
                     style="background-color: <?= $moyen['couleur']; ?>">
                <input type='radio' name='paiement' id='paiement'
                       <?= $moyen['id'] === 1 ? 'checked' : '' ?>>
                <?= $moyen['nom'] ?></label>
            <?php } ?>
          </div>
        </div>
      </div>

      <div id="boutons" class="list-group">
        <button id="encaissement" class="btn btn-success btn-lg" style="height:60px">Encaisser</button>
        <button id="impression" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-print"></span></button>
        <button id="reload" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-refresh"></button>
        <button id="remboursement" class="btn btn-danger btn-lg" data-toggle="collapse"
                data-target="#collapserembou" aria-expanded="false"
                aria-controls="collapseExample">Remboursement</button>
        <div class="collapse" id="collapserembou">
          <div class="well">
            <form action="../moteur/verif_remb_post.php?numero=<?= $numero ?>" method="post">
              <div class="input-group">
                <input name="passrmb" id="passrmb" type="password" class="form-control" placeholder="Code remboursement caisse">
                <span class="input-group-btn">
                  <button class="btn btn-default">OK</button>
                </span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php if ($_SESSION['viz_caisse']) { ?>
      <div id=visualisation" class="col-md-2 col-md-offset-2" style="width: 330px;">
        <a href="viz_caisse.php?numero=<?= $numero; ?>" target="_blank">Visualiser les <?= $_SESSION['nb_viz_caisse']; ?> dernieres ventes</a>
      </div>
    <?php } ?>
  </div>
  <script type="text/javascript">
    'use scrict';
    window.ventes = {
      nb_viz_caisse: <?= json_encode($_SESSION['nb_viz_caisse'], JSON_NUMERIC_CHECK); ?>,
      force_pes_vente: <?= json_encode($_SESSION['force_pes_vente']); ?>,
      pesees: <?= json_encode(pesees_ventes()) ?>,
      visualisation: <?= json_encode($_SESSION['viz_caisse']) ?>,
      tva_active: <?= json_encode($_SESSION['tva_active']); ?>,
      taux_tva: <?= json_encode($_SESSION['taux_tva'], JSON_NUMERIC_CHECK); ?>,
      point: <?= json_encode(points_ventes_id($bdd, $numero), JSON_NUMERIC_CHECK); ?>,
      id_user: <?= json_encode($_SESSION['id'], JSON_NUMERIC_CHECK) ?>,
      moyens_paiement: <?= json_encode(moyens_paiements($bdd), JSON_NUMERIC_CHECK) ?>
    };
  </script>
  <script src="../js/ventes.js"></script>
  <?php
  require_once 'pied.php';
} else {
  header('Location:../moteur/destroy.php');
}
?>
