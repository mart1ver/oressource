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

//Vérification des autorisations de l'utilisateur et des variables de session requises pour l'affichage de cette page:
if (isset($_SESSION['id'])
  && $_SESSION['systeme'] === "oressource"
  && is_allowed_sortie_id($numero)) {


  if ($_SESSION['affsp'] !== "oui") {
    header("Location:sortiesc.php?numero=" . $numero);
    die();
  }

  require_once('tete.php');

  $point_sortie = point_sorties_id($bdd, $numero);
  $pesee_max = (float) $point_sortie['pesee_max'];
  $types_poubelles = types_poubelles($bdd);
  ?>

  <div class="panel-body">
    <fieldset>
      <legend><?php echo$point_sortie['nom']; ?></legend>
    </fieldset>
    <div class="row">
      <div class="col-md-7 col-md-offset-1" >
        <ul class="nav nav-tabs">
          <?php if ($_SESSION['affsp'] === "oui") { ?><li class="active"><a>Poubelles</a></li><?php } ?>
          <?php if ($_SESSION['affss'] === "oui") { ?><li><a href="sortiesr.php?numero=<?php echo $numero; ?>">Sorties partenaires</a></li><?php } ?>
          <?php if ($_SESSION['affsr'] === "oui") { ?><li><a href="sortiesc.php?numero=<?php echo $numero; ?>">Recyclage</a></li><?php } ?>
          <?php if ($_SESSION['affsd'] === "oui") { ?><li><a href="sorties.php?numero=<?php echo $numero; ?>">Don</a></li><?php } ?>
          <?php if ($_SESSION['affsde'] === "oui") { ?><li><a href="sortiesd.php?numero=<?php echo $numero; ?>">Déchetterie</a></li><?php } ?>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-md-offset-1" >
        <input form="formulaire" type="hidden" name ="id_point_sortie" id="id_point_sortie" value="<?php echo $numero; ?>">
        <input form="formulaire" type="hidden" id="id_user" name="id_user" value="<?php echo $_SESSION['id']; ?>">
        <input form="formulaire" type="hidden" value="0" name ="najout" id="najout">
      </div>
      <div class="col-md-4" >
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 col-md-offset-1" >
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">
              <label>Bon de sortie poubelle: <span id="massetot" >0</span> Kgs.</label>
            </h3>
          </div>
          <?php if ($_SESSION['saisiec'] === 'oui' && is_allowed_edit_date()) { ?>
            <p style="text-align:center">Date de la sortie: <input form="formulaire" type="date" id="antidate" name="antidate" style="width: 130px;height:20px;" value="<?php echo date("Y-m-d"); ?>"></p>
          <?php } ?>
          <div class="panel-body" id="divID">
            <form id="formulaire" action="../moteur/sortiesp_post.php" method="post" id="formulaire">
              <ul class="list-group">
                <?php foreach ($types_poubelles as $poubelle) { ?>
                  <li class="list-group-item">
                    <input type="hidden" value="0" name ="<?php echo $poubelle['id']; ?>" id="<?php echo $poubelle['id'];?>">
                    <span class="badge" id="<?php echo $poubelle['nom']; ?>" style="background-color:<?php echo $poubelle['couleur']; ?>">0</span>
                    <?php echo $poubelle['nom'] ?>
                  </li>
                <?php } ?>
              </ul>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-2">
        <div class="col-md-3" style="width: 220px;" >
          <div class="panel panel-info">
            <div class="panel-body">
              <div class="row">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Masse" id="number" name="num" style="margin-left:8px;" size="12">
                </div>
              </div>
              <br>
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
          <p>La masse des différents bacs est automatiquement déduite.</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">
              <label>Bacs de sortie des poubelles:</label>
            </h3>
          </div>
          <div class="panel-body">
            <?php foreach ($types_poubelles as $poubelle) { ?>
              <button class="btn btn-default btn-sm"
                      style="margin-left:8px; margin-top:16px;"
                      onclick="masse_write(
                                      document.getElementById('<?php echo($poubelle['nom']) ?>'),
                                      document.getElementById('<?php echo($poubelle['id']) ?>'),
                      <?php echo($pesee_max); ?>,
                      <?php echo($poubelle['masse_bac']); ?>);">
                <span class="badge" id="cool"
                      style="background-color:<?php echo $poubelle['couleur'] ?>"><?php echo $poubelle['nom'] ?></span>
              </button>
            <?php } ?>
          </div>
        </div>
        <div class="row">
          <button class="btn btn-primary btn-lg" form="formulaire" type="submit" onclick="verif_form_sortie();">C'est pesé!</button>
          <button class="btn btn-primary btn-lg" style="text-align:center" onclick="printdiv('divID');" value="Print ">
            <span class="glyphicon glyphicon-print"></span>
          </button>
          <button class="btn btn-warning btn-lg" onclick="tdechet_clear();">
            <span class="glyphicon glyphicon-refresh"></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/utilitaire.js"></script>
  <script type="text/javascript">
            "use strict";
            function printdiv(divID) {
              if (parseInt(document.getElementById('najout').value) >= 1) {
                var mtot = 0
  <?php foreach ($types_poubelles as $poubelle) { ?>
                  + parseFloat(document.getElementById('<?php echo $poubelle['id']; ?>').value) +
  <?php } ?>;

                var headstr = "<html><head><title></title></head><body><small><?php echo $_SESSION['structure']; ?><br><?php echo $_SESSION['adresse']; ?><br><label>Bon de sortie poubelles</label><br>";
                var footstr = "<br>Masse totale : " + mtot + " Kgs.</body></small>";
                var newstr = document.all.item(divID).innerHTML;
                var oldstr = document.body.innerHTML;

                document.getElementById("formulaire").submit();
                document.body.innerHTML = headstr + newstr + footstr;
                window.print();
                document.body.innerHTML = oldstr;
                return false;
              }
            }


            function tdechet_clear() {
  <?php
// On recupère tout le contenu de la table types_poubelles
  $reponse = $bdd->query('SELECT * FROM types_poubelles');

// On affiche chaque entree une à une
  while ($donnees = $reponse->fetch()) {
    ?>
                document.getElementById('<?php echo$donnees['nom'] ?>').textContent = "0";
                document.getElementById(<?php echo$donnees['id'] ?>).value = "0";
    <?php
  }

  $reponse->closeCursor(); // Termine le traitement de la requête
  ?>
            }
  </script>
  <?php
  include "pied.php";
} else {
  header('Location:../moteur/destroy.php');
}
?>
