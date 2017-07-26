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

if (isset($_SESSION['id']) && $_SESSION['systeme'] === 'oressource' && is_allowed_vente_id($numero)) {
  require_once 'tete.php';
  require_once '../moteur/dbconfig.php';

  // on détermine la référence de la prochaine vente.
  $req = $bdd->query("SHOW TABLE STATUS where name='ventes'");
  $donnees = $req->fetch();
  $req->closeCursor(); // Libère la connexion au serveur
  $numero_vente = $donnees['Auto_increment'];

  // On affiche le nom du point de vente
  $req = $bdd->prepare('SELECT * FROM points_vente WHERE id = :id ');
  $req->execute(['id' => $numero]);
  $donnees = $req->fetch();
  $req->closeCursor(); // Libère la connexion au serveur
  $nom_pv = $donnees['nom'];
  $adresse_pv = $donnees['adresse'];
  ?>

  <div class="panel-body">
    <fieldset>
      <legend><?= $nom_pv; ?></legend>
    </fieldset>
    <div class="row">
      <br>
      <div class="col-md-2 col-md-offset-2" style="width: 330px;" >
        <div class="panel panel-info">
          <div class="panel-heading">
            <label class="panel-title">Ticket de caisse:</label>
            <span class ="badge" id="recaptotal" style="float:right;">0€</span>
          </div>

          <div class="panel-body" id="divID">
            <form action="../moteur/vente_post.php" id="formulaire" method="post">
              <?php if (is_allowed_saisie_collecte() && is_allowed_edit_date()) { ?>
                Date de la vente:  <input type="date" id="antidate" name="antidate" style="height:20px;" value="<?= date('Y-m-d'); ?>">
              <?php } ?>

              <ul id="liste" class="list-group">
                <li class="list-group-item">Vente: <?= $numero; ?>#<?= $numero_vente; ?>, date: <?= date('d-m-Y'); ?><br><?= $nom_pv; ?><br><?= $adresse_pv; ?>,<br>siret: <?= $_SESSION['siret']; ?></li>
              </ul>
              <ul class="list-group" id="total">
              </ul>
              <input type="hidden" id="comm" name="comm"><br>
              <input type="hidden" id="moyen" name="moyen" value="1"><br>
              <input type="hidden" id="nlignes" name="nlignes">
              <input type="hidden" id="narticles" name="narticles">
              <input type="hidden" id="ptot" name="ptot">
              <input type="hidden" id="id_user" name="id_user" value="<?= $_SESSION['id']; ?>">
              <input type="hidden" id="saisiec_user" name="saisiec_user" value="<?= $_SESSION['saisiec']; ?>">
              <input type="hidden" id="niveau_user" name="niveau_user" value="<?= $_SESSION['niveau']; ?>">
              <input type="hidden" name ="id_point_vente" id="id_point_vente" value="<?= $_GET['numero']; ?>">
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-3" style="width: 220px;">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title"id="nom_objet"><label>Objet:</label></h3>
          </div>

          <div class="panel-body" id="panelcalc">
            <?php if ($_SESSION['lot_caisse']) { ?>
              <p align="center">
                <b id="labellot">vente à:</b>
                <input class="make-switch" id="typeVente" type="checkbox"
                       name="my-checkbox" checked data-on-text="l'unité"
                       data-off-text="lot" data-handle-width="28"
                       data-size="small">
              <p>
              <?php } ?>

              <b>Quantité:</b>
              <input type="text" class="form-control" placeholder="Quantité" id="quantite" name="quantite" onfocus="fokus(this)" >
              <b id = "labelpul">Prix unitaire:</b>
              <input type="text" class="form-control" placeholder="€" id="prix" name="prix" onfocus="fokus(this)">
              <?php if ($_SESSION['pes_vente']) { ?>
                <b id = "labelmasse">Masse unitaire:</b>
                <input type="text" class="form-control" placeholder="Kgs." id="masse" name="masse" onfocus="fokus(this)">
              <?php };
              ?>
              <input type="hidden"  id="id_type_objet" name="id_type_objet">
              <input type="hidden"  id="id_objet" name="id_objet">
              <input type="hidden"  id="nom_objet0" name="nom_objet0">
              <input type="hidden"  id="sul" name="sul" value ="unite">
              <br/>
              <button type="button" class="btn btn-default btn-lg" onclick="ajout();">
                Ajouter
              </button>

            <div class="col-md-3" style="width: 200px;">
              <div class="row">
              </div>
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

        <button type="button" class="btn btn-warning" data-toggle="collapse" data-target="#collapserendu" aria-expanded="false" aria-controls="collapserendu">Rendu Monnaie</button>
        <div class="collapse" id="collapserendu">
          <ul class="list-group list-group-item-warning">
            <li class="list-group-item">
              Somme due:
              <input type="text" class="form-control "   placeholder="€" name="rendua" id="rendua"  disabled>
            </li>
            <li class="list-group-item list-group-item-success">
              <b>Réglement</b>
              <input type="text" class="form-control"   placeholder="€" name="rendub" id="rendub"  onfocus="fokus(this)" oninput="rendu()">
            </li>
            <li class="list-group-item list-group-item-danger">
              <b>A rendre</b>
              <input type="text" class="form-control"  placeholder="€"  name="renduc" id="renduc"  disabled>
            </li>
          </ul>
        </div>
      </div>

      <div class="col-md-3" >
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title"><label>Type d'objet:</label></h3>
          </div>
          <div class="panel-body">
            <?php
            // Affichage des différents Types de déchets ( "Types d'objet" )
            // avec les tarifs pré-définis (s'il y en a)
            // On recupère tout les types des déchets "actifs"
            $dechets = $bdd->query('SELECT * FROM type_dechets WHERE visible = "oui"');

            // On affiche un bouton pour chaque type de déchet
            while ($d = $dechets->fetch()) {
              $couleur_dechet = $d['couleur'];
              $id_dechet = $d['id'];
              $nom_dechet = $d['nom'];
              $action_dechet = "javascript:edite('$nom_dechet','0','$id_dechet','0')";

              echo "<div class='btn-group'>";

              // On récupère la grille de tarifs pour ce type de déchets
              $tarifs = $bdd->prepare('SELECT * FROM grille_objets WHERE id_type_dechet = :id_type_dechet AND visible = "oui"  ORDER BY nom ');
              $tarifs->execute(['id_type_dechet' => $id_dechet]);

              // S'il n'y pas de tarif pour ce type de déchets
              // Alors on affiche un bouton tout simple
              if ($tarifs->rowCount() === 0) {
                echo "<button type='button' class='btn btn-default' onclick=" . '"' . substr($action_dechet, 11) . ';"' . " style='margin-left:8px; margin-top:16px;'>";
                echo "<span class='badge' id='cool' style='background-color:$couleur_dechet'>";
                echo $nom_dechet;
                echo '</span>';
                echo '</button>';
              } else {

                // S'il y a une grille de tarif pour ce type
                // Alors on affiche un menu déroulant
                // Le premier item du menu déroulant c'est le type de déchet lui-même (avec un prix pré-défini à 0)
                echo "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' style='margin-left:8px; margin-top:16px;'>";
                echo "<span class='badge' id='cool' style='background-color:$couleur_dechet'>$nom_dechet</span>";
                echo '</button>';
                echo "<ul class='dropdown-menu' role='menu'>";
                echo "<li style='font-size:18px'>";
                echo "<a href=\"$action_dechet\" >$nom_dechet</a>";
                echo '</li>';

                // un séparateur
                echo "<li class='divider'></li>";

                // Ensuite on récupère chaque ligne de la grille de tarif
                // et on ajoute un item dans le menu déroulant
                while ($t = $tarifs->fetch()) {
                  $id_tarif = $t['id'];
                  $prix_tarif = $t['prix'];
                  $nom_tarif = $t['nom'];
                  $action_tarif = "javascript:edite('$nom_tarif','$prix_tarif','$id_dechet',$id_tarif)";

                  echo "<li style='font-size:18px'>";
                  echo "<a href=\"$action_tarif\">$nom_tarif</a>";
                  echo '</li>';
                }

                // Fin du menu déroulant
                echo '</ul>';
              }
              $tarifs->closeCursor();

              // Fin du groupe de boutons
              echo '</div>';
            }
            $dechets->closeCursor();
            ?>
          </div>
        </div>
        <div class="panel panel-info">

          <div class="panel-body">
            <label>Moyen de paiement:</label>
            <div class="btn-group" data-toggle="buttons">
              <!--
                On produit du style à la volée pour les boutons de paiement
                Si le bouton est inactif on le rend transparent
              -->
              <style type="text/css">
                .ors_btn_pay {
                  opacity: 0.5;
                  color: #dddddd;
                  font-weight: bold;
                }

                .ors_btn_pay.active, .ors_btn_pay:active {
                  opacity: 1.0;
                  color: #dddddd;
                  font-weight: bold;
                }
              </style>

              <?php
              // On recupère tout le contenu de la table point de collecte
              $reponse = $bdd->query('SELECT id, nom, couleur FROM moyens_paiement WHERE visible = "oui"');
              while ($donnees = $reponse->fetch()) {
                $id = $donnees['id'];
                if ($id === 1) {
                  $active = 'active';
                  $checked = 'checked';
                } else {
                  $active = '';
                  $checked = '';
                }

                $nom = $donnees['nom'];
                $couleur = $donnees['couleur'];
                echo "<label class='btn ors_btn_pay  btn-default $active' ";
                echo "onclick=\"moyens('$id');\" ";
                echo "style='background-color:$couleur;' ";
                echo '>';
                echo "<input type='radio' name='paiement' id='paiement' autocomplete='off' value='$id' $checked >";
                echo "$nom";
                echo '</label>';
              }
              $reponse->closeCursor();
              ?>
            </div>
            <br><br>
            <input type="text" class="form-control" name="commentaire" id="commentaire" placeholder="Commentaire">
          </div>
        </div>
        <br>

        <div id="boutons" class="list-group">
          <button class="btn btn-danger btn-lg" onclick="encaisse();" style="height:70px">Encaisser</button>
          <button class="btn btn-danger btn-lg" type="button"   align="center" onclick="printdiv('divID');" value=" Print "><span class="glyphicon glyphicon-print"></span></button>
          <button class="btn btn-warning btn-lg" onclick="javascript:window.location.reload()"><span class="glyphicon glyphicon-refresh"></button>

          <br><br>
          <?php /*
            <a href="remboursement.php?numero=<?= $_GET['numero']?>&nom=<?= $_GET['nom']?>&adresse=<?= $_GET['adresse']?>">
           */ ?>
          <button class="btn btn-danger  pull-right" type="button" data-toggle="collapse" data-target="#collapserembou" aria-expanded="false" aria-controls="collapseExample">
            Remboursement
          </button>
          <br>

          <div class="collapse" id="collapserembou">
            <div class="well">
              <form action="../moteur/verif_remb_post.php?numero=<?= $_GET['numero']; ?>" id="champpassrmb" method="post">
                <div class="input-group">
                  <input name="passrmb" id="passrmb" type="password" class="form-control" placeholder="Mot de passe...">
                  <span class="input-group-btn">
                    <button class="btn btn-default" >OK</button>
                  </span>
                </div><!-- /input-group -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if ($_SESSION['viz_caisse']) { ?>
    <div class="col-md-2 col-md-offset-2" style="width: 330px;" >
      <a href="viz_caisse.php?numero=<?= $numero; ?>" target="_blank">visualiser les <?= $_SESSION['nb_viz_caisse']; ?> dernieres ventes</a>
    </div>
  <?php } ?>

  <script defer type="text/javascript" src="../js/bootstrap-switch.js"></script>
  <script src="../js/ventes.js"></script>
  <script>
            'use strict';
            const force_pes_vente = <?= json_encode($_SESSION['force_pes_vente']); ?>;
            const tva_active = <?= json_encode($_SESSION['tva_active']); ?>;
            const taux_tva = <?= json_encode($_SESSION['taux_tva'], JSON_NUMERIC_CHECK); ?>;

            document.addEventListener('DOMContentLoaded', () => {
              $("#typeVente").bootstrapSwitch();
              $("#typeVente").on('switchChange.bootstrapSwitch', (event, state) => {
                switchlot(state); // true | false
              });
            }, false);

            function printdiv(divID) {
              if (parseInt(document.getElementById('nlignes').value) >= 1) {
                const headstr = "<html><head><title></title></head><body><small>";
                if (tva_active) {
                  const prixtot = parseFloat(document.getElementById('ptot').value).toFixed(2);
                  const prixht = (prixtot / (1 + taux_tva.toFixed(2) / 100)).toFixed(2);
                  const ptva = (prixtot - prixht).toFixed(2);
                  const footstr = `TVA à ${taux_tva}% Prix H.T. = ${prixht} + € TVA=  ${ptva} €`;
                } else {
                  const footstr = "Association non assujettie à la TVA.</body></small> ";
                }
                const commentaire = document.getElementById('commentaire').value.strip();
                const comstr = `<ul id='liste' class='list-group'><li class='list-group-item'><b>${commentaire}</b></li></ul>`;
                const newstr = document.all.item(divID).innerHTML;
                const oldstr = document.body.innerHTML;
                document.body.innerHTML = headstr + comstr + newstr + footstr;
                window.print();
                document.body.innerHTML = oldstr;
              }

              //puis encaisse
              if ((parseInt(document.getElementById('nlignes').value) >= 1)
                      && ((document.getElementById('quantite').value == "")
                              || (document.getElementById('quantite').value == "0"))
                      && ((document.getElementById('prix').value == "")
                              || (document.getElementById('prix').value == "0"))) {
                document.getElementById('comm').value = document.getElementById('commentaire').value;
                document.getElementById("formulaire").submit();
              }
            }
  </script>

  <?php
  require_once 'pied.php';
} else {
  header('Location:../moteur/destroy.php');
}
?>
