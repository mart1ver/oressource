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


// Modification de la configuration des structures

session_start();

require_once('../core/session.php');
require_once('../core/requetes.php');
require_once('../core/composants.php');

if (is_valid_session() && is_allowed_config()) {
  require_once('tete.php');
  require_once('../moteur/dbconfig.php');
  $struct = structure($bdd);
  ?>

  <div class="container">
    <h1>Configuration de Oressource</h1>
    <div class="panel-heading">
      <h1 class="panel-title"><b>Déscription de la structure:<b></h1>
    </div>
    <form id="form">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <?= textInput(['name' => 'nom', 'text' => "Nom de la structure:"], $struct['nom']) ?>
          </div>
          <div class="col-md-4">
            <?= textInput(['name' => 'adresse', 'text' => "Adresse:"], $struct['adresse']) ?>
          </div>
          <div class="col-md-3 col-md-offset-1">
            <label for="telephone">Téléphone:</label>
            <input type="tel"
                   name="telephone" id="telephone" class="form-control"
                   value="<?= $struct['telephone'] ?>" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <?= textInput(['name' => 'id_localite', 'text' => "Localité:"], $struct['id_localite']) ?>
            <label for="mail">Mail principal:</label>
            <input type="email" name="mail"
                   id="mail" class="form-control"
                   value="<?= $struct['mail'] ?>" required>
            <?= checkBox(['name' => 'saisiec', 'text' => "Permettre de dater formulaires (mode saisie):"], $struct['saisiec']) ?>
            <div class="panel panel-default">
              <h3 class="panel-title">formulaire de ventes</h3>
              <div class="panel-body custom-controls-stacked">
                <?= textInput(['name' => 'cr', 'text' => "Code de remboursement à la caisse"], $struct['cr']) ?>
                <?= checkBox(['name' => 'pes_vente', 'text' => "Activer la Pesée à la caisse"], $struct['pes_vente']) ?>
                <?= checkBox(['name' => 'force_pes_vente', 'text' => "Interdire les ventes sans pesées"], $struct['force_pes_vente']) ?>
                <?= checkBox(['name' => 'lot', 'text' => "Activer la vente par lot à la caisse"], $struct['lot']) ?>
                <?= checkBox(['name' => 'viz', 'text' => "Activer la visualisation des ventes à la caisse"], $struct['viz']) ?>
                <?= textInput(['name' => 'nb_viz', 'text' => "Nombre de ventes anterieures visibles:"], $struct['nb_viz']) ?>
                <?= checkBox(['name' => 'tva_active', 'text' => "Activer la TVA à la vente"], $struct['tva_active']) ?>
                <?= textInput(['name' => 'taux_tva', 'text' => "Taux en vigueur:"], $struct['taux_tva']) ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <?= textInput(['name' => 'siret', 'text' => "Numéro de siret:"], $struct['siret']) ?>
              <label for="description">Présentation générale de la structure:
                <textarea name="description" id="description" form="form"
                          rows="10" cols="50" required><?= $struct['description'] ?></textarea>
              </label>
              <div class="panel panel-default">
                <h2 class="panel-title">Formulaires de sorties hors boutique</h2>
                <div class="panel-body custom-controls-stacked">
                  <?= checkBox(['name' => 'affsp', 'text' => "Utiliser l'onglet « poubelles »"], $struct['affsp']) ?>
                  <?= checkBox(['name' => 'affss', 'text' => "Utiliser l'onglet « sorties partenaires »"], $struct['affss']) ?>
                  <?= checkBox(['name' => 'affsr', 'text' => "Utiliser l'onglet « recyclage »"], $struct['affsr']) ?>
                  <?= checkBox(['name' => 'affsd', 'text' => "Utiliser l'onglet « don »"], $struct['affsd']) ?>
                  <?= checkBox(['name' => 'affsde', 'text' => "Utiliser l'onglet « déchetterie »"], $struct['affsde']) ?>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-1 col-md-offset-6">
              <br>
              <button id="send" class="btn btn-warning ">Enregistrer</button>
            </div>
            <div class="col-md-1 col-md-offset-1">
              <br>
              <input type="button" onclick="location.href='exportbase.php';" value="Sauvegarder la base de données" class="btn btn-primary" />
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <script type="text/javascript">
    'use strict';
    const structure = <?= json_encode($struct); ?>;
    document.addEventListener('DOMContentLoaded', () => {
      Object.entries(structure).forEach(([key, value]) => {
        const e = document.getElementById(key);
        if (e !== null && e === 'true') {
          e.value = value;
          if (value === true) {
            e.checked = true;
          }
        }
      });

      document.getElementById('send').addEventListener('click', (e) => {
        e.preventDefault();
        const form = new FormData(document.getElementById('form'));
        const data = {
          nom: form.get('nom').trim(),
          adresse: form.get('adresse').trim(),
          id_localite: parseInt(form.get('id_localite'), 10),
          description: form.get('description').trim(),
          siret: form.get('siret').trim(),
          telephone: form.get('telephone').trim(),
          mail: form.get('mail').trim(),
          taux_tva: parseFloat(form.get('taux_tva'), 10),
          cr: form.get('cr'),
          nb_viz: form.get('nb_viz'),
          tva_active: (form.get('tva_active') !== null),
          lot: (form.get('lot') !== null),
          viz: (form.get('viz') !== null),
          saisiec: (form.get('saisiec') !== null),
          affsp: (form.get('affsp') !== null),
          affss: (form.get('affss') !== null),
          affsr: (form.get('affsr') !== null),
          affsd: (form.get('affsd') !== null),
          affsde: (form.get('affsde') !== null),
          pes_vente: (form.get('pes_vente') !== null),
          force_pes_vente: (form.get('force_pes_vente') !== null)
        };

        // TODO: verifier que les données sont correctes.
        const url = '../api/structures.php';
        fetch(url, {
          credentials: 'include',
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=utf-8'
          },
          body: JSON.stringify(data)
        }).then(status)
          .then((json) => {
            window.alert('Changements sauvegardé!');
        }).catch((ex) => {
          if (ex.status === 401) {
            login(() => {
              window.alert('Votre session à expirée, reappuyez sur enregistrer.');
            });
          } else {
            console.log('Error:', ex);
          }
        });
      });
    });
  </script>
  <?php
  require_once "pied.php";
} else {
  destroy_session();
  header('Location:../moteur/destroy.php?motif=1');
  die();
}
