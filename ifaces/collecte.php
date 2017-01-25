<?php

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
  <h2><?php echo($point_collecte['nom']);?></h2>
  <div class="row">
    <div class="col-md-3 col-md-offset-2" >
      <!--startprint-->
      <div class="panel panel-info" >
        <form action="../moteur/collecte_post.php" method="post" id="formulaire">
          <!--<legend>
  </legend>-->
          <div class="panel-heading">
            <h3 class="panel-title"><label>Bon d'apport: <span id="massetot" >0</span> Kgs.</label></h3>
          </div>
          <?php if (is_allowed_saisie_collecte() && is_allowed_edit_date()) { ?>
            <label>Date de l'apport: <input type="date" id="antidate" name="antidate" style="width:130px; height:20px;" value=<?php echo date("Y-m-d"); ?>></label>
          <?php } ?>
          <div class="panel-body">
            <ul class="list-group" id="transaction">
              <!-- Remplis via JavaScript voir push_item -->
            </ul>
            <input type="hidden" value="0" name ="najout" id="najout">
            <input type="hidden" id="id_user" name="id_user" value="<?php echo($_SESSION['id']);?>">
            <input type="hidden" id="saisiec_user" name="saisiec_user" value="<?php echo($_SESSION['saisiec']);?>">
            <input type="hidden" id="niveau_user" name="niveau_user" value="<?php echo($_SESSION['niveau']);?>">
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
          <select name ="id_type_collecte" id ="id_type_collecte" class="form-control" style="
                  font-size : 12pt" autofocus required>
            <option value="0" selected="selected"></option>
            <?php foreach ($types_collecte as $type_collecte) { ?>
              <option value="<?php echo $type_collecte['id'] ?>"><?php echo $type_collecte['nom'] ?></option>
            <?php } ?>
          </select>

          <label for="loc">Localité :</label>
          <select name ="loc" id ="loc" class="form-control" STYLE="font-size : 12pt" required>
            <option value="0" selected="selected"></option>
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
                <input type="text" class="form-control" autofocus placeholder="Masse" id="number" name="num" style="margin-left:8px;">
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
      <button class="btn btn-primary btn-lg" onclick="encaisse();">C'est pesé!</button>
      <button class="btn btn-primary btn-lg" onclick="printdiv('divID');" value=" Print " ><span class="glyphicon glyphicon-print"></span></button>
      <button class="btn btn-warning btn-lg" onclick="tdechet_clear();"><span class="glyphicon glyphicon-refresh"></button>
    </div>
  </div>

  <script src="../js/utilitaire.js" type="text/javascript"></script>
  <script type="text/javascript">
  'use strict';
  const structure = <?php echo(json_encode($_SESSION['structure'])); ?>;
  const adresse = <?php echo(json_encode($_SESSION['adresse'])); ?>;
  const user_id = <?php echo(json_encode($_SESSION['id'], JSON_NUMERIC_CHECK)); ?>;
  const saisie_collecte = <?php echo json_encode($_SESSION['saisiec'], JSON_FORCE_OBJECT); ?>;
  const user_rights = <?php echo json_encode($_SESSION['niveau']); ?>;
  const id_point_collecte = <?php echo json_encode($_GET['numero'], JSON_NUMERIC_CHECK); ?>;
  const types_collecte = <?php echo(json_encode($types_collecte, JSON_NUMERIC_CHECK & JSON_FORCE_OBJECT));?>;
  const types_dechet = <?php echo(json_encode($types_dechet, JSON_NUMERIC_CHECK & JSON_FORCE_OBJECT));?>;
  const masse_max = <?php echo(json_encode($point_collecte['pesee_max'], JSON_NUMERIC_CHECK)); ?>;

  const commit = {
    total: 0.0,
    items: [],
    comment: "",
  };
  // Classe pour manipuler le numPad de facon sure.
  //        class NumPad(id) {
  //          constructor() {
  //            this.input = document.getElementById('number');
  //          }
  //           value() {
  //
  //          }
  //
  //          setCustomValidity(msg) {
  //            this.input.setCustomValidity();
  //          }
  //
  //          clear() {
  //            this.input.value = '';
  //            num_pad.setCustomValidity('');
  //          }
  //        }

  document.addEventListener('DOMContentLoaded', () => {
    const num_pad = document.getElementById('number');
    const ul_transact = document.getElementById('transaction');
    const span_massetot = document.getElementById('massetot');

    function push_item() {
      const value = parseFloat(num_pad.value);
      if (value > 0.00) {
        if (value <= masse_max) {
          // Update de la commission en cours.
          commit.items.push({
            mass: value,
            type: this.id,
          });
          const total = commit.total;
          const new_total = value + total;
          commit.total = new_total;

          // Update de l'UI pour la masse du panier.
          span_massetot.textContent = new_total;

          // Update UI pour le panier
          // Constitution du panier
          // FIXME: types_dechets devrait etre une Map pas un Array...
          const {_, nom, couleur} = types_dechet[parseInt(this.id) - 1];
          const li = document.createElement('li');
          li.setAttribute('class', 'list-group-item');
          li.innerHTML = `<span class="badge" style="background-color:${couleur}">${value}</span>${nom}`;
          ul_transact.appendChild(li);

          // Update nombre d'items du commit.
          document.getElementById("najout").value = commit.items.length;

          // Clear du numpad.
          num_pad.value = '';
          num_pad.setCustomValidity('');
        } else {
          num_pad.setCustomValidity("Masse supérieure aux limites de pesée de la balance.");
        }
      } else {
        num_pad.setCustomValidity("Masse entrée inférieure au poids du conteneur ou inférieure ou égale à 0.");
      }
    }

    const div_list_item = document.getElementById('list_item');
    types_dechet.forEach(({ id, nom, couleur }) => {
      const button = document.createElement('button');
      button.setAttribute('id', id);
      button.setAttribute('class', 'btn btn-default');
      button.setAttribute('style', 'margin-left:8px; margin-top:16px;');
      button.innerHTML = `<span class="badge" id="cool" style="background-color:${couleur}">${nom}</span>`;
      div_list_item.appendChild(button);
      button.addEventListener('click', push_item, false);
    });
  }, false);

  function encaisse() {
    if (commit.items.length > 0
            && document.getElementById("id_type_collecte").value > 0
            && document.getElementById("loc").value > 0) {
      document.getElementById("formulaire").submit();
    }
  }

  function printdiv(divID) {
    if (parseInt(document.getElementById('najout').value) >= 1
            && document.getElementById("id_type_collecte").value > 0
            && document.getElementById("loc").value > 0) {

      const headstr = `<html><head><title></title></head><body><small>${structure}<br>${adresse}<br><label>Bon d'apport:</label><br>`;
      const footstr = `<br>Masse totale : " + commit.items.total + " Kgs.</body></small>`;
      const newstr = document.all.item(divID).innerHTML;
      const oldstr = document.body.innerHTML;
      // Verif si le commentaire est casse
      // document.getElementById('comm').value = document.getElementById('commentaire').value;
      document.getElementById("formulaire").submit();
      document.body.innerHTML = headstr + newstr + footstr;
      window.print();
      document.body.innerHTML = oldstr;
    }
  }

  function tdechet_clear() {
    // On obtient tous les visibles de la table type_dechets de manière à remettre à zéro tout les items du bon d'apport volontaire
    const range = document.createRange();
    range.selectNodeContents(document.getElementById('transaction'));
    range.deleteContents();
    commit.items = [];
    commit.total = 0.0;
  }
</script>
<?php
  include_once "pied.php";
} else {
  header('Location:../moteur/destroy.php?motif=1');
}
