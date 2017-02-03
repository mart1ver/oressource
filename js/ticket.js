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

class Ticket {
  constructor() {
    this._total = 0.0;
    this.items = new Map();
    // this.comment = '';

    this.id_interne = 0;
  }

  remove(id) {
    const item = this.items.get(id);
    this.items.delete(id);
    this._total -= item.value;
    return item;
  }

  push(item) {
    this.items.set(this.id_interne, item);
    this._total += item.masse ;
    this.id_interne += 1;
    return this.id_interne;
  }

  get total() {
    return this._total;
  }

  get size() {
    return this.items.size;
  }

  to_json() {
    return JSON.stringify(this.to_array());
  }

  to_array() {
    return [...this.items.values()];
  }

  reset() {
    this._total = 0.0;
    this.items = new Map();
    this.id_interne = 0;
  }
}

function html_saisie_item( { id, nom, couleur }, action) {
  const button = document.createElement('button');
  button.setAttribute('id', id);
  button.setAttribute('class', 'btn btn-default');
  button.setAttribute('style', 'margin-left:8px; margin-top:16px;');
  button.innerHTML = `<span class="badge" id="cool" style="background-color:${couleur}">${nom}</span>`;
  button.addEventListener('click', action, false);
  return button;
}

function ticket_update_ui(container, totalUI, type_item, value, total) {
  // Constitution du panier
  const {_, nom, couleur} = type_item;
  const li = document.createElement('li');
  li.setAttribute('class', 'list-group-item');
  li.innerHTML = `<span class="badge" style="background-color:${couleur}">${value}</span>${nom}`;
  container.appendChild(li);
  // Update de l'UI pour la masse du panier.
  totalUI.textContent = `Bon d'apport: ${total} Kg.`;
}

function ticket_clear() {
  // On supprime tout le ticket en cours.
  const range = document.createRange();
  range.selectNodeContents(document.getElementById('transaction'));
  range.deleteContents();
  document.getElementById('commentaire').textContent = '';
  totalUI.textContent = `Bon d'apport: 0 Kg.`;
  // HACK: On devrait plutot recree un nouveau ticket... Et pas avoir ticket en global...
  window.ticket.reset();
}

// TODO:
// Faire une Feuille de style minimaliste plutot que ce hack.
// Avec pour media: printer
// Ou construire une page en arriere plan.
function impression_ticket() {
  if (window.ticket.size > 0
          && document.getElementById("id_type_collecte").value > 0
          && document.getElementById("loc").value > 0) {

    const headstr = `<html><head><title></title></head><body><small>${structure}<br>${adresse}<br><label>Bon d'apport:</label><br>`;
    const footstr = `<br>Masse totale : " + ${window.ticket.total} + " Kg.</body></small>`;
    const newstr = document.getElementById('transaction').innerHTML;
    const oldstr = document.body.innerHTML;
    encaisse();
    document.body.innerHTML = headstr + newstr + footstr;
    window.print();
    document.body.innerHTML = oldstr;
    window.ticket.reset();
  }
}

function encaisse() {
  if (window.ticket.size > 0
          && document.getElementById("id_type_collecte").value > 0
          && document.getElementById("loc").value > 0) {

    const form = new FormData(document.getElementById('formulaire'));

    const data = {
      id_point_collecte: window.OressourceEnv.id_point_collecte,
      id_user: window.OressourceEnv.id_user,
      saisie_collecte: window.OressourceEnv.saisie_collecte,
      user_droit: window.OressourceEnv.user_droit,
      nb_item: window.ticket.size,
      items: window.ticket.to_array(),
      localite: parseInt(form.get('localite'), 10),
      id_type_collecte: parseInt(form.get('id_type_collecte'), 10),
      commentaire: form.get('commentaire'),
      antidate: form.get('antidate'),
    };

    // See https://github.com/github/fetch
    fetch('../moteur/collecte_post.php', {
      credentials: 'same-origin',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }).then((response) => {
      if (response.status >= 200
              && response.status < 300) {
        return response;
      } else {
        const error = new Error(response.statusText);
        error.response = response;
        throw error;
      }
      response.json();
    }).then((json) => {
      // TODO Vrai gestion de la reponse... (future mise en attente...)
      // console.log('response in Json:', json);
      ticket_clear();
    }).catch((ex) => {
      // TODO vrai message d'erreur...
      console.log('Error:', ex);
    });
  }
}
