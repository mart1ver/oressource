'use strict';
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

// use Ticket from ticket.js

//! L'interface des ventes est gérée à l'aide d'un état interne sous forme d'objets JavaScript
//! et de fonctions les manipulants et d'une interface graphique en HTML+CSS.
//!
//! «L'état de référence» sont les objets JS. L'interface graphique n'est qu'une «vue» des données
//! Par exemple clicker sur «ajouter» rajoute dans la représentation interne d'un panier de
//! vente l'objet, le prix et la quantité désirée et ensuite met à jour l'interface graphique.
//! La page et les données sont remises à 0, en cas d'envoi réussi (avec ou sans impressions)
//! ou de click sur la remise à 0.

Ticket.prototype.sum_quantite = function () {
  return this.to_array()
      .reduce((acc, vente) => acc + vente.quantite, 0);
}

Ticket.prototype.sum_prix = function () {
  return this.to_array().reduce(
    (acc, vente) =>
      acc + (vente.lot ? vente.prix : (vente.prix * vente.quantite)),
    0.0);
}

function new_state() {
  const s = {
    moyen: 1, // especes
    ticket: new Ticket(),
    last: undefined,
    vente_unite: true
  };
  // Hack pour les impressions...
  window.OressourceEnv.tickets = s.ticket;
  return s;
}

function new_numpad() {
  return {
    prix: 0,
    quantite: 0,
    masse: 0.0
  };
}

function new_rendu() {
  return {
    reglement: 0,
    difference: 0
  };
}

/// Fonction permetant de raccoursir une chaine trop longue.
/// Par exemple
/// `wrapString("Matériel élèctrique à 0.5€", 15, '…')`
/// sera evaluée à `"matériel éléc à…"`.
const wrapString = (s, n, c) => (s.length > n) ? (s.slice(0, n) + c) : s;

let state = new_state();
let numpad = new_numpad();
let rendu = new_rendu();

/// Fonction gérant les différents champs du numpad elle est appellée via le "click"
/// HTML de l'inferface de ifaces/vente.php
let current_focus = document.getElementById('quantite');
function fokus(element) {
  current_focus = element;
}

function render_numpad({ prix, quantite, masse }) {
  document.getElementById('quantite').value = quantite;
  document.getElementById('prix').value = prix;
  if (window.OressourceEnv.pesees) {
    document.getElementById('masse').value = masse;
  }
}

function reset_rendu() {
  rendu = new_rendu();
  update_rendu();
}

function reset_numpad() {
  numpad = new_numpad();
  render_numpad(numpad);
}

/// Cette fonction remet l'interface du choix du moyen de paiement à son état initial
/// c'est à dire sur «espèce».
function reset_paiement() {
  const moyens_paiement_selector = document.getElementById('moyens');
  Array.from(moyens_paiement_selector.children).forEach(elem => {
    elem.classList.remove('active');
  });

  // L'element 0 c'est les espece.
  moyens_paiement_selector.children[0].classList.add('active');
}

/// Permet de récupérer les saisies du numpad sous la forme d'un objet js.
function get_numpad() {
  const masseinput = document.getElementById('masse');
  return {
    quantite: Number.parseInt(document.getElementById('quantite').value, 10),
    masse: Number.parseFloat(masseinput === null ? NaN : masseinput.value, 10),
    prix: Number.parseFloat(document.getElementById('prix').value, 10)
  };
}

/// Ajoute au panier l'objet selectionné.
function update_state({ type, objet = { prix: 0, masse: 0.0 } }) {
  numpad.prix = objet.prix;
  numpad.quantite = 1;
  numpad.masse = objet.masse || 0.0;
  state.last = { type, objet };
  const color = objet.couleur || type.couleur;
  const name = objet.nom || type.nom;
  const html = `<span class='badge' id='cool' style="background-color:${color}">${wrapString(name, 15, '&hellip;')}</span>`;
  document.getElementById('nom_objet').innerHTML = html;
  render_numpad(numpad);
}

/// Effectue le reset des données représentant une vente et de l'interface graphique.
function reset(data, response) {
  state = new_state();

  reset_numpad();
  reset_rendu();
  reset_paiement();

  // On remet à zéro le panier
  {
    const range = document.createRange();
    range.selectNodeContents(document.getElementById('transaction'));
    range.deleteContents();
    update_recap(0.0, 0.0);
  }

  // On donne le nouveau numéro «prévisionnel» à la futur vente.
  // Attention ce numéro est «provisoire» si il y a plusieurs caisses.
  document.getElementById('num_vente').textContent = response.id + 1;
  // #FIX: 384 Reset du commentaire associé à la vente.
  document.getElementById('commentaire').value = '';
}

function moyens(moyen) {
  state.moyen = moyen;
}

function encaisse_vente() {
  if (state.ticket.size > 0) {
    const date = document.getElementById('date');
    const data = {
      classe: 'ventes',
      id_point: window.OressourceEnv.point.id,
      id_user: window.OressourceEnv.id_user,
      id_moyen: state.moyen,
      commentaire: document.getElementById('commentaire').value.trim(),
      items: state.ticket.to_array()
    };
    if (date !== null) {
      data.date = date.value;
    }
    return data;
  } else {
    return {};
  }
}

// Historiquement Oressource a une gestion des prix Hors-Taxe.
//
// ## Cas de la TVA active
//
// Actuellement Oressource ne gére que un taux de TVA unique.
//
// Si la structure active la TVA les prix affichées en boutiques sont
// Toutes Taxes Comprises, il conviens alors de calculer un prix HT
// et la part TVA pour informer, l'usager de la ressourcerie.
// Formule: Prix HT = Prix TTC * 100 / (100 + Taux)
//
// Source:
// https://www.service-public.fr/professionnels-entreprises/vosdroits/F24271
const printTva = (() => {
  if (window.OressourceEnv.tva_active) {
    return () => {
      const ttc = state.ticket.sum_prix();
      const taux_tva = window.OressourceEnv.taux_tva;
      const ht = ttc * 100 / (100 + taux_tva);
      return `Prix HT. = ${ht.toFixed(2)} €<br> Prix TTC. = ${ttc.toFixed(2)} €<br\> dont TVA ${taux_tva}% = ${part_tva.toFixed(2)} €`;
    }
  } else {
    () => "Association non assujettie à la TVA.";
  }
})();

function update_rendu() {
  const total = state.ticket.sum_prix();
  const input = document.getElementById('reglement');
  rendu.reglement = parseFloat(input.value, 10).toFixed(3);
  rendu.difference = rendu.reglement - total;
  document.getElementById('somme').value = total;
  document.getElementById('difference').value = rendu.difference || 0;
}

function numpad_input(elem) {
  current_focus.value += elem.value;
}

function remove(id) {
  const elem = state.ticket.remove(id);
  document.getElementById(id).remove();
  update_rendu();
  update_recap(state.ticket.sum_prix(), state.ticket.sum_quantite());
  reset_numpad();
}

function update_recap(total, size) {
  document.getElementById('total').innerHTML = `<li class="list-group-item">Soit : ${size} article(s) pour : <span class="badge" style="float:right;">${total.toFixed(2)} €</span></li>`;
  document.getElementById('recaptotal').innerHTML = total.toFixed(2) + ' €';
  document.getElementById('nom_objet').textContent = "Objet:";
}

/**
 * Cette fonction est utilisée lorsque l'on click sur le bouton `ajouter`
 * du numpad.
 * On récupére l'état des objets du même type à ajouter au panier puis
 * on met a jour l'interface graphique du panier.
 *
 * Deux points sont assez particulier:
 *
 * ## Cas de la vente en lot
 *
 * En lot on décide d'appliquer un prix arbitraire à un ensemble d'objet
 * du même type sans respecter le prix unitaire (une promotion en somme).
 * On ne peux donc pas utilier comme "prix total" quantité * prix
 * comme dans la vente unitaire. On prends le prix seulement du lot.
 *
 * ## Cas des pesées en vente:
 *
 * On ajoute la masse de l'ensemble des objets à ajouter au panier
 * (Comme dans une collecte ou sortie), on ne pesee pas independament tout
 * les objets on pesee en lot en somme.
 */
function add() {
  if (state.last !== undefined) {
    const { prix, quantite, masse } = get_numpad();
    if (quantite > 0 && !isNaN(prix)) {
      const current = state.last;
      state.last = undefined;
      // Idée: Ajouter un champ "prix total" pour eviter de faire un if pour les calculs sur les prix.

      const name = current.objet.nom || current.type.nom;
      const vente = {
        id_type: current.type.id,
        id_objet: current.objet.id || null,
        lot: !state.vente_unite,
        quantite,
        prix,
        masse,
        name, // Hack pour les impressions.
      };

      const lot = vente.lot ? 'lot' : '';

      vente.show = function() {
        const prix_txt = `${this.prix} €`;
        const masse_txt = this.masse >= 0.00 ? ` ${this.masse} kg` : '';

        return `<p>${lot} ${this.name} : ${prix_txt}${masse_txt}</p>`;
      };

      const id = state.ticket.push(vente);

      const li = document.createElement('li');
      li.setAttribute('id', id);
      li.setAttribute('class', 'list-group-item');
      const amount = vente.lot ?  vente.prix : vente.prix * vente.quantite;
      let html = `
            <span class="badge">${amount.toFixed(2)} €</span>
            <span class="glyphicon glyphicon-trash" aria-hidden="true"
                  onclick="remove(${id});return false;">
            </span>&nbsp;&nbsp; ${lot} ${quantite} &#215; ${name}`;
      if (masse > 0 && window.OressourceEnv.pesees) {
        html += `, ${(masse).toFixed(3)} Kgs.`;
      }
      li.innerHTML = html;
      document.getElementById('transaction').appendChild(li);
      update_recap(state.ticket.sum_prix(), state.ticket.sum_quantite());
      update_rendu();
      reset_numpad();
      // Reset du selecteur lot/unité
      $("#typeVente").bootstrapSwitch('state', true, false);
    } else {
      this.input.setCustomValidity('Quantite nulle ou inferieur a 0.');
    }
  }
}

const lot_or_unite = (label, prix_string, masse_string, bg_color) => {
  document.getElementById('labellot').textContent = label;
  document.getElementById('labelprix').textContent = prix_string;
  document.getElementById('panelcalc').style.backgroundColor = bg_color;
  if (window.OressourceEnv.pesees) {
    document.getElementById('labelmasse').textContent = masse_string;
  }
};

document.addEventListener('DOMContentLoaded', () => {
  $("#typeVente").bootstrapSwitch();
  $("#typeVente").on('switchChange.bootstrapSwitch', (event, checked) => {
    if (checked) {
      lot_or_unite("Vente à: ", "Prix unitaire:", "Masse unitaire: ", "white");
    } else {
      lot_or_unite("Vente au: ", "Prix du lot: ", "Masse du lot: ", "#E8E6BC");
    }
    state.vente_unite = checked;
  });

  const url = '../api/ventes.php';
  const ventePrint = (data, response) => {
    return impression_ticket(data, response, '€', printTva, (t) => t.sum_prix());
  }
  const send = post_data(url, encaisse_vente, reset);
  const sendAndPrint = post_data(url, encaisse_vente, reset, ventePrint);
  document.getElementById('encaissement').addEventListener('click', send, false);
  document.getElementById('impression').addEventListener('click', sendAndPrint, false);

}, false);
