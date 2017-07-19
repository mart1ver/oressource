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


// Formulaire de description de la structure
// Simple formulaire de saisie renseignant les informations fondamentales identifiant la structure

require_once('../moteur/dbconfig.php');
require_once('../core/session.php');
require_once('../core/requetes.php');

session_start();

if (is_valid_session() && is_allowed_config()) {

  require_once('../moteur/dbconfig.php');
  require_once('tete.php');
  $structure = $_SESSION['structure'];
  ?>

  <div class="container">
    <h1>Configuration de Oressource</h1>
    <label>Description de la structure</label>
    <div class="panel-heading">
    </div>
    <div class="panel-body">
      <div class="row">
        <!-- Devrait être une methode put -->
        <form id="form"></form>
        <div class="col-md-3 col-md-offset-1">
          <label for="nom">Nom de la structure:</label>
          <input form='form' type="text"
                 name="nom" id="nom" class="form-control" required autofocus></div>
        <div class="col-md-4">
          <label for="adresse">Adresse:</label>
          <input form="form" type="text"
                 name="adresse" id="adresse" class="form-control" required></div>
        <div class="col-md-2">
          <label for="telephone">Téléphone:</label>
          <input form='form' type="tel"
                 name="telephone" id="telephone" class="form-control" required></div>
        <div class="col-md-2"></div>
      </div>

      <div class="row">
        <div class="col-md-4 col-md-offset-1">
          <label for="localite">Localité:</label>
          <input form='form' type="text" name="id_localite" id="id_localite"
                 class="form-control" required >

          <label for="mail">Mail principal:</label>
          <input form='form' type="email" name="mail"
                 id="mail" class="form-control" required>

          <label class="custom-control">
            <input form='form' name="saisiec" id="saisiec" type="checkbox">
            Permettre de dater formulaires (mode saisie):
          </label>

          <!-- TODO: implem un session timeout configurable.
           timeout session: <?= 'coming soon' /* $_SESSION['session_timeout'] */ ?> secondes
          -->

          <div class="panel panel-default">
            <label class="panel-title">formulaire de ventes</label>
            <div class="panel-body custom-controls-stacked">
              <label class="custom-control">
                <input form='form' type="text" name="cr"
                       id="cr" class="form-control" required>
                Code de remboursement à la caisse
              </label>

              <label class="custom-control custom-checkbox">
                <input form='form' name="pes_vente" id="pes_vente" type="checkbox">
                Activer la Pesée à la caisse
              </label>

              <label class="custom-control custom-checkbox">
                <input form='form' name="force_pes_vente"
                       id="force_pes_vente" type="checkbox">
                Interdire les ventes sans pesées
              </label>

              <label class="custom-control custom-checkbox">
                <input form='form' name="lot" id="lot" type="checkbox">
                Activer la vente par lot à la caisse
              </label>

              <label class="custom-control custom-checkbox">
                <input form='form' name="viz" id="viz" type="checkbox">
                Activer la visualisation des ventes à la caisse
              </label>

              <label class="custom-control">Nombre de ventes anterieures visibles:
                <input form='form' type="text" name="nb_viz"
                       id="nb_viz" class="form-control" required>
              </label>

              <label class="custom-control custom-checkbox">
                <input form='form' name="tva_active" id="tva_active" type="checkbox">
                Activer la TVA à la vente
              </label>

              <label class="custom-control">Taux en vigueur:
                <input form='form' type="text" name="taux_tva"
                       id="taux_tva" class="form-control" required>
              </label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <label for="siret">Numéro de siret:</label>
            <input form='form' type="text"
                   name="siret" id="siret" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label for="description">Présentation générale de la structure:</label>
            <textarea name="description" id="description" form="form"
                      rows="10" cols="50" required></textarea>
            <div class="panel panel-default">
              <label class="panel-title">formulaires de sorties hors boutique</label>
              <div class="panel-body custom-controls-stacked">
                <label class="custom-control custom-checkbox">
                  <input form='form' name="affsp" id ="affsp" type="checkbox">
                  Utiliser l'onglet "poubelles"
                </label>

                <label class="custom-control custom-checkbox">
                  <input form='form' name="affss" id="affss" type="checkbox">
                  Utiliser l'onglet "sorties partenaires"
                </label>

                <label class="custom-control custom-checkbox">
                  <input form='form' name="affsr" id="affsr" type="checkbox">
                  Utiliser l'onglet "recyclage"
                </label>

                <label class="custom-control custom-checkbox">
                  <input form='form' name="affsd" id="affsd" type="checkbox">
                  Utiliser l'onglet "don"
                </label>

                <label class="custom-control custom-checkbox">
                  <input form='form' name="affsde" id="affsde" type="checkbox">
                  Utiliser l'onglet "déchetterie"
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-1 col-md-offset-6">
            <br>
            <button id="send" class="btn btn-default">Enregistrer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    'use strict';
    const structure = <?= json_encode($structure); ?>;
    document.addEventListener('DOMContentLoaded', () => {
      Object.entries(structure).forEach(([key, value]) => {
        const e = document.getElementById(key);
        if (e !== null && e !== true) {
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
          nom: form.get('nom'),
          adresse: form.get('adresse').trim(),
          id_localite: ParseInt(form.get('id_localite'), 10),
          description: form.get('description'),
          siret: form.get('siret'),
          telephone: form.get('telephone'),
          mail: form.get('mail'),
          taux_tva: form.get('taux_tva'),
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
          force_pes_vente: (form.get('force_pes_vente') !== null),
        };
        // TODO: verifier que les données sont correctes.
        fetch('../api/structure.php', {
          credentials: 'same-origin',
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=utf-8'
          },
          body: JSON.stringify(data)
        }).then(status)
          .then((json) => {
            // TODO Vrai gestion de la reponse... (future mise en attente...)
            // console.log('response in Json:', json);
            window.location.href = '../ifaces/index.php';
        }).catch((ex) => {
          console.log('Error:', ex);
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
