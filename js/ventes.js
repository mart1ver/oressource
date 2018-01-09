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

function new_state() {
  return {
    moyen: 1,
    ticket: new Ticket(),
    last: undefined,
    vente_unite: true
  };
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

let state = new_state();
let numpad = new_numpad();
let rendu = new_rendu();

let current_focus = document.getElementById('quantite');
function fokus(element) {
  current_focus = element;
}

function ticket_sum(ticket) {
  const f = (acc, vente) =>
    acc + (vente.vente_unite ? vente.prix * vente.quantite : vente.prix);
  return ticket.to_array().reduce(f, 0);
}

function ticket_quantite(ticket) {
  const f = (acc, vente) => acc + vente.quantite;
  return ticket.to_array().reduce(f, 0);
}

function render_numpad( { prix, quantite, masse }) {
  document.getElementById('quantite').value = quantite;
  document.getElementById('prix').value = prix;
  if (window.ventes.pesees) {
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

function get_numpad() {
  const masseinput = document.getElementById('masse');
  return {
    quantite: Number.parseInt(document.getElementById('quantite').value, 10),
    masse: Number.parseFloat(masseinput === null ? NaN : masseinput.value, 10),
    prix: Number.parseFloat(document.getElementById('prix').value, 10)
  };
}

function update_state( { type, objet = {prix: 0, masse: 0.0} }) {
  numpad.prix = objet.prix;
  numpad.quantite = 1;
  numpad.masse = objet.masse || 0.0;
  state.last = { type, objet };
  const color = objet.couleur || type.couleur;
  const name = objet.nom || type.nom;
  const html = `Objet: <span class='badge' id='cool' style="background-color:${color}">${name}</span>`;
  document.getElementById('nom_objet').innerHTML = html;
  render_numpad(numpad);
}

function reset(data) {
  state = new_state();
  reset_numpad();
  reset_rendu();
  const range = document.createRange();
  range.selectNodeContents(document.getElementById('transaction'));
  range.deleteContents();
  update_recap(ticket_sum(state.ticket), ticket_quantite(state.ticket));
  data.json().then(data => {
    document.getElementById('num_vente').textContent = data.id_vente + 1;
  });
}

function moyens(methode) {
  state.methode = methode;
}

function encaisse_vente(event) {
  event.preventDefault();
  if (state.ticket.size > 0) {
    const url = '../api/ventes.php';
    const date = document.getElementById('date');
    const data = {
      id_point: window.ventes.point.id,
      id_user: window.ventes.id_user,
      id_moyen: state.moyen,
      commentaire: document.getElementById('commentaire').value.trim(),
      items: state.ticket.to_array()
    };
    if (date !== null) {
      data.date = date.value;
    }
    post_data(url, data, reset);
  }
}

function print() {
  if (state.ticket.size > 0) {
    const f = () => {
      if (ventes.tva_active) {
        const prixtot = ticket_sum(state.ticket).toFixed(2);
        const ptva = prixtot * ventes.taux_tva.toFixed(2);
        const prixht = (prixtot - ptva).toFixed(2);
        return `TVA à ${ventes.taux_tva}% Prix H.T. = ${prixht} + € TVA =  ${ptva} €`;
      } else {
        return "Association non assujettie à la TVA.";
      }
    };
    const commentaire = document.getElementById('commentaire').value.strip();
    const newstr = document.getElementById('ticket').innerHTML;
    const oldstr = document.body.innerHTML;
    document.body.innerHTML = `<html><head><title>Ticket</title></head>
      <body><small>
      <ul id='liste' class='list-group'>
      <li class='list-group-item'><b>${commentaire}</b></li>
      </ul>${newstr}${f()}</body></small>`;
    window.print();
    document.body.innerHTML = oldstr;
    encaisse();
  }
}

function update_rendu() {
  const total = ticket_sum(state.ticket);
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
  update_recap(ticket_quantite(state.ticket), ticket_sum(state.ticket));
  update_rendu();
  reset_numpad();
}

function update_recap(total, size) {
  document.getElementById('total').innerHTML = `<li class="list-group-item">Soit : ${size} article(s) pour : <span class="badge" style="float:right;">${total.toFixed(2)} €</span></li>`;
  document.getElementById('recaptotal').innerHTML = total.toFixed(2) + ' €';
  document.getElementById('nom_objet').textContent = "Objet:";
}

function add() {
  if (state.last !== undefined) {
    const { prix, quantite, masse } = get_numpad();
    if (quantite > 0 && !isNaN(prix)) {
      const current = state.last;
      state.last = undefined;
      const vente = {
        id_type: current.type.id,
        id_objet: current.objet.id || null,
        lot: !state.vente_unite,
        quantite,
        prix,
        masse
      };
      const id = state.ticket.push(vente);

      const name = current.objet.nom || current.type.nom;
      const li = document.createElement('li');
      li.setAttribute('id', id);
      li.setAttribute('class', 'list-group-item');
      const amount = vente.vente_unite ? vente.prix * vente.quantite : vente.prix;
      let html = `
            <span class="badge">${amount.toFixed(2)} €</span>
            <span class="glyphicon glyphicon-trash" aria-hidden="true"
                  onclick="remove(${id});return false;">
            </span>&nbsp;&nbsp; ${quantite} &#215; ${name}`;
      if (masse > 0 && window.ventes.pesees) {
        html += `, ${(masse * quantite).toFixed(3)} Kgs.`;
      }
      li.innerHTML = html;
      document.getElementById('transaction').appendChild(li);
      update_recap(ticket_sum(state.ticket), ticket_quantite(state.ticket));
      update_rendu();
      reset_numpad();
    } else {
      this.input.setCustomValidity('Quantite nulle ou inferieur a 0.');
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  $("#typeVente").bootstrapSwitch();
  $("#typeVente").on('switchChange.bootstrapSwitch', (event, checked) => {
    const lot_or_unite = (label, prix_string, masse_string, bg_color) => {
      document.getElementById('labellot').textContent = label;
      document.getElementById('labelprix').textContent = prix_string;
      document.getElementById('panelcalc').style.backgroundColor = bg_color;
      if (window.ventes.pesees) {
        document.getElementById('labelmasse').textContent = masse_string;
      }
    };
    if (checked) {
      lot_or_unite("Vente à: ", "Prix unitaire:", "Masse unitaire: ", "white");
    } else {
      lot_or_unite("Vente au: ", "Prix du lot: ", "Masse du lot: ", "#E8E6BC");
    }
    state.vente_unite = checked;
  });

  document.getElementById('reload').addEventListener('click', () => window.location.reload(), false);
  document.getElementById('encaissement').addEventListener('click', encaisse_vente, false);
  document.getElementById('impression').addEventListener('click', () => print, false);
}, false);