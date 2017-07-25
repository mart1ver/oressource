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
require_once('../moteur/dbconfig.php');
if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && (strpos($_SESSION['niveau'], 'v' . $_GET['numero']) !== false)) {
  require_once 'tete.php';
  ?>
  <div class="panel-body" >
    <fieldset>
      <legend>
        <?php
        // on determine le numero de la vente
        $req = $bdd->prepare('SELECT max(id) FROM ventes WHERE id_point_vente = :id ');
        $req->execute(['id' => $_GET['numero']]);
        while ($donnees = $req->fetch()) {
          $numero_vente = $donnees['max(id)'] + 1;
        }
        $req->closeCursor();
        //on affiche le nom du point de vente
        // On recupère tout le contenu de la table point de collecte

        $req = $bdd->prepare('SELECT * FROM points_vente WHERE id = :id ');
        $req->execute(['id' => $_GET['numero']]);
        while ($donnees = $req->fetch()) {
          echo$donnees['nom'];
          $nom_pv = $donnees['nom'];
          $adresse_pv = $donnees['adresse'];
        }
        $req->closeCursor();
        ?>
      </legend>
    </fieldset>
    <div class="row">
      <br>
      <div class="col-md-2 col-md-offset-2" style="width: 330px;" >
        <div class="panel panel-danger" id="divID">
          <div class="panel-heading">
            <label class="panel-title">Remboursement:</label>
            <span class ="badge" id="recaptotal"  style="float:right;">0€
            </span>
          </div>
          <div class="panel-body">

            <form action="../moteur/remboursement_post.php" id="formulaire" method="post">
              <?php if ($_SESSION['saisiec'] === 'oui' && (strpos($_SESSION['niveau'], 'e') !== false)) { ?>
                Date de la vente:  <input type="date" id="antidate" name="antidate" style="height:20px;" value=<?= date('Y-m-d'); ?>>
                <br>
                <br>
              <?php } ?>
              <ul id="liste" class="list-group">
                <li class="list-group-item">Réference: <?= $_GET['numero']; ?>#<?= $numero_vente; ?>, date: <?= date('d-m-Y'); ?><br><?= $nom_pv; ?><br><?= $adresse_pv; ?>,<br>siret: <?= $_SESSION['siret']; ?></li>

              </ul>
              <ul class="list-group" id="total">
              </ul>
              <input type="text" class="form-control" placeholder="commentaire" id="comm" name="comm"><br>

              <br>
              <input type="hidden"  id="nlignes" name="nlignes">
              <input type="hidden"  id="narticles" name="narticles">
              <input type="hidden"  id="ptot" name="ptot">

              <input type="hidden" name ="id_point_vente" id="id_point_vente" value="<?= $_GET['numero']; ?>">
            </form>
            <ul id="boutons" class="list-group">
              <button class="btn btn-danger btn-lg" onclick="encaisse();">Rembourser!</button>
              <button class="btn btn-danger btn-lg" type="button" onclick="printdiv('divID');" align="center"><span class="glyphicon glyphicon-print"></span></button>
              <button class="btn btn-warning btn-lg" onClick="javascript:window.location.reload()"><span class="glyphicon glyphicon-refresh"></button>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-3" style="width: 220px;">

        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title"id="nom_objet"><label>Objet:</label></h3>
          </div>
          <div class="panel-body">
            Quantité: <input type="text" class="form-control" placeholder="Quantité" id="quantite" name="quantite" onfocus="fokus(this)" > Prix unitaire: <input type="text" class="form-control" placeholder="€" id="prix" name="prix" onfocus="fokus(this)">
            <input type="hidden"  id="id_type_objet" name="id_type_objet">
            <input type="hidden"  id="id_objet" name="id_objet">
            <input type="hidden"  id="nom_objet0" name="nom_objet0">
            <br>
            <button type="button" class="btn btn-default btn-lg" onclick="ajout();">
              Ajouter
            </button>
            <div class="col-md-3" style="width: 200px;">
              <div class="row">

              </div>
              <br>
              <div class="row">
                <button class="btn btn-default btn-lg" value="1" onclick="often(this);" style="margin-top:8px;">1</button>
                <button class="btn btn-default btn-lg" value="2" onclick="often(this);" style="margin-left:8px; margin-top:8px;">2</button>
                <button class="btn btn-default btn-lg" value="3" onclick="often(this);" style="margin-left:8px; margin-top:8px;">3</button>
              </div>
              <div class="row">
                <button class="btn btn-default btn-lg" value="4" onclick="often(this);" style="margin-top:8px;">4</button>
                <button class="btn btn-default btn-lg" value="5" onclick="often(this);" style="margin-left:8px; margin-top:8px;">5</button>
                <button class="btn btn-default btn-lg" value="6" onclick="often(this);" style="margin-left:8px; margin-top:8px;">6</button>
              </div>
              <div class="row">
                <button class="btn btn-default btn-lg" value="7" onclick="often(this);" style="margin-top:8px;">7</button>
                <button class="btn btn-default btn-lg" value="8" onclick="often(this);" style="margin-left:8px; margin-top:8px;">8</button>
                <button class="btn btn-default btn-lg" value="9" onclick="often(this);" style="margin-left:8px; margin-top:8px;">9</button>
              </div>
              <div class="row">
                <button class="btn btn-default btn-lg" value="c" onclick="often(this);" style="margin-top:8px;">C</button>
                <button class="btn btn-default btn-lg" value="0" onclick="often(this);" style="margin-left:8px; margin-top:8px;">0</button>
                <button class="btn btn-default btn-lg" value="." onclick="often(this);" style="margin-left:8px; margin-top:8px;">,</button>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-3" >
        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title"><label>Type d'objet:</label></h3>
          </div>
          <div class="panel-body">

            <?php
            // On recupère tout le contenu de la table point de collecte
            $reponse = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');
            while ($donnees = $reponse->fetch()) { ?>
              <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="margin-left:8px; margin-top:16px;">
                  <span class="badge" id="cool" style="background-color:<?= $donnees['couleur']; ?>"><?= $donnees['nom']; ?></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="javascript:edite('<?= $donnees['nom']; ?>','0','<?= $donnees['id']; ?>','0')" ><?= $donnees['nom']; ?></a></li>
                  <li class="divider"></li>
                  <?php
                  // On recupère tout le contenu de la table grille_objets
                  $req = $bdd->prepare('SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet AND visible = "oui"   ');
                  $req->execute(['id_type_dechet' => $donnees['id']]);
                  $i = 1;

                  while ($donneesint = $req->fetch()) { ?>
                    <li><a href="javascript:edite('<?= $donneesint['nom']; ?>','<?= $donneesint['prix']; ?>','<?= $donnees['id']; ?>','<?= $donneesint['id']; ?>')"><?= $donneesint['nom']; ?></a></li>
                    </li>
                    <?php
                  }
                  $req->closeCursor();
                  ?>
                </ul>
              </div>

              <?php
            }
            $reponse->closeCursor();
            ?>
          </div>

        </div>
        <br>
        <a href="ventes.php?numero=<?= $_GET['numero']; ?>&nom=<?= $_GET['nom']; ?>&adresse=<?= $_GET['adresse']; ?>">
          <button type="button"  class="btn btn-default pull-right" >
            Retour aux ventes
          </button>
          <a>
            <br><br>

            </div>
            </div>
            </div>

            <br>
            <?php require_once 'pied.php'; ?>
            <script type="text/javascript">
              "use strict";

              var what;

              function fokus(that) {
                what = that;
              }
              function printdiv(divID) {
                if (parseInt(document.getElementById('nlignes').value) >= 1) {
                  var headstr = "<html><head><title></title></head><body><small>";

  <?php if ($_SESSION['tva_active']) { ?>
                    var prixtot = parseFloat(document.getElementById('ptot').value).toFixed(2);
                    var prixht = parseFloat(prixtot).toFixed(2) / (1 + parseFloat(<?= $_SESSION['taux_tva']; ?>).toFixed(2) / 100);
                    var ptva = parseFloat(prixtot).toFixed(2) - parseFloat(prixht).toFixed(2)
                    var footstr = "TVA à <?= $_SESSION['taux_tva']; ?>%" + " Prix H.T. =" + parseFloat(prixht).toFixed(2) + "€ TVA=" + parseFloat(ptva).toFixed(2) + "€";
    <?php
  } else { ?>
                    var footstr = "Association non assujettie à la TVA.</body></small> ";
  <?php } ?>
                  var comstr = "<ul id='liste' class='list-group'><li class='list-group-item'><b>";
                  comstr += document.getElementById('comm').value;
                  comstr += "</b></li></ul>";
                  var newstr = document.all.item(divID).innerHTML;
                  var oldstr = document.body.innerHTML;
                  document.body.innerHTML = headstr + comstr + newstr + footstr;
                  window.print();
                  document.body.innerHTML = oldstr;
                  //return false;

                }

                //puis encaisse
                if ((parseInt(document.getElementById('nlignes').value) >= 1) && ((document.getElementById('quantite').value == "") || (document.getElementById('quantite').value == "0")) && ((document.getElementById('prix').value == "") || (document.getElementById('prix').value == "0"))) {
                  document.getElementById('comm').value = document.getElementById('comm').value

                  document.getElementById("formulaire").submit();
                }
              }
              function often(that) {
                if (isNaN(parseInt(document.getElementById('id_type_objet').value))) {
                } else {

                  if (what == null) {
                    document.getElementById('quantite').value = "";
                    what = document.getElementById('quantite');
                  }
                  if (that.value == "c") {
                    what.value = "";
                  } else {
                    what.value = what.value + that.value;
                  }

                }

              }

              function ajout() {
                if (isNaN((parseFloat(document.getElementById('prix').value) * parseFloat(document.getElementById('quantite').value)).toFixed(2))) {
                } else {
                  if (isNaN(parseInt(document.getElementById('nlignes').value))) {
                    document.getElementById('nlignes').value = 1;
                  } else {
                    document.getElementById('nlignes').value = parseInt(document.getElementById('nlignes').value) + 1;
                  }
                  if (isNaN(parseInt(document.getElementById('narticles').value))) {
                    document.getElementById('narticles').value = document.getElementById('quantite').value;
                  } else {
                    document.getElementById('narticles').value = parseInt(document.getElementById('narticles').value) + parseInt(document.getElementById('quantite').value);
                  }
                  if (isNaN(parseInt(document.getElementById('ptot').value))) {
                    document.getElementById('ptot').value = document.getElementById('prix').value * document.getElementById('quantite').value;
                  } else {
                    document.getElementById('ptot').value = parseFloat(document.getElementById('ptot').value) + parseFloat(document.getElementById('prix').value * document.getElementById('quantite').value);
                  }

                  document.getElementById('liste').innerHTML += '<li class="list-group-item"><span class="badge">' + parseFloat(parseFloat(document.getElementById('prix').value) * parseFloat(document.getElementById('quantite').value)).toFixed(2) + '€' + '</span>'
                          + document.getElementById('quantite').value + '*' + document.getElementById('nom_objet0').value
                          + '<input type="hidden"  id="tid_type_objet' + parseInt(document.getElementById('nlignes').value) + '" name="tid_type_objet' + parseInt(document.getElementById('nlignes').value) + '"value="' + document.getElementById('id_type_objet').value + '">'
                          + '<input type="hidden"  id="tid_objet' + parseInt(document.getElementById('nlignes').value) + '" name="tid_objet' + parseInt(document.getElementById('nlignes').value) + '"value="' + document.getElementById('id_objet').value + '">'
                          + '<input type="hidden"  id="tquantite' + parseInt(document.getElementById('nlignes').value) + '" name="tquantite' + parseInt(document.getElementById('nlignes').value) + '"value="' + document.getElementById('quantite').value + '">'
                          + '<input type="hidden"  id="tprix' + parseInt(document.getElementById('nlignes').value) + '" name="tprix' + parseInt(document.getElementById('nlignes').value) + '"value="' + document.getElementById('prix').value + '"></li>';
                  document.getElementById('total').innerHTML = '<li class="list-group-item">Soit : ' + document.getElementById('narticles').value + ' article(s) pour : <span class="badge" style="float:right;">' + parseFloat(document.getElementById('ptot').value).toFixed(2) + '€</span></li>';
                  document.getElementById('recaptotal').innerHTML = parseFloat(document.getElementById('ptot').value).toFixed(2) + '€';
                  document.getElementById('nom_objet').innerHTML = "<label>Objet:</label>";
                  document.getElementById('quantite').value = "";
                  document.getElementById('prix').value = "";
                  document.getElementById('id_type_objet').value = "";
                  document.getElementById('id_objet').value = "";
                  document.getElementById('nom_objet0').value = "";
                }
              }
              function edite(nom, prix, id_type_objet, id_objet) {
                document.getElementById('nom_objet').innerHTML = "<label>" + nom + "</label>";
                document.getElementById('quantite').value = "1";
                document.getElementById('prix').value = parseFloat(prix);
                document.getElementById('id_type_objet').value = parseFloat(id_type_objet);
                document.getElementById('id_objet').value = parseFloat(id_objet);
                document.getElementById('nom_objet0').value = nom;

              }
              function encaisse() {
                if (parseInt(document.getElementById('nlignes').value) >= 1) {

                  document.getElementById("formulaire").submit();
                }
              }
            </script>

            <?php
          } else {
            header('Location:../moteur/destroy.php');
          }
          ?>
